<p class="poppins text-2xl mb-6">Colour Scheme</p>
@csrf

@php
    $colors = [
        ['id' => 'primary_color', 'label' => 'Background', 'name' => 'page_background_color', 'value' => $hotel->page_background_color ?? '#ffffff'],
        ['id' => 'main_box_color', 'label' => 'Main Box', 'name' => 'main_box_color', 'value' => $hotel->main_box_color ?? '#F5D6E1'],
        ['id' => 'main_box_text_color', 'label' => 'Main Box Text', 'name' => 'main_box_text_color', 'value' => $hotel->main_box_text_color ?? '#000000'],
        ['id' => 'button_color', 'label' => 'Button', 'name' => 'button_color', 'value' => $hotel->button_color ?? '#D4F6D1'],
        ['id' => 'accent_color', 'label' => 'Accent', 'name' => 'accent_color', 'value' => $hotel->accent_color ?? '#C7EDF2'],
        ['id' => 'text_color', 'label' => 'Text', 'name' => 'text_color', 'value' => $hotel->text_color ?? '#000000'],
        ['id' => 'button_text_color', 'label' => 'Button Text', 'name' => 'button_text_color', 'value' => $hotel->button_text_color ?? '#000000'],
    ];
@endphp


<div class="grid gap-8 lg:grid-cols-[minmax(0,1.35fr)_minmax(260px,320px)] xl:grid-cols-[minmax(0,1.5fr)_minmax(300px,360px)]">
    <div class="grid gap-4">

        @foreach ($colors as $color)
            <div class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white/90 p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <x-input-label class="font-sans text-sm font-semibold text-slate-900" for="{{ $color['id'] }}" :value="__($color['label'])" />
                    <span
                        class="h-9 w-9 rounded-xl border border-white/80 shadow-sm"
                        data-colour-swatch="{{ $color['id'] }}"
                        style="background: {{ $color['value'] }}"
                    ></span>
                </div>
                <div class="flex items-center gap-3">
                    <x-color-input id="{{ $color['id'] }}" class="h-12 w-14 cursor-pointer rounded-xl border border-slate-200" type="color" name="{{ $color['name'] }}" :value="$color['value']" />
                    <div class="flex w-full flex-col gap-1">
                        <label class="text-xs font-medium text-slate-500" for="{{ $color['id'] }}_hex">Hex value</label>
                        <input
                            type="text"
                            id="{{ $color['id'] }}_hex"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 font-mono text-sm uppercase text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                            maxlength="7"
                            value="{{ $color['value'] }}"
                        />
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex w-full items-start justify-center rounded-2xl bg-slate-100 p-4">
        <div
            id="colour-preview"
            class="relative w-full max-w-[320px] overflow-hidden rounded-[32px] border-[12px] border-slate-900 bg-[color:var(--page-bg)] shadow-[0_25px_65px_-35px_rgba(15,23,42,0.55)]"
            style="
                --page-bg: {{ $colors[0]['value'] }};
                --main-box-bg: {{ $colors[1]['value'] }};
                --main-box-text: {{ $colors[2]['value'] }};
                --button-bg: {{ $colors[3]['value'] }};
                --accent: {{ $colors[4]['value'] }};
                --text: {{ $colors[5]['value'] }};
                --button-text: {{ $colors[6]['value'] }};
                background-color: var(--page-bg);
            "
        >
            <div class="absolute inset-x-16 top-3 h-1 rounded-full bg-slate-900/10"></div>

            <div class="relative h-44 w-full overflow-hidden">
                <div class="absolute inset-0 bg-[color:var(--accent)]/30"></div>
                @if ($hotel->featured_image)
                    <img src="{{ $hotel->featured_image }}" alt="{{ $hotel->name }} cover" class="h-full w-full object-cover" />
                @else
                    <div class="flex h-full w-full items-center justify-center bg-[color:var(--accent)]/50 text-xs font-semibold uppercase tracking-[0.3em] text-white/80">
                        Cover image
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-black/20 to-black/0"></div>
                <div class="absolute bottom-4 left-4 right-4 flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-xl bg-white/90 shadow-sm">
                        @if ($hotel->logo)
                            <img src="{{ $hotel->logo }}" alt="{{ $hotel->name }} logo" class="h-full w-full object-contain" />
                        @else
                            <span class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-600">Logo</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.4em] text-white/60">Welcome to</p>
                        <p class="text-lg font-semibold leading-tight text-white">{{ $hotel->name }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-4 px-5 pb-24 pt-5" style="background-color: var(--page-bg); color: var(--text);">
                <div class="rounded-2xl border border-[color:var(--text)]/10 bg-[color:var(--main-box-bg)]/90 p-4 shadow-sm">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.35em]" style="color: var(--main-box-text);">Personalise</p>
                    <p class="text-sm font-semibold" style="color: var(--main-box-text);">Make this stay unforgettable</p>
                    <p class="mt-2 text-xs leading-5" style="color: var(--main-box-text); opacity: 0.75;">
                        Pick curated experiences, dining, and on-property highlights ahead of arrival.
                    </p>
                    <button
                        class="mt-4 w-full rounded-xl px-3 py-2 text-sm font-semibold shadow-sm"
                        style="background-color: var(--button-bg); color: var(--button-text);"
                    >
                        Browse itinerary ideas
                    </button>
                </div>

                <div class="flex gap-2 overflow-x-auto text-[11px] font-semibold uppercase tracking-[0.25em]">
                    <span class="whitespace-nowrap rounded-full bg-[color:var(--accent)] px-3 py-1 text-[color:var(--text)]/80">Wellness</span>
                    <span class="whitespace-nowrap rounded-full border border-[color:var(--text)]/20 px-3 py-1">Dining</span>
                    <span class="whitespace-nowrap rounded-full border border-[color:var(--text)]/20 px-3 py-1">Experiences</span>
                    <span class="whitespace-nowrap rounded-full border border-[color:var(--text)]/20 px-3 py-1">Rooms</span>
                </div>

                <div class="space-y-3 rounded-2xl border border-[color:var(--text)]/12 bg-white/70 p-4 shadow-sm">
                    <div class="relative overflow-hidden rounded-xl">
                        <div class="h-32 w-full bg-[color:var(--accent)]/40"></div>
                        <span class="absolute bottom-3 left-3 rounded-full bg-white/90 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-700">Featured</span>
                    </div>
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold" style="color: var(--text);">Sunset cruise</p>
                            <p class="text-xs leading-5 opacity-70" style="color: var(--text);">Champagne sailing as the city lights come alive.</p>
                        </div>
                        <span class="text-xs font-semibold" style="color: var(--text);">£129</span>
                    </div>
                    <button
                        class="w-full rounded-xl px-3 py-2 text-sm font-semibold shadow-sm"
                        style="background-color: var(--button-bg); color: var(--button-text);"
                    >
                        Add to plan
                    </button>
                </div>

                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[color:var(--text)]/70">Today's picks</p>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between rounded-xl border border-[color:var(--text)]/12 bg-white/80 px-3 py-2">
                            <div>
                                <p class="text-sm font-semibold" style="color: var(--text);">Spa day pass</p>
                                <p class="text-xs opacity-70" style="color: var(--text);">Relax in the sanctuary spa.</p>
                            </div>
                            <span class="text-xs font-semibold" style="color: var(--text);">£75</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl border border-[color:var(--text)]/12 bg-white/80 px-3 py-2">
                            <div>
                                <p class="text-sm font-semibold" style="color: var(--text);">Chef's tasting</p>
                                <p class="text-xs opacity-70" style="color: var(--text);">Five courses with wine pairing.</p>
                            </div>
                            <span class="text-xs font-semibold" style="color: var(--text);">£95</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-[color:var(--text)]/10 bg-[color:var(--accent)]/25 p-4">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.3em] text-[color:var(--text)]/80">Insider tip</p>
                    <p class="text-xs leading-5" style="color: var(--text);">
                        Ask your concierge for late checkout availability and bespoke neighbourhood recommendations.
                    </p>
                </div>
            </div>

            <button
                class="absolute bottom-6 left-6 right-6 rounded-xl py-3 text-sm font-semibold text-center shadow-lg"
                style="background-color: var(--button-bg); color: var(--button-text);"
            >
                View cart
            </button>
        </div>
    </div>
