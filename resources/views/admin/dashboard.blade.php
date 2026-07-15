@extends('admin.layout')

@section('section')
    <div class="content">
        <h3>infos</h3>
        <ul>
            @foreach($informations as $k =>$infos)
            <li>{{ $k }}: {{ $infos['count'] }}</li>
            @endforeach
        </ul>
    </div>
@endsection