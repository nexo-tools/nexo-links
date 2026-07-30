{{-- The shared chrome button, so the panel's primary action matches the header's
     and clears the 44px touch target the scaffold's text-xs button did not. --}}
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'nexo-btn nexo-btn--primary']) }}>
    {{ $slot }}
</button>
