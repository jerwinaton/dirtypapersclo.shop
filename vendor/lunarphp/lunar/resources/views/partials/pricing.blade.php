<div>
    <div class="overflow-hidden shadow sm:rounded-md">
        <div class="flex-col px-4 py-5 space-y-4 bg-white sm:p-6">
            <header class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        {{ __('adminhub::partials.pricing.title') }}
                    </h3>
                </div>
                <div class="flex items-center space-x-2">
                    <div>
                        <select wire:change="setCurrency($event.target.value)" class="block w-full py-1 pl-2 pr-8 text-base text-gray-600 bg-gray-100 border-none rounded-md form-select focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm">
                            @foreach($this->currencies as $c)
                            <option value="{{ $c->id }}" @if($currency->id == $c->id) selected @endif>{{ $c->code }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </header>

            <!-- <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-hub::input.group :label="__('adminhub::inputs.tax_class.label')" for="tax_class">
                        <x-hub::input.select id="tax_class" wire:model="variant.tax_class_id">
                            @foreach($this->taxClasses as $taxClass)
                                <option wire:key="tax_class_{{ $taxClass->id }}"
                                        value="{{ $taxClass->id }}">{{ $taxClass->name }}</option>
                            @endforeach
                        </x-hub::input.select>
                    </x-hub::input.group>
                </div>
                <div>
                    <x-hub::input.group
                            :label="__('adminhub::inputs.tax_ref.label')"
                            :instructions="__('adminhub::inputs.tax_ref.instructions')"
                            :errors="$errors->get('variant.tax_ref')"
                            for="unit_quantity"
                    >
                        <x-hub::input.text wire:model="variant.tax_ref" id="tax_ref"/>
                    </x-hub::input.group>
                </div>
            </div> -->
            <div class="space-y-4">
                <div class="grid grid-cols-3 gap-4">
                    <x-hub::input.group :label="__('adminhub::inputs.unit_quantity.label')" :instructions="__('adminhub::inputs.unit_quantity.instructions')" :errors="$errors->get('variant.unit_quantity')" for="unit_quantity">
                        <x-hub::input.text type="number" wire:model="variant.unit_quantity" id="unit_quantity" />
                    </x-hub::input.group>

                    <x-hub::input.group :label="__(
                                $this->pricesIncludeTax ?
                                   'adminhub::inputs.base_price_inc_tax.label' :
                                   'adminhub::inputs.base_price_excl_tax.label'
                            )" :instructions="__(
                              $this->pricesIncludeTax ?
                                   'adminhub::inputs.base_price_inc_tax.instructions' :
                                   'adminhub::inputs.base_price_excl_tax.instructions'
                            )" for="basePrice" :errors="$errors->get('basePrices.*.price')" required>
                        <x-hub::input.price wire:model="basePrices.{{ $this->currency->code }}.price" :error="$errors->first('basePrices.'.$this->currency->code.'.price')" :currencyCode="$this->currency->code" required />
                    </x-hub::input.group>

                    <x-hub::input.group :label="__(
                                $this->pricesIncludeTax ?
                                   'adminhub::inputs.compare_at_price_inc_tax.label' :
                                   'adminhub::inputs.compare_at_price_excl_tax.label'
                            )" :instructions="__(
                                $this->pricesIncludeTax ?
                                   'adminhub::inputs.compare_at_price_inc_tax.instructions' :
                                   'adminhub::inputs.compare_at_price_excl_tax.instructions'
                            )" for="compare_at_price" :errors="$errors->get('basePrices.*.compare_price')">
                        <x-hub::input.price wire:model="basePrices.{{ $this->currency->code }}.compare_price" :currencyCode="$this->currency->code" :error="$errors->first('basePrices.*.compare_price')" />
                    </x-hub::input.group>
                </div>
            </div>


        </div>
    </div>
</div>