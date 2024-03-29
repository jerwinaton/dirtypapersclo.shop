<?php

namespace App\Http\Livewire;

use App\Mail\OrderPlaced;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\ComponentConcerns\PerformsRedirects;
use Lunar\Facades\CartSession;
use Lunar\Models\Cart;
use Lunar\Models\Order;

class CheckoutSuccessPage extends Component
{
    use PerformsRedirects;

    public ?Cart $cart;

    public Order $order;

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public function mount()
    {
        $this->cart = CartSession::current();
        if (!$this->cart || !$this->cart->completedOrder) {
            $this->redirect('/');

            return;
        }
        $this->order = $this->cart->completedOrder;


        $order = $this->cart->completedOrder;

        // Fetch the email of the user associated with the order
        $email = $order->user->email;

        // Send email with order details and PDF attachment
        Mail::to($email)->send(new OrderPlaced($order));


        CartSession::forget();
    }

    public function render()
    {
        return view('livewire.checkout-success-page');
    }
}
