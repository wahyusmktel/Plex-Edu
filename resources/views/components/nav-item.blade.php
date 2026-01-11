@props(['icon', 'label', 'active' => false, 'href' => '#!'])

<a 
    href="{{ $href }}" 
    class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 relative overflow-hidden {{ $active ? 'bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white shadow-lg shadow-pink-100' : 'text-slate-500 hover:bg-pink-50 hover:text-[#d90d8b]' }}"
>
    <!-- Background Glow Effect for active state -->
    @if($active)
        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-white/10 blur-xl"></div>
    @endif

    <i class="material-icons text-[22px] transition-transform duration-300 group-hover:scale-110 {{ $active ? 'text-white' : 'text-slate-400 group-hover:text-[#d90d8b]' }}">
        {{ $icon }}
    </i>
    
    <span 
        x-show="sidebarOpen" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-x-2"
        x-transition:enter-end="opacity-100 translate-x-0"
        class="font-semibold whitespace-nowrap {{ $active ? 'text-white' : '' }}"
    >
        {{ $label }}
    </span>

    <!-- Active Indicator Dot -->
    @if($active)
        <span class="absolute right-4 w-1.5 h-1.5 bg-white rounded-full"></span>
    @endif
</a>
