@extends('dashboard.Main.index')
@section('category-content')
<!DOCTYPE html>
<html>
<head>
    <title>Category Table</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        $(document).ready(function() {
          $('#table').DataTable();
      } );
    </script>
</head>
<body>
<div class="container">
    @yield('content')
</div>
@endsection
</body>
</html>
