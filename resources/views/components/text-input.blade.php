@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-sky-500 focus:ring-0 rounded-none shadow-sm outline-none']) }}>
