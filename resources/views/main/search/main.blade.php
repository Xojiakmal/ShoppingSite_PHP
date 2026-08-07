@extends('main.layout')


@section('content')
<div class="search_zone">
    <form action="{{ route('searchPageGet') }}" method="get">
        <input type="text" name="s">
        <input type="submit" value="search">
    </form>
</div>
<div class="sidebar">
    <form action="{{ route('searchPageGet') }}" method="get">
        <div class="category_sidebar">
            @isset($sidebar_data['category_list'])
            <ul>
                @foreach($sidebar_data['category_list'] as $parent_cate)
                <li>
                    <input type="radio" name="cate" value="{{ $parent_cate['slug'] }}" id="{{ $parent_cate['slug'] }}"
                    @checked(isset($filters['cate']) && ($parent_cate['slug'] ==  $filters['cate']))>
                    <label for="{{ $parent_cate['slug'] }}">{{ $parent_cate['category_name'] }}</label>
                    @isset($parent_cate['children'])
                        <ul>
                            @foreach($parent_cate['children'] as $cate)
                            <li>
                                <input type="radio" name="cate" value="{{ $cate['slug'] }}" id="{{ $cate['slug'] }}"
                                @checked(isset($filters['cate']) && ($parent_cate['slug'] == $filters['cate']))>
                                <label for="{{ $cate['slug'] }}">{{ $cate['category_name'] }}</label>
                                @isset($cate['children'])
                                    <ul>
                                        @foreach($cate['children'] as $child_cate)
                                        <li>
                                            <input type="radio" name="cate" value="{{ $child_cate['slug'] }}" id="{{ $child_cate['slug'] }}"
                                            @checked(isset($filters['cate']) && ($parent_cate['slug'] == $filters['cate']))>
                                            <label for="{{ $child_cate['slug'] }}">{{ $child_cate['category_name'] }}</label>
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
        <div class="price_sidebar">
            <input type="number" name="min_price" placeholder="Min price" value="{{ $filters['min_price'] ?? '' }}"><br>
            <input type="number" name="max_price" placeholder="Max price" value="{{ $filters['max_price'] ?? '' }}">
        </div>
        <div class="sort_sidebar">
            <div class="sort_price_sidebar_zone">
                Price <br>
                <input type="radio" name="sort_price" value="asc" id="asc_price"
                @checked(isset($filters['sort_price']) && $filters['sort_price'] == 'asc')><label for="asc_price">Ascend</label><br>
                <input type="radio" name="sort_price" value="desc" id="desc_price"
                @checked(isset($filters['sort_price']) && $filters['sort_price'] == 'desc')><label for="desc_price">Descend</label>
            </div>
        </div>
        <input type="submit" value="Filter">
    </form>
</div>
<div class="products_zone">
    @if(isset($product_data) && $product_data->modelKeys())
        @foreach($product_data as $prod)
        <div class="box">
            <div class="box-img"><img class="origin-img" src="{{ asset('storage/'.$prod->image_path) }}" alt="{{ $prod->slug }}-image"></div>
            <div class="title">Title: {{ $prod->product_name }}</div>
            <div class="price">Price: {{ $prod->price }}</div>
            <p>Description: {{ $prod->description ?? 'None' }}</p>
            <a href="{{ route('showProductPageGet', [$prod->slug]) }}">Enter</a>
        </div>
        @endforeach
    @endif
</div>
@endsection