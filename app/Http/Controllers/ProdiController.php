<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProdiController extends Controller
{
    public function index()
    {
        // Mengambil semua fakultas untuk dropdown di view
        $fakultas = Fakultas::all();
        return view('pageadmin.prodi.index', compact('fakultas')); // Ganti dengan nama view yang sesuai
    }

    public function getData(Request $request)
    {
        $programStudi = ProgramStudi::with('fakultas')->get(); // Mengambil data program studi dengan relasi fakultas
        return DataTables::of($programStudi)
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-warning btn-sm edit" data-id="' . $row->id . '"><i class="fas fa-edit"></i>Edit</button>
                        <button class="btn btn-danger btn-sm delete" data-id="' . $row->id . '"><i class="fas fa-trash"></i>Delete</button>';
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nm_prodi' => 'required',
            'kd_prodi' => 'required|unique:program_studi,kd_prodi',
            'akreditasi' => 'required',
            'jenjang' => 'required',
            'fakultas_id' => 'required|exists:fakultas,id', // Validasi fakultas_id
        ], [
            'nm_prodi.required' => 'Nama program studi harus diisi.',
            'kd_prodi.required' => 'Kode program studi harus diisi.',
            'kd_prodi.unique' => 'Kode program studi sudah terdaftar.',
            'akreditasi.required' => 'Akreditasi harus diisi.',
            'jenjang.required' => 'Jenjang harus diisi.',
            'fakultas_id.required' => 'Fakultas harus dipilih.',
            'fakultas_id.exists' => 'Fakultas yang dipilih tidak valid.',
        ]);

        ProgramStudi::create($request->all());
        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {
        $programStudi = ProgramStudi::find($id);

        if (!$programStudi) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($programStudi);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nm_prodi' => 'required',
            'kd_prodi' => 'required|unique:program_studi,kd_prodi,' . $id,
            'akreditasi' => 'required',
            'jenjang' => 'required',
            'fakultas_id' => 'required|exists:fakultas,id', // Validasi fakultas_id
        ], [
            'nm_prodi.required' => 'Nama program studi harus diisi.',
            'kd_prodi.required' => 'Kode program studi harus diisi.',
            'kd_prodi.unique' => 'Kode program studi sudah terdaftar.',
            'akreditasi.required' => 'Akreditasi harus diisi.',
            'jenjang.required' => 'Jenjang harus diisi.',
            'fakultas_id.required' => 'Fakultas harus dipilih.',
            'fakultas_id.exists' => 'Fakultas yang dipilih tidak valid.',
        ]);

        $programStudi = ProgramStudi::find($id);
        if (!$programStudi) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $programStudi->update($request->all());
        return response()->json(['success' => 'Data updated successfully.']);
    }

    public function destroy($id)
    {
        $programStudi = ProgramStudi::find($id);
        if (!$programStudi) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        $programStudi->delete();
        return response()->json(['success' => 'Data berhasil dihapus']);
    }
}