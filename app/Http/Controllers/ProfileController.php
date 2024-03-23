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

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();


        $prefix = config('lunar.database.table_prefix');

        // Retrieve the corresponding customer(s) for the user from the pivot table
        $customer_ids = DB::table("{$prefix}customer_user")
            ->where('user_id',  $user->id)
            ->pluck('customer_id');


        // Retrieve the customer(s) based on the retrieved IDs
        $customers = Customer::find($customer_ids);

        // Now you can work with the $customers collection
        // For example, you can update the first customer's first_name attribute:
        if ($customers->isNotEmpty()) {
            $customers->first()->update(['first_name' => $user->first_name, 'last_name' => $user->last_name, 'salutation' => $user->salutation]);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
