@extends('layouts.app')

@section('title', 'E-Library - Literasia')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">E-Library</h1>
            <p class="text-slate-500">Kelola koleksi digital (Buku, Audio, Video) dan peminjaman.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('library.loans') }}" class="flex items-center gap-2 px-5 py-2.5 bg-white text-slate-600 font-semibold rounded-xl border border-slate-200 hover:bg-slate-50 transition-all">
                <i class="material-icons text-lg">swap_horiz</i> Transaksi Peminjaman
            </a>
            <a href="{{ route('library.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white font-semibold rounded-xl shadow-md shadow-pink-100 hover:shadow-lg transition-all">
                <i class="material-icons text-lg">add</i> Tambah Koleksi
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center">
                <i class="material-icons">menu_book</i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total E-Book</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $books->count() }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center">
                <i class="material-icons">audiotrack</i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Audio Book</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $audios->count() }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center">
                <i class="material-icons">videocam</i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Video Book</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $videos->count() }}</h3>
            </div>
        </div>
    </div>

    <!-- Tabs Section -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden" x-data="{ tab: 'books' }">
        <div class="flex border-b border-slate-100 px-4">
            <button @click="tab = 'books'" :class="tab === 'books' ? 'text-[#d90d8b] border-[#d90d8b]' : 'text-slate-400 border-transparent'" class="px-6 py-4 text-sm font-bold border-b-2 transition-all cursor-pointer">
                E-BOOK (PDF)
            </button>
            <button @click="tab = 'audios'" :class="tab === 'audios' ? 'text-[#d90d8b] border-[#d90d8b]' : 'text-slate-400 border-transparent'" class="px-6 py-4 text-sm font-bold border-b-2 transition-all cursor-pointer">
                AUDIO BOOK
            </button>
            <button @click="tab = 'videos'" :class="tab === 'videos' ? 'text-[#d90d8b] border-[#d90d8b]' : 'text-slate-400 border-transparent'" class="px-6 py-4 text-sm font-bold border-b-2 transition-all cursor-pointer">
                VIDEO BOOK
            </button>
        </div>

        <div class="p-6">
            <!-- Books Tab -->
            <div x-show="tab === 'books'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($books as $book)
                    <div class="group bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden hover:shadow-md transition-all">
                        <div class="aspect-[3/4] bg-slate-200 relative">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <i class="material-icons text-5xl">book</i>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                <a href="{{ asset('storage/' . $book->file_path) }}" target="_blank" class="w-10 h-10 rounded-full bg-white text-slate-800 flex items-center justify-center hover:bg-[#d90d8b] hover:text-white transition-colors">
                                    <i class="material-icons">visibility</i>
                                </a>
                                <form action="{{ route('library.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="w-10 h-10 rounded-full bg-white text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="p-4">
                            <h4 class="font-bold text-slate-800 line-clamp-1">{{ $book->title }}</h4>
                            <p class="text-xs text-slate-500 mt-1">{{ $book->author }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400">
                        <i class="material-icons text-5xl mb-2">inbox</i>
                        <p>Belum ada koleksi E-Book.</p>
                    </div>
                @endforelse
            </div>

            <!-- Audios Tab -->
            <div x-show="tab === 'audios'" class="space-y-4">
                @forelse($audios as $audio)
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100 group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                                <i class="material-icons">music_note</i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">{{ $audio->title }}</h4>
                                <p class="text-xs text-slate-500">{{ $audio->author }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <audio src="{{ asset('storage/' . $audio->file_path) }}" controls class="h-8 max-w-[200px]"></audio>
                            <form action="{{ route('library.destroy', $audio->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin?')">
                                @csrf
                                @method('DELETE')
                                <button class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                                    <i class="material-icons">delete</i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400">
                        <i class="material-icons text-5xl mb-2">library_music</i>
                        <p>Belum ada koleksi Audio Book.</p>
                    </div>
                @endforelse
            </div>

            <!-- Videos Tab -->
            <div x-show="tab === 'videos'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($videos as $video)
                    <div class="group bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden hover:shadow-md transition-all">
                        <div class="aspect-video bg-black relative">
                            <video src="{{ asset('storage/' . $video->file_path) }}" class="w-full h-full object-cover"></video>
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                <a href="{{ asset('storage/' . $video->file_path) }}" target="_blank" class="w-12 h-12 rounded-full bg-white text-slate-800 flex items-center justify-center hover:bg-[#d90d8b] hover:text-white transition-colors shadow-lg">
                                    <i class="material-icons text-3xl">play_arrow</i>
                                </a>
                            </div>
                        </div>
                        <div class="p-4 flex items-center justify-between">
                            <div>
                                <h4 class="font-bold text-slate-800 line-clamp-1">{{ $video->title }}</h4>
                                <p class="text-xs text-slate-500 mt-1">{{ $video->author }}</p>
                            </div>
                            <form action="{{ route('library.destroy', $video->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin?')">
                                @csrf
                                @method('DELETE')
                                <button class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                                    <i class="material-icons text-xl">delete</i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400">
                        <i class="material-icons text-5xl mb-2">video_library</i>
                        <p>Belum ada koleksi Video Book.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
