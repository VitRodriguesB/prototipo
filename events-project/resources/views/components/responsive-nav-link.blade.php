@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-3 border-l-4 border-indigo-500 text-start text-xs font-black uppercase tracking-widest text-white bg-indigo-500/10 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-3 border-l-4 border-transparent text-start text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-white hover:bg-white/5 hover:border-white/10 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
