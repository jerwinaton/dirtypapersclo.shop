<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

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