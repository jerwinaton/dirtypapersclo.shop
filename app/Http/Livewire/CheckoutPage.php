<?php

namespace App\Http\Livewire;

use App\Mail\OrderPlaced;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\ComponentConcerns\PerformsRedirects;
use Lunar\Facades\CartSession;
use Lunar\Facades\Payments;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Cart;
use Lunar\Models\CartAddress;
use Lunar\Models\Country;
use Lunar\Models\Customer;

class CheckoutPage extends Component
{
    use PerformsRedirects;

    /**
     * The Cart instance.
     */
    public ?Cart $cart;

    /**
     * The shipping address instance.
     */
    public ?CartAddress $shipping = null;

    /**
     * The billing address instance.
     */
    public ?CartAddress $billing = null;

    /**
     * The current checkout step.
     */
    public int $currentStep = 1;


    /**
     * Whether the shipping address is the billing address too.
     */
    public bool $shippingIsBilling = true;

    /**
     * The chosen shipping option.
     *
     * @var string|int
     */
    public $chosenShipping = null;

    /**
     * The checkout steps.
     */
    public array $steps = [
        'shipping_address' => 1,
        'shipping_option' => 2,
        'billing_address' => 3,
        'payment' => 4,
    ];

    /**
     * The payment type we want to use.
     *
     * @var string
     */
    public $paymentType = 'card';

    /**
     * {@inheritDoc}
     */
    protected $listeners = [
        'cartUpdated' => 'refreshCart',
        'selectedShippingOption' => 'refreshCart',
    ];

    public $payment_intent = null;

    public $payment_intent_client_secret = null;

