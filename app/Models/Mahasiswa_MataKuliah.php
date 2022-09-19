<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;

class Mahasiswa_MataKuliah extends Model
{
    use HasFactory;
    protected $table = 'mahasiswa_matakuliah';    
    protected $fillable = ['mahasiswa_id'];
    
}