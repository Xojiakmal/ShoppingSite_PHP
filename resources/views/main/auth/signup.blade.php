@extends('main.auth.layout')

@section('title')
Signup page
@endsection

@section('content')
<form action="{{ route('signupPost') }}" method="POST">
    @csrf
    <input type="text" name="name" placeholder="Your name"><br>
    <input type="email" name="email" placeholder="email@example.com"><br>
    <input type="password" name="pass1" placeholder="enter password"><br>
    <input type="password" name="pass2" placeholder="confirm password"><br>
    <input type="submit" value="Sign up">
</form>
<a href="{{ route('loginGet') }}">Have an account?</a>

@if ($errors->any())
<ul>
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
</ul>
@endif
@endsection