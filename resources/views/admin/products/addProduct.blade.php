@extends('admin.layout')

@section('section')
    <form action="{{ route('adminAddProductPost') }}" method='POST'>
        @csrf
        <input type="text" name="product_name" placeholder="Product name"><br>
        <input type="text" name="slug" placeholder="Slug"><br>
        <input type="tel" name="price" placeholder="Price"> $ <br>
        Category:
        <select name="category">
            <option value="">...</option>
            @isset($category_data)
            @foreach($category_data as $cate)
                <option value="{{ $cate->id }}">{{ $cate->category_name }}</option>
            @endforeach
            @endisset
        </select><br>
        <textarea name="description" placeholder="description"></textarea><br>
        <input type="submit" value="Save">
    </form>
    @if(session('success'))
        <h4>{{ session('success') }}</h4>
    @endif
    @isset($errors)
        <ul>
            @foreach($errors as $err) 
            <li>{{$error}}</li>
            @endforeach
        </ul>
    @endisset
@endsection