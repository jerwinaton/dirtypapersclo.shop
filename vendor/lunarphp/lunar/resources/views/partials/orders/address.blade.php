<header class="flex items-center justify-between">
  <strong class="text-gray-700">
    {{ $heading }}
  </strong>

  @if($address && ($editTrigger ?? false))
  <!-- <button
      class="px-4 py-2 text-xs font-bold text-gray-700 bg-gray-100 border border-transparent rounded hover:border-gray-100 hover:bg-gray-50"
      type="button"
      wire:click.prevent="$set('{{ $editTrigger }}', true)"
    >
      {{ __('adminhub::global.edit') }}
    </button> -->
  @endif
</header>

@if($address->id)
@if(!$hidden)
<address class="mt-4 text-sm not-italic text-gray-600">
  <div class="flex justify-between border-b border-slate-200">
    <span>{{ __('adminhub::inputs.name')}}</span>
    {{ $address->fullName }}
  </div>
  <div class="flex justify-between border-b border-slate-200">
    <span>{{ __('adminhub::inputs.address_line_one.label')}}</span>
    {{ $address->line_one }}
  </div>
  @if ($address->line_two)
  <div class="flex justify-between border-b border-slate-200">
    <span>{{ __('adminhub::inputs.address_line_two.label')}}</span>
    {{ $address->line_two }}
  </div>
  @endif
  @if ($address->line_three)
  <div class="flex justify-between border-b border-slate-200">
    <span>{{ __('adminhub::inputs.address_line_three.label')}}</span>
    {{ $address->line_three }}
  </div>
  @endif
  @if ($address->city)
  <div class="flex justify-between border-b border-slate-200">
    <span>{{ __('adminhub::inputs.city.label')}}</span>
    {{ $address->city }}
  </div>
  @endif
  @if ($address->state)
  <div class="flex justify-between border-b border-slate-200">
    <span>{{ __('adminhub::inputs.state.label')}}</span>
    {{ $address->state }}
  </div>
  @endif
  <div class="flex justify-between border-b border-slate-200">
    <span>{{ __('adminhub::inputs.postcode.label')}}</span>
    {{ $address->postcode }} <br>
  </div>
  <div class="flex justify-between border-b border-slate-200">
    <span>{{ __('adminhub::inputs.country.label')}}</span>
    {{ $address->country->name }} <br>
  </div>


  <div class="mt-2">
    <div class="flex items-center">
      <x-hub::icon ref="phone" class="w-4 mr-2" />
      @if($address->contact_phone)
      <a href="tel:{{ $address->contact_phone }}" class="text-sky-500 underline">{{ $address->contact_phone }}</a>
      @else
      <span class="text-xs text-gray-500">{{ __('adminhub::global.not_provided') }}</span>
      @endif
    </div>

    <div class="flex items-center">
      <x-hub::icon ref="mail" class="w-4 mr-2" />
      @if($address->contact_email)
      <a href="mailto:{{ $address->contact_email }}" class="text-sky-500 underline">{{ $address->contact_email }}</a>
      @else
      <span class="text-xs text-gray-500">{{ __('adminhub::global.not_provided') }}</span>
      @endif
    </div>
  </div>
</address>
@else
<span class="text-sm text-gray-600">{{ $message ?? null }}</span>
@endif
@else
<span class="text-sm text-gray-600">
  {{ __('adminhub::partials.orders.address.not_set') }}
</span>
@endif