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
        $Category = new Category();

        $validator = Validator::make($request->query(), [
            's' =>'nullable|string',
            'cate' =>'nullable|string',
            'min_price' =>'nullable|integer',
            'max_price' =>'nullable|integer',
            'sort_price' =>'nullable|in:asc,desc'
        ]);

        $filters = $validator->validated();
        $data = 0;
        $search = $filters['s'] ?? '';

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

        $categories_data = $Category->where('parent_id', null)->get()->map(function ($cate) {
            return [
                'category_name' =>$cate->category_name,
                'slug' =>$cate->slug,
                'children' =>$cate->children->map(function ($child_cate) {
                    return [
                        'category_name' => $child_cate->category_name,
                        'slug' =>$child_cate->slug,
                        'children' =>$child_cate->children->map(function ($grandson_cate) {
                            return [
                                'category_name' => $grandson_cate->category_name,
                                'slug' =>$grandson_cate->slug,
                            ];
                        }),
                    ];
                }),
            ];
        });

        // dd($filters);
        return view('main.search.main', [
            'product_data' =>$product_data,
            'filters' =>$filters,
            'sidebar_data' =>[
                'category_list' =>$categories_data,
            ]
        ]);
    }

    function searchCategoryPageGet(Request $request, $slug=null) {
        $Category = new Category();
        $Product = new Product();

        $categories_data = $Category->where('parent_id', null)->get()->map(function ($cate) {
            return [
                'category_name' =>$cate->category_name,
                'slug' =>$cate->slug,
                'children' =>$cate->children->map(function ($child_cate) {
                    return [
                        'category_name' => $child_cate->category_name,
                        'slug' =>$child_cate->slug,
                        'children' =>$child_cate->children->map(function ($grandson_cate) {
                            return [
                                'category_name' => $grandson_cate->category_name,
                                'slug' =>$grandson_cate->slug,
                            ];
                        }),
                    ];
                }),
            ];
        });

        $product_data = $Product->where('category', 'like', '%' . $slug . '%')->get()->reverse();

        return view('main.search.category', [
            'category_list' =>$categories_data,
            'products_data' =>$product_data,
        ]);
    }

    function showProductPageGet(Request $request, $slug) {
        $Product = new Product();

        $product_data = $Product->where('slug', $slug)->first();

        if ($product_data == null) {
            return back();
        }   

        // dd($product_data);
        return view('main.product.show', [
            'product_data' =>$product_data,

        ]);
    }

    function addProductToBasketPageGet(Request $request) {
        $validator = Validator::make($request->query(), [
            'product_slug' =>'required|string'
        ]);

        $validated = $validator->validated();
        $Storage = new Storage();
        $Product = new Product();

        $product_data = $Product->where('slug', $validated['product_slug'])->first();

        if (!isset($product_data->id)) {
            return back()->with('basket_message', 'Not added');
        }

        $quantity = $Storage->where('product_id', $product_data->id);

        if ($quantity <= 0) {
            return back()->with('basket_message', 'Not added');
        }

        $session_data = [];
        if(session()->has('basket')) {
            $session_data = session()->get('basket');
        }
        if(!in_array($validated['product_slug'], array_keys($session_data))) {
            $session_data[$validated['product_slug']] = 0;
        }

        $session_data[$validated['product_slug']] += 1;

        session(['basket' => $session_data]);

        return back()->with('basket_message', 'Added');
    }

    function showBasketPageGet(Request $request) {
        $basket_data = [];
        if (session()->has('basket')) {
            $basket_data = session()->get('basket');
        }

        return view('main.product.basket', [
            'basket_data' =>$basket_data
        ]);
    }

    function confirmationBasketPost(Request $request) {

    }
}
