@extends('admin.layout')

@section('section')
    @if($products_data->modelKeys())
    <table border="2px">
        <tr>
            <th>id</th>
            <th>name</th>
            <th style="max-width:100px">description</th>
            <th>price</th>
            <th colspan="2">tools</th>
        </tr>
        @foreach($products_data as $prod)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $prod->product_name }}</td>
            <td>{{ $prod->description }}</td>
            <td>{{ $prod->price }}</td>
            <td><a href="{{ route('adminUpdateProductGet', ['product_id'=>$prod->id]) }}">update</a></td>
            <td><a href="{{ route('adminDeleteProductDelete', ['product_id'=>$prod->id]) }}">delete</a></td>
            {{-- <td><input type="radio" name="category_parent" value="{{ $prod->id }}"></td>   --}}
        </tr>
        
        @endforeach
    </table>
    @endisset
    <div><a href="{{ route('adminAddProductGet') }}">Add product</a></div>
    @isset($errors)
    <ul>
        @foreach($errors as $err) 
        <li>{{$error}}</li>
        @endforeach
    </ul>
    @else
    <h3>Empty</h3>
    @endif
@endsection