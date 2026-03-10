@props(['disabled' => false])

<style>
    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus {
        -webkit-text-fill-color: #f0eee9 !important;
        -webkit-box-shadow: 0 0 0px 1000px #1a1a1d inset !important;
        transition: background-color 5000s ease-in-out 0s;
    }
</style>

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-[#1a1a1d] border-white/5 focus:border-[#4f46e5] focus:ring-[#4f46e5] rounded-2xl text-[#f0eee9] placeholder-slate-600 shadow-inner transition-all py-4 px-8 w-full']) }}>