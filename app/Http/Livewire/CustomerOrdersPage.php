<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Lunar\Models\Customer;
use Lunar\Models\Order;

class CustomerOrdersPage extends Component
{
    public $customerId = null;
    public $perPage = 5;
    public $selectedStatus = 'pending';
    public $statuses = [
        'pending' => 'payment-received',
        'dispatched' => 'dispatched',
        'completed' => 'completed',
        'cancelled' => 'Cancelled',

    ];
    public function setSelectedStatus($status)
    {
        $this->selectedStatus = $status;
        $this->render();
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

        return view('livewire.customer-orders-page')->with('orders', $orders)->layout('layouts.orders');
    }
}
