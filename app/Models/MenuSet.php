<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuSet extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'name',
        'desc',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
