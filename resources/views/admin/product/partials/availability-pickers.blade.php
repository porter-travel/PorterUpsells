@if($type == 'standard' && $hotel->property_type == 'hotel')
    <div class="space-y-4">
        <div>
            <h4 class="text-lg font-semibold text-slate-900">Availability moments</h4>
            <p class="text-sm text-slate-500">Select when guests can order this experience during their stay.</p>
        </div>
        <div class="space-y-3">
            <input type="hidden" name="specifics[on_arrival]" value="0">
            <x-fancy-checkbox
                label="Available on date of arrival"
                name="specifics[on_arrival]"
                :isChecked="isset($product->specifics['on_arrival']) ? $product->specifics['on_arrival'] : true"
            />
        </div>
        <div class="space-y-3">
            <input type="hidden" name="specifics[during_stay]" value="0">
            <x-fancy-checkbox
                label="Available during stay"
                name="specifics[during_stay]"
                :isChecked="isset($product->specifics['during_stay']) ? $product->specifics['during_stay'] : false"
            />
        </div>
        <div class="space-y-3">
            <input type="hidden" name="specifics[on_departure]" value="0">
            <x-fancy-checkbox
                label="Available on date of departure"
                name="specifics[on_departure]"
                :isChecked="isset($product->specifics['on_departure']) ? $product->specifics['on_departure'] : false"
            />
        </div>
    </div>
@else
    <input type="hidden" name="specifics[on_departure]" value="1">
    <input type="hidden" name="specifics[on_arrival]" value="1">
    <input type="hidden" name="specifics[during_stay]" value="1">
@endif