</div>

<script>
    (function () {
        const colourPreview = document.getElementById('colour-preview');
        if (!colourPreview) {
            return;
        }

        const colourMap = {
            primary_color: '--page-bg',
            main_box_color: '--main-box-bg',
            main_box_text_color: '--main-box-text',
            button_color: '--button-bg',
            accent_color: '--accent',
            text_color: '--text',
            button_text_color: '--button-text',
        };

        const normalizeHex = (value) => {
            const hex = value.trim().toUpperCase();
            return /^#[0-9A-F]{6}$/.test(hex) ? hex : null;
        };

        Object.keys(colourMap).forEach((id) => {
            const colourInput = document.getElementById(id);
            const hexInput = document.getElementById(`${id}_hex`);
            const swatch = document.querySelector(`[data-colour-swatch="${id}"]`);

            if (!colourInput || !hexInput) {
                return;
            }

            const updateColour = (value) => {
                const normalized = normalizeHex(value);
                if (!normalized) {
                    return;
                }

                colourInput.value = normalized;
                hexInput.value = normalized;
                colourPreview.style.setProperty(colourMap[id], normalized);

                if (swatch) {
                    swatch.style.background = normalized;
                }
            };

            colourInput.addEventListener('input', () => {
                updateColour(colourInput.value);
            });

            hexInput.addEventListener('input', () => {
                const normalized = normalizeHex(hexInput.value);
                if (normalized) {
                    updateColour(normalized);
                }
            });

            hexInput.addEventListener('paste', (event) => {
                const pasted = event.clipboardData.getData('text');
                const normalized = normalizeHex(pasted);
                if (normalized) {
                    updateColour(normalized);
                }
                event.preventDefault();
            });

            // Ensure preview is correct on load
            updateColour(colourInput.value);
        });
    })();
</script>
