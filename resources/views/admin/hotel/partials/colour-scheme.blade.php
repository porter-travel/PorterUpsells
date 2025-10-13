

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
            class="relative w-full max-w-sm overflow-hidden rounded-2xl border-8 border-slate-900"
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
            <div class="p-4" style="background-color: var(--accent);">
                @if ($hotel->logo)
                    <img src="{{ $hotel->logo }}" alt="{{ $hotel->name }} logo" class="h-8 object-contain" />
                @else
                    <span class="font-semibold uppercase tracking-wide" style="color: var(--text);">your logo here</span>
                @endif
            </div>

            <div class="space-y-4 p-4 pb-20" style="background-color: var(--page-bg); color: var(--text);">
                <div class="flex flex-wrap gap-2 text-[11px] font-semibold uppercase tracking-[0.18em]">
                    <span class="rounded-full bg-[color:var(--accent)] px-3 py-1 text-[color:var(--text)]/80">Wellness</span>
                    <span class="rounded-full border border-[color:var(--text)]/20 px-3 py-1">Dining</span>
                    <span class="rounded-full border border-[color:var(--text)]/20 px-3 py-1">Experiences</span>
                </div>

                <div class="space-y-3 rounded-2xl border border-[color:var(--text)]/10 bg-[color:var(--main-box-bg)]/80 p-4 shadow-inner">
                    <div class="flex items-start justify-between gap-3 text-sm font-semibold">
                        <span style="color: var(--main-box-text);">Sunset cruise</span>
                        <span class="text-xs" style="color: var(--main-box-text);">£129</span>
                    </div>
                    <p class="text-xs leading-5 opacity-80" style="color: var(--main-box-text);">
                        Reserve an unforgettable evening sail with complimentary champagne and canapés.
                    </p>
                    <button
                        class="w-full rounded-xl px-3 py-2 text-sm font-semibold shadow-sm"
                        style="background-color: var(--button-bg); color: var(--button-text);"
                    >
                        Book now
                    </button>
                </div>

                <div class="space-y-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-[color:var(--text)]/70">Today's picks</p>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between rounded-xl border border-[color:var(--text)]/10 bg-white/70 px-3 py-2">
                            <div>
                                <p class="text-sm font-semibold" style="color: var(--text);">Spa day pass</p>
                                <p class="text-xs opacity-70" style="color: var(--text);">Relax in our award-winning spa.</p>
                            </div>
                            <span class="text-xs font-semibold" style="color: var(--text);">£75</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl border border-[color:var(--text)]/10 bg-white/70 px-3 py-2">
                            <div>
                                <p class="text-sm font-semibold" style="color: var(--text);">Chef's tasting</p>
                                <p class="text-xs opacity-70" style="color: var(--text);">Five courses with wine pairing.</p>
                            </div>
                            <span class="text-xs font-semibold" style="color: var(--text);">£95</span>
                        </div>
                    </div>
                </div>
            </div>

            <button
                class="absolute bottom-4 left-4 right-4 rounded-lg py-2 text-sm font-semibold text-center shadow-md"
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
