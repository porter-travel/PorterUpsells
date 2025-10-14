<x-hotel-admin-layout :hotel="$hotel">
    <x-slot name="header">
        <div class="flex items-center justify-between rounded-2xl bg-white px-6 py-4 shadow-sm">
            <h2 class="text-2xl font-semibold tracking-tight text-slate-900">
                {{ __('Calendar Orders for: ') . $product->name }}
            </h2>

            <div id="product-selector" class="relative flex items-center rounded-xl border border-slate-200 bg-white px-3 py-2 shadow-sm">
                <span class="text-sm font-medium text-slate-700">
                {{$product->name}}
                    </span>
                <ul class="absolute left-0 top-full z-20 mt-2 hidden w-48 overflow-hidden rounded-xl border border-slate-200 bg-white py-2 shadow-lg">
                    @php
                        $queryString = request()->getQueryString();
                        $queryString = $queryString ? '?' . $queryString : '';
                    @endphp
                    @foreach($products as $loopProduct)
                        <li class="@if($product->id == $loopProduct->id) hidden @endif">
                            <a href="{{ route('calendar.list-bookings-for-product', ['hotel_id' => $hotel->id, 'product_id' => $loopProduct->id]) . $queryString }}" class="block px-4 py-2 text-sm text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                                {{$loopProduct->name}}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <button class="ml-3 inline-flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:border-[#FF5E57] hover:text-[#FF5E57]">
                    <svg width="15" height="8" viewBox="0 0 15 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.5 8L15 0H0L7.5 8Z" fill="black"/>
                    </svg>
                </button>
            </div>
        </div>
    </x-slot>
    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl">
                <div class="space-y-8 bg-white p-8">
                    <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                        <form method="get" class="rounded-2xl border border-slate-200/60 bg-slate-50/60 p-4 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500">Date</p>
                            <div class="mt-3 flex items-center gap-3">
                                <a href="?date={{$yesterday}}" class="group inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:border-[#FF5E57] hover:text-[#FF5E57]">
                                    <svg width="6" height="11" viewBox="0 0 6 11" fill="none" xmlns="http://www.w3.org/2000/svg" class="transition group-hover:scale-110">
                                        <path d="M-2.40413e-07 5.5L6 11L6 0L-2.40413e-07 5.5Z" fill="currentColor"/>
                                    </svg>
                                </a>
                                <div>
                                    <label class="sr-only block" for="calendarDatePicker">Date</label>
                                    <input id="calendarDatePicker" onchange="this.form.submit();" type="date" name="date" value="{{$date}}" class="h-10 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm outline-none transition focus:border-[#FF5E57] focus:ring-2 focus:ring-[#FF5E57]/30">
                                </div>
                                <a href="?date={{$tomorrow}}" class="group inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:border-[#FF5E57] hover:text-[#FF5E57]">
                                    <svg width="6" height="11" viewBox="0 0 6 11" fill="none" xmlns="http://www.w3.org/2000/svg" class="transition group-hover:scale-110">
                                        <path d="M6 5.5L-4.80825e-07 0L0 11L6 5.5Z" fill="currentColor"/>
                                    </svg>
                                </a>
                            </div>
                        </form>

                        <div class="flex flex-col gap-4 text-right text-sm text-slate-500">
                            <span class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400">Legend</span>
                            <div class="flex flex-wrap items-center justify-end gap-3">
                                <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-emerald-700"><span class="h-2 w-2 rounded-full bg-emerald-500"></span> Available</span>
                                <span class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-sky-50 px-3 py-1 text-sky-700"><span class="h-2 w-2 rounded-full bg-sky-500"></span> New booking</span>
                                <span class="inline-flex items-center gap-2 rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-rose-700"><span class="h-2 w-2 rounded-full bg-rose-500"></span> Blocked</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative rounded-[28px] border border-slate-200 bg-white shadow-[0_24px_60px_-24px_rgba(15,23,42,0.2)]">
                        <div class="absolute inset-0 rounded-[28px] border border-white"></div>
                        <div class="relative flex h-[80vh] overflow-hidden rounded-[28px] bg-slate-50">

                        @if($availableTimes)
                            <div style="width: {{(count($availableTimes) * 16.66667)}}%" class="absolute left-[8.33333%] top-0 z-10 -translate-y-full rounded-t-3xl border border-b-0 border-slate-200 bg-white px-5 py-3 text-sm font-semibold uppercase tracking-[0.25em] text-slate-500 shadow-sm">
                                Times
                            </div>
                        <div
                            class="relative z-0 flex h-full basis-1/12 flex-col overflow-hidden rounded-bl-3xl border border-r border-slate-200 bg-white px-2">
                            @foreach($availableTimes[0] as $key => $slot)
                                @if($key != 0)
                                    <div
                                        style="top: {{(100 / count($availableTimes[0])) * ($key)}}%; width: {{(count($availableTimes) * 16.66667)}}%; left: 8.33333%"
                                        class="absolute border-t border-slate-200"></div>
                                @endif
                                <div class="relative w-full" style="height: {{100 / count($availableTimes[0])}}%">
                                    <p class="pt-6 text-center text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">{{$slot['time']}}</p>
                                </div>
                            @endforeach
                        </div>
                        @foreach($availableTimes as $key => $availability)

                            <div class="relative h-full w-1/6 basis-1/6">
                                <div class="absolute top-0 z-10 -translate-y-full w-full rounded-t-3xl border border-b-0 border-slate-200 bg-white px-4 py-3 text-center text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 shadow-sm">
                                    Slot {{$key + 1}}
                                </div>
                                <div
                                    class="flex h-full flex-col justify-between border border-l-0 border-slate-200 bg-white p-2 @if($key == array_key_last($availableTimes)) rounded-br-3xl @endif">
                                    @foreach($availability as $slotKey => $slot)
                                        @if($slot['booking'] && $slot['booking']['parent_booking_id'])
                                            @continue
                                        @endif
                                        <div class="group relative w-full cursor-pointer"
                                             style="height: {{(100 / count($availableTimes[0])) * ($slot['booking'] ? $slot['booking']['bookings_count'] : 1)}}%">

                                            @if(!empty($slot['booking']))
                                                <div
                                                    data-time="{{$slot['time']}}"
                                                    data-slot="{{$key}}"
                                                    data-end-time="{{$slot['booking']['end_time']}}"
                                                    data-booking-id="{{$slot['booking']['id']}}"
                                                    data-name="{{$slot['booking']['name']}}"
                                                    data-room="{{$slot['booking']['room_number']}}"
                                                    data-email="{{$slot['booking']['email']}}"
                                                    data-phone="{{$slot['booking']['mobile']}}"
                                                    class="mx-1 flex h-full items-stretch rounded-2xl border border-slate-200 bg-white p-2 shadow transition hover:-translate-y-0.5 hover:border-[#FF5E57]/70 hover:shadow-lg modifyModalBookingTrigger">
                                                    @if($slot['booking']['name'] == '__block__')

                                                        <div class="flex h-full w-full flex-col justify-between rounded-xl bg-rose-50 p-3 text-left">
                                                            <p class="text-xs font-semibold uppercase tracking-wide text-rose-500">Block</p>
                                                            <p class="text-sm font-medium text-rose-600">{{substr($slot['booking']['start_time'], 0, -3)}}
                                                                - {{substr($slot['booking']['end_time'], 0, -3)}}</p>
                                                        </div>
                                                    @else

                                                        <div class="flex h-full w-full flex-col justify-between rounded-xl bg-sky-50 p-3 text-left text-slate-700">
                                                            <p class="text-sm font-semibold text-slate-900">{{$slot['booking']['name']}}</p>
                                                            @if($slot['booking']['room_number'])
                                                                <p class="text-xs text-slate-500">Room: {{$slot['booking']['room_number']}}</p>
                                                            @endif
                                                            <p class="text-xs font-medium tracking-wide text-slate-600">{{substr($slot['booking']['start_time'], 0, -3)}}
                                                                - {{substr($slot['booking']['end_time'], 0, -3)}}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else

                                                <div data-time="{{$slot['time']}}"
                                                     data-slot="{{$key}}"
                                                     class="mx-1 flex h-full items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white p-1 text-slate-400 transition hover:border-[#FF5E57] hover:bg-[#FF5E57]/5 hover:text-[#FF5E57] newModalBookingTrigger">
                                                    <div class="flex h-full w-full items-center justify-center rounded-xl border border-dashed border-inherit bg-slate-50 p-2">
                                                        <svg class="h-6 w-6" viewBox="0 0 512 512">
                                                            <path fill="currentColor"
                                                                  d="M417.4,224H288V94.6c0-16.9-14.3-30.6-32-30.6c-17.7,0-32,13.7-32,30.6V224H94.6C77.7,224,64,238.3,64,256c0,17.7,13.7,32,30.6,32H224v129.4c0,16.9,14.3,30.6,32,30.6c17.7,0,32-13.7,32-30.6V288h129.4c16.9,0,30.6-14.3,30.6-32C448,238.3,434.3,224,417.4,224z"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach


                                </div>
                            </div>

                        @endforeach
                        @else
                            <div class="flex h-full w-full items-center justify-center bg-white">
                                <p class="rounded-full border border-slate-200 bg-slate-50 px-6 py-3 text-sm font-medium text-slate-500 shadow-sm">No bookings available for this product on this date.</p>
                            </div>
                        @endif
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="newBookingModalContainer" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/30 backdrop-blur-sm"></div>
        <div class="fixed inset-x-0 top-16 z-50 mx-auto w-full max-w-3xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_40px_120px_-20px_rgba(15,23,42,0.3)]">
            <div class="flex items-start justify-between border-b border-slate-200 bg-white px-6 py-5">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">{{$product->name}}</p>
                    <h2 class="mt-1 text-2xl font-semibold text-slate-900"><span id="modal_title_verb">New</span> Booking</h2>
                </div>
                <button type="button" class="closeNewBookingModal inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-[#FF5E57] hover:text-[#FF5E57]">
                    <span class="sr-only">Close</span>
                    &times;
                </button>
            </div>
            <div class="max-h-[70vh] overflow-y-auto bg-white px-6 py-6">
                <form method="post"
                      id="calendar-booking-form"
                      class="space-y-6"
                      data-store-action="{{route('calendar.store-booking', ['hotel_id' => $hotel->id, 'product_id' => $product->id])}}"
                      data-update-action="{{route('calendar.update-booking')}}"
                >
                    @csrf
                    <input type="hidden" name="product_id" value="{{$product->id}}">
                    <input type="hidden" name="date" value="{{$date}}">
                    <input type="hidden" name="slot" value="">
                    <input type="hidden" name="booking_id" value="">

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <x-input-label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500" for="start_time">Start Time</x-input-label>
                            <x-text-input disabled id="start_time_fake" class="rounded-2xl border-slate-200 bg-slate-50 text-slate-700"/>
                            <select id="start_time_select"
                                    class="hidden h-12 w-full rounded-2xl border border-slate-200 bg-white px-3 text-sm font-medium text-slate-700 shadow-sm outline-none transition focus:border-[#FF5E57] focus:ring-2 focus:ring-[#FF5E57]/30">
                                <option value="">Select</option>
                            </select>
                            <input type="hidden" name="start_time" id="start_time"/>
                        </div>
                        <div class="space-y-2">
                            <x-input-label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500" for="end_time">End Time</x-input-label>
                            <select name="end_time" id="end_time"
                                    class="h-12 w-full rounded-2xl border border-slate-200 bg-white px-3 text-sm font-medium text-slate-700 shadow-sm outline-none transition focus:border-[#FF5E57] focus:ring-2 focus:ring-[#FF5E57]/30">
                                <option value="">Select</option>
                            </select>
                            <x-text-input disabled id="end_time_fake" class="rounded-2xl border-slate-200 bg-slate-50 text-slate-700"/>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-slate-200/70 bg-slate-50/80 p-4">
                        <x-fancy-checkbox id="make_unavailable" name="make_unavailable"
                                          label="Mark as Unavailable"></x-fancy-checkbox>
                        <p id="confirmation-message" class="mt-3 hidden rounded-xl border border-amber-200/70 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-700">
                            Confirming this booking will prevent other people from booking this slot at this time.
                        </p>
                    </div>
                    <div id="form-fields" class="grid gap-4 md:grid-cols-2">
                        <div class="flex flex-col gap-2">
                            <x-input-label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500" for="name">Name</x-input-label>
                            <x-text-input required type="text" name="name" id="name" class="rounded-2xl border-slate-200 bg-white text-slate-900"/>
                        </div>
                        <div class="flex flex-col gap-2">
                            <x-input-label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500" for="room">Room Number</x-input-label>
                            <x-text-input type="text" name="room" id="room" class="rounded-2xl border-slate-200 bg-white text-slate-900"/>
                        </div>
                        <div class="flex flex-col gap-2">
                            <x-input-label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500" for="email">Email</x-input-label>
                            <x-text-input type="email" name="email" id="email" class="rounded-2xl border-slate-200 bg-white text-slate-900"/>
                        </div>
                        <div class="flex flex-col gap-2">
                            <x-input-label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500" for="phone">Mobile Number</x-input-label>
                            <x-text-input type="tel" name="phone" id="phone" class="rounded-2xl border-slate-200 bg-white text-slate-900"/>
                        </div>
                    </div>
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div class="text-xs text-slate-500">All bookings are instantly synced with your availability grid.</div>
                        <div class="flex items-center gap-3">
                            <x-danger-button type="button" id="deleteBooking" class="hidden rounded-2xl px-6 py-2">Delete</x-danger-button>
                            <x-primary-button id="store-button" type="submit" class="rounded-2xl bg-[#FF5E57] px-6 py-2 hover:bg-[#e85750] focus:ring-2 focus:ring-[#FF5E57]/40">Save</x-primary-button>
                        </div>
                    </div>
                </form>
                <form method="post" id="deleteForm" class="mt-6 hidden" action="{{route('calendar.delete-booking')}}">
                    @csrf
                    <div class="rounded-2xl border border-rose-200/70 bg-rose-50 px-6 py-5">
                        <h3 class="text-lg font-semibold text-rose-700">Delete this booking?</h3>
                        <p class="mt-2 text-sm text-rose-600">This action cannot be undone and will cause the booking to be permanently deleted.</p>
                        <input type="hidden" name="booking_id" value="">
                        <x-danger-button type="submit" class="mt-4 rounded-2xl px-6 py-2">Delete Permanently</x-danger-button>
                    </div>

                </form>
            </div>

        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const checkbox = document.querySelector('#make_unavailable');
            const formFields = document.querySelector('#form-fields');
            const confirmationMessage = document.querySelector('#confirmation-message');
            const nameField = document.querySelector('#name');

            checkbox.addEventListener('change', () => {
                if (checkbox.checked) {
                    // Hide the form fields and show the confirmation message
                    formFields.classList.add('hidden');
                    confirmationMessage.classList.remove('hidden');

                    // Set the name field to "__block__"
                    nameField.value = '__block__';
                } else {
                    // Show the form fields and hide the confirmation message
                    formFields.classList.remove('hidden');
                    confirmationMessage.classList.add('hidden');

                    // Clear the name field value
                    nameField.value = '';
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const bookingTriggers = document.querySelectorAll('.newModalBookingTrigger');
            const modal_title_verb = document.getElementById('modal_title_verb');
            const form = document.getElementById('calendar-booking-form');
            const storeButton = document.getElementById('store-button');
            const deleteButton = document.getElementById('deleteBooking');
            const deleteForm = document.getElementById('deleteForm');
            const startTimeSelect = document.getElementById('start_time_select');
            startTimeSelect.classList.add('hidden');
            bookingTriggers.forEach(trigger => {
                trigger.addEventListener('click', async function () {
                    modal_title_verb.innerText = 'New';
                    const newBookingModal = document.getElementById('newBookingModalContainer');
                    const startTime = this.getAttribute('data-time');
                    const endTime = this.getAttribute('data-end-time');
                    const slot = this.getAttribute('data-slot');
                    const startTimeInput = document.getElementById('start_time_fake');
                    const startTimeInputReal = document.getElementById('start_time');
                    const slotInput = document.querySelector('input[name="slot"]');
                    slotInput.value = slot;
                    startTimeInput.value = startTime;
                    startTimeInputReal.value = startTime;

                    startTimeSelect.classList.add('hidden');
                    startTimeInput.classList.remove('hidden');

                    const endTimeInput = document.getElementById('end_time');
                    endTimeInput.classList.remove('hidden');
                    const endTimeInputFake = document.getElementById('end_time_fake');
                    endTimeInputFake.classList.add('hidden');

                    axios.post('/admin/calendar/{{$product->id}}/get-future-availability-on-same-day', {
                        date: '{{$date}}',
                        hotel_id: '{{$hotel->id}}',
                        slot: slot,
                        start_time: startTime,
                        end_time: endTime
                    }).then(response => {
                        const endTimeInput = document.getElementById('end_time');
                        //Create an option for each available time
                        console.log(endTimeInput);
                        endTimeInput.innerHTML = '';
                        response.data.forEach(time => {
                            const option = document.createElement('option');
                            option.value = time;
                            option.innerText = time;
                            endTimeInput.appendChild(option);
                        });
                    });

                    //Set the action of the form to the store action

                    form.action = form.getAttribute('data-store-action');
                    storeButton.innerText = 'Save';
                    deleteButton.classList.add('hidden');
                    deleteForm.classList.add('hidden');

                    // Show the modal
                    newBookingModal.classList.remove('hidden');
                });
            });

            const modifyBookingTriggers = document.querySelectorAll('.modifyModalBookingTrigger');

            modifyBookingTriggers.forEach(trigger => {
                trigger.addEventListener('click', async function () {
                    modal_title_verb.innerText = 'Modify';
                    const newBookingModal = document.getElementById('newBookingModalContainer');
                    const startTime = this.getAttribute('data-time');
                    const endTime = this.getAttribute('data-end-time');
                    const slot = this.getAttribute('data-slot');
                    const startTimeInput = document.getElementById('start_time_fake');
                    const startTimeInputReal = document.getElementById('start_time');
                    const slotInput = document.querySelector('input[name="slot"]');
                    const booking_id = this.getAttribute('data-booking-id');
                    const bookingIdInput = document.querySelectorAll('input[name="booking_id"]');
                    const name = this.getAttribute('data-name');
                    const room = this.getAttribute('data-room');
                    const email = this.getAttribute('data-email');
                    const phone = this.getAttribute('data-phone');

                    startTimeInput.classList.add('hidden');
                    startTimeSelect.classList.remove('hidden');

                    deleteButton.classList.remove('hidden');
                    deleteForm.classList.add('hidden');


                    getStartTimes(slot, startTime, endTime, booking_id);
                    getEndTimes(slot, startTime, endTime, booking_id);



                    const nameInput = document.getElementById('name');
                    nameInput.value = name;

                    if (name === '__block__') {
                        const checkbox = document.querySelector('#make_unavailable');
                        checkbox.checked = true;
                        const formFields = document.querySelector('#form-fields');
                        const confirmationMessage = document.querySelector('#confirmation-message');
                        formFields.classList.add('hidden');
                        confirmationMessage.classList.remove('hidden');
                    }

                    const roomInput = document.getElementById('room');
                    roomInput.value = room;
                    const emailInput = document.getElementById('email');
                    emailInput.value = email;
                    const phoneInput = document.getElementById('phone');
                    phoneInput.value = phone;

                    bookingIdInput.forEach(input => {
                        input.value = booking_id;
                    })
                    slotInput.value = slot;
                    startTimeInput.value = startTime;
                    startTimeInputReal.value = startTime;

                    // const endTimeInput = document.getElementById('end_time');
                    // endTimeInput.classList.add('hidden');
                    const endTimeInputFake = document.getElementById('end_time_fake');
                    endTimeInputFake.classList.add('hidden');
                    // endTimeInputFake.value = endTime;

                    //set the action of the form to the update action
                    form.action = form.getAttribute('data-update-action');
                    storeButton.innerText = 'Update';


                    // Show the modal
                    newBookingModal.classList.remove('hidden');
                });
            });

            function getStartTimes(slot, startTime, endTime, booking_id) {
                axios.post('/admin/calendar/{{$product->id}}/get-future-availability-on-same-day', {
                    date: '{{$date}}',
                    hotel_id: '{{$hotel->id}}',
                    slot: slot,
                    end_time: endTime,
                    booking_id: booking_id
                }).then(response => {
                    const startTimeInput = document.getElementById('start_time_select');
                    //Create an option for each available time
                    console.log(response.data)
                    startTimeInput.innerHTML = '';
                    response.data.forEach(time => {
                        const option = document.createElement('option');
                        option.value = time;
                        option.innerText = time;
                        startTimeInput.appendChild(option);
                    });
                    startTimeInput.value = startTime;
                });
            }

            function getEndTimes(slot, startTime, endTime, booking_id) {
                axios.post('/admin/calendar/{{$product->id}}/get-future-availability-on-same-day', {
                    date: '{{$date}}',
                    hotel_id: '{{$hotel->id}}',
                    slot: slot,
                    start_time: startTime,
                    end_time: endTime,
                    booking_id: booking_id
                }).then(response => {
                    const endTimeInput = document.getElementById('end_time');
                    //Create an option for each available time
                    console.log(endTimeInput);
                    console.log(response.data)
                    endTimeInput.innerHTML = '';
                    response.data.forEach(time => {
                        const option = document.createElement('option');
                        option.value = time;
                        option.innerText = time;
                        endTimeInput.appendChild(option);
                    });
                    endTimeInput.value = endTime;
                });
            }

            startTimeSelect.addEventListener('change', function () {
                const startTime = document.getElementById('start_time');
                startTime.value = this.value;
                const startTimeFake = document.getElementById('start_time_fake');
                startTimeFake.value = this.value;
                const endTime = document.getElementById('end_time');
                //Make the end time select required
                endTime.setAttribute('required', 'required');

                console.log('start_time', this.value);

                console.log('end_time', document.getElementById('end_time').value);
                getEndTimes(document.querySelector('input[name="slot"]').value, this.value, document.getElementById('end_time').value, document.querySelector('input[name="booking_id"]').value);
            });

            deleteButton.addEventListener('click', function () {
                const deleteForm = document.getElementById('deleteForm');
                deleteForm.classList.remove('hidden');
            });

            const closeButtons = document.querySelectorAll('.closeNewBookingModal');
            closeButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    const newBookingModal = document.getElementById('newBookingModalContainer');
                    newBookingModal.classList.add('hidden');
                });
            });


        });

        const productSelector = document.getElementById('product-selector');
        const productSelectorList = productSelector.querySelector('ul');
        const productSelectorButton = productSelector.querySelector('button');

        productSelectorButton.addEventListener('click', () => {
            productSelectorList.classList.toggle('hidden');
        });

    </script>
</x-hotel-admin-layout>
