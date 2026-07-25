<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Storage;
use App\Models\Category;

class BasicPagesController extends Controller
{
    function mainPage(Request $request) {
        $Product = new Product();
        $Storage = new Storage();
        $Category = new Category();


        $products_id = $Product->where('updated_at', '>', today())->get()->modelKeys();
        $storages_id = $Storage->where('updated_at', '>', today())->get()->modelKeys();

        $today_tops = $Storage->where('id', $storages_id)->orWhere('product_id', $products_id)->get();


        $categories_id = $Category->where('parent_id', null)->get()->reverse()->first();

        dd($categories_id);
        return view('main.mainPage', [
            'today_tops' =>$today_tops
        ]);
    }
}
