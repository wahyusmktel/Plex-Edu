<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileApiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'username' => $user->username,
            'role' => $user->role,
            'avatar_url' => $user->avatar_url,
            'created_at' => $user->created_at->format('Y-m-d H:i:s'),
        ];

        if ($user->role === 'siswa') {
            $siswa = $user->siswa()->with(['kelas.jurusan'])->first();
            if ($siswa) {
                // Calculate Stats
                $subjectIds = \App\Models\Schedule::where('kelas_id', $siswa->kelas_id)
                    ->pluck('subject_id')
                    ->unique();

                $totalModules = \App\Models\ELearningModule::whereHas('chapter.course', function($q) use ($subjectIds) {
                    $q->whereIn('subject_id', $subjectIds);
                })->count();
                $completedModules = \App\Models\ELearningProgress::where('siswa_id', $siswa->id)->count();
                $activityPercent = $totalModules > 0 ? round(($completedModules / $totalModules) * 100) : 0;

                $booksBorrowed = \App\Models\LibraryLoan::where('student_id', $siswa->id)->count();
                
                $violationPoints = \App\Models\PelanggaranSiswa::where('siswa_id', $siswa->id)
                    ->join('master_pelanggarans', 'pelanggaran_siswas.master_pelanggaran_id', '=', 'master_pelanggarans.id')
                    ->sum('master_pelanggarans.poin');

                $data['detail'] = [
                    'nis' => $siswa->nis ?? '-',
                    'nisn' => $siswa->nisn ?? '-',
                    'jenis_kelamin' => $siswa->jenis_kelamin ?? '-',
                    'tempat_lahir' => $siswa->tempat_lahir ?? '-',
                    'tanggal_lahir' => $siswa->tanggal_lahir ?? '-',
                    'alamat' => $siswa->alamat ?? '-',
                    'no_hp' => $siswa->no_hp ?? '-',
                    'kelas' => $siswa->kelas->nama ?? '-',
                    'jurusan' => $siswa->kelas->jurusan->nama ?? '-',
                ];
                
                $data['stats'] = [
                    'activity' => $activityPercent . '%',
                    'books' => (string)$booksBorrowed,
                    'points' => (string)$violationPoints,
                ];
            } else {
                $data['detail'] = [
                    'nis' => '-', 'nisn' => '-', 'jenis_kelamin' => '-',
                    'tempat_lahir' => '-', 'tanggal_lahir' => '-',
                    'alamat' => '-', 'no_hp' => '-', 'kelas' => '-', 'jurusan' => '-',
                ];
                $data['stats'] = [
                    'activity' => '0%',
                    'books' => '0',
                    'points' => '0',
                ];
            }
        } else {
            $fungsionaris = $user->fungsionaris;
            if ($fungsionaris) {
                $data['detail'] = [
                    'nip' => $fungsionaris->nip ?? '-',
                    'jabatan' => $fungsionaris->jabatan ?? '-',
                    'no_hp' => $fungsionaris->no_hp ?? '-',
                    'alamat' => $fungsionaris->alamat ?? '-',
                ];
            } else {
                $data['detail'] = [
                    'nip' => '-',
                    'jabatan' => '-',
                    'no_hp' => '-',
                    'alamat' => '-',
                ];
            }
            $data['stats'] = [
                'activity' => '-',
                'books' => '-',
                'points' => '-',
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ];

        // Role specific update rules
        if ($user->role === 'siswa') {
            $rules['alamat'] = 'nullable|string';
            $rules['no_hp'] = 'nullable|string';
        } else {
            $rules['no_hp'] = 'nullable|string';
            $rules['alamat'] = 'nullable|string';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Update secondary model
        if ($user->role === 'siswa' && $user->siswa) {
            $user->siswa()->update([
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
            ]);
        } else if ($user->fungsionaris) {
            $user->fungsionaris()->update([
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data' => $this->index()->original['data']
        ]);
    }

    public function updateAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diperbarui.',
                'avatar_url' => $user->avatar_url
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengunggah foto profil.'
        ], 400);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Kata sandi saat ini tidak cocok.'
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi berhasil diperbarui.'
        ]);
    }
}
