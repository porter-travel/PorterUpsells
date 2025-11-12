@php use App\Helpers\Money; @endphp
<x-hotel-admin-layout :hotel="$hotel">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Emails') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-8">
                        Pre-Arrival Email Schedule
                    </h2>

                    <form method="post" action="{{route('email.store-customisations', $hotel->id)}}" class="">
                        @csrf
                        @if($hotel->property_type == 'hotel')
                        <div class="flex">
                            <div>

                                <x-fancy-checkbox dusk="pre-arrival-email"
                                                  :isChecked="(!empty($email_schedule) && isset($email_schedule['email-schedule-2-day-pre-arrival'])) ? $email_schedule['email-schedule-2-day-pre-arrival'] : true"
                                                  name="hotel_meta[email-schedule-2-day-pre-arrival]"
                                                  label="2 days pre-arrival"/>
                            </div>
                            <div>
                                <x-fancy-checkbox dusk="pre-arrival-email"
                                                  :isChecked="(!empty($email_schedule) && isset($email_schedule['email-schedule-7-day-pre-arrival'])) ? $email_schedule['email-schedule-7-day-pre-arrival'] : true"
                                                  name="hotel_meta[email-schedule-7-day-pre-arrival]"
                                                  label="7 days pre-arrival"/>
                            </div>
                            <div>
                                <x-fancy-checkbox dusk="pre-arrival-email"
                                                  :isChecked="(!empty($email_schedule) && isset($email_schedule['email-schedule-14-day-pre-arrival'])) ? $email_schedule['email-schedule-14-day-pre-arrival'] : true"
                                                  name="hotel_meta[email-schedule-14-day-pre-arrival]"
                                                  label="14 days pre-arrival"/>
                            </div>
                            <div>
                                <x-fancy-checkbox dusk="pre-arrival-email"
                                                  :isChecked="(!empty($email_schedule) && isset($email_schedule['email-schedule-21-day-pre-arrival'])) ? $email_schedule['email-schedule-21-day-pre-arrival'] : true"
                                                  name="hotel_meta[email-schedule-21-day-pre-arrival]"
                                                  label="21 days pre-arrival"/>
                            </div>
                            <div>
                                <x-fancy-checkbox dusk="pre-arrival-email"
                                                  :isChecked="(!empty($email_schedule) && isset($email_schedule['email-schedule-30-day-pre-arrival'])) ? $email_schedule['email-schedule-30-day-pre-arrival'] : true"
                                                  name="hotel_meta[email-schedule-30-day-pre-arrival]"
                                                  label="30 days pre-arrival"/>
                            </div>
                        </div>
                            @else
                            <div class="flex">
                                <div>

                                    <x-fancy-checkbox dusk="pre-arrival-email"
                                                      :isChecked="(!empty($email_schedule) && isset($email_schedule['email-schedule-1-day-pre-arrival'])) ? $email_schedule['email-schedule-1-day-pre-arrival'] : true"
                                                      name="hotel_meta[email-schedule-1-day-pre-arrival]"
                                                      label="1 days pre-arrival"/>
                                </div>

                                <div>

                                    <x-fancy-checkbox dusk="pre-arrival-email"
                                                      :isChecked="(!empty($email_schedule) && isset($email_schedule['email-schedule-3-day-pre-arrival'])) ? $email_schedule['email-schedule-3-day-pre-arrival'] : true"
                                                      name="hotel_meta[email-schedule-3-day-pre-arrival]"
                                                      label="3 days pre-arrival"/>
                                </div>
                                <div>
                                    <x-fancy-checkbox dusk="pre-arrival-email"
                                                      :isChecked="(!empty($email_schedule) && isset($email_schedule['email-schedule-5-day-pre-arrival'])) ? $email_schedule['email-schedule-5-day-pre-arrival'] : true"
                                                      name="hotel_meta[email-schedule-5-day-pre-arrival]"
                                                      label="5 days pre-arrival"/>
                                </div>
                            </div>
                        @endif
                        <div class="flex items-end justify-end">
                            <x-primary-button dusk="save-pre-arrival-email" class="mt-4">Save</x-primary-button>
                        </div>
                    </form>

                    <div class="border-t border-[#C4C4C4] mt-4 pt-4">
                        <form method="post" action="{{route('email.store-customisations', $hotel->id)}}">
                            <input type="hidden" name="hotel_email[email_type]" value="pre-arrival-email">
                            @csrf
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-8">
                                Pre-Arrival Email Setup
                            </h2>

                            @include('admin.emails.snippets.email-template-form', ['email_content' => $email_content, 'hotel' => $hotel])
                        </form>
                    </div>
                    <div class="border-t border-[#C4C4C4] mt-4 pt-4">
                        <form method="post" action="{{route('email.store-customisations', $hotel->id)}}">
                            @csrf
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-8">
                                Order Confirmation Email
                            </h2>
                            <p class="text-large">Send a copy of any order confirmation emails to the following
                                addresses
                                (separate with a comma)</p>
                            <x-text-input
                                name="hotel_meta[email-recipients]" type="text" value="{{$email_recipients}}"
                                class="w-full"/>

                            <div class="flex items-end justify-end">
                                <x-primary-button dusk="save-pre-arrival-email-setup" class="mt-4">Save
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>


@include('admin.emails.snippets.email-template-form-scripts', ['hotel' => $hotel])
</x-hotel-admin-layout>
