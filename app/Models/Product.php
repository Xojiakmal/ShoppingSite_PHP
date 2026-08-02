<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Category;
use Laravel\Scout\Searchable;

class Product extends Model
{

    use Searchable;

    protected $fillable = ['product_name', 'category_id'];

    public function category() {
        return $this->BelongsTo(Category::class);
    }
}
