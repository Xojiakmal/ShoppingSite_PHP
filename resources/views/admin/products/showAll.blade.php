@extends('admin.layout')

@section('section')
    <div><a href="{{ route('adminAddProductGet') }}">Add product</a></div>
    @if(session('success'))
        <h4>{{ session('success') }}</h4>
    @endif
    <table border="2px">
        <tr>
            <th>id</th>
            <th>name</th>
            <th style="max-width:100px">description</th>
            <th>price</th>
            <th colspan="2">tools</th>
        </tr>
    @if($products_data->modelKeys())
        @foreach($products_data as $prod)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $prod->product_name }}</td>
            <td>{{ $prod->description }}</td>
            <td>{{ $prod->price }}</td>
            <td><a href="{{ route('adminUpdateProductGet', ['product_id'=>$prod->id]) }}">update</a></td>
            <td><a href="{{ route('adminDeleteProductDelete', ['product_id'=>$prod->id]) }}">delete</a></td>
        </tr>
        @endforeach
    @else
        <tr>
            <td colspan="5"><center>empty</center></td>
        </tr>
    @endif
    </table>
    @isset($errors)
    <ul>
        @foreach($errors as $err) 
        <li>{{$error}}</li>
        @endforeach
    </ul>
    @else
    <h3>Empty</h3>
    @endisset
@endsection