@props([
    'name',
    'title' => '',
])

<div
    x-data="{ show: false }"
    x-show="show"
    x-on:open-modal.window="if ($event.detail === '{{ $name }}') show = true"
    x-on:close-modal.window="if ($event.detail === '{{ $name }}') show = false"
    x-on:keydown.escape.window="show = false"
    style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
>
    <!-- Backdrop -->
    <div 
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"
        @click="show = false"
    ></div>

    <!-- Modal Content -->
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative w-full max-w-2xl bg-white rounded-[2.5rem] shadow-2xl overflow-hidden"
    >
        <!-- Modal Header -->
        @if($title)
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
            <h3 class="text-xl font-black text-slate-800 tracking-tight">{{ $title }}</h3>
            <button @click="show = false" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all">
                <i class="material-icons">close</i>
            </button>
        </div>
        @endif

        <!-- Modal Body -->
        <div class="modal-body">
            {{ $slot }}
        </div>
    </div>
</div>
