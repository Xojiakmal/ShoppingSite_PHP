<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Storage;

class AdminPanelController extends Controller
{
    function dashboard(Request $request) {
        $User = new User();
        $Category = new Category();
        $Product = new Product();

        $user_data = $User->where('role', 'user')->get(['name', 'email']);
        $category_data = $Category->all();
        $product_data = $Product->all();


        return view('admin.dashboard', [
            'informations' =>[
                'users' =>[
                    'count' =>count($user_data),
                    'users' =>$user_data
                ],
                'categories' =>[
                    'count' =>count($category_data),
                    'categories' =>$category_data
                ],
                'products' =>[
                    'count' =>count($product_data),
                    'products' =>$product_data
                ]
            ]
        ]);
    }

    // Control users
    function showAllUsersGet(Request $request) {
        $User = new User;

        $users = $User->where('role', 'user')->orWhere('role', 'admin')->get(['id', 'name', 'email', 'role'])->reverse();
        
        return view('admin.users.showAll', ['users' =>$users]);
        
    }

    function updateUserGet(Request $request, $user_id) {
        if (!is_numeric($user_id)) {
            return redirect()->route('adminDashboard');
        }

        $User = new User();

        $user_data = $User->find($user_id);
        
        if ($user_data == null) {
            return redirect()->route('adminDashboard');
        }

        $filtered_data = $user_data->only(['id', 'name', 'email', 'role']);
        // dd($filtered_data);

        return view('admin.users.update', ['user_data' =>$filtered_data]);
    }

    function updateUserPut(Request $request, $user_id) {
        $validator = Validator::make($request->all(), [
            'name' =>'required|regex:/^[A-Za-z\']+$/',
            'email' =>'required|email',
            'pass' =>'min:6|nullable',
            'role' =>'required|in:user,admin',
        ]);
        
        $validated = $validator->validated();

        $User = new User();

        $check_email = $User->where('email', $validated['email'])->where('id', '!=', $user_id)->first();
 
        if ($check_email != null) {
            $validator->errors()->add('email', 'Email was used');
            $validator->validated();
        }

        $user_data = $User->find($user_id);

        $user_data->name = $validated['name'];
        $user_data->email = $validated['email'];
        $user_data->role = $validated['role'];
        if ($validated['pass'] != null) {
            $user_data->password = Hash::make($validated['pass']);
        }

        $user_data->save();

        return redirect()->route('adminShowAllUsers')->with('success', 'Updated successfully');
    }

    function deleteUserDelete(Request $request, $user_id) {
        try {
            DB::beginTransaction();

            $User = new User();

            $user_data = $User->find($user_id);

            if (isset($user_data->role) && $user_data->role == 'superadmin' || $user_data->id == Auth::id()) {
                throw new Exeption('You can\'t delete Super Admin');
            }

            $user_data->delete();

            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
        }

        return redirect()->route('adminShowAllUsers')->with('success', 'Deleted successfully');
    }

    // Control categories
    function showAllCategoriesGet(Request $request) {
        $Category = new Category();
        $category_id = $request->query('pi');

        if ($category_id != null && is_numeric($category_id)) {

            $p_category_data = $Category->find($category_id);

            if (!isset($p_category_data->id)) {
                return view('admin.categories.showAll');
            }

            $category_data = $Category->where('parent_id', $category_id)->get()->reverse();
            
            if (!isset($category_data->first()->id)) {
                return view('admin.categories.showAll');
            }
        }
        else {
            $category_data = $Category->where('parent_id', null)->get()->reverse();

            return view('admin.categories.showAll', [
                'category_data' =>$category_data,
                'rank' => [
                    'name' =>'hight',
                    'id' =>1
                ]
            ]);
        }

        $ranks = [
            'hight' =>1,
            'medium' =>2,
            'low' =>3
        ];

        // dd($category_data->first());

        return view('admin.categories.showAll', [
            'category_data' =>$category_data,
            'rank' => [
                'name' =>$category_data->first()->rank,
                'id' =>$ranks[$category_data->first()->rank]
            ]
        ]);
    }

    function addCategoryPost(Request $request) {
        $validator = Validator::make($request->all(), [
            'category_name' =>'required|regex:/^[\pL\s\']+$/',
            'category_parent' =>'nullable|integer',
            'rank' =>'required|in:1,2,3'
        ]);

        $validated = $validator->validated();
        $Category = new Category();

        
        $Category->category_name = $validated['category_name'];
        $Category->slug = str($validated['category_name'])->slug('-') . '-?';
        if (isset($validated['category_parent'])) {
            $check_parent = $Category->find($validated['category_parent']);
            
            $ranks = [
                '1' =>'hight',
                '2' =>'medium',
                '3' =>'low'
            ];
            if($check_parent->rank != $ranks[$validated['rank']] || !(
                ($check_parent->rank == 'hight' && $validated['rank'] == '1') ||
                ($check_parent->rank == 'medium' && $validated['rank'] == '2')
            )) {
                // dd($validated);
                $validator->errors()->add('rank', 'Something is invalid');
                $validator->validated();
            }
            $Category->rank = $ranks[$validated['rank']+1];

            $Category->parent_id = $validated['category_parent'];
        }
        else {
            $Category->rank = 'hight';
        }

        $Category->save();

        $Category->slug = str_replace('?', $Category->id, $Category->slug);
        $Category->save();

        return redirect()->route('adminShowAllCategoriesGet')->with('success', 'Created successfully');

    }

