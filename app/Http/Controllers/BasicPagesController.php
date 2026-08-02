<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Storage;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class BasicPagesController extends Controller
{
    function mainPage(Request $request) {
        $Product = new Product();
        $Storage = new Storage();
        $Category = new Category();


        $products_id = $Product->where('updated_at', '>', today())->get()->modelKeys();
        $storages_id = $Storage->where('updated_at', '>', today())->get()->modelKeys();

        $today_tops = $Storage->where('id', $storages_id)->orWhere('product_id', $products_id)->get();


        $categories_id = $Category->where('parent_id', null)->get()->reverse();

        // dd($today_tops);
        return view('main.mainPage', [
            'today_tops' =>$today_tops,
            'categories_data' =>$categories_id
        ]);
    }

    function searchPageGet(Request $request) {
        $Product = new Product();

        $validator = Validator::make($request->query(), [
            's' =>'nullable|string',
            'cate' =>'nullable|string',
            'min_price' =>'nullable|integer',
            'max_price' =>'nullable|integer',
            'sort_price' =>'nullable|in:asc,desc'
        ]);

        $data = 0;
        $search = $filters['s'] ?? '';
        $filters = $validator->validated();

        $product_data = $Product->search($search, function ($meilisearch, $query, $options) use ($filters) {

            $cate = isset($filters['cate']) ? 'category = ' . $filters['cate'] : '';
            $min_price = isset($filters['min_price']) ? 'price >= ' . $filters['min_price'] : '';
            $max_price = isset($filters['max_price']) ? 'price <= ' . $filters['max_price'] : '';

            $options['filter'] = [
                $cate,
                $min_price,
                $max_price,
            ];

            if (isset($filters['sort_price'])) {
                $sort_price = $filters['sort_price'] == 'asc' ? 'price:asc' : 'price:desc';
                $options['sort'][] = $sort_price;
            }

            return $meilisearch->search($query, $options);

        })->get()->reverse();

        // dd($product_data);
        return view('main.search.main', [
            'product_data' =>$product_data,
            'filters' =>$filters,
        ]);
    }

    function searchCategoryPageGet(Request $request, $slug=null) {
        $Category = new Category();
        $Product = new Product();

        if ($slug != null) {
            $product_data = $Product->where('category', 'like', '%' . $slug . '%');
        }
        else {
            
        }

        dd($slug);


        return view('main.search.category');
    }
}
