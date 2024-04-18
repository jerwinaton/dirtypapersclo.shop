@foreach ($order->lines->filter(function($line) {
return in_array($line->type, ['physical', 'digital']);
}) as $line)
<div x-data="{ showDetails: false }" class="my-2">
    <div class="flex">
        <div class="">
            @if ($thumbnail = $line->purchasable?->getThumbnail())
            <x-hub::thumbnail :src="$thumbnail->getUrl('small')" />
            @else
            <x-hub::icon ref="photograph" class="w-16 h-16 text-gray-300" />
            @endif
        </div>
        <button class="flex mt-2 group xl:mt-0" x-on:click="showDetails = !showDetails" type="button">
            <div class="transition-transform " :class="{
                                 '-rotate-90 ': !showDetails
                             }">
                <x-hub::icon ref="chevron-down" class="w-6 mx-1 text-gray-400 -mt-7 group-hover:text-gray-500 xl:mt-0" />
            </div>
            <div class="max-w-sm space-y-2 text-left">
                <x-hub::tooltip :text="$line->description" :left="true">
                    <p class="text-sm font-bold leading-tight text-gray-800 truncate">
                        {{ $line->description }}
                    </p>
                </x-hub::tooltip>

                <div class="flex text-xs font-medium text-gray-600">
                    <p>{{ $line->identifier }}</p>

                    @if ($line->purchasable?->getOptions()?->count())
                    <dl class="flex before:content-['|'] before:mx-3 before:text-gray-200 space-x-3">
                        @foreach ($line->purchasable->getOptions() as $option)
                        <div class="flex gap-0.5">
                            <dt>{{ $option }}</dt>
                        </div>
                        @endforeach
                    </dl>
                    @endif
                </div>
            </div>
        </button>
        <div class="ms-auto">
            <p class="text-sm font-medium text-gray-700">
                {{ $line->quantity }} @ {{ $line->unit_price->formatted }}

                <span class="ml-1">
                    {{ $line->sub_total->formatted }}
                </span>
            </p>
        </div>

        <div class="ms-3">
            @php

            $reviewAvailable = $line->purchasable && $line->purchasable->reviews()->where([
            'product_id' => $line->purchasable->product_id,
            'product_variant_id' => $line->purchasable->id,
            'customer_id' => auth()->id(),
            ])->exists();
            @endphp


            <x-primary-button x-on:click.prevent="$wire.set('selectedProductVariantToReviewId', {{ $line->purchasable->id }});$dispatch('open-modal', 'review-form');" class="bg-primary"> @if ($reviewAvailable) View Review @else Add Review @endif</x-primary-button>

        </div>

    </div>
    <div x-show="showDetails" class="mt-4 space-y-4">
        <article class="text-sm">
            <p>
                <strong>{{ __('adminhub::global.notes') }}:</strong>

                {{ $line->notes }}
            </p>
        </article>

        <div class="overflow-hidden overflow-x-auto border border-gray-200 rounded">
            <table class="min-w-full text-xs divide-y divide-gray-200">
                <tbody class="divide-y divide-gray-200">
                    <tr class="divide-x divide-gray-200">
                        <td class="p-2 font-medium text-gray-900 whitespace-nowrap">
                            {{ __('adminhub::partials.orders.lines.unit_price') }}
                        </td>

                        <td class="p-2 text-gray-700 whitespace-nowrap">
                            {{ $line->unit_price->formatted }} / {{ $line->unit_quantity }}
                        </td>
                    </tr>

                    <tr class="divide-x divide-gray-200">
                        <td class="p-2 font-medium text-gray-900 whitespace-nowrap">
                            {{ __('adminhub::partials.orders.lines.quantity') }}
                        </td>

                        <td class="p-2 text-gray-700 whitespace-nowrap">
                            {{ $line->quantity }}
                        </td>
                    </tr>

                    <tr class="divide-x divide-gray-200">
                        <td class="p-2 font-medium text-gray-900 whitespace-nowrap">
                            {{ __('adminhub::partials.orders.lines.sub_total') }}
                        </td>

                        <td class="p-2 text-gray-700 whitespace-nowrap">
                            {{ $line->sub_total->formatted }}
                        </td>
                    </tr>

                    <tr class="divide-x divide-gray-200">
                        <td class="p-2 font-medium text-gray-900 whitespace-nowrap">
                            {{ __('adminhub::partials.orders.lines.discount_total') }}
                        </td>

                        <td class="p-2 text-gray-700 whitespace-nowrap">
                            {{ $line->discount_total->formatted }}
                        </td>
                    </tr>

                    @foreach ($line->tax_breakdown->amounts as $tax)
                    <tr class="divide-x divide-gray-200">
                        <td class="p-2 font-medium text-gray-900 whitespace-nowrap">
                            {{ $tax->description }}
                        </td>

                        <td class="p-2 text-gray-700 whitespace-nowrap">
                            {{ $tax->price->formatted }}
                        </td>
                    </tr>
                    @endforeach

                    <tr class="divide-x divide-gray-200">
                        <td class="p-2 font-medium text-gray-900 whitespace-nowrap">
                            {{ __('adminhub::partials.orders.lines.total') }}
                        </td>

                        <td class="p-2 text-gray-700 whitespace-nowrap">
                            {{ $line->total->formatted }}
                        </td>
                        <td>

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endforeach