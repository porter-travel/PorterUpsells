<x-hotel-admin-layout :hotel="$hotel">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Emails') }}
        </h2>
    </x-slot>
    @if ($errors->any())
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-red rounded-2xl p-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        </div>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="">
                        <form method="post" action="{{route('email-v2.store-template', $hotel->id)}}">
                            <input type="hidden" name="template_id" value="{{$template_id ?? ''}}">
                            @csrf
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-8">
                                Email Setup
                            </h2>

                            <div class="grid grid-cols-2 gap-4 mb-8">
                                <div class="w-full">
                                    <x-input-label for="subject" value="Email Name" class="mr-4"/>
                                    <x-text-input class="w-full" name="email_name" value="{{$meta['email_name']}}"></x-text-input>
                                </div>
                                <div class="">
                                    <x-input-label for="subject" value="Email Subject" class="mr-4"/>
                                    <x-text-input class="w-full" name="email_subject" value="{{$meta['email_subject']}}"></x-text-input>
                                </div>
                                <div class="">
                                    <x-input-label for="when_to_send" value="When To Send" class="mr-4"/>
                                    <select onchange="updateDaysLabel()" class="w-full rounded" name="when_to_send" id="when_to_send">
                                        <option {{$meta['when_to_send'] == 'before_arrival' ? 'selected' : ''}} value="before">
                                            Before Arrival
                                        </option>
                                        <option {{$meta['when_to_send'] == 'after_arrival' ? 'selected' : ''}} value="after_arrival">
                                            After Arrival
                                        </option>
                                        <option {{$meta['when_to_send'] == 'before_checkout' ? 'selected' : ''}} value="after_checkout">
                                            Before Check-out
                                        </option>
                                        <option {{$meta['when_to_send'] == 'after_checkout' ? 'selected' : ''}} value="after_checkout">
                                            After Check-out
                                        </option>

                                    </select>
                                </div>
                                <div class=" grid grid-cols-2 gap-6">
                                <div >
                                    <x-input-label id="days" for="days_count" value="Days Before Arrival" class="mr-4"/>
                                    <x-text-input value="{{$meta['days']}}" label="days_count" class="w-full" type="number"  name="days"></x-text-input>
                                </div>
                                    <div class="">
                                        <x-input-label id="time" for="time" value="Time" class="mr-4"/>
                                        <x-text-input value="{{$meta['time']}}" class="w-full" type="time"  name="time"></x-text-input>
                                    </div>
                                </div>
                            </div>

                            @include('admin.emails.snippets.email-template-form', ['email_content' => $email_content, 'hotel' => $hotel])
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function updateDaysLabel() {
            const whenToSend = document.getElementById('when_to_send').value;
            const daysLabel = document.getElementById('days');

            if (whenToSend === 'before') {
                daysLabel.innerText = 'Days Before Arrival';
            } else if (whenToSend === 'after_arrival') {
                daysLabel.innerText = 'Days After Arrival';
            } else if (whenToSend === 'after_checkout') {
                daysLabel.innerText = 'Days After Check-out';
            }
        }
    </script>


    @include('admin.emails.snippets.email-template-form-scripts', ['hotel' => $hotel])
</x-hotel-admin-layout>
