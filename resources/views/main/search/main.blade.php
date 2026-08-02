@extends('main.layout')


@section('content')
<form action="{{ route('searchPageGet') }}" method="get">
    <input type="text" name="s">
    <input type="submit" value="search">
</form>
@if($product_data->modelKeys())
<ul>
    @foreach($product_data as $prod)
    <li>{{ $prod->product_name }}</li>
    @endforeach
</ul>
@else 
empty
@endif
@endsection