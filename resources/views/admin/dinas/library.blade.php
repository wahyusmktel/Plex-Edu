@extends('layouts.app')

@section('title', 'E-Library Global - Literasia')

@section('content')
<div class="space-y-8" x-data="{ tab: '{{ $selectedCategory ?? 'all' }}' }">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-600 shadow-lg shadow-pink-100 flex items-center justify-center text-white">
                <i class="material-icons text-3xl">local_library</i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Monitoring Koleksi Digital</p>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">E-Library Global</h1>
            </div>
        </div>
        <button @click="$store.libraryForm.open = true" class="flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
            <i class="material-icons">add_circle</i>
            Tambah Koleksi
        </button>
    </div>

    <!-- Stats Monitoring Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Collection Breakdown -->
        <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm col-span-1">
            <h3 class="text-lg font-extrabold text-slate-800 mb-6 flex items-center gap-2">
                <i class="material-icons text-pink-500">pie_chart</i> Komposisi Koleksi
            </h3>
            <div class="space-y-6">
                @php
                    $categories_info = [
                        'book' => ['icon' => 'menu_book', 'label' => 'E-Book', 'color' => 'bg-blue-500', 'text' => 'text-blue-500'],
                        'audio' => ['icon' => 'audiotrack', 'label' => 'Audio Book', 'color' => 'bg-orange-500', 'text' => 'text-orange-500'],
                        'video' => ['icon' => 'videocam', 'label' => 'Video Book', 'color' => 'bg-purple-500', 'text' => 'text-purple-500'],
                    ];
                @endphp
                @foreach($categories_info as $key => $info)
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <div class="flex items-center gap-2">
                                <i class="material-icons text-sm {{ $info['text'] }}">{{ $info['icon'] }}</i>
                                <span class="text-sm font-bold text-slate-600">{{ $info['label'] }}</span>
                            </div>
                            <span class="text-sm font-black text-slate-800">{{ number_format($typeStats[$key]) }}</span>
                        </div>
                        <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $info['color'] }} rounded-full" style="width: {{ $totalItems > 0 ? ($typeStats[$key] / $totalItems) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8 pt-6 border-t border-slate-50 flex items-center justify-between text-slate-400">
                <span class="text-xs font-bold uppercase tracking-widest">Total Koleksi</span>
                <span class="text-xl font-black text-slate-800">{{ number_format($totalItems) }}</span>
            </div>
        </div>

        <!-- Top Contributors -->
        <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm col-span-2">
            <h3 class="text-lg font-extrabold text-slate-800 mb-6 flex items-center gap-2">
                <i class="material-icons text-emerald-500">trending_up</i> Kontributor Koleksi Terbanyak
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[250px] overflow-y-auto pr-2 custom-scrollbar">
                @foreach($schoolStats->sortByDesc('total_items')->take(10) as $school)
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-100 group hover:border-pink-200 transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-slate-400 group-hover:text-pink-500 shadow-sm transition-colors">
                                <i class="material-icons text-xl">school</i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800 line-clamp-1">{{ $school->nama_sekolah }}</p>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $school->jenjang }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-black text-slate-800 leading-none">{{ $school->total_items }}</p>
                            <p class="text-[10px] font-bold text-slate-400">Items</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" x-data="{ 
        showSchools: false, 
        searchSchool: '',
        schools: @js($schools),
        selectedSchoolId: '{{ $selectedSchoolId }}',
        selectedSchoolName: '{{ $selectedSchoolId ? ($schools->firstWhere("id", $selectedSchoolId)->nama_sekolah ?? "-- Pilih Sekolah --") : "-- Semua Sekolah --" }}',
        
        showCategories: false,
        searchCategory: '',
        categories: [
            { id: '', name: 'Semua Tipe', count: {{ $totalItems }} },
            { id: 'book', name: 'E-Book (PDF)', count: {{ $typeStats['book'] }} },
            { id: 'audio', name: 'Audio Book', count: {{ $typeStats['audio'] }} },
            { id: 'video', name: 'Video Book', count: {{ $typeStats['video'] }} }
        ],
        selectedCategoryId: '{{ $selectedCategory }}',
        selectedCategoryName: '{{ $selectedCategory ? ($selectedCategory == "book" ? "E-Book (PDF)" : ($selectedCategory == "audio" ? "Audio Book" : "Video Book")) : "-- Semua Tipe --" }}',

        get filteredSchools() {
            return this.schools.filter(s => s.nama_sekolah.toLowerCase().includes(this.searchSchool.toLowerCase()));
        },
        get filteredCategories() {
            return this.categories.filter(c => c.name.toLowerCase().includes(this.searchCategory.toLowerCase()));
        }
    }">
        <form action="{{ route('dinas.library') }}" method="GET" class="space-y-6">
            <input type="hidden" name="school_id" :value="selectedSchoolId">
            <input type="hidden" name="category" :value="selectedCategoryId">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Search -->
                <div class="space-y-1.5 col-span-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Cari Koleksi</label>
                    <div class="relative">
                        <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</i>
                        <input name="search" type="text" value="{{ $search }}" class="w-full bg-slate-50 border border-slate-100 rounded-2xl pl-12 pr-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all" placeholder="Judul atau penulis...">
                    </div>
                </div>

                <!-- Jenjang -->
                <div class="space-y-1.5 col-span-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jenjang Sekolah</label>
                    <select name="jenjang" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all">
                        <option value="">Semua Jenjang</option>
                        <option value="sd" {{ $selectedJenjang === 'sd' ? 'selected' : '' }}>SD</option>
                        <option value="smp" {{ $selectedJenjang === 'smp' ? 'selected' : '' }}>SMP</option>
                        <option value="sma_smk" {{ $selectedJenjang === 'sma_smk' ? 'selected' : '' }}>SMA/SMK</option>
                    </select>
                </div>

                <!-- Searchable School Select -->
                <div class="space-y-1.5 col-span-1 relative">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Sekolah</label>
                    <div class="relative">
                        <button 
                            @click="showSchools = !showSchools; if(showSchools) showCategories = false"
                            type="button"
                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 text-left flex justify-between items-center focus:ring-2 focus:ring-pink-100 transition-all"
                        >
                            <span class="truncate" x-text="selectedSchoolName"></span>
                            <i class="material-icons text-slate-400" :class="showSchools ? 'rotate-180' : ''">expand_more</i>
                        </button>

                        <div 
                            x-show="showSchools" 
                            @click.away="showSchools = false"
                            x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="absolute left-0 right-0 mt-2 bg-white border border-slate-100 rounded-[2rem] shadow-xl z-50 overflow-hidden"
                        >
                            <div class="p-4 border-b border-slate-50">
                                <div class="relative">
                                    <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-300">search</i>
                                    <input 
                                        type="text" 
                                        x-model="searchSchool"
                                        placeholder="Cari sekolah..." 
                                        class="w-full bg-slate-50 border border-slate-100 rounded-xl pl-12 pr-4 py-2.5 text-sm font-semibold outline-none focus:ring-2 focus:ring-pink-100"
                                        @click.stop
                                    >
                                </div>
                            </div>
                            <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                <button 
                                    @click="selectedSchoolId = ''; selectedSchoolName = '-- Semua Sekolah --'; showSchools = false"
                                    type="button"
                                    class="w-full text-left px-6 py-4 text-sm font-bold transition-all hover:bg-slate-50 flex items-center justify-between"
                                    :class="selectedSchoolId == '' ? 'bg-pink-50 text-[#d90d8b]' : 'text-slate-600'"
                                >
                                    <span>-- Semua Sekolah --</span>
                                </button>
                                <template x-for="school in filteredSchools" :key="school.id">
                                    <button 
                                        @click="selectedSchoolId = school.id; selectedSchoolName = school.nama_sekolah; showSchools = false"
                                        type="button"
                                        class="w-full text-left px-6 py-4 text-sm font-bold transition-all hover:bg-slate-50 flex items-center justify-between"
                                        :class="school.id == selectedSchoolId ? 'bg-pink-50 text-[#d90d8b]' : 'text-slate-600'"
                                    >
                                        <div class="min-w-0 pr-4">
                                            <p class="truncate" x-text="school.nama_sekolah"></p>
                                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest" x-text="school.jenjang"></p>
                                        </div>
                                        <span class="text-[10px] font-black px-2.5 py-1 rounded-lg shrink-0" 
                                              :class="school.total_items > 0 ? 'bg-pink-100 text-[#d90d8b]' : 'bg-slate-50 text-slate-300'"
                                              x-text="school.total_items + ' Buku'"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Searchable Category Select -->
                <div class="space-y-1.5 col-span-1 relative">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tipe Media</label>
                    <div class="relative">
                        <button 
                            @click="showCategories = !showCategories; if(showCategories) showSchools = false"
                            type="button"
                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 text-left flex justify-between items-center focus:ring-2 focus:ring-pink-100 transition-all"
                        >
                            <span class="truncate" x-text="selectedCategoryName"></span>
                            <i class="material-icons text-slate-400" :class="showCategories ? 'rotate-180' : ''">expand_more</i>
                        </button>

                        <div 
                            x-show="showCategories" 
                            @click.away="showCategories = false"
                            x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="absolute left-0 right-0 mt-2 bg-white border border-slate-100 rounded-[2rem] shadow-xl z-50 overflow-hidden"
                        >
                            <div class="p-4 border-b border-slate-50">
                                <div class="relative">
                                    <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-300">search</i>
                                    <input 
                                        type="text" 
                                        x-model="searchCategory"
                                        placeholder="Cari tipe..." 
                                        class="w-full bg-slate-50 border border-slate-100 rounded-xl pl-12 pr-4 py-2.5 text-sm font-semibold outline-none focus:ring-2 focus:ring-pink-100"
                                        @click.stop
                                    >
                                </div>
                            </div>
                            <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                <template x-for="cat in filteredCategories" :key="cat.id">
                                    <button 
                                        @click="selectedCategoryId = cat.id; selectedCategoryName = cat.name; showCategories = false"
                                        type="button"
                                        class="w-full text-left px-6 py-4 text-sm font-bold transition-all hover:bg-slate-50 flex items-center justify-between"
                                        :class="cat.id == selectedCategoryId ? 'bg-pink-50 text-[#d90d8b]' : 'text-slate-600'"
                                    >
                                        <span x-text="cat.name"></span>
                                        <span class="text-[10px] font-black px-2.5 py-1 rounded-lg shrink-0" 
                                              :class="cat.count > 0 ? 'bg-pink-100 text-[#d90d8b]' : 'bg-slate-50 text-slate-300'"
                                              x-text="cat.count + ' Koleksi'"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-50">
                <a href="{{ route('dinas.library') }}" class="px-8 py-3.5 rounded-2xl text-xs font-black text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-all uppercase tracking-widest">Reset Filter</a>
                <button type="submit" class="px-10 py-3.5 rounded-2xl bg-gradient-to-r from-pink-500 to-rose-600 text-white text-xs font-black shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all uppercase tracking-widest">
                    Cari Koleksi
                </button>
            </div>
        </form>
    </div>

    <!-- Items Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @forelse($items as $item)
            <div class="group bg-white rounded-3xl border border-slate-100 overflow-hidden hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500 flex flex-col">
                <div class="aspect-[3/4] bg-slate-100 relative overflow-hidden">
                    @if($item->cover_image)
                        <img src="{{ Storage::url($item->cover_image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                            <i class="material-icons text-6xl">@php
                                echo $item->category === 'book' ? 'menu_book' : ($item->category === 'audio' ? 'audiotrack' : 'videocam');
                            @endphp</i>
                        </div>
                    @endif
                    
                    <!-- Type Badge -->
                    <div class="absolute top-4 left-4 z-10">
                        @php
                            $badgeClass = $item->category === 'book' ? 'bg-blue-500' : ($item->category === 'audio' ? 'bg-orange-500' : 'bg-purple-500');
                            $icon = $item->category === 'book' ? 'menu_book' : ($item->category === 'audio' ? 'audiotrack' : 'videocam');
                        @endphp
                        <span class="flex items-center gap-1.5 {{ $badgeClass }} text-white px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg">
                            <i class="material-icons text-xs">{{ $icon }}</i>
                            {{ $item->category }}
                        </span>
                    </div>

                    <!-- Overlay actions -->
                    <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 z-20">
                        @if($item->category === 'book')
                        <button type="button" 
                            @click="$store.reader.openModal('{{ addslashes($item->title) }}', '{{ Storage::url($item->file_path) }}')" 
                            class="w-12 h-12 rounded-full bg-white text-slate-800 flex items-center justify-center hover:bg-pink-500 hover:text-white transition-all cursor-pointer shadow-xl">
                            <i class="material-icons">visibility</i>
                        </button>
                        @elseif($item->category === 'video')
                        <button type="button" 
                            @click="$store.videoPlayer.openModal('{{ addslashes($item->title) }}', '{{ Storage::url($item->file_path) }}')"
                            class="w-12 h-12 rounded-full bg-white text-slate-800 flex items-center justify-center hover:bg-pink-500 hover:text-white transition-all shadow-xl cursor-pointer">
                            <i class="material-icons text-3xl">play_arrow</i>
                        </button>
                        @elseif($item->category === 'audio')
                        <button @click="playAudio('{{ Storage::url($item->file_path) }}', '{{ addslashes($item->title) }}')" class="w-12 h-12 rounded-full bg-white text-slate-800 flex items-center justify-center hover:bg-orange-500 hover:text-white transition-all shadow-xl cursor-pointer">
                            <i class="material-icons">play_arrow</i>
                        </button>
                        @endif
                    </div>
                </div>

                <div class="p-6 flex-grow flex flex-col">
                    <h4 class="font-bold text-slate-800 line-clamp-2 leading-tight mb-2 group-hover:text-pink-600 transition-colors">{{ $item->title }}</h4>
                    <p class="text-xs font-bold text-slate-400 mb-4 flex items-center gap-1">
                        <i class="material-icons text-xs">person</i> {{ $item->author }}
                    </p>
                    
                    <div class="mt-auto pt-4 border-t border-slate-50 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 shrink-0">
                            <i class="material-icons text-sm">school</i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Sekolah Pemilik</p>
                            <p class="text-[11px] font-bold text-slate-600 truncate">{{ $item->school->nama_sekolah ?? 'Global / Semua Sekolah' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center bg-white rounded-[2.5rem] border border-slate-100 shadow-sm">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200">
                    <i class="material-icons text-6xl">library_books</i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Koleksi Tidak Ditemukan</h3>
                <p class="text-slate-400 font-medium">Coba sesuaikan filter atau kata kunci pencarian Anda.</p>
                <div class="mt-8">
                    <a href="{{ route('dinas.library') }}" class="px-8 py-3 bg-pink-50 text-pink-600 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-pink-100 transition-all">Lihat Semua Koleksi</a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($items->hasPages())
        <div class="mt-12 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            {{ $items->links() }}
        </div>
    @endif

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
                    <button @click="$store.reader.zoomOut()" class="p-2 rounded-xl hover:bg-slate-50 text-slate-400 hover:text-pink-500 transition-all">
                        <i class="material-icons">zoom_out</i>
                    </button>
                    <span class="px-2 py-1 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg flex items-center" x-text="($store.reader.zoom * 100).toFixed(0) + '%' "></span>
                    <button @click="$store.reader.zoomIn()" class="p-2 rounded-xl hover:bg-slate-50 text-slate-400 hover:text-pink-500 transition-all">
                        <i class="material-icons">zoom_in</i>
                    </button>
                    <div class="w-px h-6 bg-slate-100 mx-2"></div>
                    <button @click="$store.reader.prev()" class="p-2 rounded-xl hover:bg-slate-50 text-slate-400 hover:text-pink-500 transition-all">
                        <i class="material-icons">chevron_left</i>
                    </button>
                    <button @click="$store.reader.next()" class="p-2 rounded-xl hover:bg-slate-50 text-slate-400 hover:text-pink-500 transition-all">
                        <i class="material-icons">chevron_right</i>
                    </button>
                    <div class="w-px h-6 bg-slate-100 mx-2"></div>
                    <button @click="$store.reader.fullscreen = !$store.reader.fullscreen" class="p-2 rounded-xl hover:bg-slate-50 text-slate-400 hover:text-pink-500 transition-all">
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
                </div>
                
                <!-- Loading State -->
                <div x-show="$store.reader.loading" class="absolute inset-0 flex flex-col items-center justify-center bg-white/50 backdrop-blur-sm z-50">
                    <div class="w-12 h-12 border-4 border-pink-500/20 border-t-pink-500 rounded-full animate-spin mb-4"></div>
                    <p class="text-sm font-bold text-pink-500 animate-pulse">Menyiapkan Buku...</p>
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
        <div class="relative w-full max-w-5xl bg-slate-900 rounded-[2.5rem] overflow-hidden flex flex-col shadow-2xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-8 py-5 bg-slate-800 border-b border-slate-700">
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

    <!-- Add Collection Modal -->
    <div 
        x-show="$store.libraryForm && $store.libraryForm.open" 
        x-cloak
        class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-900/90 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
    >
        <div class="relative w-full max-w-4xl bg-white rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-10 py-6 border-b border-slate-50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-pink-50 flex items-center justify-center text-pink-500">
                        <i class="material-icons">add_to_photos</i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-800">Tambah Koleksi Global</h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Koleksi ini akan tampil di semua sekolah</p>
                    </div>
                </div>
                <button @click="$store.libraryForm.open = false" class="w-10 h-10 rounded-xl hover:bg-slate-50 text-slate-400 transition-all flex items-center justify-center">
                    <i class="material-icons">close</i>
                </button>
            </div>

            <!-- Form Content -->
            <div class="flex-grow overflow-y-auto custom-scrollbar p-10">
                <form id="globalLibraryForm" action="{{ route('dinas.library.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kategori Koleksi</label>
                                <select name="category" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all outline-none" required>
                                    <option value="book">E-Book (PDF)</option>
                                    <option value="audio">Audio Book (MP3)</option>
                                    <option value="video">Video Book (MP4/WebM)</option>
                                </select>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Judul Koleksi</label>
                                <input type="text" name="title" placeholder="Masukkan judul..." class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all outline-none" required>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pengarang / Pembuat</label>
                                <input type="text" name="author" placeholder="Nama pengarang..." class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all outline-none" required>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kategori / Label</label>
                                <input type="text" name="kategori" placeholder="Fiksi, Sains, dll..." class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all outline-none">
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Deskripsi Ringkas</label>
                                <textarea name="description" rows="3" placeholder="Informasi singkat..." class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-pink-100 transition-all outline-none"></textarea>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Unggah Fail Digital</label>
                                <div class="relative group border-2 border-dashed border-slate-100 rounded-[2rem] p-8 transition-all hover:bg-pink-50/20 hover:border-pink-200 text-center">
                                    <input type="file" name="file" id="digitalFileForm" class="absolute inset-0 opacity-0 cursor-pointer" @change="$store.libraryForm.handleFileSelect($event)" required>
                                    <div x-show="!$store.libraryForm.fileName">
                                        <i class="material-icons text-4xl text-slate-300 mb-2">cloud_upload</i>
                                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Pilih Fail (PDF, MP3, MP4)</p>
                                        <p class="text-[9px] font-black text-slate-400 mt-1 uppercase tracking-widest">Maks. 500MB</p>
                                    </div>
                                    <div x-show="$store.libraryForm.fileName">
                                        <i class="material-icons text-4xl text-emerald-500 mb-2">verified</i>
                                        <p class="text-[11px] font-bold text-slate-700 truncate" x-text="$store.libraryForm.fileName"></p>
                                        <p class="text-[10px] font-black text-[#d90d8b] mt-1" x-text="$store.libraryForm.fileSize"></p>
                                    </div>
                                </div>
                            </div>

                            <div x-show="$store.libraryForm.uploading" class="p-6 bg-pink-50 rounded-3xl border border-pink-100">
                                <div class="flex items-center gap-4">
                                    <div class="flex-grow">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-[10px] font-black text-[#d90d8b] uppercase tracking-widest">Mengunggah...</span>
                                            <span class="text-[10px] font-black text-[#d90d8b]" x-text="Math.round($store.libraryForm.uploadProgress) + '%'"></span>
                                        </div>
                                        <div class="w-full h-2 bg-pink-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] transition-all duration-300" :style="'width: ' + $store.libraryForm.uploadProgress + '%'"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Sampul (Opsional)</label>
                                <div class="relative group border-2 border-dashed border-slate-100 rounded-[2rem] p-8 transition-all hover:bg-pink-50/20 hover:border-pink-200 text-center">
                                    <input type="file" name="cover_image" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                                    <i class="material-icons text-4xl text-slate-300 mb-2">add_photo_alternate</i>
                                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Unggah Sampul</p>
                                    <p class="text-[9px] font-black text-slate-400 mt-1 uppercase tracking-widest">JPEG/PNG, Maks. 2MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="p-10 border-t border-slate-50 bg-slate-50/50 flex justify-end gap-3">
                <button @click="$store.libraryForm.open = false" class="px-8 py-4 rounded-2xl text-xs font-black text-slate-500 hover:text-slate-800 hover:bg-white transition-all uppercase tracking-widest">Batal</button>
                <button @click="$store.libraryForm.submitForm()" :disabled="$store.libraryForm.uploading" class="px-10 py-4 rounded-2xl bg-gradient-to-r from-pink-500 to-rose-600 text-white text-xs font-black shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all uppercase tracking-widest disabled:opacity-50">
                    <span x-show="!$store.libraryForm.uploading">Simpan Koleksi</span>
                    <span x-show="$store.libraryForm.uploading" class="flex items-center gap-2">
                        <i class="material-icons text-lg animate-spin">autorenew</i> Memproses...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
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
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #f1f5f9;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #e2e8f0;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.min.js"></script>

<script>
    function initLibraryReader() {
        // Non-reactive storage for heavy objects to avoid Alpine Proxy issues
        let pdfDoc = null;
        let renderedPages = [];
        let pageFlipInstance = null;

        const storeData = {
            open: false,
            title: '',
            url: '',
            loading: false,
            totalPages: 0,
            currentPage: 0,
            zoom: 1,
            fullscreen: false,

            async openModal(title, url) {
                this.title = title;
                this.url = url;
                this.open = true;
                this.loading = true;
                this.currentPage = 0;
                setTimeout(() => this.initReader(), 300);
            },

            async initReader() {
                const pdfjsLib = window['pdfjs-dist/build/pdf'] || window.pdfjsLib;
                if (!pdfjsLib) {
                    this.loading = false;
                    return;
                }
                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

                try {
                    const loadingTask = pdfjsLib.getDocument(this.url);
                    const pdf = await loadingTask.promise;
                    this.totalPages = pdf.numPages;
                    pdfDoc = pdf;
                    renderedPages = new Array(this.totalPages + 1).fill(false);

                    const rootContainer = document.getElementById('book-container');
                    if (!rootContainer) {
                        this.loading = false;
                        return;
                    }
                    rootContainer.innerHTML = '';
                    
                    const flipbook = document.createElement('div');
                    flipbook.style.width = '600px';
                    flipbook.style.height = '800px';
                    rootContainer.appendChild(flipbook);

                    // Pre-create all page containers
                    for (let n = 1; n <= this.totalPages; n++) {
                        const pageDiv = document.createElement('div');
                        pageDiv.classList.add('page');
                        pageDiv.id = `pdf-page-${n}`;
                        
                        const placeholder = document.createElement('div');
                        placeholder.className = 'flex flex-col items-center justify-center h-full bg-slate-50 text-slate-300';
                        placeholder.innerHTML = `
                            <i class="material-icons text-4xl mb-2 animate-pulse">menu_book</i>
                            <span class="text-xs font-bold uppercase tracking-widest">Memuat Halaman ${n}...</span>
                        `;
                        pageDiv.appendChild(placeholder);
                        flipbook.appendChild(pageDiv);
                    }

                    if (pageFlipInstance) {
                        try { pageFlipInstance.destroy(); } catch(e) {}
                        pageFlipInstance = null;
                    }

                    await this.renderPageRange(1, Math.min(3, this.totalPages));

                    setTimeout(() => {
                        try {
                            pageFlipInstance = new St.PageFlip(flipbook, {
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
                                usePortrait: false,
                                startPage: 0
                            });

                            pageFlipInstance.loadFromHTML(flipbook.querySelectorAll('.page'));
                            pageFlipInstance.on('flip', (e) => { 
                                this.currentPage = e.data;
                                const current = e.data + 1;
                                this.renderPageRange(Math.max(1, current - 2), Math.min(this.totalPages, current + 3));
                            });
                            this.loading = false;
                        } catch (err) {
                            console.error('PageFlip Error:', err);
                            this.loading = false;
                        }
                    }, 300);
                } catch (error) {
                    console.error('Reader Error:', error);
                    this.loading = false;
                    this.closeModal();
                }
            },

            async renderPageRange(start, end) {
                for (let i = start; i <= end; i++) {
                    await this.renderSinglePage(i);
                }
            },

            async renderSinglePage(pageNum) {
                if (!pdfDoc || renderedPages[pageNum]) return;
                
                try {
                    const page = await pdfDoc.getPage(pageNum);
                    const scale = 2;
                    const viewport = page.getViewport({ scale });

                    const canvas = document.createElement('canvas');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;
                    canvas.classList.add('page-content');

                    const context = canvas.getContext('2d');
                    await page.render({
                        canvasContext: context,
                        viewport: viewport
                    }).promise;

                    const container = document.getElementById(`pdf-page-${pageNum}`);
                    if (container) {
                        container.innerHTML = '';
                        container.appendChild(canvas);
                        renderedPages[pageNum] = true;
                    }
                    page.cleanup();
                } catch (err) {
                    console.error(`Error rendering page ${pageNum}:`, err);
                }
            },

            next() { if (pageFlipInstance) pageFlipInstance.flipNext(); },
            prev() { if (pageFlipInstance) pageFlipInstance.flipPrev(); },
            zoomIn() { if (this.zoom < 2) this.zoom = Math.min(2, this.zoom + 0.1); },
            zoomOut() { if (this.zoom > 0.5) this.zoom = Math.max(0.5, this.zoom - 0.1); },

            closeModal() {
                this.open = false;
                this.loading = false;
                this.zoom = 1;
                this.fullscreen = false;
                if (pageFlipInstance) {
                    try { pageFlipInstance.destroy(); } catch(e) {}
                    pageFlipInstance = null;
                }
                pdfDoc = null;
                renderedPages = [];
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
                if (video) { video.pause(); video.currentTime = 0; }
            }
        };

        const formStoreData = {
            open: false,
            uploading: false,
            uploadProgress: 0,
            fileName: '',
            fileSize: '',

            handleFileSelect(event) {
                if (event.target.files.length > 0) {
                    const file = event.target.files[0];
                    this.fileName = file.name;
                    const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                    this.fileSize = sizeMB + ' MB';
                }
            },

            async submitForm() {
                const form = document.getElementById('globalLibraryForm');
                const digitalFile = document.getElementById('digitalFileForm').files[0];
                
                if (!digitalFile) {
                    Swal.fire('Oops...', 'Silakan pilih fail digital.', 'error');
                    return;
                }

                this.uploading = true;
                this.uploadProgress = 0;

                try {
                    const signedResponse = await $.ajax({
                        url: '{{ route("dinas.library.signed-url") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            file_name: digitalFile.name,
                            file_type: digitalFile.type,
                            category: form.category.value
                        }
                    });

                    if (signedResponse.supported) {
                        const uploadUrl = signedResponse.url;
                        const filePath = signedResponse.path;

                        await new Promise((resolve, reject) => {
                            const xhr = new XMLHttpRequest();
                            xhr.open('PUT', uploadUrl, true);
                            xhr.setRequestHeader('Content-Type', digitalFile.type);
                            
                            xhr.upload.onprogress = (e) => {
                                if (e.lengthComputable) {
                                    this.uploadProgress = (e.loaded / e.total) * 100;
                                }
                            };
                            
                            xhr.onload = () => {
                                if (xhr.status === 200 || xhr.status === 201) resolve();
                                else reject(new Error('Gagal mengunggah fail ke cloud storage.'));
                            };
                            
                            xhr.onerror = () => reject(new Error('Kesalahan jaringan saat mengunggah.'));
                            xhr.send(digitalFile);
                        });

                        const formData = new FormData(form);
                        formData.append('file_path', filePath);
                        formData.delete('file');

                        await $.ajax({
                            url: form.action,
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false
                        });
                    } else {
                        const formData = new FormData(form);
                        await new Promise((resolve, reject) => {
                            const xhr = new XMLHttpRequest();
                            xhr.open('POST', form.action, true);
                            xhr.upload.onprogress = (e) => {
                                if (e.lengthComputable) {
                                    this.uploadProgress = (e.loaded / e.total) * 100;
                                }
                            };
                            xhr.onload = () => {
                                if (xhr.status === 200 || xhr.status === 302) resolve();
                                else {
                                    try {
                                        const err = JSON.parse(xhr.responseText);
                                        reject(new Error(err.message || 'Gagal menyimpan data ke server.'));
                                    } catch(e) {
                                        reject(new Error('Gagal menyimpan data ke server.'));
                                    }
                                }
                            };
                            xhr.onerror = () => reject(new Error('Kesalahan jaringan saat mengunggah.'));
                            xhr.send(formData);
                        });
                    }

                    this.uploading = false;
                    this.open = false;
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Koleksi global berhasil ditambahkan.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });

                } catch (err) {
                    this.uploading = false;
                    console.error('Upload Error:', err);
                    let msg = 'Terjadi kesalahan saat mengunggah.';
                    if (err.message) msg = err.message;
                    if (err.responseJSON?.message) msg = err.responseJSON.message;
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: msg
                    });
                }
            }
        };

        if (window.Alpine) {
            Alpine.store('reader', storeData);
            Alpine.store('videoPlayer', videoStoreData);
            Alpine.store('libraryForm', formStoreData);
        } else {
            document.addEventListener('alpine:init', () => {
                Alpine.store('reader', storeData);
                Alpine.store('videoPlayer', videoStoreData);
                Alpine.store('libraryForm', formStoreData);
            });
        }
    }

    function playAudio(url, title) {
        Swal.fire({
            title: title,
            html: `
                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 mt-4">
                    <i class="material-icons text-5xl text-orange-500 mb-4 animate-bounce">audiotrack</i>
                    <audio src="${url}" controls class="w-full"></audio>
                </div>
            `,
            showConfirmButton: false,
            showCloseButton: true,
            customClass: {
                popup: 'rounded-[2.5rem]',
            }
        });
    }

    initLibraryReader();
</script>
@endsection
