<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping site</title>
    <style>
        .box {
            display:inline-block;
            width:300px;
            border:solid 2px;
            justify-self: unset;
            padding:5px;
        }
        .title {
            display:inline-block;
        }
        .box .box-img {
            width:300px;
            height:300px;
            
        }
        .products_zone {
            max-width:1500px;
            display:inline-block;
        }
        .box .box-img .origin-img {
            width:300px;
        }
        .sidebar {
            display:inline-block;
        }
        .product_top .image_zone, .information_zone {
            display:inline-block;
            border:solid 2px;
        }
        .product_top .image_zone .origin_img {
            height:500px;
        }
        .product_top .image_zone {
            width:1000px;
        }
        .container {
            margin-left: 5%;
            margin-right: 5%;
        }

    </style>
</head>
<body>
    <nav>
        <ul style="list-style-type:none">
            <li style="display:inline-block"><a href="{{ route('mainPage') }}">Home</a></li>
            <li style="display:inline-block"><a href="{{ route('searchCategoryPageGet') }}">Categories</a></li>
            <li style="display:inline-block"><a href="{{ route('searchPageGet') }}">Search</a></li>
            <li style="display:inline-block"><a href="{{ route('showBasketPageGet') }}">Basket</a></li>
        </ul>
    </nav>
    @yield('content')
</body>
</html>