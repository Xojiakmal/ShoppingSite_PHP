<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Storage;

class AdminPanelController extends Controller
{
    function dashboard(Request $request) {
        $User = new User();

        $user_data = $User->where('role', 'user')->get(['name', 'email']);
        $category_data = Category::all();

        return view('admin.dashboard', [
            'informations' =>[
                'users' =>[
                    'count' =>count($user_data),
                    'users' =>$user_data
                ],
                'categories' =>[
                    'count' =>count($category_data),
                    'categories' =>$category_data
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
            'name' =>'required|alpha',
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

        return redirect()->route('adminShowAllUsers');
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

        return redirect()->route('adminShowAllUsers');
    }

    // Control categories
    function showAllCategoriesGet(Request $request) {
        $Category = new Category();

        $parent_id = $request->query('pi', null);
        

        $category_data = $Category->where('parent_id', $parent_id)->get()->reverse();
        
        // dd($category_data->modelKeys() == null);

        return view('admin.categories.showAll', ['category_data' =>$category_data]);
    }

    function addCategoryPost(Request $request) {
        $validator = Validator::make($request->all(), [
            'category_name' =>'required|alpha',
            'category_parent' =>'nullable|numeric'
        ]);

        $validated = $validator->validated();
        $Category = new Category();

        // dd($validated);

        $Category->category_name = $validated['category_name'];
        if (isset($validated['category_parent'])) {
            $Category->parent_id = $validated['category_parent'];
        }

        $Category->save();

        return redirect()->route('adminShowAllCategoriesGet');
    }

    function deleteCategoryDelete(Request $request, $category_id) {
        $Category = new Category();

        $category_data = $Category->find($category_id);

        if (isset($category_data->id)) {
            $category_data->delete();
        }

        return redirect()->route('adminShowAllCategoriesGet');
    }

    // Control products
    function showAllProductsGet(Request $request) {
        $Product = new Product();

        $product_data = $Product->all()->reverse();

        return view('admin.products.showAll', ['products_data' =>$product_data]);
    }

    function addProductGet(Request $request) {
        $Category = new Category();

        $category_data = $Category->all()->reverse();
        
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
            $validated['slug'] = Str::of($validated['product_name'])->slug('-')->value;
        }

        try {
            DB::beginTransaction();
            $Product = new Product();
            $Storage = new Storage();

            $Product->product_name = $validated['product_name'];
            $Product->slug = $validated['slug'];
            $Product->price = $validated['price'];
            $Product->description = $validated['description'];
            $Product->category_id = $validated['category'];

            $Product->save();

            $Storage->product_id = $Product->id;
            $Storage->quantity = 0;

            $Storage->save();

            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('adminAddProductGet');
        }

        return redirect()->route('adminShowAllProductsGet');
    }

    // Control storage
    function showAllStorageGet(Request $request) {
        $Storage = new Storage();

        $storage_data = $Storage->all()->reverse();

        return view('admin.storage.showAll', ['storage_data' =>$storage_data]);
    }

    function addProductToStoragePut(Request $request) {
        $validator = Validator::make($request->all(), [
            ''
        ]);
    }
}