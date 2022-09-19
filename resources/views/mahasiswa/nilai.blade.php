@extends('mahasiswa.layout')

@section('content')
    <div class="container text-center">
        <h2>JURUSAN TEKNOLOGI INFORMASI-POLITEKNIK NEGERI MALANG</h2>
        <h1>KARTU HASIL STUDI (KHS)</h1>
        <div class="text-left">
            <p><b>Nama : </b>{{ $mahasiswa->nama }}</p>
            <p><b>NIM : </b>{{ $mahasiswa->nim }}</p>
            <p><b>Kelas : </b>{{ $mahasiswa->kelas->nama_kelas }}</p>

        </div>
        <table class="table table-bordered">
            <tr>
                <th>Mata Kuliah</th>
                <th>SKS</th>
                <th>Semester</th>
                <th>Nilai</th>
            </tr>
            @foreach ($mahasiswa->matakuliah as $mhs)
                <tr>
                    <td>{{ $mhs->nama_matkul }}</td>
                    <td>{{ $mhs->sks }}</td>
                    <td>{{ $mhs->semester }}</td>
                    <td>{{ $mhs->pivot->nilai }}</td>                    
                </tr>
            @endforeach
        </table>
    </div>
@endsection