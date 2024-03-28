<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Orders') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
        <div x-data="{ tab: 'pending' }" class="mx-2">
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
                                <tr wire:key="{{ $order->id }}" class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-sky-100/40' }} hover:bg-sky-500/20  cursor-pointer">
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
                                                    Variation:
                                                    @foreach ($order->lines->first()->purchasable->getOptions() as $option)
                                                    <div class="flex gap-0.5">
                                                        <dt>{{ $option }}</dt>
                                                    </div>
                                                    @endforeach
                                                </dl>
                                                @endif
                                                @if( $order->lines->sum('quantity') > 1)
                                                <p class="text-xs text-muted-foreground">And {{$order->lines->sum('quantity')-1}} more</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ $order->total->formatted() }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 hidden md:table-cell">
                                        {{ $order->lines->sum('quantity') }}
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
    </div>
</div>