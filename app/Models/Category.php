<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'name',
        'desc',
        'type',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
