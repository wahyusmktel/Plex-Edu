<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory, HasUuids, \App\Traits\BelongsToSchool;

    protected $table = 'siswas';

    protected $fillable = [
        'school_id',
        'user_id',
        'kelas_id',
        'nis',
        'nisn',
        'nama_lengkap',
        'nipd',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'nik',
        'agama',
        'alamat',
        'rt',
        'rw',
        'dusun',
        'kelurahan',
        'kecamatan',
        'kode_pos',
        'jenis_tinggal',
        'alat_transportasi',
        'telepon',
        'no_hp',
        'email',
        'skhun',
        'penerima_kps',
        'no_kps',
        'nama_ayah',
        'ayah_tahun_lahir',
        'ayah_pendidikan',
        'ayah_pekerjaan',
        'ayah_penghasilan',
        'ayah_nik',
        'nama_ibu',
        'ibu_tahun_lahir',
        'ibu_pendidikan',
        'ibu_pekerjaan',
        'ibu_penghasilan',
        'ibu_nik',
        'nama_wali',
        'wali_tahun_lahir',
        'wali_pendidikan',
        'wali_pekerjaan',
        'wali_penghasilan',
        'wali_nik',
        'no_peserta_ujian',
        'no_seri_ijazah',
        'penerima_kip',
        'no_kip',
        'nama_di_kip',
        'no_kks',
        'no_akta_lahir',
        'bank',
        'no_rekening_bank',
        'rekening_atas_nama',
        'layak_pip',
        'alasan_layak_pip',
        'kebutuhan_khusus',
        'sekolah_asal',
        'anak_ke',
        'lintang',
        'bujur',
        'no_kk',
        'berat_badan',
        'tinggi_badan',
        'lingkar_kepala',
        'jml_saudara_kandung',
        'jarak_rumah_km',
        'no_hp_ortu',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
