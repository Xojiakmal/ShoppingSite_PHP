<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin panel</title>
    <style>
        .sidebar {
            display: inline-block;
            width: 200px;
            border-right: 3px solid;
        }
        .section-body{
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>Sidebar</h3>
        <ul>
            <li><a href="{{ route('adminDashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('adminShowAllUsersGet') }}">Users</a></li>
            <li><a href="{{ route('adminShowAllCategoriesGet') }}">Categories</a></li>
            <li><a href="{{ route('adminShowAllProductsGet') }}">Products</a></li>
            <li><a href="{{ route('adminShowAllStorageGet') }}">Storage</a></li>
        </ul>
    </div>
    <div class="section-body">
    @yield('section')
    </div>
</body>
</html>