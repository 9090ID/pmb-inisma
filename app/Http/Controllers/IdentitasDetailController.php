<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\IdentitasDetail;
use App\Models\Pendaftaran;


class IdentitasDetailController extends Controller
{
    public function index()
    {
        // Ambil pengguna yang sedang login
    $user = Auth::user();

    // Ambil pendaftaran berdasarkan user_id
    $pendaftaran = Pendaftaran::where('user_id', $user->id)->with('identitasDetail')->first();
    
    // Jika pendaftaran ditemukan, tampilkan data
    if ($pendaftaran) {
        return view('pagemhs.identitas', compact('pendaftaran'));
    } else {
        return redirect()->back()->with('error', 'Data pendaftaran tidak ditemukan.');
    }
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'NIK' => 'required|string|unique:identitas_details,NIK',
            'NISN' => 'nullable|string|unique:identitas_details,NISN',
            'asalsekolah' => 'required|string',
            'tempatlahir' => 'required|string',
            'uploadktp' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'uploadkk' => 'required|file|mimes:pdf|max:2048',
            'uploadijazah' => 'required|file|mimes:pdf|max:2048',
            'uploadtranskripnilaisma' => 'required|file|mimes:pdf|max:2048',
        ]);

        $pendaftaran = $user->pendaftarans()->first(); // Mengambil data pendaftaran pertama user

        $identitas = IdentitasDetail::create([
            'pendaftaran_id' => $pendaftaran->id,
            'NIK' => $request->NIK,
            'NISN' => $request->NISN,
            'asalsekolah' => $request->asalsekolah,
            'tempatlahir' => $request->tempatlahir,
        ]);

        // Simpan gambar & dokumen ke Spatie Media Library
        $identitas->addMedia($request->file('uploadktp'))->toMediaCollection('ktp');
        $identitas->addMedia($request->file('foto'))->toMediaCollection('foto');
        $identitas->addMedia($request->file('uploadkk'))->toMediaCollection('kk');
        $identitas->addMedia($request->file('uploadijazah'))->toMediaCollection('ijazah');
        $identitas->addMedia($request->file('uploadtranskripnilaisma'))->toMediaCollection('transkrip');

        return response()->json(['message' => 'Identitas berhasil disimpan']);
    }

    public function update(Request $request, IdentitasDetail $identitas)
    {
        $user = Auth::user();

        if ($identitas->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'NIK' => 'required|string|unique:identitas_details,NIK,' . $identitas->id,
            'NISN' => 'nullable|string|unique:identitas_details,NISN,' . $identitas->id,
            'asalsekolah' => 'required|string',
            'tempatlahir' => 'required|string',
            'uploadktp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'uploadkk' => 'nullable|file|mimes:pdf|max:2048',
            'uploadijazah' => 'nullable|file|mimes:pdf|max:2048',
            'uploadtranskripnilaisma' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $identitas->update([
            'NIK' => $request->NIK,
            'NISN' => $request->NISN,
            'asalsekolah' => $request->asalsekolah,
            'tempatlahir' => $request->tempatlahir,
          
        ]);

        // Update file jika ada yang diunggah
        if ($request->hasFile('uploadktp')) {
            $identitas->clearMediaCollection('ktp');
            $identitas->addMedia($request->file('uploadktp'))->toMediaCollection('ktp');
        }
        if ($request->hasFile('foto')) {
            $identitas->clearMediaCollection('foto');
            $identitas->addMedia($request->file('foto'))->toMediaCollection('foto');
        }
        if ($request->hasFile('uploadkk')) {
            $identitas->clearMediaCollection('kk');
            $identitas->addMedia($request->file('uploadkk'))->toMediaCollection('kk');
        }
        if ($request->hasFile('uploadijazah')) {
            $identitas->clearMediaCollection('ijazah');
            $identitas->addMedia($request->file('uploadijazah'))->toMediaCollection('ijazah');
        }
        if ($request->hasFile('uploadtranskripnilaisma')) {
            $identitas->clearMediaCollection('transkrip');
            $identitas->addMedia($request->file('uploadtranskripnilaisma'))->toMediaCollection('transkrip');
        }

        return response()->json(['message' => 'Identitas berhasil diperbarui']);
    }
}
