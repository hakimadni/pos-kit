<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'name',
        'price',
        'desc',
        'category_id',
        'set_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function menuSet()
    {
        return $this->belongsTo(MenuSet::class, 'set_id');
    }
}
