<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Category;

class Product extends Model
{
    protected $fillable = ['product_name', 'category_id'];

    public function category() {
        return $this->BelongsTo(Category::class);
    }
}
