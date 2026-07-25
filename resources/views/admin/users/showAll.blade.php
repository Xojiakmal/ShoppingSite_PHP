@extends('admin.layout')

@section('section')
    @if(session('success'))
        <h4>session('success')</h4>
    @endif
    <table border='1px'>
        <tr>
            <th>id</th>
            <th>name</th>
            <th>email</th>
            <th>role</th>
            <th colspan="2">tools</th>  
        </tr>
    @if($users->modelKeys())
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
    @else
    <tr>
        <td colspan="5"><center>empty</center></td>
    </tr>
    @endif
    </table>
@endsection