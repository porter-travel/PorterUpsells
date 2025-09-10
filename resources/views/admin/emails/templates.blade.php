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

                    @foreach($email_templates as $template)
                        <div class="py-2 border-b flex items-center justify-between">
                            <a href="{{route('email-v2.edit-template', ['hotel_id' => $hotel->id, 'template_id' => $template->email_id])}}">
                                <h4 class="font-semibold">{{$template->email_name}}</h4>
                            </a>
                            <div class="flex items-center justify-end">
                                <span class="mr-4">{{$template->days}} {{$template->days > 1 ? 'Days' : 'Day'}} {{$template->when_to_send == 'before' ? 'Before Arrival ' : ($template->when_to_send == 'after_arrival' ? 'After Arrival' : 'After Checkout')}}</span>
                            <a href="{{route('email-v2.delete-template', $template->email_id)}}">
                                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <circle cx="18" cy="18" r="18" fill="#C20000"></circle>
                                    <rect x="6" y="6" width="24" height="24" fill="url(#pattern0_5506_7)"></rect>
                                    <defs>
                                        <pattern id="pattern0_5506_7" patternContentUnits="objectBoundingBox" width="1" height="1">
                                            <use xlink:href="#image0_5506_7" transform="scale(0.0111111)"></use>
                                        </pattern>
                                        <image id="image0_5506_7" width="90" height="90" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFoAAABaCAYAAAA4qEECAAAACXBIWXMAAAsTAAALEwEAmpwYAAAB40lEQVR4nO2cS2rDQBBEtYpz8ijnyWLQhfK5RRmBDAaHENszPVXd9UDglal+SK0RGvWyGGOMMcYYY4wxxpgbAJwAvAP4Po7990lFFRTyA3gB8IFbNgCvCzkS+f8IyRdWNf8/QvKEVc1/R0hK2RL5HwhJJVsi/3F3bnicNvNuLpP/WPY8S5shu4PkC2tE2H2N2YMtso080S5+40dJdJjszpJ3PpeA0Cv60ka2kY7t4pq3UXlHB28jZCtljboUu7cRhYzyhYA4W5qCQJipC0yFgSjLEBgKBEGGEGYWiiqSZxaMapJnFI6qkiMFoLrkCBGw5LBH4DbgP7neeN/LoLOvdrsQlL2lkUwse0snmVB2XslEsvNLJpBdR/JE2fUkT5BdV3KgbEsOkG3J11h0AHDrSCH5Qt0WAi/vUkqud2bDj+AlJOc/s8EjOa9s8EnOJ5tYch7ZfjkbgLcbBOANNAF4S1gA3uQYgLftBsCwFw4EGYbCVCCIsnSFsTAQZnoK5oJAnC1dIRDImOazXwhlHTWvQ/2je6l5HcpjJKTmdWzCg1Fk5nU08VE/EvM6modXZRpnliW/xIC+LPklRk5myS8xRDVLfomxwFnyH6uRfen3dRyr0me/EM9vjDHGGGOMMcYYswRyBpias+umnbidAAAAAElFTkSuQmCC"></image>
                                    </defs>
                                </svg></a>
                            </div>
                        </div>

                    @endforeach

                    <a href="{{route('email-v2.add-template', $hotel->id)}}"
                       class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <x-primary-button>
                            Add New Template

                        </x-primary-button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-hotel-admin-layout>
