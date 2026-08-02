@extends('main.layout')

@section('content')
    <div style="display:inline-block;border:solid 2px;padding:5px">
        <h3>Added products of today</h3>
        <ul>
    @if(isset($today_tops) && $today_tops->modelKeys())
        @foreach($today_tops as $prod)
        <li>{{ $prod->product->product_name }}</li>
        @endforeach
    @else
        <li>Empty</li>
    @endif
        </ul>
    </div>
    <div style="display:inline-block;border:solid 2px;padding:5px">
        <h3>Categories</h3>
        <ul>
        @if(isset($categories_data) && $categories_data->modelKeys())
            @foreach($categories_data as $cate)
            <li><a href="">{{ $cate->category_name }}</a></li>
            @endforeach
        @else
            <li>Empty</li>
        @endif
        </ul>
    </div>
@endsection