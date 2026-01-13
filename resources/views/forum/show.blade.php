@extends('layouts.app')

@section('title', $forum->title . ' - Forum Diskusi')

@section('content')
<div x-data="{ createTopicModalOpen: false }">
    <!-- Breadcrumb & Header -->
    <div class="mb-10">
        <nav class="flex items-center gap-3 text-xs font-bold uppercase tracking-widest text-slate-400 mb-6">
            <a href="{{ route('forum.index') }}" class="hover:text-[#d90d8b] transition-colors">FORUM</a>
            <i class="material-icons text-xs">chevron_right</i>
            <span class="text-slate-300">{{ $forum->title }}</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-[#ba80e8]">
                    <i class="material-icons text-3xl">chat</i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ $forum->title }}</h1>
                    <p class="text-sm text-slate-400 font-medium">{{ $forum->description }}</p>
                </div>
            </div>
            <button 
                @click="createTopicModalOpen = true"
                class="flex items-center gap-2 px-6 py-3 bg-[#d90d8b] text-white text-sm font-bold rounded-2xl shadow-lg shadow-pink-100 hover:scale-[1.02] transition-all cursor-pointer {{ auth()->user()->is_suspended_from_forum ? 'opacity-50 cursor-not-allowed' : '' }}"
                {{ auth()->user()->is_suspended_from_forum ? 'disabled' : '' }}
            >
                <i class="material-icons text-lg">add</i>
                MULAI DISKUSI
            </button>
        </div>
    </div>

    <!-- Topics List -->
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Topik Diskusi</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Interaksi</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Author</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($topics as $topic)
                    <tr class="group hover:bg-slate-50/50 transition-colors {{ $topic->is_pinned ? 'bg-amber-50/30' : '' }}">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                @if($topic->is_pinned)
                                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center">
                                    <i class="material-icons text-sm">push_pin</i>
                                </div>
                                @elseif($topic->is_locked)
                                <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center">
                                    <i class="material-icons text-sm">lock</i>
                                </div>
                                @else
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-400 flex items-center justify-center">
                                    <i class="material-icons text-sm">chat_bubble</i>
                                </div>
                                @endif
                                <div>
                                    <a href="{{ route('forum.topic.show', $topic->id) }}" class="text-base font-bold text-slate-700 hover:text-[#d90d8b] transition-colors leading-tight block mb-1">
                                        {{ $topic->title }}
                                    </a>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $topic->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-50 rounded-full border border-slate-100">
                                <i class="material-icons text-xs text-slate-400">forum</i>
                                <span class="text-xs font-black text-slate-600">{{ $topic->posts_count }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-8 h-8 rounded-full border-2 border-white shadow-sm ring-2 ring-slate-100 overflow-hidden">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($topic->user->name) }}&background=ba80e8&color=fff" alt="Avatar">
                                </div>
                                <span class="text-[10px] font-black text-slate-500 uppercase tracking-tight">{{ explode(' ', $topic->user->name)[0] }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($topic->status === 'suspended')
                            <span class="px-3 py-1 bg-red-50 text-red-500 text-[10px] font-black rounded-full border border-red-100 uppercase tracking-widest">SUSPEND</span>
                            @elseif($topic->is_locked)
                            <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-black rounded-full border border-slate-200 uppercase tracking-widest">LOCKED</span>
                            @else
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-500 text-[10px] font-black rounded-full border border-emerald-100 uppercase tracking-widest">ACTIVE</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 rounded-3xl bg-slate-50 flex items-center justify-center text-slate-200 mb-6">
                                    <i class="material-icons text-4xl">inventory_2</i>
                                </div>
                                <h3 class="text-lg font-extrabold text-slate-800 mb-1">Belum Ada Topik</h3>
                                <p class="text-sm text-slate-400 font-medium">Jadilah yang pertama untuk memulai diskusi di forum ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Topic Modal -->
    <template x-if="true">
    <div 
        x-show="createTopicModalOpen" 
        x-cloak
        class="fixed inset-0 z-[60] flex items-center justify-center p-4 shadow-2xl"
    >
        <div 
            x-show="createTopicModalOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            @click="createTopicModalOpen = false"
            class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"
        ></div>

        <div 
            x-show="createTopicModalOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            class="relative w-full max-w-2xl bg-white rounded-[2.5rem] shadow-2xl p-10 overflow-hidden"
        >
            <div class="absolute top-0 right-0 p-8">
                <button @click="createTopicModalOpen = false" class="text-slate-300 hover:text-slate-500 transition-colors">
                    <i class="material-icons">close</i>
                </button>
            </div>

            <div class="mb-8">
                <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center mb-6">
                    <i class="material-icons text-3xl">create</i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight">Mulai Diskusi Baru</h3>
                <p class="text-slate-400 font-medium">Ciptakan topik yang bermanfaat bagi seluruh komunitas.</p>
            </div>

            <form action="{{ route('forum.topic.store', $forum->id) }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 ms-1">JUDUL DISKUSI</label>
                        <input 
                            type="text" 
                            name="title" 
                            required 
                            placeholder="Apa yang ingin Anda diskusikan?"
                            class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold placeholder:text-slate-300 focus:outline-none focus:ring-4 focus:ring-pink-50 focus:bg-white transition-all"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 ms-1">KONTEN / PERTANYAAN</label>
                        <textarea 
                            name="content" 
                            rows="6" 
                            required 
                            placeholder="Tuliskan detail pertanyaan atau topik diskusi Anda secara lengkap..."
                            class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold placeholder:text-slate-300 focus:outline-none focus:ring-4 focus:ring-pink-50 focus:bg-white transition-all resize-none"
                        ></textarea>
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <button 
                        type="button" 
                        @click="createTopicModalOpen = false"
                        class="flex-1 py-4 bg-slate-50 text-slate-400 font-bold rounded-2xl hover:bg-slate-100 transition-colors cursor-pointer"
                    >
                        BATAL
                    </button>
                    <button 
                        type="submit"
                        class="flex-[2] py-4 bg-gradient-to-r from-indigo-500 to-[#d90d8b] text-white font-bold rounded-2xl shadow-lg shadow-pink-100 hover:scale-[1.02] transition-all cursor-pointer"
                    >
                        POSTING DISKUSI
                    </button>
                </div>
            </form>
        </div>
    </div>
    </template>
</div>
@endsection
