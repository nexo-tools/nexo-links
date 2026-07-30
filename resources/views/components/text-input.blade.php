@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-control bg-bg text-ink focus:border-primary focus:ring-ring rounded-md shadow-sm']) }}>
