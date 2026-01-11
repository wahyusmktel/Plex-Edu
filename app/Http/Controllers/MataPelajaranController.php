<?php

namespace App\Http\Controllers;

use App\Models\JamPelajaran;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\Kelas;
use App\Models\Fungsionaris;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $jams = JamPelajaran::orderBy('hari')->orderBy('jam_mulai')->get()->groupBy('hari');
        
        $query = Subject::with('guru');
        if ($request->has('search')) {
            $query->where('nama_pelajaran', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_pelajaran', 'like', '%' . $request->search . '%');
        }
        $subjects = $query->paginate(10)->withQueryString();

        $activeSetting = SchoolSetting::where('is_active', true)->first();
        $kelas = Kelas::all();
        $gurus = Fungsionaris::where('jabatan', 'guru')->get();

        return view('admin.mata-pelajaran.index', compact('jams', 'subjects', 'activeSetting', 'kelas', 'gurus'));
    }

    // Jam Pelajaran CRUD
    public function storeJam(Request $request)
    {
        $request->validate([
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);
        JamPelajaran::create($request->all());
        return response()->json(['success' => 'Jam pelajaran berhasil ditambahkan']);
    }

    public function destroyJam($id)
    {
        JamPelajaran::destroy($id);
        return response()->json(['success' => 'Jam pelajaran berhasil dihapus']);
    }

    // Mata Pelajaran CRUD
    public function storeSubject(Request $request)
    {
        $request->validate([
            'kode_pelajaran' => 'required|unique:subjects',
            'nama_pelajaran' => 'required',
        ]);
        Subject::create($request->all());
        return response()->json(['success' => 'Mata pelajaran berhasil ditambahkan']);
    }

    public function showSubject($id)
    {
        return response()->json(Subject::findOrFail($id));
    }

    public function updateSubject(Request $request, $id)
    {
        $request->validate([
            'kode_pelajaran' => 'required|unique:subjects,kode_pelajaran,' . $id,
            'nama_pelajaran' => 'required',
        ]);
        $subject = Subject::findOrFail($id);
        $subject->update($request->all());
        return response()->json(['success' => 'Mata pelajaran berhasil diperbarui']);
    }

    public function destroySubject($id)
    {
        Subject::destroy($id);
        return response()->json(['success' => 'Mata pelajaran berhasil dihapus']);
    }

    // Schedule CRUD
    public function storeSchedule(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required',
            'subject_id' => 'required',
            'jam_id' => 'required',
            'hari' => 'required',
        ]);

        $activeSetting = SchoolSetting::where('is_active', true)->first();
        if (!$activeSetting) {
            return response()->json(['error' => 'Tidak ada pengaturan sekolah yang aktif'], 422);
        }

        $schedule = Schedule::updateOrCreate(
            [
                'kelas_id' => $request->kelas_id,
                'jam_id' => $request->jam_id,
                'hari' => $request->hari,
                'school_setting_id' => $activeSetting->id
            ],
            [
                'subject_id' => $request->subject_id
            ]
        );

        return response()->json(['success' => 'Jadwal berhasil diperbarui', 'data' => $schedule]);
    }

    public function destroySchedule($id)
    {
        Schedule::destroy($id);
        return response()->json(['success' => 'Jadwal berhasil dihapus']);
    }

    public function getSchedulesByKelas($kelas_id)
    {
        $activeSetting = SchoolSetting::where('is_active', true)->first();
        if (!$activeSetting) return response()->json([]);

        $schedules = Schedule::with(['subject', 'jam'])
            ->where('kelas_id', $kelas_id)
            ->where('school_setting_id', $activeSetting->id)
            ->get()
            ->groupBy('hari');

        return response()->json($schedules);
    }
}
