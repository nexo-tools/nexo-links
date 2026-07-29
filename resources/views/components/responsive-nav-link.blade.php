@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-brand-400 text-start text-base font-medium text-primary bg-surface-sunken focus:outline-none focus-visible:border-brand-700 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-muted hover:text-ink hover:bg-surface-sunken hover:border-line focus:outline-none focus-visible:text-ink focus-visible:bg-surface-sunken focus-visible:border-line transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
