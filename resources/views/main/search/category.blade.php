@extends('main.layout')

@section('content')
<div class="sidebar">
    <h3>Sidebar of Categories</h3>
    @isset($category_list)
    <ul>
        @foreach($category_list as $parent_cate)
        <li>
            <a href="{{ route('searchCategoryPageGet', [$parent_cate['slug']]) }}">{{ $parent_cate['category_name'] }}</a>
            @isset($parent_cate['children'])
                <ul>
                    @foreach($parent_cate['children'] as $cate)
                    <li>
                        <a href="{{ route('searchCategoryPageGet', [$cate['slug']]) }}">{{ $cate['category_name'] }}</a>
                        @isset($cate['children'])
                            <ul>
                                @foreach($cate['children'] as $child_cate)
                                <li>
                                    <a href="{{ route('searchCategoryPageGet', [$child_cate['slug']]) }}">{{ $child_cate['category_name'] }}</a>
                                </li>
                                @endforeach
                            </ul>
                        @endisset
                    </li>
                    @endforeach
                </ul>
            @endisset
        </li>
        @endforeach
    </ul>
    @endisset
</div>
<div class="products_zone">
    @if(isset($products_data) && $products_data->modelKeys())
        @foreach($products_data as $prod)
        <div class="box">
            <div class="box-img"><img class="origin-img" src="{{ asset('storage/'.$prod->image_path) }}" alt=""></div>
            <div class="title">Title: {{ $prod->product_name }}</div>
            <div class="price">Price: {{ $prod->price }}</div>
            
            <p>Description: {{ $prod->description ?? 'None' }}</p>
            <a href="{{ route('showProductPageGet', [$prod->slug]) }}">Enter</a>
        </div>
        @endforeach
    @endif
</div>
@endsection