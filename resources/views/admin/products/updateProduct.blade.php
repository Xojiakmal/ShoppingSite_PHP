@extends('admin.layout')

@section('section')
    <form action="{{ route('adminUpdateProductPut', ['product_id'=>$product_data['id']]) }}" method="post">
        @csrf
        @method('PUT')
        <input type="text" name="product_name" value="{{ $product_data->product_name }}" placeholder="Product name"><br>
        <input type="text" name="slug" value="{{ $product_data->slug }}" placeholder="Slug"><br>
        <input type="number" name="price" value="{{ $product_data->price }}" placeholder="Price"><br>
        <select name="category_id">
            @foreach($category_data as $cate)
                @if($product_data->category_id == $cate->id)
                <option value="{{ $cate->id }}" selected>{{ $cate->category_name }}</option>
                @else
                <option value="{{ $cate->id }}">{{ $cate->category_name }}</option>
                @endif
            @endforeach
        </select><br>
        <textarea name="description">{{ $product_data->description }}</textarea>

        <input type="submit" value="Change">
    </form>
    @isset($errors)
        <ul>
            @foreach($errors as $err) 
            <li>{{$error}}</li>
            @endforeach
        </ul>
    @endisset
@endsection