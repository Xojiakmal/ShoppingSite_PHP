@extends('main.auth.layout')

@section('title')
Login page
@endsection

@section('content')
<form action="{{ route('loginPost') }}" method="POST">
    @csrf
    <input type="email" name="email" placeholder="email@example.com"><br>
    <input type="password" name="password" placeholder="enter password"><br>
    <input type="submit" value="Log in">
</form>
<a href="{{ route('signupGet') }}">Have not an account?</a>

@if ($errors->any())
<ul>
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
</ul>
@endif
@endsection