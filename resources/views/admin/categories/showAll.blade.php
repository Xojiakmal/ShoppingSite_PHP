@extends('admin.layout')

@section('section')
    <form action="{{ route('adminAddCategoryPost') }}" method="POST">
        @csrf
        @method('POST')
        <div>
            <div>
                Category level: 
                @isset($rank)
                {{ ucfirst($rank['name']) }}
                <input type='hidden' name="rank" value="{{ $rank['id'] }}"></input>
                @else
                Unknown
                @endisset
            </div>
            <input type="text" name='category_name' placeholder="Category name"><br>
            <input type="submit" value="Save"><br>
            @if(request()->query('pi'))
            <a href="{{ route('adminShowAllCategoriesGet') }}">Back to hight</a>
            @endif

            @isset($errors)
            <ul>
                @foreach($errors as $err) 
                <li>{{$err}}</li>
                @endforeach
            </ul>
            @else
                <h4>Empty</h4>
            @endisset
            @if(session('success'))
                <h4>{{ session('success') }}</h4>
            @endif
        </div>

        <table border="2px">
            <tr>
                <th>id</th>
                <th>Category name</th>
                <th colspan="3">tools</th>
            </tr>
            @if(isset($category_data) && $category_data->modelKeys())
            @foreach($category_data as $cate)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $cate->category_name }}</td>
                @if($cate->rank != 'low')
                <td><a href="?pi={{ $cate->id }}">enter</a></td>
                <td><input type="radio" name="category_parent" value="{{ $cate->id }}" id="{{ $loop->iteration }}"><label for="{{ $loop->iteration }}">select</label></td>
                @endif
                <td><a href="{{ route('adminDeleteCategoryDelete', ['category_id'=>$cate->id]) }}">delete</a></td>
            </tr>
            
            @endforeach
            @else
            <tr>
                <td colspan="3"><center> empty</center></td>
            </tr>

            @endif
        </table>
    </form>
@endsection