@extends('admin.layout')

@section('section')
    @if($storage_data->modelKeys())
    <form action="{{ route('adminUpdateStoragePut') }}" method="POST">
        @csrf
        @method('PUT')
        {{-- <p>If you want to reduce the product, write with "-".</p> --}}
        <p>
            <label for="pasitive"><input type="radio" name="sign" value="+" id="pasitive">Add</label><br>
            <label for="negative"><input type="radio" name="sign" value="-" id="negative">Subtraction</label><br>
        </p>
        <input type="text" name="quantity" placeholder="Quantity"><br>
        <input type="submit" value="Save">
        <table border='1px'>
            <tr>
                <th>id</th>
                <th>name</th>
                <th>quantity</th>
                <th>choose</th>  
            </tr>
            @foreach($storage_data as $sto)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $sto->product->product_name }}</td>
                <td>{{ $sto->quantity }}x</td>
                <td><input type="radio" name="chosen_product" value="{{ $sto->id }}"></td>
            </tr>
            @endforeach
        </table>
    </form>
    @endif
@endsection