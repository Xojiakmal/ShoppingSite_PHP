@extends('admin.layout')

@section('section')
    <form action="" method="POST">
        @csrf
        @method('POST')

        <input type="text" name='category_name' placeholder="Category name"><br>
        <input type="submit" value="Save">

        @if($category_data->modelKeys())
        <table border="2px">
            <tr>
                <th>id</th>
                <th>name</th>
                <th colspan="3">tools</th>
            </tr>
            @foreach($category_data as $cate)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $cate->category_name }}</td>
                <td><a href="{{ route('adminShowAllCategoriesGet') }}?pi={{ $cate->id }}">enter</a></td>
                <td><a href="{{ route('adminDeleteCategoryDelete', ['category_id'=>$cate->id]) }}">D</a></td>
                <td><input type="radio" name="category_parent" value="{{ $cate->id }}"></td>  
            </tr>
            
            @endforeach
        </table>
        @endif
    </form>
@endsection