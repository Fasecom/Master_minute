@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-[#2B3F63] bg-indigo-50 font-sans font-semibold transition duration-150 ease-in-out' // активный
    : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-[#234E9B] font-sans font-semibold hover:text-[#2B3F63] transition duration-150 ease-in-out'; // обычный
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} style="font-family: 'Open Sans', sans-serif;">
    {{ $slot }}
</a>
