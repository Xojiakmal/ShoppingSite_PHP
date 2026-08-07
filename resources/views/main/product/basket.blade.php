@extends('main.layout')


@section('content')
    @if(isset($basket_data) && $basket_data != null)
    @foreach($basket_data as $bask)
        
    @endforeach
    @endif
@endsection