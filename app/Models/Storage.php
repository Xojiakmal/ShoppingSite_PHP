<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Product;

class Storage extends Model
{
    // protected $fillable = ['parent_id'];

    // public function parent()
    // {
    //     return $this->belongsTo(Category::class, 'parent_id');
    // }

    // public function products()
    // {
    //     return $this->hasOne(Storage::class, 'id');
    // }

    public function product() 
    {
        return $this->belongsTo(Product::class);
    }
}
