<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping site</title>
</head>
<body>
    <nav>
        <ul style="list-style-type:none">
            <li style="display:inline-block"><a href="{{ route('mainPage') }}">Home</a></li>
            <li style="display:inline-block"><a href="{{ route('searchCategoryPageGet') }}">Categories</a></li>
            <li style="display:inline-block"><a href="{{ route('searchPageGet') }}">Search</a></li>
        </ul>
    </nav>
    @yield('content')
</body>
</html>