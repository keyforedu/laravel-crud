<?php

namespace App\Http\Controllers;

use App\Models\Pemeriksaan;
use App\Models\Pasien as PasienModel;
use App\Models\Dokter as DokterModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App;

class PemeriksaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        App::setLocale('idn');
        $datapemeriksaan = Pemeriksaan::join('pasien', 'pasien.id', '=', 'pemeriksaan.idPasien')
                            ->join('dokter', 'dokter.id', '=', 'pemeriksaan.idDokter')
                            ->select(['pemeriksaan.*', 'pasien.nama as namaPasien', 'dokter.nama as namaDokter', 'dokter.spesialisasi'])
                            ->get();
        
        return view('admin.pemeriksaan.index', compact('datapemeriksaan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $dataPasien = PasienModel::all();
        $dataDokter = DokterModel::all();
        return view('admin.pemeriksaan.create', compact('dataPasien', 'dataDokter'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // dd($request);
        $rules = [
            "pasien" => 'required',
            "dokter" => 'required',
            "tanggalPeriksa" => 'required|date',
            "keluhan" => 'required|string|min:3|max:255',
            "fileLampiran" => 'required|mimes:jpg,png,jpeg,gif|max:2048'
        ];
        $message=[
            'required' => ':attribute wajib diisi',
            'min' => ':attribute minimal berisi :min karakter',
            'max' => ':attribute maksimal berisi :max karakter',
            'fileLampiran.mimes' => 'file harus berupa gambar dengan format jpg, png, jpeg, gif'
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if($validator->fails()){
            return redirect()->back()
            ->withInput()
            ->withErrors($validator)
            ->with('danger', 'Pastikan semua field diisi');
        }else{
            // dd($request);
            $noPemeriksaan = 'REG-'.date('Ymd').'-'.Str::upper(Str::random(6));

            $fileLampiran = $request->file('fileLampiran');
            $namaFileLampiran = time().".".$fileLampiran->getClientOriginalExtension();
            //cara memindahkan file ke server
            $pathFileLampiran = Storage::disk('public')->putFileAs('fileLampiran', $fileLampiran, $namaFileLampiran);
            //Storage//App//Public//fileLampiran

            $simpanPemeriksaan = Pemeriksaan::create([
                'no_transaksi_pemeriksaan' => $noPemeriksaan,
                'idDokter' => $request->dokter,
                'idPasien' =>$request->pasien,
                'tanggalPeriksa' => $request->tanggalPeriksa,
                'fileLampiran' => $namaFileLampiran,
                'keluhan' => $request->keluhan
            ]);

            return redirect()->route('pemeriksaan.index')->with('success', 'Tambah Data Pemeriksaan berhasil');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pemeriksaan $pemeriksaan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pemeriksaan $pemeriksaan)
    {
        // dd($pemeriksaan);
        $datapasien = PasienModel::all();
        $datadokter = DokterModel::all();
        return view('admin.pemeriksaan.edit', compact('pemeriksaan', 'datapasien', 'datadokter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pemeriksaan $pemeriksaan)
    {
        //
        $rules = [
            "pasien" => 'required',
            "dokter" => 'required',
            "tanggalPeriksa" => 'required|date',
            "keluhan" => 'required|string|min:3|max:255',
            "fileLampiran" => 'required|mimes:jpg,png,jpeg,gif|max:2048'
        ];
        $message=[
            'required' => ':attribute wajib diisi',
            'min' => ':attribute minimal berisi :min karakter',
            'max' => ':attribute maksimal berisi :max karakter',
            'fileLampiran.mimes' => 'file harus berupa gambar dengan format jpg, png, jpeg, gif'
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if($validator->fails()){
            return redirect()->back()
            ->withInput()
            ->withErrors($validator)
            ->with('danger', 'Pastikan semua field diisi');
        }else{
            // dd($request);
            // $noPemeriksaan = 'REG-'.date('Ymd').'-'.Str::upper(Str::random(6));

            $fileLampiran = $request->file('fileLampiran');
            $namaFileLampiran = time().".".$fileLampiran->getClientOriginalExtension();
            //cara memindahkan file ke server
            $pathFileLampiran = Storage::disk('public')->putFileAs('fileLampiran', $fileLampiran, $namaFileLampiran);
            //Storage//App//Public//fileLampiran

            $pemeriksaan->idPasien = $request->pasien;
            $pemeriksaan->idDokter = $request->dokter;
            $pemeriksaan->tanggalPeriksa = $request->tanggalPeriksa;
            $pemeriksaan->keluhan = $request->keluhan;
            $pemeriksaan->fileLampiran = $namaFileLampiran;
            $pemeriksaan->save();

            // $simpanPemeriksaan = Pemeriksaan::create([
            //     'no_transaksi_pemeriksaan' => $noPemeriksaan,
            //     'idDokter' => $request->dokter,
            //     'idPasien' =>$request->pasien,
            //     'tanggalPeriksa' => $request->tanggalPeriksa,
            //     'fileLampiran' => $namaFileLampiran,
            //     'keluhan' => $request->keluhan
            // ]);

            return redirect()->route('pemeriksaan.index')->with('success', 'Edit Data Pemeriksaan berhasil');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pemeriksaan $pemeriksaan)
    {
        $pemeriksaan->delete();
        return redirect()->route('pemeriksaan.index')->with('success', 'Hapus Data Pemeriksaan berhasil');
    }

    public function download($file){
        $filepath = 'public/fileLampiran/'.$file;
        if(Storage::exists($filepath)){
            return Storage::download($filepath);
        } else {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

    }
}
