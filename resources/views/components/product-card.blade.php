@props(['product'])

<a class="block group" href="{{ route('product.view', $product->defaultUrl->slug) }}">
    <div class="overflow-hidden rounded-lg aspect-w-1 aspect-h-1 mb-2">
        @if ($product->thumbnail)
        <img class="object-cover transition-transform duration-300 group-hover:scale-105" src="{{ $product->thumbnail->getUrl('medium') }}" alt="{{ $product->translateAttribute('name') }}" />
        @endif
    </div>

    <strong class="mt-2 font-medium">
        {{ $product->translateAttribute('name') }}
    </strong>
    {{ $product->stock }}
    <p class="mt-1 text-xl text-primary font-bold">
        <span class="sr-only">
            Price
        </span>

        <x-product-price :product="$product" />
    </p>
    <article class="mt-2">
        @php
        $averageRating = $product->reviews()->avg('star_rating');
        @endphp
        @if($averageRating)
        <div class="flex justify-between">

            <div class="flex space-x-2 items-center w-full">
                <p>{{number_format($averageRating,1)}}</p>
                <x-bladewind::rating clickable="false" :rating="floor($averageRating)" color="yellow" />
                <p class="me-auto text-sm text-gray-500">({{ $product->reviews()->count() }})</p>
            </div>
            <p class=" text-muted-foreground text-sm text-nowrap">{{ $product->total_units_sold }} sold</p>
        </div>
        @else
        <p class="text-muted-foreground text-sm text-nowrap">No reviews yet</p>
        @endif

    </article>


</a>