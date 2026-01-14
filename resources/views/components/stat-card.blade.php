@props(['icon', 'label', 'value', 'color' => 'blue'])

@php
    $colors = [
        'pink' => 'bg-pink-50 text-pink-600 border-pink-100',
        'purple' => 'bg-purple-50 text-purple-600 border-purple-100',
        'yellow' => 'bg-yellow-50 text-yellow-600 border-yellow-100',
        'blue' => 'bg-blue-50 text-blue-600 border-blue-100',
        'red' => 'bg-red-50 text-red-600 border-red-100',
        'cyan' => 'bg-cyan-50 text-cyan-600 border-cyan-100',
        'emerald' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
        'orange' => 'bg-orange-50 text-orange-600 border-orange-100',
        'indigo' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
    ];
@endphp

<div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group">
    <div class="w-12 h-12 mb-4 rounded-2xl flex items-center justify-center transition-all duration-300 {{ $colors[$color] ?? $colors['blue'] }} group-hover:scale-110">
        <i class="material-icons text-2xl">{{ $icon }}</i>
    </div>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $label }}</p>
    <h4 class="text-2xl font-black text-slate-800 tracking-tight">{{ $value }}</h4>
</div>
