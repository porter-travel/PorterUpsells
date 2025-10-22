@php
    $resdiaryMicrositeName = optional($hotel->connections->firstWhere('key', 'resdiary_microsite_name'))->value;
@endphp

<div class="space-y-4">
    <div>
        <h3 class="text-lg font-semibold text-gray-900">{{ $hotel->name }} Integrations</h3>
        <p class="mt-1 text-sm text-gray-500">Configure PMS and ResDiary settings for this property.</p>
    </div>

    <form method="post" action="{{ route('hotel.update', ['id' => $hotel->id]) }}">
        @csrf
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <x-input-label for="integration_name_{{ $hotel->id }}" :value="__('PMS Name')" />
                <select id="integration_name_{{ $hotel->id }}" name="integration_name" class="mt-1 block w-full rounded-md border-[#C4C4C4]">
                    <option value=""></option>
                    <option value="zonal" @selected($hotel->integration_name === 'zonal')>Zonal / High Level Software</option>
                </select>
            </div>
            <div>
                <x-input-label for="id_for_integration_{{ $hotel->id }}" :value="__('Hotel PMS ID')" />
                <x-text-input id="id_for_integration_{{ $hotel->id }}" name="id_for_integration" type="text"
                              class="mt-1 block w-full"
                              :value="$hotel->id_for_integration" placeholder="Integration Partner ID" />
            </div>
        </div>
        <div class="mt-6">
            <x-input-label for="resdiary_microsite_name_{{ $hotel->id }}" :value="__('ResDiary Microsite Name')" />
            <x-text-input id="resdiary_microsite_name_{{ $hotel->id }}" name="resdiary_microsite_name" type="text"
                          class="mt-1 block w-full"
                          :value="$resdiaryMicrositeName" placeholder="EnhanceMyStay" />
        </div>
        <div class="mt-6 text-right">
            <x-primary-button>Update</x-primary-button>
        </div>
    </form>
</div>
