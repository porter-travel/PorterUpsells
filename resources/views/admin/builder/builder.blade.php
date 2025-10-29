<x-hotel-admin-layout :hotel="$hotel">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  shadow-sm sm:rounded-lg">
                <div id="builder"
                     data-type="email"
                     data-hotel-id="{{$hotel_id}}"
                     data-template-id="{{$template_id}}"
                     data-hotel-name="{{$hotel->name}}"
                     data-hotel-logo="{{$hotel->logo}}"
                     data-hotel-featured-image="{{$hotel->featured_image}}"
                ></div>
            </div>
        </div>
    </div>

    <script>
        @if(isset($data))
        window.example_template_data = {!! json_encode($data) !!};
        @else
        window.example_template_data = null;
        @endif
    </script>

</x-hotel-admin-layout>
