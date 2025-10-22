@props(['active', 'inverted' => false])

@php
$classes = 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out ';

if ($inverted) {
    $classes .= ($active ?? false)
        ? 'border-white text-white focus:outline-none focus:border-white'
        : 'border-transparent text-white/80 hover:text-white hover:border-white/70 focus:outline-none focus:text-white focus:border-white/70';
} else {
    $classes .= ($active ?? false)
        ? 'border-coral text-brand-text focus:outline-none focus:border-coral-dark'
        : 'border-transparent text-brand-muted hover:text-brand-text hover:border-coral-light focus:outline-none focus:text-brand-text focus:border-coral-light';
}
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
