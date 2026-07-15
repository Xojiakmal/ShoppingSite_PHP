@extends('admin.layout')

@section('section')
    @if($storage_data->modelKeys())
    <table border='1px'>
        <tr>
            <th>id</th>
            <th>name</th>
            <th>quantity</th>
            {{-- <th>email</th>
            <th>role</th>
            <th colspan="2">tools</th>   --}}
        </tr>
        @foreach($storage_data as $sto)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $sto->product->product_name }}</td>
            <td>{{ $sto->quantity }}x</td>
            {{-- <td>{{ $user->role }}</td>
            <td><a href="{{ route('adminUpdateUserGet', ['id'=>$user->id]) }}">E</a></td>
            <td><a href="{{ route('adminDeleteUserDelete', ['id'=>$user->id]) }}">D</a></td> --}}
        </tr>
        @endforeach
    </table>
    @endif
@endsection