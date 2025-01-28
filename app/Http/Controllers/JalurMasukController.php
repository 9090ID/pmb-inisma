<?php

namespace App\Http\Controllers;

use App\Models\JalurMasuk;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JalurMasukController extends Controller
{
    public function index()
    {
        return view('pageadmin.jalurmasuk.index'); // Ganti dengan nama view yang sesuai
    }

    public function getData(Request $request)
    {
        $jalurmasuk = JalurMasuk::all();
        return DataTables::of($jalurmasuk)
        ->addColumn('biaya_pendaftaran', function ($row) {
            return 'Rp. ' . number_format($row->biaya_pendaftaran, 0, ',', '.');
        })
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-warning btn-sm edit" data-id="' . $row->id . '"><i class="fas fa-edit"></i>Edit</button>
                        <button class="btn btn-danger btn-sm delete" data-id="' . $row->id . '"><i class="fas fa-trash"></i>Delete</button>';
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nm_jalur' => 'required',
            'tahun' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $mulaiPendaftaran = $request->input('mulai_pendaftaran');
                    $selesaiPendaftaran = $request->input('selesai_pendaftaran');
        
                    if ($mulaiPendaftaran && $value > date('Y', strtotime($mulaiPendaftaran))) {
                        $fail('Tahun tidak boleh lebih besar dari tahun di tanggal mulai pendaftaran.');
                    }
                    if ($selesaiPendaftaran && $value > date('Y', strtotime($selesaiPendaftaran))) {
                        $fail('Tahun tidak boleh lebih besar dari tahun di tanggal selesai pendaftaran.');
                    }
                }
            ],
            'biaya_pendaftaran' => 'required|numeric',
            'mulai_pendaftaran' => 'required|date|before_or_equal:selesai_pendaftaran',
            'selesai_pendaftaran' => 'required|date|after_or_equal:mulai_pendaftaran',
        ], [
            'nm_jalur.required' => 'Nama jalur harus diisi.',
            'biaya_pendaftaran.required' => 'Biaya pendaftaran harus diisi.',
            'biaya_pendaftaran.numeric' => 'Biaya pendaftaran harus berupa angka.',
            'mulai_pendaftaran.before_or_equal' => 'Tanggal mulai pendaftaran harus lebih rendah atau sama dengan tanggal selesai pendaftaran.',
            'selesai_pendaftaran.after_or_equal' => 'Tanggal selesai pendaftaran harus lebih besar atau sama dengan tanggal mulai pendaftaran.',
        ]);

        JalurMasuk::create($request->all());
        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {
        $jalurMasuk = JalurMasuk::find($id);

        if (!$jalurMasuk) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($jalurMasuk);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nm_jalur' => 'required',
            'tahun' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $mulaiPendaftaran = $request->input('mulai_pendaftaran');
                    $selesaiPendaftaran = $request->input('selesai_pendaftaran');
        
                    if ($mulaiPendaftaran && $value > date('Y', strtotime($mulaiPendaftaran))) {
                        $fail('Tahun tidak boleh lebih besar dari tahun di tanggal mulai pendaftaran.');
                    }
                    if ($selesaiPendaftaran && $value > date('Y', strtotime($selesaiPendaftaran))) {
                        $fail('Tahun tidak boleh lebih besar dari tahun di tanggal selesai pendaftaran.');
                    }
                }
            ],
            'biaya_pendaftaran' => 'required|numeric',
            'mulai_pendaftaran' => 'required|date|before_or_equal:selesai_pendaftaran',
            'selesai_pendaftaran' => 'required|date|after_or_equal:mulai_pendaftaran',
        ], [
            'nm_jalur.required' => 'Nama jalur harus diisi.',
            'biaya_pendaftaran.required' => 'Biaya pendaftaran harus diisi.',
            'biaya_pendaftaran.numeric' => 'Biaya pendaftaran harus berupa angka.',
            'mulai_pendaftaran.before_or_equal' => 'Tanggal mulai pendaftaran harus lebih rendah atau sama dengan tanggal selesai pendaftaran.',
            'selesai_pendaftaran.after_or_equal' => 'Tanggal selesai pendaftaran harus lebih besar atau sama dengan tanggal mulai pendaftaran.',
        ]);

        $jalurmasuk = JalurMasuk::find($id);
        $jalurmasuk->update($request->all());
        return response()->json(['success' => 'Data updated successfully.']);
    }

    public function destroy($id)
    {
        $jalurMasuk = JalurMasuk::find($id);
        if (!$jalurMasuk) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        $jalurMasuk->delete();
        return response()->json(['success' => 'Data berhasil dihapus']);
    }
    // public function show($id)
    // {
    //     $jalurmasuk = JalurMasuk::find($id);
    //     return response()->json($jalurmasuk);
    // }
}