    protected $queryString = [
        'payment_intent',
        'payment_intent_client_secret',
    ];

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return array_merge(
            $this->getAddressValidation('shipping'),
            $this->getAddressValidation('billing'),
            [
                'shippingIsBilling' => 'boolean',
                'chosenShipping' => 'required',
            ]
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public function mount()
    {
        if (!$this->cart = CartSession::current()) {
            $this->redirect('/');

            return;
        }

        $this->setShippingOptionBasedOnItemQuantity();


        // Fetch the customer from your database based on your application's logic
        //if you have a logged-in user, you can fetch the customer associated with that user

        $userId = Auth::user()->id;

        if (!$userId) {
            $this->redirect('/');
            return;
        }
        $prefix = config('lunar.database.table_prefix');
        // Retrieve the corresponding customer(s) for the user from the pivot table
        $customer_ids = DB::table("{$prefix}customer_user")
            ->where('user_id', $userId)
            ->pluck('customer_id');

        // Retrieve the customer(s) based on the retrieved IDs
        $customers = Customer::find($customer_ids);

        // Associate the customer with the cart
        if ($customers->isNotEmpty()) {
            $customer = $customers; // Assuming a user can only be associated with one customer
            $this->cart->setCustomer($customer->first());
        }

        if ($this->payment_intent) {
            $payment = Payments::driver($this->paymentType)->cart($this->cart)->withData([
                'payment_intent_client_secret' => $this->payment_intent_client_secret,
                'payment_intent' => $this->payment_intent,
            ])->authorize();

            if ($payment->success) {

                redirect()->route('checkout-success.view');

                return;
            }
        }

        // Do we have a shipping address?
        $this->shipping = $this->cart->shippingAddress ?: new CartAddress;

        $this->billing = $this->cart->billingAddress ?: new CartAddress;

        $this->determineCheckoutStep();
    }

    /**
     * {@inheritDoc}
     */
    public function hydrate()
    {
        $this->cart = CartSession::current();
    }

    /**
     * Trigger an event to refresh addresses.
     *
     * @return void
     */
    public function triggerAddressRefresh()
    {
        $this->emit('refreshAddress');
    }

    /**
     * Determines what checkout step we should be at.
     *
     * @return void
     */
    public function determineCheckoutStep()
    {
        $shippingAddress = $this->cart->shippingAddress;
        $billingAddress = $this->cart->billingAddress;

        if ($shippingAddress) {
            if ($shippingAddress->id) {
                $this->currentStep = $this->steps['shipping_address'] + 1;
            }

            // Do we have a selected option?
            if ($this->shippingOption) {
                $this->setShippingOptionBasedOnItemQuantity();
                $this->currentStep = $this->steps['shipping_option'] + 1;
            } else {
                $this->currentStep = $this->steps['shipping_option'];

                $this->setShippingOptionBasedOnItemQuantity();

                return;
            }
        }

        if ($billingAddress) {
            $this->currentStep = $this->steps['billing_address'] + 1;
        }
    }

    /**
     * Refresh the cart instance.
     *
     * @return void
     */
    public function refreshCart()
    {
        $this->cart = CartSession::current();
        $this->setShippingOptionBasedOnItemQuantity();
    }

    /**
     * Return the shipping option.
     *
     * @return void
     */
    public function getShippingOptionProperty()
    {
        $shippingAddress = $this->cart->shippingAddress;

        if (!$shippingAddress) {
            return;
        }

        if ($option = $shippingAddress->shipping_option) {
            return ShippingManifest::getOptions($this->cart)->first(function ($opt) use ($option) {
                return $opt->getIdentifier() == $option;
            });
        }

        return null;
    }

    /**
     * Save the address for a given type.
     *
     * @param  string  $type
     * @return void
     */
    public function saveAddress($type)
    {
        $validatedData = $this->validate(
            $this->getAddressValidation($type)
        );

        $address = $this->{$type};

        if ($type == 'billing') {
            $this->cart->setBillingAddress($address);
            $this->billing = $this->cart->billingAddress;
        }

        if ($type == 'shipping') {
            $this->cart->setShippingAddress($address);
            $this->shipping = $this->cart->shippingAddress;

            if ($this->shippingIsBilling) {
                // Do we already have a billing address?
                if ($billing = $this->cart->billingAddress) {
                    $billing->fill($validatedData['shipping']);
                    $this->cart->setBillingAddress($billing);
                } else {
                    $address = $address->only(
                        $address->getFillable()
                    );
                    $this->cart->setBillingAddress($address);
                }

                $this->billing = $this->cart->billingAddress;
            }
        }

        $this->determineCheckoutStep();
    }

    /**
     * Save the selected shipping option.
     *
     * @return void
     */
    public function saveShippingOption()
    {
        $option = $this->shippingOptions->first(fn ($option) => $option->getIdentifier() == $this->chosenShipping);

        CartSession::setShippingOption($option);

        $this->refreshCart();

        $this->determineCheckoutStep();
    }

    public function checkout()
    {
        $payment = Payments::cart($this->cart)->withData([
            'payment_intent_client_secret' => $this->payment_intent_client_secret,
            'payment_intent' => $this->payment_intent,
        ])->authorize();

        if ($payment->success) {
            redirect()->route('checkout-success.view');

            return;
        }

        return redirect()->route('checkout-success.view');
    }

    /**
     * Return the available countries.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCountriesProperty()
    {
        return Country::whereIn('iso3', ['PHL'])->get();
    }

    /**
     * Return available shipping options.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getShippingOptionsProperty()
    {
        return ShippingManifest::getOptions(
            $this->cart
        );
    }

    /**
     * Return the address validation rules for a given type.
     *
     * @param  string  $type
     * @return array
     */
    protected function getAddressValidation($type)
    {
        return [
            "{$type}.first_name" => 'required',
            "{$type}.last_name" => 'required',
            "{$type}.line_one" => 'required',
            "{$type}.country_id" => 'required',
            "{$type}.city" => 'required',
            "{$type}.postcode" => 'required',
            "{$type}.company_name" => 'nullable',
            "{$type}.line_two" => 'nullable',
            "{$type}.line_three" => 'nullable',
            "{$type}.state" => 'nullable',
            "{$type}.delivery_instructions" => 'nullable',
            "{$type}.contact_email" => 'required|email',
            "{$type}.contact_phone" => 'nullable',
        ];
    }

    public function setShippingOptionBasedOnItemQuantity()
    {
        // Determine the number of items in the cart
        $numberOfItems = $this->cart->lines()->sum('quantity');

        if ($numberOfItems >= 1 && $numberOfItems <= 4) {
            $this->chosenShipping = 'REGDEL';
        } elseif ($numberOfItems >= 5 && $numberOfItems <= 10) {
            $this->chosenShipping = 'MIDDEL';
        } else {
            $this->chosenShipping = 'EXTDEL';
        }
    }

    public function render()
    {
        return view('livewire.checkout-page')
            ->layout('layouts.checkout');
    }
}
