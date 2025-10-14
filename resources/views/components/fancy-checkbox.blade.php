<label
    class="flex items-center gap-3 rounded-xl border border-slate-300 bg-slate-50 px-3 py-2 cursor-pointer transition hover:border-indigo-300 hover:bg-white fancy-checkbox"
>
    {{-- Hidden input for unchecked state --}}
    <input type="hidden" name="{{ $name }}" value="0">

    {{-- Visible checkbox --}}
    <input
        type="checkbox"
        name="{{ $name }}"
        id="{{ $name }}"
        value="1"
        @checked($isChecked)
        class="peer hidden"
    >

    {{-- Custom checkbox box --}}
    <span
        class="flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-slate-400 bg-white transition-all peer-checked:border-indigo-500 peer-checked:bg-indigo-500"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-3.5 w-3.5 text-white opacity-0 transition-opacity duration-150 peer-checked:opacity-100"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="3"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
    </span>

    {{-- Label text --}}
    <span class="font-medium text-slate-800">{{ $label }}</span>
</label>
