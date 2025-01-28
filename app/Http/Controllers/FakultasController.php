<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class FakultasController extends Controller
{
    public function index()
    {
        return view('pageadmin.fakultas.index'); // Ganti dengan nama view yang sesuai
    }

    public function getData(Request $request)
    {
        $fakultas = Fakultas::all();
        return DataTables::of($fakultas)
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-warning btn-sm edit" data-id="' . $row->id . '"><i class="fas fa-edit"></i>Edit</button>
                        <button class="btn btn-danger btn-sm delete" data-id="' . $row->id . '"><i class="fas fa-trash"></i>Delete</button>';
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nm_fakultas' => 'required',
            'kode_fakultas' => 'required|unique:fakultas,kode_fakultas',
            'akreditasi' => 'required',
        ], [
            'nm_fakultas.required' => 'Nama fakultas harus diisi.',
            'kode_fakultas.required' => 'Kode fakultas harus diisi.',
            'kode_fakultas.unique' => 'Kode fakultas sudah terdaftar.',
            'akreditasi.required' => 'Akreditasi harus diisi.',
        ]);

        Fakultas::create($request->all());
        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {
        $fakultas = Fakultas::find($id);

        if (!$fakultas) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($fakultas);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nm_fakultas' => 'required',
            'kode_fakultas' => 'required|unique:fakultas,kode_fakultas,' . $id,
            'akreditasi' => 'required',
        ], [
            'nm_fakultas.required' => 'Nama fakultas harus diisi.',
            'kode_fakultas.required' => 'Kode fakultas harus diisi.',
            'kode_fakultas.unique' => 'Kode fakultas sudah terdaftar.',
            'akreditasi.required' => 'Akreditasi harus diisi.',
        ]);

        $fakultas = Fakultas::find($id);
        if (!$fakultas) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $fakultas->update($request->all());
        return response()->json(['success' => 'Data updated successfully.']);
    }

    public function destroy($id)
    {
        $fakultas = Fakultas::find($id);
        if (!$fakultas) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        $fakultas->delete();
        return response()->json(['success' => 'Data berhasil dihapus']);
    }
}