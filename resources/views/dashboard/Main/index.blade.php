@if (session('username')==NULL)
        <script>window.location.href = '/admin/login';</script>
@endif
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title>Ecommerce Admin Panel</title>
        <!-- Favicon-->
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href={{asset('assets-1/css/style1.css')}} rel="stylesheet" />
        <style>
            .profile-image {
                display: inline-block;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background-color: #262121;
                color: #ff6600;
                text-align: center;
                font-size: 18px;
                line-height: 40px;
                margin-right: 10px;
                font-weight: bold;
                font-family: 'Arial', sans-serif;
                text-transform: uppercase;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                transition: background-color 0.3s ease, color 0.3s ease;
            }

            .profile-image:hover {
                background-color: #ff6600;
                color:dodgerblue;
            }
        </style>
        {{-- Profile CSS --}}
        <link rel="stylesheet" type="text/css" href={{asset('profile/img')}}>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href={{asset('profile/css/style.css')}}>
    </head>
    <body>
        <div class="d-flex" id="wrapper">
            <!-- Sidebar-->
            @include('dashboard.Components.sidebar')
            <!-- Page content wrapper-->
            <div id="page-content-wrapper">
                <!-- Top navigation-->
                @include('dashboard.Components.header')
                <!-- Page content-->
                @yield('category-content')
                @yield('user-content')
                @yield('profile-content')
            </div>
        </div>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src={{asset('assets-1/js/scripts.js')}}></script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://kit.fontawesome.com/d98a6653af.js" crossorigin="anonymous"></script>
    </body>
</html>
