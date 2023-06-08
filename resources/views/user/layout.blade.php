@extends('dashboard.Main.index')
@section('user-content')
<!DOCTYPE html>
<html>
<head>
    <title>User Table</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    @yield('content')
</div>
<script>
    $(document).ready(function() {
      $('#table').DataTable();
  } );
</script>
@endsection
</body>
</html>
