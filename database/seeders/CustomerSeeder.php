<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Lunar\Models\Address;
use Lunar\Models\Customer;

class CustomerSeeder extends AbstractSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            // $faker = Factory::create();
            $customers = Customer::factory(100)->create();

            // Business 2 Consumer SETUP
            foreach ($customers as $customer) {
                $user = User::factory()->create();
                $customer->users()->attach($user);

                Address::factory()->create([
                    'shipping_default' => true,
                    'country_id' => 175, // Assuming country_id for B2C setup is Philippines (PH)
                    'customer_id' => $customer->id,
                ]);
            }
            // B2B SETUP
            // foreach ($customers as $customer) {
            //     for ($i = 0; $i < $faker->numberBetween(1, 10); $i++) {
            //         $user = User::factory()->create();

            //         $customer->users()->attach($user);
            //     }

            //     Address::factory()->create([
            //         'shipping_default' => true,
            //         'country_id' => 175,
            //         'customer_id' => $customer->id,
            //     ]);

            //     Address::factory()->create([
            //         'shipping_default' => false,
            //         'country_id' => 175,
            //         'customer_id' => $customer->id,
            //     ]);

            //     Address::factory()->create([
            //         'shipping_default' => false,
            //         'billing_default' => true,
            //         'country_id' => 175,
            //         'customer_id' => $customer->id,
            //     ]);

            //     Address::factory()->create([
            //         'shipping_default' => false,
            //         'billing_default' => false,
            //         'country_id' => 175,
            //         'customer_id' => $customer->id,
            //     ]);
            // }
        });
    }
}
