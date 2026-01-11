@props(['label', 'name', 'type' => 'text', 'placeholder' => '', 'required' => false])

<div class="space-y-1.5" {{ $attributes->only('x-show') }}>
    <label for="{{ $name }}" class="text-xs font-extrabold text-slate-400 uppercase tracking-widest ml-1">{{ $label }}</label>
    <input 
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->whereStartsWith('x-model') }}
        class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 placeholder-slate-300 focus:ring-2 focus:ring-pink-100 focus:border-[#d90d8b]/30 outline-none transition-all duration-200"
    >
</div>
