<div class="py-12" x-data=" { tab: 'pending' }">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 ">
        @if ($showDetails)
        @if(!$showReview)
        <div class="max-w-5xl mx-auto">
            <div class="flex space-x-2 items-center mb-2">
                <button class="text-blue-500 p-2" wire:click="$set('showDetails', false)">Go Back</button>
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
            @if(!is_null( $selectedOrder))
            <div class="bg-white rounded p-4">
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
            </div>
            @endif
        </div>
        @else
        <div class="max-w-5xl mx-auto">
            <div class="flex space-x-2 items-center mb-2">
                <button class="text-blue-500 p-2" wire:click=" $set('showDetails',false)">Go Back</button>
                @if ($showDetails && $selectedOrderId && $orders->isNotEmpty() )
                <p class="font-bold text-muted-foreground">
                    Order Reference: {{ $orders->first()->reference }}
                </p>
                @endif
            </div>
            <!-- Show detailed information -->
            <div class="bg-white rounded p-4">
                @if(!is_null( $selectedOrder))
                @include('partials.orders.lines-with-review-btn' ,['order'=>$selectedOrder, 'showReviewBtn'=>true])
                @endif
            </div>
        </div>
        @endif
        @else
        <div class="mx-2">
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
                            <thead class="border-b border-neutral-200  text-muted-foreground font-medium dark:border-white/10">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Item</th>
                                    <!-- <th scope="col" class="px-6 py-4 hidden md:table-cell">Quantity</th> -->
                                    @if ($orders && $orders->isNotEmpty())
                                    @php
                                    $firstOrder = $orders->first();
                                    $statusesToShow = ['payment-received', 'completed', 'cancelled'];
                                    @endphp

                                    @if (in_array($firstOrder->status, $statusesToShow))
                                    <th scope="col" class="px-6 py-4 hidden md:table-cell">Placed at</th>
                                    @endif
                                    @endif
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
                                <tr wire:key=" {{ $order->id }}" class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-sky-100/40' }} ">
                                    <td class="truncate px-6 py-4 hover:bg-sky-500/20 cursor-pointer" wire:click="showOrderDetails({{ $order->id }})">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                @if ($thumbnail = $order->lines->first()->purchasable?->getThumbnail())
                                                <x-hub::thumbnail :src="$thumbnail->getUrl('small')" />
                                                @else
                                                <x-hub::icon ref="photograph" class="w-16 h-16 text-gray-300" />
                                                @endif
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-blue-500 font-bold leading-tight hover:underline truncate">
                                                    {{$order->lines->first()->description}}
                                                </p>
                                                @if ($order->lines->first()->purchasable?->getOptions()?->count())
                                                <dl class="flex text-gray-800 text-xs space-x-3">
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
                                                <p class="text-primary text-xs"> {{ $order->total->formatted() }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- <td class="whitespace-nowrap px-6 py-4 hidden md:table-cell">
                                        {{ $order->lines->filter(function($line) {
                                     return in_array($line->type, ['physical', 'digital']);
                                        })->sum('quantity') }}
                                    </td> -->
                                    @if ($orders && $orders->isNotEmpty())
                                    @php
                                    $firstOrder = $orders->first();
                                    $statusesToShow = ['payment-received', 'completed', 'cancelled'];
                                    @endphp

                                    @if (in_array($firstOrder->status, $statusesToShow))
                                    <td class="whitespace-nowrap px-6 py-4 text-foreground hidden md:table-cell">
                                        {{ \Illuminate\Support\Carbon::parse($order->placed_at)->timezone('Asia/Manila')->format('M j, Y g:i A') }}
                                    </td>
                                    @endif
                                    @endif
                                    @if($order->status == "dispatched" && $order->dispatched_at)
                                    <td class="whitespace-nowrap px-6 py-4 text-foreground hidden md:table-cell">
                                        {{ \Illuminate\Support\Carbon::parse($order->dispatched_at)->timezone('Asia/Manila')->format('M j, Y g:i A') }}
                                    </td>
                                    @endif
                                    @if($order->status == "completed" && $order->completed_at)
                                    <td class="whitespace-nowrap px-6 py-4 text-foreground hidden md:table-cell">
                                        {{ \Illuminate\Support\Carbon::parse($order->completed_at)->timezone('Asia/Manila')->format('M j, Y g:i A') }}
                                    </td>
                                    @endif
                                    @if($order->status == "cancelled" && $order && $order->cancelled_at)
                                    <td class="whitespace-nowrap px-6 py-4 text-foreground hidden md:table-cell">
                                        {{ \Illuminate\Support\Carbon::parse($order->cancelled_at)->timezone('Asia/Manila')->format('M j, Y g:i A') }}
                                    </td>
                                    @endif
                                    @if($order->status == "dispatched" )
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <x-primary-button wire:click="setSelectedOrderId({{$order->id}})" x-on:click.prevent="$dispatch('open-modal', 'confirm-order-receive')" class="bg-primary">Order Received</x-primary-button>
                                    </td>

                                    @endif
                                    @if($order->status == "completed" )
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <x-primary-button wire:click="showReviewView({{$order->id}})" class="bg-primary">Add review</x-primary-button>
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
        <x-modal name="confirm-order-receive" maxWidth="sm">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Confirm order was received?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Order reference: ') }} {{$selectedOrder?->reference}}
                </p>
                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-primary-button wire:click="receiveOrder({{ $selectedOrder?->id }})" x-on:click="$dispatch('close')" class="ms-3">
                        Confirm
                    </x-primary-button>
                </div>
            </div>
        </x-modal>

        <x-modal name="review-form" maxWidth="sm">
            @if(!is_null($selectedProductVariant?->id))
            @php
            $review = $selectedProductVariant->reviews()
            ->where('product_id', $selectedProductVariant->product_id)
            ->where('product_variant_id', $selectedProductVariant->id)
            ->where('customer_id', auth()->id())
            ->where('order_id', $selectedOrder?->id)
            ->first();
            @endphp
            <div class="p-6">

                <h2 class="text-lg font-medium text-gray-900">
                    @if($review)
                    {{ __('View Review') }}
                    @else
                    {{ __('Add Review') }}
                    @endif
                </h2>

                @if ($selectedProductVariant?->getOptions()?->count())
                <dl class="flex text-gray-800 text-xs space-x-3">
                    <span>Variation:</span>
                    @foreach ($selectedProductVariant->getOptions() as $option)
                    <div class="flex gap-0.5">
                        <dt>{{ $option }}</dt>
                    </div>
                    @endforeach
                </dl>
                @endif
                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Product Name: ') }} {{$selectedProductVariant?->getDescription() }}
                </p>

                <div class="mt-6 flex">
                    @if(!is_null($review))
                    <div class="flex flex-col">
                        <label for="review">Review:</label>
                        <x-bladewind::rating clickable="false" :rating="$review->star_rating" color="yellow" name="starRatingView" />
                        <p>{{$review->review}}</p>
                    </div>
                    @else
                    <!-- Show add review form when no review is available -->
                    <livewire:add-product-review wire:key="'add-product-review-' . $selectedProductVariant?->id" :orderId="$selectedOrder?->id" :productId="$selectedProductVariant?->product_id" :productVariantId="$selectedProductVariant?->id" />
                    @endif
                </div>
            </div>
            @else
            <!-- Show loading icon while review is being fetched -->
            <div class="flex justify-center items-center">
                <p class="me-2">Loading</p>
                <x-icon.loading />
            </div>
            @endif
        </x-modal>

    </div>
    <x-bladewind::modal ok_button_action="location.reload()" cancel_button_label="" stretched_action_buttons="true" size="medium" type="success" title="Review Submitted" backdrop_can_close="false" name="review_submitted">
        Review submitted successfully
    </x-bladewind::modal>
</div>



<script>
    document.addEventListener('livewire:load', function() {
        Livewire.on('reviewSubmitted', function() {
            // Reload the page
            showModal('review_submitted')
            console.log("hey")
        });
    });
</script>