@extends('category.layout')
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css"> --}}
<script src="//code.jquery.com/jquery-1.12.3.js"></script>
{{-- <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script> --}}
{{-- <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script> --}}
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Category Data</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('category.create') }}"> Create New Category</a>
            </div>
        </div>
    </div>
    <form action="{{ route('category.index') }}" method="GET">

        <div class="form-group">
            <input type="text" name="search" id="" class="form-control" placeholder="Search by name" value="{{$search}}">
            <button class="btn btn-primary">Search</button>
        <a href="{{url('/category')}}">
            <button class="btn btn-primary">Reset</button>
        </a>
        </div>

    </form>
    <table class="table" id="table">
        <thead>
            <tr>
                <th class="text-center">No.</th>
                <th class="text-center">Name</th>
                <th class="text-center">Status</th>
                <th class="text-center">Image</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr class="{{$category->id}}">
                <td class="text-center">{{++$i}}</td>
                <td class="text-center">{{$category->name}}</td>
                <td class="text-center">{{$category->status==1?'Active':'Inactive'}}</td>
                <td class="text-center"><img src="{{$category->image}}" width="100px"></td>
                <td class="text-center">
                    <form action="{{ route('category.destroy',$category->id) }}" method="POST">

                        <a class="btn btn-info" href="{{ route('category.show',$category->id) }}">Show</a>

                        <a class="btn btn-primary" href="{{ route('category.edit',$category->id) }}">Edit</a>

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
    </table>
    {!! $categories->links() !!}

@endsection
