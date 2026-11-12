<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Iklan;
use Illuminate\Support\Facades\File;

class IklanController extends Controller
{
    public function index()
    {
        $iklanList = Iklan::latest()->get();
        return view('admin.iklan.index', compact('iklanList'));
    }

    public function store(Request $request)
{
    $request->validate([
        'judul' => 'required|string|max:150',
        'video' => 'required|mimes:mp4,webm,ogg,mkv,avi,mov|max:153600', // Maksimal 150MB (153600 KB)
    ]);

    if ($request->hasFile('video')) {
        $file = $request->file('video');
        $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        
        // Pastikan folder penyimpanan ada
        $destinationPath = public_path('uploads/iklan');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true, true);
        }

        $file->move($destinationPath, $filename);

        Iklan::create([
            'judul' => $request->judul,
            'file_media' => 'uploads/iklan/' . $filename,
        ]);
    }

    return redirect()->route('admin.iklan.index')->with('success', 'Video iklan berhasil ditambahkan.');
}

    public function destroy($id)
    {
        $iklan = Iklan::findOrFail($id);
        
        // Sesuaikan juga penghapusan file menggunakan 'file_media'
        if ($iklan->file_media && file_exists(public_path($iklan->file_media))) {
            @unlink(public_path($iklan->file_media));
        }
        
        $iklan->delete();

        return redirect()->route('admin.iklan.index')->with('success', 'Video iklan berhasil dihapus.');
    }
}