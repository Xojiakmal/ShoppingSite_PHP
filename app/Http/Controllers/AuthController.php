<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // GET
    function loginGet() {
        return view('main.auth.login');
    }

    function signupGet() {
        return view('main.auth.signup');
    }

    // POST
    function loginPost(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' =>'required|email',
            'password' =>'required|min:6',
        ]);

        $validated = $validator->validated();

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();

            $role = Auth::user()['role'];

            if ($role == 'user') {
                return redirect()->intended('/');
            }
            return redirect()->intended('/admin');
        }

        return back()->withErrors([
            'email' =>'Email or password is incorrect'
        ])->onlyInput('email');
    }

    function signupPost(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' =>'required|regex:/^[A-Za-z\']+$/',
            'email' =>'required|unique:users|email',
            'pass1' =>'required|min:6',
            'pass2' =>'required|min:6'
        ]);

        $validated = $validator->validated();

        if ($validated['pass1'] != $validated['pass2']) {
            $validator->errors()->add('pass1', 'Passwords are not similar');
            $validator->errors()->add('pass2', 'Passwords are not similar');
        }

        $validated = $validator->validated();

        $User = new User();

        $user_count = $User->all()->modelKeys();

        if($user_count == null) {
            $role = 'superadmin';
        }
        else {
            $role = 'user';
        }

        $hashed_pass = Hash::make($validated['pass1']);

        $User->name = $validated['name'];
        $User->email = $validated['email'];
        $User->password = $hashed_pass;
        $User->role = $role;

        $User->save();


        return redirect()->route('loginGet');
    }
}
