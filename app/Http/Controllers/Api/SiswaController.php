<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswa = Siswa::all();
        return response()->json($siswa);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'nis' => 'required|unique:siswas,nis',
            'gender' => 'required',
            'alamat' => 'required',
            'kontak' => 'required|unique:siswas,kontak',
            'email' => 'required|email|unique:siswas,email',
            'foto' => 'required',
            'status_pkl' => 'nullable',
        ]);
    
        // Jika validasi gagal, hentikan eksekusi dan berikan pesan error
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal! Silakan cek kembali input Anda.',
                'errors' => $validator->errors()
            ], 422);
        }

        //upload gambar
        $foto = $request->file('foto');
        $foto->storeAs('public/siswa', $foto->hashName());
    
        // Jika validasi berhasil, simpan data siswa
        $siswa = Siswa::create($request->all());
    
        return response()->json([
            'success' => true,
            'message' => 'Data Siswa Berhasil Disimpan!',
            'siswa' => $siswa
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //menacri siswa berdasar id
        $siswa = siswa::find($id);

        //jika siswa tidak ditemukan
        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa Tidak Ditemukan!',
            ], 404);
        }
        //return data siswa
        return response()->json([
            'success' => true,
            'message' => 'Data siswa Berhasil Ditemukan!',
            'siswa' =>$siswa
        ], 200);
            

    }

    /**
     * Update the specified resource in storage.
     */


public function update(Request $request, string $id)
{
    // Temukan siswa berdasarkan ID
    $siswa = Siswa::find($id);

    if (!$siswa) {
        return response()->json([
            'success' => false,
            'message' => 'Siswa tidak ditemukan!',
        ], 404);
    }

    // Validasi
    $validator = Validator::make($request->all(), [
        'nama' => 'required',
        'nis' => 'required',
        'gender' => 'required',
        'alamat' => 'required',
        'kontak' => 'required',
        'email' => 'required|email',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'status_pkl' => 'nullable',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal! Silakan cek kembali input Anda.',
            'errors' => $validator->errors()
        ], 422);
    }

    // Tangani Foto
    if ($request->hasFile('foto')) {
        $foto = $request->file('foto');
        $fotoPath = $foto->storeAs('public/siswa', $foto->hashName());

        // Hapus foto lama jika ada
        if ($siswa->foto) {
            Storage::delete('public/siswa/' . $siswa->foto);
        }

        $siswa->foto = $foto->hashName();
    }

    // Perbarui data lain
    $siswa->fill($request->except(['foto']));
    $siswa->save();

    return response()->json([
        'success' => true,
        'message' => 'Data Siswa Berhasil Diupdate!',
        'siswa' => $siswa
    ], 200);
}




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //berdasar id
        $siswa = Siswa::find($id);

        //delete foto
        Storage::delete('public/siswa/' . $siswa->foto);

        //hapus data
        $siswa->delete();

        //retrun respone
        return response()->json([
            'success' => true,
            'message' => 'Data Siswa Berhasil Dihapus!',
        ], 200);
    }
}