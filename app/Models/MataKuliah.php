<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa;
use App\Models\Mahasiswa_MataKuliah;

class MataKuliah extends Model
{
    use HasFactory;
    protected $table = 'matakuliah';
    protected $primaryKey = 'id';
    public function mahasiswa()
    {
        return $this->belongsToMany(Mahasiswa::class, 'mahasiswa_matakuliah');
    }
}