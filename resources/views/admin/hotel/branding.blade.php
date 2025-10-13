<x-hotel-admin-layout :hotel="$hotel">
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-start gap-4">
                <img src="{{ $hotel->logo }}" alt="{{ $hotel->name }} logo" class="h-20 w-20 rounded-3xl object-cover ring-4 ring-indigo-100">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500">Branding & identity</p>
                    <h1 class="text-3xl font-semibold text-slate-900 sm:text-4xl">Keep {{ $hotel->name }} perfectly on-brand</h1>
                    <p class="max-w-2xl text-sm text-slate-500">Update logos, imagery, and colour palettes so every guest touchpoint feels aligned with your property.</p>
                </div>
            </div>
            <div class="w-full max-w-sm space-y-4 rounded-3xl border border-slate-200 bg-white/90 p-5 shadow-sm backdrop-blur">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Your upsell link</p>
                    <div class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 py-2 shadow-sm">
                        <input id="hotel-welcome-url" type="text" readonly class="w-full bg-transparent text-sm text-slate-600" value="{{ env('APP_URL') }}/hotel/{{ $hotel->slug }}/welcome">
                        <button type="button" onclick="copyToClipboard()" class="inline-flex items-center gap-1 rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-100">
                            <i data-lucide="copy" class="h-3.5 w-3.5"></i>
                            Copy
                        </button>
                    </div>
                    <p id="confirmation-text" class="text-xs text-slate-500">Use this link to preview your guest marketplace in real time.</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4 text-xs text-slate-500">
                    <p class="font-semibold text-slate-700">Tip</p>
                    <p class="mt-1">We recommend updating imagery seasonally to keep your marketplace feeling fresh.</p>
                </div>
            </div>
        </div>
    </x-slot>

    <section class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:p-8">
        @include('admin.hotel.partials.branding-settings')
    </section>

    <script>
        function copyToClipboard() {
            const copyText = document.getElementById('hotel-welcome-url');
            copyText.select();
            copyText.setSelectionRange(0, 99999);

            navigator.clipboard.writeText(copyText.value);
            const confirmationText = document.getElementById('confirmation-text');
            confirmationText.innerHTML = 'Copied to clipboard';
            setTimeout(() => {
                confirmationText.innerHTML = 'Use this link to preview your guest marketplace in real time.';
            }, 2000);
        }
    </script>
</x-hotel-admin-layout>
