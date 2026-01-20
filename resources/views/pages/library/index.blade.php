@extends('layouts.app')

@section('title', 'E-Library - Literasia')

@section('content')
<div class="space-y-6" x-data="{ tab: 'books' }">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">E-Library</h1>
            <p class="text-slate-500">Kelola koleksi digital (Buku, Audio, Video) dan peminjaman.</p>
        </div>
        <div class="flex items-center gap-3">
            @if(auth()->user()->role !== 'siswa')
            <a href="{{ route('library.loans') }}" class="flex items-center gap-2 px-5 py-2.5 bg-white text-slate-600 font-semibold rounded-xl border border-slate-200 hover:bg-slate-50 transition-all">
                <i class="material-icons text-lg">swap_horiz</i> Transaksi Peminjaman
            </a>
            @if(auth()->user()->role !== 'guru')
            <a href="{{ route('library.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white font-semibold rounded-xl shadow-md shadow-pink-100 hover:shadow-lg transition-all">
                <i class="material-icons text-lg">add</i> Tambah Koleksi
            </a>
            @endif
            @endif
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-{{ auth()->user()->role === 'siswa' ? '4' : '3' }} gap-6">
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
        @if(auth()->user()->role === 'siswa')
        <div class="bg-emerald-50/50 p-6 rounded-2xl border border-emerald-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                <i class="material-icons">bookmark_added</i>
            </div>
            <div>
                <p class="text-sm font-medium text-emerald-600/70">Sedang Dipinjam</p>
                <h3 class="text-2xl font-bold text-emerald-700">{{ $totalBorrowedCount }}</h3>
            </div>
        </div>
        @endif
    </div>

    <!-- Tabs Section -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
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

        <!-- Filter Bar -->
        @if($categories->count() > 0)
        <div class="px-6 py-4 bg-slate-50/50 flex flex-wrap gap-2 items-center">
            <span class="text-xs font-bold text-slate-400 mr-2">FILTER KATEGORI:</span>
            <a href="{{ route('library.index') }}" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all {{ !request('kategori') ? 'bg-[#d90d8b] text-white shadow-md shadow-pink-100' : 'bg-white text-slate-500 border border-slate-200 hover:border-[#d90d8b]/50' }}">
                SEMUA
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('library.index', ['kategori' => $cat]) }}" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all {{ request('kategori') == $cat ? 'bg-[#d90d8b] text-white shadow-md shadow-pink-100' : 'bg-white text-slate-500 border border-slate-200 hover:border-[#d90d8b]/50' }}">
                {{ strtoupper($cat) }}
            </a>
            @endforeach
        </div>
        @endif

        <div class="p-6">
            <!-- Books Tab -->
            <div x-show="tab === 'books'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
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
                            <!-- Overlay actions -->
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 z-10">
                                @php $isBorrowed = array_key_exists($book->id, $borrowedItems) || auth()->user()->role !== 'siswa'; @endphp
                                <button type="button" 
                                    @if($isBorrowed)
                                        @click="$store.reader.openModal('{{ addslashes($book->title) }}', '{{ asset('storage/' . $book->file_path) }}')" 
                                    @else
                                        @click="$dispatch('open-modal', 'borrow-item-{{ $book->id }}')"
                                    @endif
                                    class="w-10 h-10 rounded-full bg-white text-slate-800 flex items-center justify-center hover:bg-[#d90d8b] hover:text-white transition-colors cursor-pointer shadow-lg outline-none border-none">
                                    <i class="material-icons">{{ $isBorrowed ? 'visibility' : 'lock' }}</i>
                                </button>
                                @if(auth()->user()->role === 'admin')
                                <button type="button" 
                                    @click="deleteItem('{{ $book->id }}', '{{ addslashes($book->title) }}')"
                                    class="w-10 h-10 rounded-full bg-white text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors shadow-lg cursor-pointer">
                                    <i class="material-icons">delete</i>
                                </button>
                                @endif
                            </div>
                        </div>
                        <div class="p-4">
                            <h4 class="font-bold text-slate-800 line-clamp-1">{{ $book->title }}</h4>
                            <p class="text-xs text-slate-500 mt-1">{{ $book->author }}</p>
                            @if($book->kategori)
                                <div class="mt-2 text-[10px] font-bold text-[#d90d8b] bg-pink-50 px-2 py-0.5 rounded-md inline-block uppercase">
                                    {{ $book->kategori }}
                                </div>
                            @endif
                            @if(array_key_exists($book->id, $borrowedItems))
                                @php 
                                    $dueDate = \Carbon\Carbon::parse($borrowedItems[$book->id]);
                                    $remaining = round(now()->diffInDays($dueDate, false));
                                    $remaining = $remaining < 0 ? 0 : $remaining;
                                @endphp
                                <div class="mt-2 text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md inline-block uppercase">
                                    Dipinjam (Sisa {{ $remaining }} Hari)
                                </div>
                            @endif
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
                                <div class="flex items-center gap-2 mt-0.5">
                                    <p class="text-xs text-slate-500">{{ $audio->author }}</p>
                                    @if($audio->kategori)
                                        <span class="text-[9px] font-bold text-orange-600 bg-orange-50 px-1.5 py-0.5 rounded uppercase">
                                            {{ $audio->kategori }}
                                        </span>
                                    @endif
                                    @if(array_key_exists($audio->id, $borrowedItems))
                                        @php 
                                            $dueDate = \Carbon\Carbon::parse($borrowedItems[$audio->id]);
                                            $remaining = round(now()->diffInDays($dueDate, false));
                                            $remaining = $remaining < 0 ? 0 : $remaining;
                                        @endphp
                                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded uppercase">
                                            Dipinjam (Sisa {{ $remaining }} Hari)
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            @php $isBorrowed = array_key_exists($audio->id, $borrowedItems) || auth()->user()->role !== 'siswa'; @endphp
                            @if($isBorrowed)
                                <audio src="{{ asset('storage/' . $audio->file_path) }}" controls class="h-8 max-w-[200px]"></audio>
                            @else
                                <button type="button" @click="$dispatch('open-modal', 'borrow-item-{{ $audio->id }}')" class="flex items-center gap-2 px-4 py-2 bg-orange-50 text-orange-600 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-orange-100 transition-all cursor-pointer">
                                    <i class="material-icons text-sm">lock</i> Pinjam Sekarang
                                </button>
                            @endif
                            @if(auth()->user()->role === 'admin')
                            <button type="button" @click="deleteItem('{{ $audio->id }}', '{{ addslashes($audio->title) }}')" class="p-2 text-slate-400 hover:text-red-500 transition-colors cursor-pointer">
                                <i class="material-icons">delete</i>
                            </button>
                            @endif
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
                                @php $isBorrowed = array_key_exists($video->id, $borrowedItems) || auth()->user()->role !== 'siswa'; @endphp
                                <button type="button" 
                                    @if($isBorrowed)
                                        @click="$store.videoPlayer.openModal('{{ addslashes($video->title) }}', '{{ asset('storage/' . $video->file_path) }}')"
                                    @else
                                        @click="$dispatch('open-modal', 'borrow-item-{{ $video->id }}')"
                                    @endif
                                    class="w-12 h-12 rounded-full bg-white text-slate-800 flex items-center justify-center hover:bg-[#d90d8b] hover:text-white transition-colors shadow-lg cursor-pointer">
                                    <i class="material-icons text-3xl">{{ $isBorrowed ? 'play_arrow' : 'lock' }}</i>
                                </button>
                            </div>
                        </div>
                        <div class="p-4 flex items-center justify-between">
                            <div>
                                <h4 class="font-bold text-slate-800 line-clamp-1">{{ $video->title }}</h4>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <p class="text-xs text-slate-500">{{ $video->author }}</p>
                                    @if($video->kategori)
                                        <span class="text-[9px] font-bold text-purple-600 bg-purple-50 px-1.5 py-0.5 rounded uppercase">
                                            {{ $video->kategori }}
                                        </span>
                                    @endif
                                    @if(array_key_exists($video->id, $borrowedItems))
                                        @php 
                                            $dueDate = \Carbon\Carbon::parse($borrowedItems[$video->id]);
                                            $remaining = round(now()->diffInDays($dueDate, false));
                                            $remaining = $remaining < 0 ? 0 : $remaining;
                                        @endphp
                                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded uppercase">
                                            Dipinjam (Sisa {{ $remaining }} Hari)
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if(auth()->user()->role !== 'guru')
                            <button type="button" @click="deleteItem('{{ $video->id }}', '{{ addslashes($video->title) }}')" class="p-2 text-slate-400 hover:text-red-500 transition-colors cursor-pointer">
                                <i class="material-icons text-xl">delete</i>
                            </button>
                            @endif
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

    <!-- Reader Modal -->
    <div 
        x-show="$store.reader && $store.reader.open" 
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/90 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="relative w-full h-full bg-white overflow-hidden flex flex-col shadow-2xl transition-all duration-300"
             :class="$store.reader.fullscreen ? 'rounded-none' : 'max-w-6xl max-h-[90vh] rounded-3xl m-4'">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-8 py-4 border-b border-slate-100 bg-white">
                <div>
                    <h3 class="font-bold text-slate-800" x-text="$store.reader.title"></h3>
                    <p class="text-xs text-slate-500" x-text="'Halaman ' + ($store.reader.currentPage + 1) + ' dari ' + $store.reader.totalPages"></p>
                </div>
                <div class="flex gap-2">
                    <!-- Zoom Controls -->
                    <button @click="$store.reader.zoomOut()" class="p-2 rounded-xl hover:bg-slate-50 text-slate-400 hover:text-[#d90d8b] transition-all" title="Perkecil">
                        <i class="material-icons">zoom_out</i>
                    </button>
                    <span class="px-2 py-1 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg" x-text="($store.reader.zoom * 100).toFixed(0) + '%'"></span>
                    <button @click="$store.reader.zoomIn()" class="p-2 rounded-xl hover:bg-slate-50 text-slate-400 hover:text-[#d90d8b] transition-all" title="Perbesar">
                        <i class="material-icons">zoom_in</i>
                    </button>
                    <div class="w-px h-6 bg-slate-100 mx-2"></div>
                    <!-- Page Controls -->
                    <button @click="$store.reader.prev()" class="p-2 rounded-xl hover:bg-slate-50 text-slate-400 hover:text-[#d90d8b] transition-all">
                        <i class="material-icons">chevron_left</i>
                    </button>
                    <button @click="$store.reader.next()" class="p-2 rounded-xl hover:bg-slate-50 text-slate-400 hover:text-[#d90d8b] transition-all">
                        <i class="material-icons">chevron_right</i>
                    </button>
                    <div class="w-px h-6 bg-slate-100 mx-2"></div>
                    <!-- Fullscreen Toggle -->
                    <button @click="$store.reader.fullscreen = !$store.reader.fullscreen" class="p-2 rounded-xl hover:bg-slate-50 text-slate-400 hover:text-[#d90d8b] transition-all" title="Layar Penuh">
                        <i class="material-icons" x-text="$store.reader.fullscreen ? 'fullscreen_exit' : 'fullscreen'"></i>
                    </button>
                    <button @click="$store.reader.closeModal()" class="p-2 rounded-xl hover:bg-red-50 text-slate-400 hover:text-red-500 transition-all">
                        <i class="material-icons">close</i>
                    </button>
                </div>
            </div>

            <!-- Reader Container -->
            <div class="flex-grow bg-slate-100 flex items-center justify-center overflow-auto p-4 relative">
                <div id="book-container" 
                    class="transition-all duration-300" 
                    :class="$store.reader.loading ? 'opacity-0' : 'opacity-100'"
                    :style="'transform: scale(' + $store.reader.zoom + '); transform-origin: center center;'">
                    <!-- Pages will be rendered here -->
                </div>
                
                <!-- Loading State -->
                <div x-show="$store.reader.loading" class="absolute inset-0 flex flex-col items-center justify-center bg-white/50 backdrop-blur-sm z-50">
                    <div class="w-12 h-12 border-4 border-[#d90d8b]/20 border-t-[#d90d8b] rounded-full animate-spin mb-4"></div>
                    <p class="text-sm font-bold text-[#d90d8b] animate-pulse">Menyiapkan Buku...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Player Modal -->
    <div 
        x-show="$store.videoPlayer && $store.videoPlayer.open" 
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/95 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="relative w-full max-w-5xl bg-slate-900 rounded-3xl overflow-hidden flex flex-col shadow-2xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 bg-slate-800">
                <h3 class="font-bold text-white" x-text="$store.videoPlayer.title"></h3>
                <button @click="$store.videoPlayer.closeModal()" class="p-2 rounded-xl hover:bg-slate-700 text-slate-400 hover:text-white transition-all">
                    <i class="material-icons">close</i>
                </button>
            </div>
            <!-- Video Player -->
            <div class="aspect-video bg-black">
                <video id="video-player" 
                    :src="$store.videoPlayer.url" 
                    class="w-full h-full" 
                    controls 
                    autoplay
                    x-ref="videoElement">
                </video>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->role === 'siswa')
    @foreach($books->concat($audios)->concat($videos) as $item)
        @if(!array_key_exists($item->id, $borrowedItems))
            <x-modal name="borrow-item-{{ $item->id }}" title="Pinjam Koleksi">
                <form action="{{ route('library.borrow', $item->id) }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <div class="flex items-start gap-6">
                        <div class="w-24 h-32 rounded-xl bg-slate-100 flex-shrink-0 overflow-hidden shadow-sm">
                            @if($item->cover_image)
                                <img src="{{ asset('storage/' . $item->cover_image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <i class="material-icons text-3xl">library_books</i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-800 mb-1 tracking-tight">{{ $item->title }}</h3>
                            <p class="text-sm font-bold text-slate-400 mb-4">{{ $item->author }}</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-[9px] font-black uppercase tracking-widest">{{ $item->category }}</span>
                                @if($item->kategori)
                                    <span class="px-3 py-1 bg-pink-50 text-[#d90d8b] rounded-full text-[9px] font-black uppercase tracking-widest">{{ $item->kategori }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 pt-4 border-t border-slate-50">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Durasi Peminjaman (Hari)</label>
                        <div class="grid grid-cols-4 gap-3" x-data="{ duration: 3 }">
                            <template x-for="d in [1, 3, 7, 14]">
                                <button type="button" 
                                    @click="duration = d"
                                    :class="duration === d ? 'bg-[#d90d8b] text-white shadow-md shadow-pink-100' : 'bg-slate-50 text-slate-400 hover:bg-slate-100'"
                                    class="py-4 rounded-2xl text-xs font-black transition-all cursor-pointer"
                                    x-text="d + ' Hari'">
                                </button>
                            </template>
                            <input type="hidden" name="duration" :value="duration">
                        </div>
                    </div>

                    <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100 flex items-start gap-3">
                        <i class="material-icons text-indigo-500">info</i>
                        <p class="text-[10px] font-bold text-indigo-700 leading-relaxed uppercase tracking-wide">Setelah dikonfirmasi, Anda dapat segera menikmati koleksi ini sesuai dengan durasi yang dipilih.</p>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="submit" class="flex-1 py-5 bg-gradient-to-r from-slate-900 to-slate-800 text-white text-[10px] font-black rounded-2xl uppercase tracking-widest hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-slate-200">
                            KONFIRMASI PINJAM
                        </button>
                    </div>
                </form>
            </x-modal>
        @endif
    @endforeach
@endif
@endsection

@section('styles')
<style>
    .st-page-flip {
        background-color: transparent !important;
    }
    .page {
        background-color: white;
        width: 550px;
        height: 733px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }
    .page-content {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: contain;
    }
    #book-container {
        min-width: 1100px;
        min-height: 733px;
    }
    #book-container canvas {
        width: 100% !important;
        height: 100% !important;
    }
    [x-cloak] { display: none !important; }
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.min.js"></script>

