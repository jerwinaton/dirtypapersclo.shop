<?php

namespace App\Http\Livewire;

use App\Mail\OrderCompleted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;
use Lunar\Models\Customer;
use Lunar\Models\Order;
use Lunar\Models\OrderAddress;
use Lunar\Models\ProductVariant;

class CustomerOrdersPage extends Component

{
    use WithPagination;

    public $customerId = null;
    public $perPage = 5;
    public $selectedStatus = 'pending';
    public $statuses = [
        'pending' => 'payment-received',
        'dispatched' => 'dispatched',
        'completed' => 'completed',
        'cancelled' => 'Cancelled',

    ];

    /**
     * The current order in view.
     */
    public Order $order;
    /**
     * The instance of the shipping address.
     *
     * @var \Lunar\Models\OrderAddress
     */
    public ?OrderAddress $shippingAddress = null;

    /**
     * The instance of the shipping address.
     *
     * @var \Lunar\Models\OrderAddress
     */
    public ?OrderAddress $billingAddress = null;

    /**
     * Whether all lines should be visible.
     */
    public bool $allLinesVisible = true;

    /**
     * The maximum lines to show on load.
     */
    public int $maxLines = 5;




    public $showDetails = false;
    public $showReview = false;
    public $selectedOrderId;
    public $selectedProductVariantToReviewId;
    public $selectedOrderToReceiveId;


    public function setSelectedStatus($status)
    {
        $this->selectedStatus = $status;
        $this->render();
    }

    public function showReviewView($orderId)
    {
        $this->showReview = true;
        $this->showOrderDetails($orderId);
    }
    public function showOrderDetails($orderId)
    {
        $this->showDetails = true;
        $this->selectedOrderId = $orderId;
    }

    public function closeOrderDetails()
    {
        $this->showDetails = false;
        $this->selectedOrderId = null;
    }
    public function getSelectedOrderProperty()
    {
        if ($this->selectedOrderId) {
            return Order::find($this->selectedOrderId);
        }
        return null;
    }
    public function getSelectedProductVariantProperty()
    {
        if ($this->selectedProductVariantToReviewId) {
            return ProductVariant::find($this->selectedProductVariantToReviewId);
        }
        return null;
    }
    public function getBillingShippingProperty()
    {
        $this->shippingAddress = $this->selectedOrder->shippingAddress ?: new OrderAddress();

        $this->billingAddress = $this->selectedOrder->billingAddress ?: new OrderAddress();
    }
    public function getShippingLinesProperty()
    {
        return $this->selectedOrder->shippingLines;
    }
    public function receiveOrder($orderId)
    {
        $order = Order::find($orderId);
        //update status
        if ($order) {
            // Update the status of the order
            $order->update(['status' => 'completed', 'completed_at' => now()]);

            // sending notifications, etc.

            // Fetch the email of the user associated with the order
            $email = $order->user->email;

            // Send email with order details and PDF attachment
            Mail::to($email)->send(new OrderCompleted($order));

            // Emit an event to inform the frontend that the order has been received
            $this->emit('orderReceived', $orderId);
        }
    }
    public function setSelectedOrderId($orderId)
    {
        $this->selectedOrderId = $orderId;
    }
    public function render()
    {
        $this->customerId = Auth::user()->id;


        $prefix = config('lunar.database.table_prefix');

        // Retrieve the corresponding customer(s) for the user from the pivot table
        $customer_id = DB::table("{$prefix}customer_user")
            ->where('user_id',  $this->customerId)
            ->pluck('customer_id');


        // Retrieve the customer(s) based on the retrieved IDs
        $customer = Customer::find($customer_id);

        // Now you can work with the $customers collection
        // For example, you can update the first customer's first_name attribute:

        if ($customer) {
            // Fetch orders based on the selected status
            $ordersQuery = Order::whereCustomerId($customer->first()->id)
                ->where('status', $this->statuses[$this->selectedStatus]);

            // Adjust orderBy based on the selected status
            switch ($this->selectedStatus) {
                case 'dispatched':
                    $ordersQuery->orderBy('dispatched_at', 'desc');
                    break;
                case 'cancelled':
                    $ordersQuery->orderBy('cancelled_at', 'desc');
                    break;
                case 'completed':
                    $ordersQuery->orderBy('completed_at', 'desc');
                    break;
                default:
                    $ordersQuery->orderBy('cancelled_at', 'desc');
                    break;
            }

            $orders = $ordersQuery->paginate($this->perPage);
        }

        $selectedOrder = $this->selectedOrder;
        $selectedProductVariant = $this->selectedProductVariant;


        return view('livewire.customer-orders-page')->with('selectedOrder', $selectedOrder)->with('orders', $orders)->with('selectedProductVariant', $selectedProductVariant)->layout('layouts.orders');
    }
}
