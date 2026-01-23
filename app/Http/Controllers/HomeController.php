<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\School;
use App\Models\Siswa;
use App\Models\Fungsionaris;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $news = Berita::latest()->take(3)->get();
        
        $stats = [
            'schools' => School::count(),
            'students' => Siswa::count(),
            'teachers' => Fungsionaris::where('jabatan', 'like', '%Guru%')->count(),
            'staff' => Fungsionaris::where('jabatan', 'not like', '%Guru%')->count(),
        ];

        return view('welcome', compact('news', 'stats'));
    }

    public function privacy()
    {
        return view('legal.privacy-policy');
    }

    public function terms()
    {
        return view('legal.terms');
    }

    public function newsShow($id)
    {
        $berita = Berita::findOrFail($id);
        return view('news.detail', compact('berita'));
    }

    public function about()
    {
        return view('about');
    }
}
