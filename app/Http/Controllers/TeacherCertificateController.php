<?php

namespace App\Http\Controllers;

use App\Models\TeacherCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TeacherCertificateController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $fungsionaris = $user->fungsionaris;

        if (!$fungsionaris && $user->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Profil fungsionaris tidak ditemukan.');
        }

        $certificates = TeacherCertificate::when($user->role === 'guru', function ($query) use ($fungsionaris) {
            return $query->where('teacher_id', $fungsionaris->id);
        })
        ->latest()
        ->paginate(12);

        return view('certificates.index', compact('certificates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'expiry_type' => 'required|in:date,year,none',
            'expiry_date' => 'nullable|required_if:expiry_type,date|date',
            'expiry_year' => 'nullable|required_if:expiry_type,year|integer|min:1900|max:2100',
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $user = Auth::user();
        $fungsionaris = $user->fungsionaris;

        if (!$fungsionaris && $user->role !== 'admin') {
            return back()->with('error', 'Profil fungsionaris tidak ditemukan.');
        }

        $filePath = $request->file('file')->store('certificates', 'public');

        TeacherCertificate::create([
            'teacher_id' => $fungsionaris->id,
            'name' => $request->name,
            'description' => $request->description,
            'year' => $request->year,
            'expiry_type' => $request->expiry_type,
            'expiry_date' => $request->expiry_type === 'date' ? $request->expiry_date : null,
            'expiry_year' => $request->expiry_type === 'year' ? $request->expiry_year : null,
            'file_path' => $filePath,
        ]);

        return back()->with('success', 'Sertifikat berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $certificate = TeacherCertificate::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'expiry_type' => 'required|in:date,year,none',
            'expiry_date' => 'nullable|required_if:expiry_type,date|date',
            'expiry_year' => 'nullable|required_if:expiry_type,year|integer|min:1900|max:2100',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'year' => $request->year,
            'expiry_type' => $request->expiry_type,
            'expiry_date' => $request->expiry_type === 'date' ? $request->expiry_date : null,
            'expiry_year' => $request->expiry_type === 'year' ? $request->expiry_year : null,
        ];

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($certificate->file_path);
            $data['file_path'] = $request->file('file')->store('certificates', 'public');
        }

        $certificate->update($data);

        return back()->with('success', 'Sertifikat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $certificate = TeacherCertificate::findOrFail($id);
        Storage::disk('public')->delete($certificate->file_path);
        $certificate->delete();

        return back()->with('success', 'Sertifikat berhasil dihapus.');
    }
}
