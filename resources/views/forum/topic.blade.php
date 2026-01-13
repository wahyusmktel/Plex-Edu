@extends('layouts.app')

@section('title', $topic->title . ' - Forum Diskusi')

@section('content')
<div x-data="{ parentId: '', replyingToName: '', isReplying: false }">
    <!-- Breadcrumb & Thread Header -->
    <div class="mb-10">
        <nav class="flex items-center gap-3 text-xs font-bold uppercase tracking-widest text-slate-400 mb-6">
            <a href="{{ route('forum.index') }}" class="hover:text-[#d90d8b] transition-colors">FORUM</a>
            <i class="material-icons text-xs">chevron_right</i>
            <a href="{{ route('forum.show', $topic->forum_id) }}" class="hover:text-[#d90d8b] transition-colors">{{ $topic->forum->title }}</a>
            <i class="material-icons text-xs">chevron_right</i>
            <span class="text-slate-300 truncate max-w-[200px]">{{ $topic->title }}</span>
        </nav>

        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8 md:p-10 mb-8">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-8">
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-500 shadow-sm shadow-indigo-50">
                        <i class="material-icons text-3xl">question_answer</i>
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            @if($topic->is_pinned)
                            <span class="px-3 py-1 bg-amber-50 text-amber-500 text-[10px] font-black rounded-full border border-amber-100 uppercase tracking-widest flex items-center gap-1">
                                <i class="material-icons text-xs">push_pin</i> PINNED
                            </span>
                            @endif
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Diposting {{ $topic->created_at->diffForHumans() }}</span>
                        </div>
                        <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-tight mb-4">{{ $topic->title }}</h1>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full border-2 border-slate-100 overflow-hidden">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($topic->user->name) }}&background=ba80e8&color=fff" alt="Author">
                            </div>
                            <div>
                                <p class="text-sm font-black text-slate-700">{{ $topic->user->name }}</p>
                                <p class="text-[10px] font-bold text-[#d90d8b] uppercase tracking-widest">{{ $topic->user->role }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 self-end md:self-auto">
                    <form action="{{ route('forum.topic.bookmark', $topic->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-slate-400 hover:bg-pink-50 hover:text-[#d90d8b] hover:border-pink-100 transition-all cursor-pointer @if($isBookmarked) bg-pink-50 text-[#d90d8b] border-pink-100 @endif" title="Bookmark">
                            <i class="material-icons">{{ $isBookmarked ? 'bookmark' : 'bookmark_border' }}</i>
                        </button>
                    </form>
                    
                    @if(auth()->user()->role === 'guru' || auth()->user()->role === 'admin')
                    <div class="relative" x-data="{ modOpen: false }" @click.away="modOpen = false">
                        <button @click="modOpen = !modOpen" class="flex items-center gap-2 px-5 py-3 h-[48px] bg-slate-800 text-white text-xs font-bold rounded-2xl shadow-lg shadow-slate-200 hover:scale-[1.02] transition-all cursor-pointer">
                            MODERASI
                            <i class="material-icons text-sm">expand_more</i>
                        </button>
                        <div x-show="modOpen" x-cloak class="absolute right-0 mt-3 w-56 p-2 bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 z-50">
                            <form action="{{ route('forum.topic.moderate', $topic->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="pin">
                                <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-xs font-bold text-slate-600 rounded-xl hover:bg-slate-50 transition-colors">
                                    <i class="material-icons text-lg">{{ $topic->is_pinned ? 'push_pin' : 'push_pin' }}</i> 
                                    {{ $topic->is_pinned ? 'UNPIN TOPIK' : 'PIN TOPIK' }}
                                </button>
                            </form>
                            <form action="{{ route('forum.topic.moderate', $topic->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="lock">
                                <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-xs font-bold text-slate-600 rounded-xl hover:bg-slate-50 transition-colors">
                                    <i class="material-icons text-lg">{{ $topic->is_locked ? 'lock_open' : 'lock' }}</i> 
                                    {{ $topic->is_locked ? 'BUKA KUNCI' : 'KUNCI TOPIK' }}
                                </button>
                            </form>
                            <form action="{{ route('forum.topic.moderate', $topic->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="suspend">
                                <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-xs font-bold text-red-500 rounded-xl hover:bg-red-50 transition-colors">
                                    <i class="material-icons text-lg">block</i> 
                                    {{ $topic->status === 'suspended' ? 'AKTIFKAN' : 'TANGGUHKAN' }}
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="mt-10 pt-10 border-t border-slate-50">
                <div class="prose max-w-none text-slate-600 font-medium leading-[1.8] text-lg">
                    {!! nl2br(e($topic->content)) !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Content & Sidebar -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Main Timeline -->
        <div class="lg:col-span-8">
            <div class="mb-8 flex items-center justify-between">
                <h3 class="text-xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                    <i class="material-icons text-[#ba80e8]">comment</i>
                    BALASAN ({{ count($posts) }})
                </h3>
            </div>

            <div class="space-y-6">
                @forelse($posts as $post)
                <div class="group bg-white rounded-[2rem] p-6 md:p-8 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-start gap-5">
                        <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-[#ba80e8] overflow-hidden">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name) }}&background=f8fafc&color=ba80e8" alt="Avatar">
                        </div>
                        <div class="flex-grow">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-sm font-black text-slate-800">{{ $post->user->name }}</h4>
                                    @if($post->user->role === 'guru')
                                    <span class="px-2 py-0.5 bg-indigo-50 text-indigo-500 text-[8px] font-black rounded border border-indigo-100 uppercase tracking-widest">GURU</span>
                                    @endif
                                </div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="text-slate-600 font-medium leading-relaxed text-sm mb-4">
                                {!! nl2br(e($post->content)) !!}
                            </div>
                            <div class="flex items-center gap-4">
                                @if(!$topic->is_locked && $topic->status !== 'suspended' && !auth()->user()->is_suspended_from_forum)
                                <button 
                                    @click="parentId = '{{ $post->id }}'; replyingToName = '{{ $post->user->name }}'; isReplying = true; $nextTick(() => { document.getElementById('reply-editor').scrollIntoView({ behavior: 'smooth' }); document.getElementById('reply-textarea').focus(); })"
                                    class="text-xs font-black text-[#ba80e8] hover:text-[#d90d8b] transition-colors flex items-center gap-1 cursor-pointer"
                                >
                                    <i class="material-icons text-sm">reply</i> BALAS
                                </button>
                                @endif

                                @if(auth()->user()->role === 'guru' || auth()->user()->role === 'admin')
                                <form action="{{ route('forum.user.suspend', $post->user_id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs font-black text-red-300 hover:text-red-500 transition-colors flex items-center gap-1 cursor-pointer uppercase tracking-widest">
                                        <i class="material-icons text-sm">person_off</i> 
                                        {{ $post->user->is_suspended_from_forum ? 'Unsuspend' : 'Suspend' }}
                                    </button>
                                </form>
                                @endif
                            </div>

                            <!-- Nested Replies -->
                            @if($post->replies->count() > 0)
                            <div class="mt-8 space-y-6 border-l-2 border-slate-50 pl-6 md:pl-8">
                                @foreach($post->replies as $reply)
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-9 h-9 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 overflow-hidden ring-2 ring-white">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name) }}&background=f1f5f9&color=94a3b8" alt="Avatar">
                                    </div>
                                    <div class="flex-grow">
                                        <div class="flex items-center justify-between mb-1">
                                            <h5 class="text-xs font-black text-slate-700">{{ $reply->user->name }}</h5>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="text-slate-500 font-medium leading-relaxed text-xs">
                                            {!! nl2br(e($reply->content)) !!}
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-[2rem] p-12 border border-slate-100 shadow-sm text-center flex flex-col items-center">
                    <div class="w-20 h-20 rounded-3xl bg-slate-50 flex items-center justify-center text-slate-200 mb-6">
                        <i class="material-icons text-4xl">chat_bubble_outline</i>
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-800 mb-1">Belum Ada Balasan</h3>
                    <p class="text-sm text-slate-400 font-medium">Jadilah orang pertama yang memberikan tanggapan!</p>
                </div>
                @endforelse
            </div>

            <!-- Reply Form -->
            @if(!$topic->is_locked && $topic->status !== 'suspended' && !auth()->user()->is_suspended_from_forum)
            <div id="reply-editor" class="mt-12 group bg-white rounded-[2.5rem] p-8 md:p-10 border border-slate-100 shadow-xl shadow-slate-200/50">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-pink-50 text-[#d90d8b] flex items-center justify-center">
                        <i class="material-icons">mode_comment</i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight" x-text="isReplying ? 'Balas Komentar' : 'Tulis Balasan'">Tulis Balasan</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Kontribusi pada diskusi</p>
                    </div>
                </div>

                <form action="{{ route('forum.post.store', $topic->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="parent_id" x-model="parentId">
                    
                    <div x-show="isReplying" x-cloak class="mb-6 flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <div class="flex items-center gap-3">
                            <i class="material-icons text-indigo-400 text-sm">reply</i>
                            <span class="text-xs font-bold text-slate-600">Membalas ke: <strong class="text-slate-800" x-text="replyingToName"></strong></span>
                        </div>
                        <button type="button" @click="isReplying = false; parentId = ''; replyingToName = ''" class="text-slate-300 hover:text-red-500 transition-colors">
                            <i class="material-icons text-sm">close</i>
                        </button>
                    </div>

                    <div class="mb-8">
                        <textarea 
                            id="reply-textarea"
                            name="content" 
                            rows="5" 
                            required 
                            placeholder="Apa tanggapan atau pertanyaan Anda?"
                            class="w-full px-8 py-6 bg-slate-50 border border-slate-100 rounded-[2rem] text-slate-800 font-bold placeholder:text-slate-300 focus:outline-none focus:ring-4 focus:ring-pink-50 focus:bg-white transition-all resize-none"
                        ></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-10 py-4 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white font-bold rounded-2xl shadow-lg shadow-pink-100 hover:scale-[1.05] active:scale-95 transition-all cursor-pointer">
                            KIRIM BALASAN
                        </button>
                    </div>
                </form>
            </div>
            @else
            <div class="mt-12 bg-slate-100/50 rounded-[2rem] border border-dashed border-slate-200 p-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-white flex items-center justify-center text-slate-300 mx-auto mb-6">
                    <i class="material-icons text-3xl">lock</i>
                </div>
                <h3 class="text-lg font-black text-slate-600 mb-1">Diskusi Tidak Tersedia</h3>
                <p class="text-sm font-medium text-slate-400">
                    @if($topic->is_locked)
                        Diskusi ini telah dikunci oleh guru.
                    @elseif($topic->status === 'suspended')
                        Diskusi ini telah ditangguhkan karena alasan tertentu.
                    @elseif(auth()->user()->is_suspended_from_forum)
                        Akun Anda ditangguhkan dari forum komunitas.
                    @endif
                </p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-4 space-y-8">
            <!-- Stats Card -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden text-center p-8">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-8">Informasi Diskusi</p>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-slate-50 rounded-2xl">
                        <p class="text-2xl font-black text-slate-800">{{ count($posts) }}</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Balasan</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl">
                        <p class="text-2xl font-black text-slate-800">{{ count($posts->pluck('user_id')->unique()) }}</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Partisipan</p>
                    </div>
                </div>
            </div>

            <!-- Notifications / Actions -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-pink-50 text-[#d90d8b] flex items-center justify-center">
                        <i class="material-icons">notifications_active</i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-slate-800">Notifikasi</h4>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Update Diskusi</p>
                    </div>
                </div>
                <p class="text-xs text-slate-500 font-medium leading-relaxed mb-8">
                    Terima notifikasi untuk setiap balasan baru pada topik diskusi ini.
                </p>
                <form action="{{ route('forum.topic.mute', $topic->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-4 text-xs font-black rounded-2xl transition-all flex items-center justify-center gap-2 cursor-pointer border {{ $isMuted ? 'bg-slate-50 text-slate-400 border-slate-100' : 'bg-white text-red-400 border-red-100 hover:bg-red-50' }}">
                        <i class="material-icons text-lg">{{ $isMuted ? 'volume_up' : 'volume_off' }}</i>
                        {{ $isMuted ? 'BUNYIKAN TOPIK' : 'DIAMKAN TOPIK' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
