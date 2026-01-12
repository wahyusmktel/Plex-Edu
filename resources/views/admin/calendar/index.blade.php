@extends('layouts.app')

@section('title', 'Kalender Sekolah - Literasia')

@section('styles')
<!-- FullCalendar CSS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<style>
    :root {
        --fc-border-color: #f1f5f9;
        --fc-daygrid-event-dot-width: 8px;
        --fc-today-bg-color: #f8fafc;
        --fc-event-border-color: transparent;
        --fc-button-bg-color: #ffffff;
        --fc-button-border-color: #f1f5f9;
        --fc-button-hover-bg-color: #f8fafc;
        --fc-button-hover-border-color: #e2e8f0;
        --fc-button-active-bg-color: #f1f5f9;
        --fc-button-active-border-color: #cbd5e1;
        --fc-button-text-color: #64748b;
    }

    .fc {
        font-family: 'Inter', sans-serif;
    }

    /* Premium Toolbar Styling */
    .fc .fc-toolbar {
        margin-bottom: 2rem !important;
        background: white;
        padding: 1.5rem;
        border-radius: 2rem;
        border: 1px solid #f1f5f9;
    }

    .fc .fc-toolbar-title {
        font-size: 1.25rem !important;
        font-weight: 900 !important;
        color: #1e293b !important;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .fc .fc-button {
        border-radius: 1rem !important;
        font-weight: 700 !important;
        font-size: 0.75rem !important;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        padding: 0.6rem 1.2rem !important;
        box-shadow: none !important;
        transition: all 0.2s;
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active, 
    .fc .fc-button-primary:not(:disabled):active {
        background-color: #ba80e8 !important;
        border-color: #ba80e8 !important;
        color: white !important;
    }

    /* Grid Styling */
    .fc-theme-standard td, .fc-theme-standard th {
        border: 1px solid #f8fafc !important;
    }

    .fc .fc-scrollgrid {
        border-radius: 2.5rem;
        overflow: hidden;
        border: 1px solid #f1f5f9 !important;
        background: white;
    }

    .fc .fc-col-header-cell {
        background: #f8fafc;
        padding: 1rem 0 !important;
    }

    .fc .fc-col-header-cell-cushion {
        font-size: 0.7rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #94a3b8;
    }

    .fc .fc-daygrid-day-number {
        font-weight: 800;
        color: #64748b;
        padding: 0.75rem !important;
        font-size: 0.85rem;
    }

    .fc .fc-day-today {
        background: rgba(186, 128, 232, 0.05) !important;
    }

    .fc .fc-day-today .fc-daygrid-day-number {
        color: #ba80e8;
    }

    /* Event Styling */
    .fc-event {
        border-radius: 0.75rem !important;
        padding: 0.25rem 0.5rem !important;
        font-size: 0.7rem !important;
        font-weight: 700 !important;
        margin: 2px 4px !important;
        border: none !important;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .fc-event:hover {
        transform: scale(1.02);
    }

    /* Popover Styling */
    .fc-popover {
        border-radius: 1.5rem !important;
        border: 1px solid #f1f5f9 !important;
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1) !important;
    }

    .fc-popover-header {
        background: #f8fafc !important;
        border-top-left-radius: 1.5rem !important;
        border-top-right-radius: 1.5rem !important;
        padding: 0.75rem 1rem !important;
        font-weight: 800 !important;
        font-size: 0.8rem !important;
        color: #64748b !important;
    }
</style>
@endsection

@section('content')
<div x-data="calendarPage()" x-init="init()" class="space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Kalender Sekolah</h1>
            <p class="text-slate-500 font-medium mt-1">Kelola agenda kegiatan dan hari libur sekolah</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button @click="openCreateModal()" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#ba80e8] to-[#d90d8b] text-white rounded-2xl text-sm font-bold shadow-lg shadow-pink-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                <i class="material-icons text-[20px]">add_circle</i> Tambah Acara
            </button>
        </div>
    </div>

    <!-- Calendar Container -->
    <div class="p-2 bg-slate-50 rounded-[3rem]">
        <div id="calendar" class="min-h-[700px]"></div>
    </div>

    <!-- Legend -->
    <div class="flex flex-wrap items-center gap-6 px-10">
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full" style="background: #ba80e8;"></div>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Event Sekolah</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full" style="background: #f43f5e;"></div>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Hari Libur</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full" style="background: #0ea5e9;"></div>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ujian/AKG</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full" style="background: #64748b;"></div>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Lainnya</span>
        </div>
    </div>

    <!-- Modals -->
    @include('admin.calendar.modals')

</div>
@endsection

@section('scripts')
<script>
    function calendarPage() {
        return {
            openModal: false,
            editMode: false,
            calendar: null,
            formData: {
                id: '',
                title: '',
                category: 'event',
                start_date: '',
                end_date: '',
                description: '',
            },

            init() {
                this.initCalendar();
            },

            initCalendar() {
                const calendarEl = document.getElementById('calendar');
                this.calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    locale: 'id',
                    events: '{{ route('calendar.events') }}',
                    selectable: true,
                    editable: true,
                    dayMaxEvents: true,
                    
                    select: (info) => {
                        this.openCreateModal(info.startStr, info.endStr);
                    },

                    eventClick: (info) => {
                        this.editEvent(info.event.id);
                    },

                    // Handle Drag & Drop / Resize
                    eventDrop: (info) => {
                        this.updateEventDates(info.event);
                    },
                    eventResize: (info) => {
                        this.updateEventDates(info.event);
                    }
                });
                this.calendar.render();
            },

            openCreateModal(start = '', end = '') {
                this.editMode = false;
                this.formData = {
                    id: '',
                    title: '',
                    category: 'event',
                    start_date: start ? start.slice(0, 16) : new Date().toISOString().slice(0, 16),
                    end_date: end ? end.slice(0, 16) : new Date().toISOString().slice(0, 16),
                    description: '',
                };
                this.openModal = true;
            },

            editEvent(id) {
                $.get(`{{ url('calendar') }}/${id}`, (data) => {
                    this.formData = {
                        id: data.id,
                        title: data.title,
                        category: data.category,
                        start_date: data.start_date.slice(0, 16).replace(' ', 'T'),
                        end_date: data.end_date.slice(0, 16).replace(' ', 'T'),
                        description: data.description || '',
                    };
                    this.editMode = true;
                    this.openModal = true;
                });
            },

            saveEvent() {
                if (!this.formData.title || !this.formData.start_date || !this.formData.end_date) {
                    Swal.fire('Oops...', 'Judul dan Tanggal wajib diisi!', 'warning');
                    return;
                }

                Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                const url = this.editMode ? `{{ url('calendar') }}/${this.formData.id}` : `{{ route('calendar.store') }}`;
                const method = this.editMode ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        ...this.formData,
                        _token: '{{ csrf_token() }}',
                        _method: method
                    },
                    success: (res) => {
                        this.openModal = false;
                        this.calendar.refetchEvents();
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.success, timer: 1500, showConfirmButton: false });
                    },
                    error: (err) => {
                        let msg = err.responseJSON?.message || 'Terjadi kesalahan.';
                        if (err.responseJSON?.errors) msg = Object.values(err.responseJSON.errors).join('<br>');
                        Swal.fire('Oops...', msg, 'error');
                    }
                });
            },

            updateEventDates(event) {
                $.ajax({
                    url: `{{ url('calendar') }}/${event.id}`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'PUT',
                        title: event.title,
                        category: event.extendedProps.category,
                        start_date: event.start.toISOString().slice(0, 19).replace('T', ' '),
                        end_date: (event.end ? event.end : event.start).toISOString().slice(0, 19).replace('T', ' '),
                        description: event.extendedProps.description || ''
                    },
                    success: () => {
                        console.log('Event updated via drag/drop');
                    },
                    error: (err) => {
                        this.calendar.refetchEvents();
                        Swal.fire('Error', 'Gagal memperbarui tanggal acara.', 'error');
                    }
                });
            },

            deleteEvent() {
                Swal.fire({
                    title: 'Hapus Acara?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f43f5e',
                    confirmButtonText: 'Ya, Hapus'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('calendar') }}/${this.formData.id}`,
                            method: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: (res) => {
                                this.openModal = false;
                                this.calendar.refetchEvents();
                                Swal.fire('Dihapus!', res.success, 'success');
                            }
                        });
                    }
                });
            }
        }
    }
</script>
@endsection
