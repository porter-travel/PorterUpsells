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
                                <img src="/img/icons/remove.svg"
                                     alt="remove"
                                     class=" w-6 h-6"/>

                            </a>
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
