<div class="space-y-4">
  {{ $this->addresses->links() }}
  <div class="grid gap-4 text-sm grid-cols-1 sm:grid-cols-2 md:grid-cols-3">
    @foreach($this->addresses as $address)
    <div wire:key="address_{{ $address->id }}" class="leading-relaxed bg-white rounded shadow">
      <div class="flex justify-between px-4 py-3 rounded-t bg-gray-50">
        <div class="space-x-1">
          @if($address->billing_default)
          <span class="px-3 py-1 text-xs text-sky-500 bg-sky-50">Billing Default</span>
          @endif
          @if($address->shipping_default)
          <span class="px-3 py-1 text-xs text-green-600 bg-green-50">Shipping Default</span>
          @endif
        </div>
        <!-- hide -->
        <!-- <div class="flex space-x-4">
          <x-hub::button theme="gray" size="xs" wire:click.prevent="$set('addressIdToEdit', '{{ $address->id }}')">Edit</x-hub::button>

          <x-hub::button theme="danger" size="xs" wire:click.prevent="$set('addressToRemove', '{{ $address->id }}')">
            {{ __('adminhub::components.customers.show.remove_address_btn') }}
          </x-hub::button>
        </div> -->
      </div>

      <div class="p-4">
        <div class="flex justify-between border-b border-slate-200">
          <span>Name</span>
          <span class="block">{{ $address->first_name }} {{ $address->last_name }}</span>
        </div>
        <!-- 
        @if($address->company_name)
        <span class="block">{{ $address->company_name }}
          @endif -->
        <div class="flex justify-between border-b border-slate-200">
          <span>{{ __('adminhub::inputs.address_line_one.label')}}</span>
          <span class="block">{{ $address->line_one }}</span>
        </div>
        <div class="flex justify-between border-b border-slate-200">
          <span>{{ __('adminhub::inputs.address_line_two.label')}}</span>
          @if($address->line_two)
          <span class="block">{{ $address->line_two }}
            @endif
        </div>
        <div class="flex justify-between border-b border-slate-200">
          <span>{{ __('adminhub::inputs.address_line_three.label')}}</span>
          @if($address->line_three)
          <span class="block">{{ $address->line_three }}</span>
          @endif
        </div>
        <div class="flex justify-between border-b border-slate-200">
          <span>{{ __('adminhub::inputs.city.label')}}</span>
          <span class="block">{{ $address->city }}</span>
        </div>
        <div class="flex justify-between border-b border-slate-200">
          <span>{{ __('adminhub::inputs.state.label')}}</span>
          <span class="block">{{ $address->state }}</span>
        </div>
        <div class="flex justify-between border-b border-slate-200">
          <span>{{ __('adminhub::inputs.postcode.label')}}</span>
          <span class="block">{{ $address->postcode }}</span>
        </div>
        <div class="flex justify-between border-b border-slate-200">
          <span>{{ __('adminhub::inputs.country.label')}}</span>
          <span class="block">{{ $address->country?->name }}</span>
        </div>
        <div class="flex justify-between border-b border-slate-200">
          <span>{{ __('adminhub::inputs.contact_email.label')}}</span>
          <span class="block">{{ $address->contact_email }}</span>
        </div>
        <div class="flex justify-between border-b border-slate-200">
          <span>{{ __('adminhub::inputs.contact_phone.label')}}</span>
          <span class="block">{{ $address->contact_phone }}</span>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>