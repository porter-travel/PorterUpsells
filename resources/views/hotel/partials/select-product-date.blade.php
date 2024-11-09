<div class="select-product-date" @if(count($dateArray) == 1) style="display: none" @endif>
    <p class="hotel-text-color text-xl font-bold">Select when you would like this.</p>
    <ul class="flex flex-wrap mt-4">
        @php($i = 1)
        @php($firstAvailableChecked = false) <!-- Flag to track if the first available date has been checked -->
        @foreach ($dateArray as $date)
            <li class="basis-full sm:basis-1/2 md:basis-1/3 @if($date['status'] == 'unavailable') opacity-10 @endif">
                <label
                    class="border border-black bg-[#F7F7F7] rounded p-2 flex items-center mr-2 mb-2 basis-1/3 fancy-checkbox">
                    <input
                        data-stay-date-selector
                        style="width: 0; height: 0; opacity: 0"
                        name="dates[]"
                        type="checkbox"
                        value="{{ $date['date'] }}"
                        @if($date['status'] == 'unavailable')
                            disabled
                        @endif
                        @if($date['status'] == 'available' && !$firstAvailableChecked)
                            checked
                    @php($firstAvailableChecked = true) <!-- Set flag to true after the first available date is checked -->
                    @endif
                    >
                    <span class="w-[29px] h-[29px] border border-darkGrey rounded mr-2 relative"></span>
                    <span></span>
                    <span class="relative">
                <span class="font-bold">Day {{$i}}</span>
                ({{ \Carbon\Carbon::parse($date['date'])->format('jS M') }})
            </span>
                </label>
            </li>
            @php($i++)
        @endforeach

    </ul>
</div>
