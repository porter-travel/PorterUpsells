<x-hotel-admin-layout :hotel="$hotel">
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-2">
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500">Email builder</p>
                <div>
                    <h1 class="text-3xl font-semibold text-slate-900 sm:text-4xl">Design your guest emails</h1>
                    <p class="max-w-2xl text-sm text-slate-500">
                        Drag blocks, customise copy and fine-tune delivery settings for {{ $hotel->name }}.
                    </p>
                </div>
            </div>

            <a href="{{ route('email-v2.list-templates', ['hotel_id' => $hotel->id]) }}"
               class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white/70 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:-translate-y-0.5 hover:border-slate-300 hover:bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                Back to emails
            </a>
        </div>
    </x-slot>


    <section class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:p-8">
        <div id="builder"
             data-type="email"
             data-hotel-id="{{ $hotel_id }}"
             data-template-id="{{ $template_id ?? '' }}"
             data-hotel-name="{{ $hotel->name }}"
             data-hotel-logo="{{ $hotel->logo ?? '' }}"
             data-hotel-featured-image="{{ $hotel->featured_image ?? '' }}"
        ></div>
    </section>

    <script>
        @if(isset($data))
            window.example_template_data = {!! json_encode($data) !!};
        @else
            window.example_template_data = null;
        @endif
    </script>

</x-hotel-admin-layout>
