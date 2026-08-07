@extends('main.layout')

@section('content')
<div class="container">
    <div class="product_top">
        <div class="image_zone">
            <img src="{{ asset('storage/'.$product_data->image_path) }}" class="origin_img" alt="">
        </div>
        <div class="information_zone">
            <div class="info">
                <div class="title"><h1>Name: {{ $product_data->product_name }}</h1></div>
                <div class="description"> <p>Description: {{ $product_data->description ?? 'None' }}</p></div>
            </div>
            <div class="button"><a href="{{ route('addProductToBasketPageGet') }}?product_slug={{ $product_data->slug }}">Add to basket</a></div>
        </div>
    </div>
</div>
@endsection