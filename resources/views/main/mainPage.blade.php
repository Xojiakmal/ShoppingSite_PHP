@extends('main.layout')

@section('content')
@if($today_tops->modelKeys() > 0)
    <ul>
        @foreach($today_tops as $prod)
        <li>{{ $prod->product->product_name }}</li>
        @endforeach
    </ul>
@endif
@endsection