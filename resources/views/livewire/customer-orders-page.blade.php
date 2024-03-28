<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 ">
        @if ($showDetails)
        <div class="max-w-4xl mx-auto">
            <div class="flex space-x-2 items-center mb-2">
                <button class="text-blue-500 p-2" wire:click=" $set('showDetails', false)">Go Back</button>
                @if ($showDetails && $selectedOrderId && $orders->isNotEmpty() )
                <p class="font-bold text-muted-foreground">
                    Order Reference: {{ $orders->first()->reference }}
                </p>
                <a class="inline-flex text-xs ms-auto items-center px-4 py-2 font-bold transition border border-transparent border-gray-200 rounded hover:bg-white bg-gray-50 hover:border-gray-200" href="{{ route('hub.orders.pdf', $selectedOrderId) }}" target="_blank">
                    <x-hub::icon ref="download" class="w-4 mr-2" />
                    e-Invoice
                </a>
                @endif
            </div>
            <!-- Show detailed information -->
            <div class="bg-white rounded p-4">
                @if(!is_null( $selectedOrder))
                @include('partials.orders.lines' ,['order'=>$selectedOrder])
                <div class="mt-4 grid grid-cols-2 sm:grid-cols-2 gap-2">
                    <div>

                        @include('partials.orders.totals', ['order'=>$selectedOrder] )
                    </div>


                    <section class="p-4 bg-white rounded-lg shadow">
                        @include('partials.orders.address', [
                        'heading' => __('adminhub::components.orders.show.shipping_header'),
                        'hidden' => false,
                        'address' => $selectedOrder->shippingAddress,
                        ])
                    </section>
                </div>
                @endif
            </div>
        </div>
        @else
        <div x-data=" { tab: 'pending' }" class="mx-2">
            <div>
                <div class="">
                    <nav class="flex space-x-4" aria-label="Tabs">

                        <button type="button" x-on:click.prevent="tab = 'pending'" wire:click="setSelectedStatus('pending')" class="px-3 py-2 text-sm font-medium rounded-md " :class="{
                                    'bg-white shadow': tab == 'pending',
                                    'hover:text-gray-700 text-gray-500': tab != 'pending'
                                }">
                            Pending
                        </button>

                        <button type="button" x-on:click.prevent="tab = 'dispatched'" wire:click="setSelectedStatus('dispatched')" class="px-3 py-2 text-sm font-medium rounded-md " :class="{
                                    'bg-white shadow': tab == 'dispatched',
                                    'hover:text-gray-700 text-gray-500': tab != 'dispatched'
                                }">
                            To Ship
                        </button>

                        <button type="button" x-on:click.prevent="tab = 'completed'" wire:click="setSelectedStatus('completed')" class="px-3 py-2 text-sm font-medium rounded-md " :class="{
                                    'bg-white shadow': tab == 'completed',
                                    'hover:text-gray-700 text-gray-500': tab != 'completed'
                                }">
                            Completed
                        </button>
                        <button type="button" x-on:click.prevent="tab = 'cancelled'" wire:click="setSelectedStatus('cancelled')" class="px-3 py-2 text-sm font-medium rounded-md " :class="{
                                    'bg-white shadow': tab == 'cancelled',
                                    'hover:text-gray-700 text-gray-500': tab != 'cancelled'
                                }">
                            Cancelled
                        </button>

                    </nav>
                </div>
            </div>


        </div>
        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                    <div class="mx-2">
                        @if(!$orders->isNotEmpty())
                        <div class="flex justify-center py-4 rounded bg-white">
                            <p>
                                No items
                            </p>
                        </div>
                        @else
                        <table class="rounded-xl border bg-white min-w-full text-left text-sm font-light text-surface dark:text-white ">
                            <thead class="border-b border-neutral-200 font-medium dark:border-white/10">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Item</th>
                                    <th scope="col" class="px-6 py-4">Total</th>
                                    <th scope="col" class="px-6 py-4 hidden md:table-cell">Quantity</th>
                                    <th scope="col" class="px-6 py-4 hidden md:table-cell">Placed at</th>
                                    @if($orders && $orders->isNotEmpty() && $orders->first()->status == "dispatched")
                                    <th scope="col" class="px-6 py-4 hidden md:table-cell">Shipped at</th>
                                    @endif
                                    @if($orders && $orders->isNotEmpty() && $orders->first()->status == "completed")
                                    <th scope="col" class="px-6 py-4 hidden md:table-cell">Completed at</th>
                                    @endif
                                    @if($orders && $orders->isNotEmpty() && $orders->first()->status == "cancelled")
                                    <th scope="col" class="px-6 py-4 hidden md:table-cell">Cancelled at</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($orders as $index => $order )
                                <tr wire:click="showOrderDetails({{ $order->id }})" wire:key=" {{ $order->id }}" class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-sky-100/40' }} hover:bg-sky-500/20  cursor-pointer">
                                    <td class="truncate px-6 py-4">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                @if ($thumbnail = $order->lines->first()->purchasable?->getThumbnail())
                                                <x-hub::thumbnail :src="$thumbnail->getUrl('small')" />
                                                @else
                                                <x-hub::icon ref="photograph" class="w-16 h-16 text-gray-300" />
                                                @endif
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-bold leading-tight text-gray-800 truncate">
                                                    {{$order->lines->first()->description}}
                                                </p>
                                                @if ($order->lines->first()->purchasable?->getOptions()?->count())
                                                <dl class="flex before:text-gray-200 text-xs space-x-3">
                                                    <span>Variation:</span>
                                                    @foreach ($order->lines->first()->purchasable->getOptions() as $option)
                                                    <div class="flex gap-0.5">
                                                        <dt>{{ $option }}</dt>
                                                    </div>
                                                    @endforeach
                                                </dl>
                                                @endif
                                                @if ($order->lines->filter(function($line) {
                                                return in_array($line->type, ['physical', 'digital']);
                                                })->sum('quantity') > 1)
                                                <p class="text-xs text-muted-foreground">
                                                    And {{ $order->lines->filter(function($line) {
            return in_array($line->type, ['physical', 'digital']);
        })->sum('quantity') - 1 }} more
                                                </p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ $order->total->formatted() }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 hidden md:table-cell">
                                        {{ $order->lines->filter(function($line) {
    return in_array($line->type, ['physical', 'digital']);
})->sum('quantity') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 hidden md:table-cell">
                                        {{ \Illuminate\Support\Carbon::parse($order->placed_at)->timezone('Asia/Manila')->format('M j, Y g:i A') }}
                                    </td>
                                    @if($order->dispatched_at)
                                    <td class="whitespace-nowrap px-6 py-4 hidden md:table-cell">
                                        {{ \Illuminate\Support\Carbon::parse($order->dispatched_at)->timezone('Asia/Manila')->format('M j, Y g:i A') }}
                                    </td>
                                    @endif
                                    @if($order->completed_at)
                                    <td class="whitespace-nowrap px-6 py-4 hidden md:table-cell">
                                        {{ \Illuminate\Support\Carbon::parse($order->completed_at)->timezone('Asia/Manila')->format('M j, Y g:i A') }}
                                    </td>
                                    @endif
                                    @if($order && $order->cancelled_at)
                                    <td class="whitespace-nowrap px-6 py-4 hidden md:table-cell">
                                        {{ \Illuminate\Support\Carbon::parse($order->cancelled_at)->timezone('Asia/Manila')->format('M j, Y g:i A') }}
                                    </td>
                                    @endif
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                        @endif


                    </div>
                </div>
            </div>

        </div>
        {{ $orders->links() }}
        @endif
    </div>
</div>