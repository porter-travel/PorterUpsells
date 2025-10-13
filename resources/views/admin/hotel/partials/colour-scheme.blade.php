

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
    <div class="grid gap-4 md:grid-cols-2">

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

    <div class="mx-auto w-full max-w-xs">
        <div
            id="colour-preview"
            class="relative overflow-hidden rounded-[2.5rem] border border-slate-200 bg-[color:var(--page-bg)] p-6 shadow-lg"
            style="
                --page-bg: {{ $colors[0]['value'] }};
                --main-box-bg: {{ $colors[1]['value'] }};
                --main-box-text: {{ $colors[2]['value'] }};
                --button-bg: {{ $colors[3]['value'] }};
                --accent: {{ $colors[4]['value'] }};
                --text: {{ $colors[5]['value'] }};
                --button-text: {{ $colors[6]['value'] }};
            "
        >
            <div class="absolute left-1/2 top-2 h-1 w-20 -translate-x-1/2 rounded-full bg-slate-200"></div>
            <div class="space-y-5 pt-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[color:var(--accent)] font-semibold text-[color:var(--text)]">LOGO</div>
                    <div>
                        <p class="text-sm font-semibold text-[color:var(--text)]">Guest Marketplace</p>
                        <p class="text-xs text-[color:var(--text)]/70">Preview</p>
                    </div>
                </div>

                <div class="space-y-3 rounded-2xl bg-[color:var(--main-box-bg)] p-5 shadow-inner">
                    <p class="text-sm font-semibold text-[color:var(--main-box-text)]">Welcome to your stay</p>
                    <p class="text-xs leading-5 text-[color:var(--main-box-text)]/80">
                        Explore curated recommendations, book activities, and request services directly from your phone.
                    </p>
                    <button class="w-full rounded-xl bg-[color:var(--button-bg)] py-2 text-sm font-semibold text-[color:var(--button-text)] shadow-sm">
                        Book airport transfer
                    </button>
                </div>

                <div class="space-y-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[color:var(--text)]/70">Highlights</p>
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-3 rounded-2xl border border-slate-200/60 bg-white/60 p-3">
                            <span class="h-10 w-10 rounded-xl bg-[color:var(--accent)]"></span>
                            <div>
                                <p class="text-sm font-semibold text-[color:var(--text)]">Spa day pass</p>
                                <p class="text-xs text-[color:var(--text)]/70">Relax in our award-winning spa.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 rounded-2xl border border-slate-200/60 bg-white/60 p-3">
                            <span class="h-10 w-10 rounded-xl bg-[color:var(--button-bg)]"></span>
                            <div>
                                <p class="text-sm font-semibold text-[color:var(--text)]">Sunset cruise</p>
                                <p class="text-xs text-[color:var(--text)]/70">Reserve an unforgettable evening.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
