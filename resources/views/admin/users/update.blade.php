@extends('admin.layout')

@section('section')
    <form action="{{ route('adminUpdateUserPut', ['id'=>$user_data['id']]) }}" method="post">
        @csrf
        @method('PUT')
        <input type="text" name="name" value="{{ $user_data['name'] }}" placeholder="Your name"><br>
        <input type="text" name="email" value="{{ $user_data['email'] }}" placeholder="Email"><br>
        <input type="text" name="pass" placeholder="Password"><br>
        <select name="role">
            @if($user_data['role'] == 'user')
            <option value="admin">Admin</option>
            <option value="user" selected>User</option>
            @else
            <option value="admin" selected>Admin</option>
            <option value="user">User</option>
            @endif
        </select><br>
        <input type="submit" value="Change">
    </form>
    <!-- {{dd($errors)}} -->
@endsection