<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Lunar\Models\Customer;
use Lunar\Models\Order;

class CustomerOrdersController extends Controller
{
    public $customerId = null;
    public $perPage = 5;

    /**
     * Update the user's profile information.
     */
    public function index()
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
            $orders = Order::whereCustomerId($customer->first()?->id)->orderBy('placed_at', 'desc')
                ->paginate($this->perPage);
        }

        return view('livewire.orders.index')->with('orders', $orders);
    }
}
