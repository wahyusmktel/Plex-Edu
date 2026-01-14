@props(['icon', 'label', 'value', 'color' => 'blue'])

@php
    $bgColors = [
        'yellow' => 'bg-amber-500 shadow-amber-200',
        'blue' => 'bg-blue-500 shadow-blue-200',
        'purple' => 'bg-indigo-500 shadow-indigo-200',
        'red' => 'bg-rose-500 shadow-rose-200',
    ];
@endphp

<div class="flex items-center justify-between p-5 bg-white rounded-3xl border border-slate-100 shadow-sm hover:translate-x-1 transition-transform duration-300">
    <div class="flex items-center gap-4">
        <div class="flex-shrink-0 w-12 h-12 rounded-2xl flex items-center justify-center text-white shadow-lg {{ $bgColors[$color] ?? $bgColors['blue'] }}">
            <i class="material-icons text-2xl">{{ $icon }}</i>
        </div>
        <p class="text-sm font-bold text-slate-700">{{ $label }}</p>
    </div>
    <div class="text-right">
        <p class="text-xl font-black text-slate-900">{{ $value }}</p>
    </div>
</div>