<script>
    function initLibraryReader() {
        const storeData = {
            open: false,
            title: '',
            url: '',
            loading: false,
            totalPages: 0,
            currentPage: 0,
            pageFlip: null,
            zoom: 1,
            fullscreen: false,

            async openModal(title, url) {
                console.log('Reader: Opening', title, url);
                this.title = title;
                this.url = url;
                this.open = true;
                this.loading = true;
                this.currentPage = 0;
                
                // Allow modal to show before heavy PDF processing
                setTimeout(() => this.initReader(), 300);
            },

            async initReader() {
                console.log('Reader: Starting initialization...');
                const pdfjsLib = window['pdfjs-dist/build/pdf'] || window.pdfjsLib;
                if (!pdfjsLib) {
                    console.error('Reader: pdf.js not found');
                    this.loading = false;
                    return;
                }
                
                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

                try {
                    const loadingTask = pdfjsLib.getDocument(this.url);
                    const pdf = await loadingTask.promise;
                    this.totalPages = pdf.numPages;
                    console.log('Reader: PDF loaded, pages:', this.totalPages);

                    const rootContainer = document.getElementById('book-container');
                    if (!rootContainer) {
                        this.loading = false;
                        return;
                    }
                    
                    // Always start with a clean root
                    rootContainer.innerHTML = '';
                    rootContainer.style.opacity = '1';
                    
                    // Create a fresh flipbook element
                    const flipbook = document.createElement('div');
                    flipbook.style.width = '600px';
                    flipbook.style.height = '800px';
                    rootContainer.appendChild(flipbook);

                    console.log('Reader: Rendering pages...');
                    for (let n = 1; n <= this.totalPages; n++) {
                        const page = await pdf.getPage(n);
                        const scale = 2; // Higher quality
                        const viewport = page.getViewport({ scale });

                        const canvas = document.createElement('canvas');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        canvas.classList.add('page-content');

                        await page.render({
                            canvasContext: canvas.getContext('2d'),
                            viewport: viewport
                        }).promise;

                        const pageDiv = document.createElement('div');
                        pageDiv.classList.add('page');
                        pageDiv.appendChild(canvas);
                        flipbook.appendChild(pageDiv);
                        
                        page.cleanup();
                    }

                    console.log('Reader: DOM ready, starting PageFlip');

                    if (this.pageFlip) {
                        try { this.pageFlip.destroy(); } catch(e) {}
                        this.pageFlip = null;
                    }

                    // Give browser time to recognize new DOM nodes and their sizes
                    setTimeout(() => {
                        try {
                            this.pageFlip = new St.PageFlip(flipbook, {
                                width: 600,
                                height: 800,
                                size: "stretch",
                                minWidth: 315,
                                maxWidth: 1000,
                                minHeight: 420,
                                maxHeight: 1350,
                                maxShadowOpacity: 0.5,
                                showCover: true,
                                mobileScrollSupport: false,
                                usePortrait: false, // Force spread if possible
                                startPage: 0
                            });

                            this.pageFlip.loadFromHTML(flipbook.querySelectorAll('.page'));
                            
                            this.pageFlip.on('flip', (e) => {
                                this.currentPage = e.data;
                            });

                            this.loading = false;
                            console.log('Reader: Initialization complete');
                        } catch (err) {
                            console.error('PageFlip Error:', err);
                            this.loading = false;
                            alert('Gagal menginisialisasi efek buku.');
                        }
                    }, 300);
                } catch (error) {
                    console.error('Reader Error:', error);
                    this.loading = false;
                    alert('Gagal memuat buku. Pastikan file PDF valid.');
                    this.closeModal();
                }
            },

            next() { if (this.pageFlip) this.pageFlip.flipNext(); },
            prev() { if (this.pageFlip) this.pageFlip.flipPrev(); },

            zoomIn() { 
                if (this.zoom < 2) this.zoom = Math.min(2, this.zoom + 0.1); 
            },
            zoomOut() { 
                if (this.zoom > 0.5) this.zoom = Math.max(0.5, this.zoom - 0.1); 
            },

            closeModal() {
                console.log('Reader: Closing');
                this.open = false;
                this.loading = false;
                this.zoom = 1;
                this.fullscreen = false;
                if (this.pageFlip) {
                    try {
                        this.pageFlip.destroy();
                    } catch(e) { console.warn('PageFlip destroy error', e); }
                    this.pageFlip = null;
                }
                const container = document.getElementById('book-container');
                if (container) container.innerHTML = '';
            }
        };

        const videoStoreData = {
            open: false,
            title: '',
            url: '',

            openModal(title, url) {
                this.title = title;
                this.url = url;
                this.open = true;
            },

            closeModal() {
                this.open = false;
                this.url = '';
                const video = document.getElementById('video-player');
                if (video) {
                    video.pause();
                    video.currentTime = 0;
                }
            }
        };

        if (window.Alpine) {
            Alpine.store('reader', storeData);
            Alpine.store('videoPlayer', videoStoreData);
        } else {
            document.addEventListener('alpine:init', () => {
                Alpine.store('reader', storeData);
                Alpine.store('videoPlayer', videoStoreData);
            });
        }
    }

    function deleteItem(id, title) {
        Swal.fire({
            title: 'Hapus Koleksi?',
            html: `Apakah Anda yakin ingin menghapus <strong>"${title}"</strong>?<br>Tindakan ini tidak dapat dibatalkan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d90d8b',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/library/${id}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    initLibraryReader();
</script>
@endsection
