<section>
    <div class="max-w-screen-xl px-4 py-12 mx-auto sm:px-6 lg:px-8">
        <div class="grid items-start grid-cols-1 gap-8 md:grid-cols-2">
            <div class="grid grid-cols-2 gap-4 md:grid-cols-1">
                @if ($this->image)
                <div class="aspect-w-1 aspect-h-1">
                    <img class="object-cover rounded-xl" src="{{ $this->image->getUrl('large') }}" alt="{{ $this->product->translateAttribute('name') }}" />
                </div>
                @endif

                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    @foreach ($this->images as $image)
                    <div class="aspect-w-1 aspect-h-1" wire:key="image_{{ $image->id }}">
                        <img loading="lazy" class="object-cover rounded-xl" src="{{ $image->getUrl('small') }}" alt="{{ $this->product->translateAttribute('name') }}" />
                    </div>
                    @endforeach
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-bold">
                        {{ $this->product->translateAttribute('name') }}
                    </h1>

                    <x-product-price class="ml-4 font-medium" :variant="$this->variant" />
                </div>

                <p class="mt-1 text-sm text-gray-500">
                    {{ $this->variant->sku }}
                </p>


                <article class="mt-4 text-gray-700">
                    {{ $this->product->translateAttribute('description') }}
                </article>

                <article class="mt-2">
                    @php
                    $averageRating = $this->product->reviews()->avg('star_rating');
                    @endphp
                    @if($averageRating)
                    <div class="flex justify-between">

                        <div class="flex space-x-2">
                            <span>{{number_format($averageRating,1)}}</span> <x-bladewind::rating clickable="false" :rating="floor($averageRating)" color="yellow" /> <span class="text-sm text-gray-500">({{ $this->product->reviews()->count() }})</span>
                        </div>
                        <p class=" text-muted-foreground text-sm text-nowrap">{{ $this->product->total_units_sold }} sold</p>
                    </div>
                    @else
                    <p class="text-muted-foreground text-sm">No reviews yet</p>
                    @endif
                </article>


                <form class="mt-4">
                    <div class="space-y-4">
                        @foreach ($this->productOptions as $option)
                        <fieldset>
                            <legend class="text-xs font-medium text-gray-700">
                                {{ $option['option']->translate('name') }}
                            </legend>

                            <div class="flex flex-wrap gap-2 mt-2 text-xs tracking-wide uppercase" x-data="{
                                         selectedOption: @entangle('selectedOptionValues'),
                                         selectedValues: [],
                                     }" x-init="selectedValues = Object.values(selectedOption);
                                     $watch('selectedOption', value =>
                                         selectedValues = Object.values(selectedOption)
                                     )">
                                @foreach ($option['values'] as $value)
                                <button class="px-6 py-4 font-medium border rounded-lg focus:outline-none focus:ring" type="button" wire:click="
                                                $set('selectedOptionValues.{{ $option['option']->id }}', {{ $value->id }})
                                            " :class="{
                                                    'bg-primary border-gray-600 text-white hover:bg-primary/80': selectedValues
                                                        .includes({{ $value->id }}),
                                                    'hover:bg-gray-100': !selectedValues.includes({{ $value->id }})
                                                }">
                                    {{ $value->translate('name') }}
                                </button>
                                @endforeach
                            </div>
                        </fieldset>
                        @endforeach
                    </div>

                    <div class="max-w-xs mt-8">
                        <p class="mb-2 text-sm text-gray-500">
                            Stock: {{ $this->variant->stock }}
                        </p>
                        <livewire:components.add-to-cart :purchasable="$this->variant" :wire:key="$this->variant->id">
                    </div>
                </form>
            </div>
        </div>
        <div class="mt-10">
            <p class="font-bold mb-5">Product Reviews</p>
            <livewire:show-product-reviews :productId="$this->variant->product->id">
        </div>
    </div>
</section>