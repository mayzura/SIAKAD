<?php

namespace App\Http\Controllers;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;
use App\Models\Mahasiswa_MataKuliah;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //fungsi eloquent menampilkan data menggunakan pagination 
        // $mahasiswa = $mahasiswa = DB::table('mahasiswa')->get(); // Mengambil semua isi tabel

        // Mengambil 3 data tabel
        // $mahasiswa = $mahasiswa = DB::table('mahasiswa')->paginate(3);

        //yang semula Mahasiswa::all, diubah menjadi with() yang menyatakan relasi
        $mahasiswa = Mahasiswa::with('kelas')->get();

        // Mengambil semua isi tabel 
        $posts = Mahasiswa::orderBy('nim', 'asc')->paginate(3);
        return view('mahasiswa.index', ['mahasiswa' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kelas = Kelas::all(); //mendapatkan data dari tabel kelas
        return view('mahasiswa.create', ['kelas' => $kelas]);

        // return view('mahasiswa.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //melakukan validasi data
        if ($request->file('foto')){
            $foto = $request->file('foto')->store('foto','public');
        }
        $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'kelas' => 'required',
            'Jurusan' => 'required',

            
            'Email' => ['required', 'Email:dns'],
            'Alamat' => 'required',
            'Ttl' => 'required',
        ]);

    
        $mahasiswa = new Mahasiswa;
        $mahasiswa->nim = $request->get('Nim');
        $mahasiswa->nama = $request->get('Nama');
        // $mahasiswa->kelas_id = $request->get('Kelas');
        $mahasiswa->jurusan = $request->get('Jurusan');
        $mahasiswa->foto = $request->get('Foto');
        $mahasiswa->email = $request->get('Email');
        $mahasiswa->alamat = $request->get('Alamat');
        $mahasiswa->ttl = $request->get('Ttl');
        $mahasiswa->save();

        $idkelas = $request->get('kelas');
        $kelas = Kelas::find($idkelas);

        // //fungsi eloquent untuk menambah data
        // Mahasiswa::create($request->all());
        $mahasiswa->kelas()->associate($kelas);
        $mahasiswa->save();

        //jika data berhasil ditambahkan, akan kembali ke halaman utama
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($Nim)
    {
        //menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa
        //code sebelum dibuat relasi --> $Mahasiswa = Mahasiswa::find($Nim);
        
        $Mahasiswa = Mahasiswa::with('kelas')->where('nim', $Nim)->first();
        return view('mahasiswa.detail', compact('Mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($Nim)
    {
        //menampilkan detail data dengan menemukan berdasarkan Nim Mahasiswa untuk diedit
        //codingan sebelumnya --> $Mahasiswa = DB::table('mahasiswa')->where('nim', $Nim)->first();

       
        $Mahasiswa = Mahasiswa::with('kelas')->where('nim', $Nim)->first();
        $kelas = Kelas::all(); //mendapatkan data dari tabel kelas
        return view('mahasiswa.edit', compact('Mahasiswa', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $Nim)
    {
        //melakukan validasi data
        $data = $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'Kelas' => 'required',
            'Jurusan' => 'required',
            'Foto' => 'required',
            'Email' => ['required', 'Email:dns'],
            'Alamat' => 'required',
            'Ttl' => 'required',
        ]);
        $mahasiswa = Mahasiswa::with('kelas')->where('nim', $Nim)->first();
        // $mahasiswa->nim = $request->get('Nim');
        $mahasiswa->nama = $request->get('Nama');
        $mahasiswa->kelas_id = $request->get('Kelas');
        $mahasiswa->jurusan = $request->get('Jurusan');
        if ($mahasiswa->foto && file_exists(storage_path('app/pubic/' . $mahasiswa->foto))) {
            Storage::delete('public/' . $mahasiswa->foto);
        }
        $foto = $request->file('foto')->store('foto', 'public');
        $mahasiswa->foto = $foto;
        $mahasiswa->email = $request->get('Email');
        $mahasiswa->alamat = $request->get('Alamat');
        $mahasiswa->ttl = $request->get('Ttl');
        $mahasiswa->save();

        //fungsi eloquent untuk mengupdate data inputan kita
        // Mahasiswa::find($Nim)->update($request->all());

        $idkelas = $request->get('Kelas');
        $kelas = Kelas::find($idkelas);

        //fungsi eloquent untuk mengupdate data dengan relasi belongsTo
        $mahasiswa->kelas()->associate($kelas);
        $mahasiswa->save();

        //jika data berhasil diupdate, akan kembali ke halaman utama
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($Nim)
    {
        //fungsi eloquent untuk menghapus data
        Mahasiswa::find($Nim)->delete();
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa Berhasil Dihapus');
    }

    
    public function search(Request $request)
    {
        $keyword = $request->search;
        $mahasiswa = Mahasiswa::where('nama', 'like', "%" . $keyword . "%")->paginate(3);
        return view(view: 'mahasiswa.index', data: compact(var_name: 'mahasiswa'));
    }

    
    public function nilai($Nim)
    {
        $mahasiswa = Mahasiswa::with('kelas')->where('nim', $Nim)->first();
        $kelas = Kelas::all();
        $matakuliah = MataKuliah::all();
        $mahasiswa_matakuliah = Mahasiswa_MataKuliah::all();
        return view('mahasiswa.nilai', compact('mahasiswa', 'kelas', 'matakuliah', 'mahasiswa_matakuliah'));
    }
}