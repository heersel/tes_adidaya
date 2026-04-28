<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    use HasFactory;

    protected $table = 'inventaris';

    protected $fillable = [
        'nama', 'kategori', 'stok', 'harga', 'created_at'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
