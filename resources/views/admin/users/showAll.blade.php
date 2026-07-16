@extends('admin.layout')

@section('section')
    @if($users->modelKeys())
    <table border='1px'>
        <tr>
            <th>id</th>
            <th>name</th>
            <th>email</th>
            <th>role</th>
            <th colspan="2">tools</th>  
        </tr>
        @foreach($users as $user)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
            <td><a href="{{ route('adminUpdateUserGet', ['id'=>$user->id]) }}">enter</a></td>
            <td><a href="{{ route('adminDeleteUserDelete', ['id'=>$user->id]) }}">delete</a></td>
        </tr>
        @endforeach
    </table>
    @endif
@endsection