    function deleteCategoryDelete(Request $request, $category_id) {
        $Category = new Category();
        $Product = new Product();
        $Storage = new Storage();

        try {
            DB::beginTransaction();

            $category_data = $Category->find($category_id);

            if (isset($category_data->id)) {
                $product_data = $Product->where('category', 'like', '%' . $category_data->slug . '%')->get();
                $storage_data = $Storage->whereIn('product_id', $product_data->modelKeys())->get();

                if ($storage_data->modelKeys() != null) {
                    $Storage->whereIn('id', $storage_data->modelKeys())->delete();
                }
                if($product_data->modelKeys() != null) {
                    $Product->whereIn('id', $product_data->modelKeys())->delete();
                }
                $category_data->delete();
            }

            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
        }

        return redirect()->route('adminShowAllCategoriesGet')->with('success', 'Deleted successfully');
    }

    // Control products
    function showAllProductsGet(Request $request) {
        $Product = new Product();

        $product_data = $Product->all()->reverse();

        return view('admin.products.showAll', ['products_data' =>$product_data]);
    }

    function addProductGet(Request $request) {
        $Category = new Category();

        $category_data = $Category->where('rank', 'low')->get()->reverse();
        
        return view('admin.products.addProduct', ['category_data' =>$category_data]);
    }

    function addProductPost(Request $request) {
        $validator = Validator::make($request->all(), [
            'product_name' =>'required|string',
            'price' =>'required|int',
            'category' =>'required|int',
            'description' =>'nullable',
            'slug' =>'nullable|unique:products'
        ]);

        $validated = $validator->validated();

        if (!isset($validated['slug']) || $validated['slug'] == null) {
            $validated['slug'] = str($validated['product_name'])->slug('-');
        }

        try {
            DB::beginTransaction();
            $Product = new Product();
            $Storage = new Storage();
            $Category = new Category();

            $category_low = $Category->where('id', $validated['category'])->where('rank', 'low')->first();
            $category_medium = $Category->where('id', $category_low->parent_id)->where('rank', 'medium')->first();
            $category_hight = $Category->where('id', $category_medium->parent_id)->where('rank', 'hight')->first();

            $Product->product_name = $validated['product_name'];
            $Product->slug = $validated['slug'];
            $Product->price = $validated['price'];
            $Product->description = $validated['description'];
            $Product->category = $category_hight->slug."/".$category_medium->slug."/".$category_low->slug;
            
            $Product->save();
            
            $Storage->product_id = $Product->id;
            $Storage->quantity = 0;

            $Storage->save();

            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('success', 'Product did not created');
        }

        return redirect()->route('adminShowAllProductsGet')->with('success', 'Created successfully');
    }

    function updateProductGet(Request $request, $product_id) {
        if (!is_numeric($product_id)) {
            return redirect()->route('adminDashboard');
        }

        $Product = new Product();
        $Category = new Category();

        $category_data = $Category->all()->reverse();

        $product_data = $Product->find($product_id);
        
        if ($product_data == null) {
            return redirect()->route('adminDashboard');
        }

        // $filtered_data = $product_data->only(['id', 'product_name', 'description', 'price', 'category_id']);
        // dd($product_data);

        return view('admin.products.updateProduct', ['product_data' =>$product_data, 'category_data' =>$category_data]);
    }
    
    function updateProductPut(Request $request, $product_id) {
        if (!is_numeric($product_id)) {
            return redirect()->route('adminDashboard');
        }

        $validator = Validator::make($request->all(), [
            'product_name' =>'required|string',
            'price' =>'required|integer',
            'slug' =>'required|string',
            'category_id' =>'required|integer',
            'description' =>'string|nullable'
        ]);
        $validated = $validator->validated();
        // dd($validator);


        try {
            DB::beginTransaction();
            $Product = new Product();

            $product_data = $Product->find($product_id);

            $product_data->product_name = $validated['product_name'];
            $product_data->price = $validated['price'];
            $product_data->slug = $validated['slug'];
            $product_data->category = $validated['category_id'];
            $product_data->description = $validated['description'];

            $product_data->save();

            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
        }
        
        return redirect()->route('adminShowAllProductsGet')->with('success', 'Updated successfully');
    }

    function deleteProductDelete(Request $request, $product_id) {
        if (!is_numeric($product_id)) {
            return redirect()->route('adminDashboard');
        }

        try {
            DB::beginTransaction();

            $Product = new Product();
            $Storage = new Storage();

            $product_data = $Product->find($product_id);
            $storage_data = $Storage->where('product_id', $product_data->id)->first();

            $storage_data->delete();
            $product_data->delete();

            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
        }

        return redirect()->route('adminShowAllProductsGet')->with('success', 'Deleted successfully');
    }

    // Control storage
    function showAllStorageGet(Request $request) {
        $Storage = new Storage();

        $storage_data = $Storage->all()->reverse();

        return view('admin.storage.showAll', ['storage_data' =>$storage_data]);
    }

    function updateStoragePut(Request $request) {
        $validator = Validator::make($request->all(), [
            'sign' =>'required|in:+,-',
            'quantity' =>'required|integer',
            'chosen_product' =>'required|integer'
        ]);

        $validated = $validator->validated();

        $Storage = new Storage();

        $storage_data = $Storage->find($validated['chosen_product']);
        
        if ($validated['sign'] == '+') {
            $storage_data->quantity += $validated['quantity'];
        }
        elseif ($storage_data->quantity >= $validated['quantity']) {
            $storage_data->quantity -= $validated['quantity'];
        }
        else {
            $validator->errors()->add('basic', "You cannot withdraw more than the quantity in the storage");
            $validator->validated();
        }

        $storage_data->save();

        return redirect()->route('adminShowAllStorageGet')->with('success', 'Updated successfully');
    }
}