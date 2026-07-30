{{-- Renders a <button> when no href is given, so actions that must travel by POST
     (log out) stay real submits: an <a href> pointing at a POST-only route 405s
     as soon as JS is unavailable. Both shapes keep one class list. --}}
@php($classes = 'block w-full px-4 py-2 text-start text-sm leading-5 text-ink hover:bg-surface-sunken focus:bg-surface-sunken focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-ring transition duration-150 ease-in-out')

@if ($attributes->has('href'))
    <a {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['type' => 'submit', 'class' => $classes]) }}>{{ $slot }}</button>
@endif
