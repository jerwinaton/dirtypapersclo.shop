<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div>
                <div x-data="{ tab: 'cart' }">
                    <div>
                        <div class="hidden sm:block">
                            <nav class="flex space-x-4" aria-label="Tabs">

                                <button type="button" x-on:click.prevent="tab = 'cart'" class="px-3 py-2 text-sm font-medium rounded-md " :class="{
                                    'bg-white shadow': tab == 'cart',
                                    'hover:text-gray-700 text-gray-500': tab != 'cart'
                                }">
                                    Cart
                                </button>

                                <button type="button" x-on:click.prevent="tab = 'to_ship'" class="px-3 py-2 text-sm font-medium rounded-md " :class="{
                                    'bg-white shadow': tab == 'to_ship',
                                    'hover:text-gray-700 text-gray-500': tab != 'to_ship'
                                }">
                                    To Ship
                                </button>



                                <a href="#" x-on:click.prevent="tab = 'completed'" class="px-3 py-2 text-sm font-medium rounded-md " :class="{
                               'bg-white shadow': tab == 'completed',
                               'hover:text-gray-700 text-gray-500': tab != 'completed'
                           }">
                                    Completed
                                </a>
                            </nav>
                        </div>
                    </div>

                    <div x-show="tab == 'cart'" class="mt-4">
                        <div class="w-full mt-12 text-sm text-center text-gray-500">
                            Cart
                        </div>

                    </div>

                    <div x-show="tab == 'to_ship'" class="mt-4">
                        <div class="w-full mt-12 text-sm text-center text-gray-500">
                            To Ship
                        </div>
                    </div>


                    <div x-show="tab == 'completed'" class="mt-4">
                        <div class="w-full mt-12 text-sm text-center text-gray-500">
                            Completed
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                        <div class="overflow-hidden">
                            <table class="rounded-xl border bg-white min-w-full text-left text-sm font-light text-surface dark:text-white">
                                <thead class="border-b border-neutral-200 font-medium dark:border-white/10">
                                    <tr>
                                        <th scope="col" class="px-6 py-4">Reference</th>
                                        <th scope="col" class="px-6 py-4">Total</th>
                                        <th scope="col" class="px-6 py-4">Status</th>
                                        <th scope="col" class="px-6 py-4">Placed at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $index => $order )
                                    <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-sky-100/40' }} hover:bg-primary/10">

                                        <td class="whitespace-nowrap px-6 py-4">

                                            {{ $order->reference }}

                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            {{ $order->total->formatted() }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            {{ $order->status }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            {{ $order->placed_at }}
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>