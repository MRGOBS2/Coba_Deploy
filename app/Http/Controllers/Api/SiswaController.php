<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::all();
        return response()->json($siswa);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'nis' => 'required|unique:siswas,nis',
            'gender' => 'required',
            'alamat' => 'required',
            'kontak' => 'required|unique:siswas,kontak',
            'email' => 'required|email|unique:siswas,email',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // ubah menjadi nullable
            'status_pkl' => 'nullable',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal! Silakan cek kembali input Anda.',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();

        // Upload gambar jika ada
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $foto->storeAs('public/siswa', $foto->hashName());
            $data['foto'] = $foto->hashName();
        }

        $siswa = Siswa::create($data);
    
        return response()->json([
            'success' => true,
            'message' => 'Data Siswa Berhasil Disimpan!',
            'siswa' => $siswa
        ], 201);
    }

    public function show(string $id)
    {
        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa Tidak Ditemukan!',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data siswa Berhasil Ditemukan!',
            'siswa' => $siswa
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $siswa = Siswa::find($id);
        
        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa Tidak Ditemukan!',
            ], 404);
        }

        // Validasi (opsional, jika ingin tambahkan validasi)
        $validator = Validator::make($request->all(), [
            'nis' => 'unique:siswas,nis,' . $id,
            'kontak' => 'unique:siswas,kontak,' . $id,
            'email' => 'email|unique:siswas,email,' . $id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // nullable
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal! Silakan cek kembali input Anda.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update data
        $siswa->nama = $request->nama ?? $siswa->nama;
        $siswa->nis = $request->nis ?? $siswa->nis;
        $siswa->gender = $request->gender ?? $siswa->gender;
        $siswa->alamat = $request->alamat ?? $siswa->alamat;
        $siswa->kontak = $request->kontak ?? $siswa->kontak;
        $siswa->email = $request->email ?? $siswa->email;
        $siswa->status_pkl = $request->status_pkl ?? $siswa->status_pkl;

        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($siswa->foto) {
                Storage::delete('public/siswa/' . $siswa->foto);
            }
            // Simpan foto baru
            $foto = $request->file('foto');
            $foto->storeAs('public/siswa', $foto->hashName());
            $siswa->foto = $foto->hashName();
        }

        $siswa->save();

        return response()->json([
            'success' => true,
            'message' => 'Data Siswa Berhasil Diupdate!',
            'siswa' => $siswa
        ]);
    }

    public function destroy(string $id)
    {
        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa Tidak Ditemukan!',
            ], 404);
        }

        if ($siswa->foto) {
            Storage::delete('public/siswa/' . $siswa->foto);
        }

        $siswa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Siswa Berhasil Dihapus!',
        ], 200);
    }
}
