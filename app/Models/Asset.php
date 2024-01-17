<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
    protected $table = 'tbl_assets';
    protected $primaryKey = 'id_asset';
    protected $fillable = [
        'status'
    ];
}
