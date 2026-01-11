@extends('layouts.app')

@section('title', 'Data Fungsionaris - Literasia')

@section('styles')
<style>
    .fungsionaris-container {
        padding: 20px 0;
    }
    .card-full {
        background: #fff;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        border: 1px solid #f0f0f0;
    }
    .tabs .tab a {
        color: #757575;
        font-weight: 600;
        text-transform: none;
    }
    .tabs .tab a:hover, .tabs .tab a.active {
        color: #d90d8b;
    }
    .tabs .indicator {
        background-color: #d90d8b;
    }
    .btn-gradient {
        background: linear-gradient(135deg, #ba80e8 0%, #d90d8b 100%);
        border-radius: 25px;
        text-transform: none;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(217, 13, 139, 0.2);
    }
    .btn-gradient:hover {
        opacity: 0.9;
    }
    .action-btns i {
        font-size: 20px;
        cursor: pointer;
        margin: 0 5px;
    }
    .btn-edit { color: #2196f3; }
    .btn-delete { color: #f44336; }
    
    /* Modal Tabs */
    .modal-tabs .tab a { font-size: 13px; }
    .modal { border-radius: 15px; max-height: 90%; width: 60%; }
    
    .status-badge {
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .status-aktif { background: #e8f5e9; color: #4caf50; }
    .status-nonaktif { background: #ffebee; color: #f44336; }
</style>
@endsection

@section('content')
<div class="fungsionaris-container">
    <div class="row">
        <div class="col s12">
            <div class="card-full">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                    <div>
                        <h5 style="font-weight: 700; margin: 0;">Data Fungsionaris</h5>
                        <p class="grey-text" style="margin: 5px 0 0 0;">Kelola data Guru dan Pegawai Sekolah</p>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <a href="{{ route('fungsionaris.download-template') }}" class="btn white black-text waves-effect" style="border-radius: 25px; text-transform: none; font-weight: 600; border: 1px solid #e0e0e0;">
                            <i class="material-icons left">file_download</i>Template
                        </a>
                        <button class="btn white black-text waves-effect modal-trigger" data-target="modal-import" style="border-radius: 25px; text-transform: none; font-weight: 600; border: 1px solid #e0e0e0;">
                            <i class="material-icons left">file_upload</i>Import
                        </button>
                        <button class="btn btn-gradient modal-trigger" data-target="modal-fungsionaris" onclick="resetForm()">
                            <i class="material-icons left">add</i>Tambah Baru
                        </button>
                    </div>
                </div>

                <ul id="tabs-fungsionaris" class="tabs">
                    <li class="tab col s3"><a class="active" href="#tab-guru">Guru</a></li>
                    <li class="tab col s3"><a href="#tab-pegawai">Pegawai</a></li>
                </ul>

                <div id="tab-guru" class="col s12" style="padding-top: 20px;">
                    <table class="highlight responsive-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Posisi</th>
                                <th>Username</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($guru as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $item->nama }}</strong></td>
                                <td>{{ $item->nip }}</td>
                                <td>{{ $item->posisi }}</td>
                                <td>{{ $item->user->username }}</td>
                                <td>
                                    <span class="status-badge {{ $item->status === 'aktif' ? 'status-aktif' : 'status-nonaktif' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="action-btns">
                                    <i class="material-icons btn-edit" onclick="editData('{{ $item->id }}')">edit</i>
                                    <i class="material-icons btn-delete" onclick="deleteData('{{ $item->id }}')">delete</i>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="center-align grey-text">Data Guru Kosong</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div id="tab-pegawai" class="col s12" style="padding-top: 20px;">
                    <table class="highlight responsive-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Posisi</th>
                                <th>Username</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pegawai as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $item->nama }}</strong></td>
                                <td>{{ $item->nip }}</td>
                                <td>{{ $item->posisi }}</td>
                                <td>{{ $item->user->username }}</td>
                                <td>
                                    <span class="status-badge {{ $item->status === 'aktif' ? 'status-aktif' : 'status-nonaktif' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="action-btns">
                                    <i class="material-icons btn-edit" onclick="editData('{{ $item->id }}')">edit</i>
                                    <i class="material-icons btn-delete" onclick="deleteData('{{ $item->id }}')">delete</i>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="center-align grey-text">Data Pegawai Kosong</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Structure -->
<div id="modal-fungsionaris" class="modal">
    <div class="modal-content">
        <h5 id="modal-title" style="font-weight: 700;">Tambah Fungsionaris</h5>
        <div class="row">
            <div class="col s12">
                <ul class="tabs modal-tabs" id="form-tabs">
                    <li class="tab col s6"><a class="active" href="#data-wajib">Data Wajib</a></li>
                    <li class="tab col s6"><a href="#data-lainnya">Data Lainnya</a></li>
                </ul>
            </div>
            <form id="form-fungsionaris">
                @csrf
                <input type="hidden" name="id" id="fungsionaris-id">
                
                <div id="data-wajib" class="col s12" style="padding-top: 20px;">
                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input id="nama" name="nama" type="text" required>
                            <label for="nama">Nama Lengkap</label>
                        </div>
                        <div class="input-field col s12 m6">
                            <input id="nip" name="nip" type="text" required>
                            <label for="nip">NIP</label>
                        </div>
                        <div class="input-field col s12 m6">
                            <input id="nik" name="nik" type="text" required>
                            <label for="nik">NIK</label>
                        </div>
                        <div class="input-field col s12 m6">
                            <input id="posisi" name="posisi" type="text" required placeholder="Contoh: Guru Matematika">
                            <label for="posisi">Posisi / Jabatan Struktural</label>
                        </div>
                        <div class="input-field col s12 m6">
                            <select id="jabatan" name="jabatan" class="browser-default" style="border: 1px solid #e0e0e0; height: 45px; border-radius: 8px;">
                                <option value="" disabled selected>Pilih Jabatan</option>
                                <option value="guru">Guru</option>
                                <option value="pegawai">Pegawai</option>
                            </select>
                        </div>
                        <div class="input-field col s12 m6">
                            <select id="status" name="status" class="browser-default" style="border: 1px solid #e0e0e0; height: 45px; border-radius: 8px;">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Non-Aktif</option>
                            </select>
                        </div>
                        <div class="input-field col s12 m6">
                            <input id="username" name="username" type="text" required>
                            <label for="username">Username Login</label>
                        </div>
                        <div class="input-field col s12 m6">
                            <input id="password" name="password" type="password">
                            <label for="password">Password</label>
                            <span class="helper-text">*Kosongkan jika tidak ingin ganti password (saat edit)</span>
                        </div>
                    </div>
                </div>

                <div id="data-lainnya" class="col s12" style="padding-top: 20px;">
                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input id="no_hp" name="no_hp" type="text">
                            <label for="no_hp">No. Handphone</label>
                        </div>
                        <div class="input-field col s12 m6">
                            <select id="jenis_kelamin" name="jenis_kelamin" class="browser-default" style="border: 1px solid #e0e0e0; height: 45px; border-radius: 8px;">
                                <option value="" disabled selected>Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="input-field col s12 m6">
                            <input id="tempat_lahir" name="tempat_lahir" type="text">
                            <label for="tempat_lahir">Tempat Lahir</label>
                        </div>
                        <div class="input-field col s12 m6">
                            <input id="tanggal_lahir" name="tanggal_lahir" type="date">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                        </div>
                        <div class="input-field col s12">
                            <textarea id="alamat" name="alamat" class="materialize-textarea"></textarea>
                            <label for="alamat">Alamat Lengkap</label>
                        </div>
                        <div class="input-field col s12">
                            <input id="pendidikan_terakhir" name="pendidikan_terakhir" type="text">
                            <label for="pendidikan_terakhir">Pendidikan Terakhir</label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal-footer">
        <button class="modal-close btn-flat grey-text">Batal</button>
        <button class="btn btn-gradient" id="btn-save" onclick="saveData()">Simpan Data</button>
    </div>
</div>

<!-- Modal Import -->
<div id="modal-import" class="modal">
    <div class="modal-content">
        <h5 style="font-weight: 700;">Import Data Fungsionaris</h5>
        <p class="grey-text">Silakan upload file Excel sesuai template yang tersedia.</p>
        <form id="form-import" enctype="multipart/form-data">
            @csrf
            <div class="file-field input-field">
                <div class="btn pink">
                    <span>Pilih File</span>
                    <input type="file" name="file" required>
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text" placeholder="Upload file .xlsx atau .xls">
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button class="modal-close btn-flat grey-text">Batal</button>
        <button class="btn btn-gradient" onclick="importData()">Proses Import</button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        $('.tabs').tabs();
        $('.modal').modal();
    });

    function resetForm() {
        $('#form-fungsionaris')[0].reset();
        $('#fungsionaris-id').val('');
        $('#modal-title').text('Tambah Fungsionaris');
        $('#password').attr('required', true);
        M.updateTextFields();
        var tabs = M.Tabs.getInstance($('#form-tabs'));
        tabs.select('data-wajib');
    }

    function saveData() {
        const id = $('#fungsionaris-id').val();
        const url = id ? `{{ url('fungsionaris/update') }}/${id}` : `{{ route('fungsionaris.store') }}`;
        
        $.ajax({
            url: url,
            method: 'POST',
            data: $('#form-fungsionaris').serialize(),
            success: function(res) {
                $('.modal').modal('close');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: res.success,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    location.reload();
                });
            },
            error: function(err) {
                const errors = err.responseJSON.errors;
                let errorMsg = '';
                for (let key in errors) {
                    errorMsg += errors[key][0] + '<br>';
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: errorMsg
                });
            }
        });
    }

    function editData(id) {
        $.get(`{{ url('fungsionaris/show') }}/${id}`, function(data) {
            $('#fungsionaris-id').val(data.id);
            $('#nama').val(data.nama);
            $('#nip').val(data.nip);
            $('#nik').val(data.nik);
            $('#posisi').val(data.posisi);
            $('#jabatan').val(data.jabatan);
            $('#status').val(data.status);
            $('#username').val(data.user.username);
            $('#password').val('').removeAttr('required');
            
            // Optional Data
            $('#no_hp').val(data.no_hp);
            $('#jenis_kelamin').val(data.jenis_kelamin);
            $('#tempat_lahir').val(data.tempat_lahir);
            $('#tanggal_lahir').val(data.tanggal_lahir);
            $('#alamat').val(data.alamat);
            $('#pendidikan_terakhir').val(data.pendidikan_terakhir);

            $('#modal-title').text('Edit Fungsionaris');
            M.updateTextFields();
            $('#modal-fungsionaris').modal('open');
            
            var tabs = M.Tabs.getInstance($('#form-tabs'));
            tabs.select('data-wajib');
        });
    }

    function deleteData(id) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d90d8b',
            cancelButtonColor: '#757575',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('fungsionaris/destroy') }}/${id}`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Dihapus!',
                            text: res.success,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            }
        })
    }

    function importData() {
        let formData = new FormData($('#form-import')[0]);
        Swal.fire({
            title: 'Sedang memproses...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: `{{ route('fungsionaris.import') }}`,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: res.success,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    location.reload();
                });
            },
            error: function(err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gagal mengimport data, pastikan format sesuai.'
                });
            }
        });
    }
</script>
@endsection
