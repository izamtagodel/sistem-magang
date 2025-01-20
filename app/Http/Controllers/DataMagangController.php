<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DataMagangController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'user') {
            $dataMhs = Mahasiswa::where('user_id', Auth::user()->id)->first();
            $dataDosen = User::where('role', 'dosen')->get();
            $dataJadwal = Jadwal::where('mahasiswa_id', Auth::user()->id)->first();
            $dataNilai = Nilai::where('mahasiswa_id', Auth::user()->id)->first();
            return view('formulir-daftar-magang', [
                'dataMhs' => $dataMhs,
                'dataDosen' => $dataDosen,
                'dataJadwal' => $dataJadwal,
                'dataNilai' => $dataNilai,
            ]);
        }

        if (Auth::user()->role == 'dosen' || Auth::user()->role == 'dosen_penguji' || Auth::user()->role == 'admin') {
            $dataMhs = Mahasiswa::all();
            return view('data-magang', [
                'dataMhs' => $dataMhs,
            ]);
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nim' => 'required|digits:10|unique:mahasiswas',
            'prodi' => 'required',
            'tgl_lahir' => 'required',
            'tempat_lahir' => 'required',
            'agama' => 'required',
            'gender' => 'required',
            'alamat' => 'required',
            'dospem' => 'required',
        ]);

        $data['user_id'] = Auth::user()->id;

        DB::transaction(function () use ($data) {
            $mhs = Mahasiswa::create($data);
            Jadwal::create([
                'mahasiswa_id' => $mhs->id,
            ]);
            Nilai::create([
                'mahasiswa_id' => $mhs->id,
            ]);
        });

        return redirect()->route('data-magang')->with('success', 'Anda berhasil daftar magang');
    }

    public function show(string $id)
    {
        $dataMhs = Mahasiswa::find($id);
        $dataJadwal = Jadwal::where('mahasiswa_id', $id)->first();
        $dataNilai = Nilai::where('mahasiswa_id', $id)->first();
        return view('detail-data-mahasiswa', [
            'dataMhs' => $dataMhs,
            'dataJadwal' => $dataJadwal,
            'dataNilai' => $dataNilai,
        ]);
    }

    public function update_tempat_magang(string $id, Request $request)
    {
        $mhs = Mahasiswa::find($id);
        $mhs->update([
            'tempat_magang' => $request->tempat_magang,
            'lokasi_magang' => $request->lokasi_magang,
            'awal_magang' => $request->awal_magang,
            'akhir_magang' => $request->akhir_magang,
        ]);

        return redirect()->back();
    }

    public function edit(string $id, Request $request)
    {
        $dataMhs = Mahasiswa::find($id);
        $dataDosen = User::where('role', 'dosen')->get();

        return view('edit-data-diri', [
            'dataMhs' => $dataMhs,
            'dataDosen' => $dataDosen,
        ]);
    }

    public function update(string $id, Request $request)
    {
        $mhs = Mahasiswa::find($id);

        $mhs->update([
            'nim' => $request->nim,
            'prodi' => $request->prodi,
            'tgl_lahir' => $request->tgl_lahir,
            'tempat_lahir' => $request->tempat_lahir,
            'agama' => $request->agama,
            'gender' => $request->gender,
            'alamat' => $request->alamat,
            'dospem' => $request->dospem,
        ]);
        return redirect('data-magang');
    }
}
