<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $table = 'tbl_expenses';
    protected $primaryKey = 'id_expense';

    protected $fillable = [
        'id_asset',
        'id_category',
        'name',
        'date',
        'price',
        'desc'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }
}
