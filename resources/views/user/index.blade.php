@extends('user.layout')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>User Data</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('user.create') }}"> Create New User</a>
            </div>
        </div>
    </div>
    {{-- <form action="{{ route('user.index') }}" method="GET">

        <div class="form-group">
            <input type="text" name="search" id="" class="form-control" placeholder="Search by name" value="{{$search}}">
            <button class="btn btn-primary">Search</button>
        <a href="{{url('/user')}}">
            <button type="button" class="btn btn-primary">Reset</button>
        </a>
        </div>

    </form> --}}
    {{-- <table class="table" id="table">
        <thead>
            <tr>
                <th class="text-center">No.</th>
                <th class="text-center">Username</th>
                <th class="text-center">Email</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="{{$user->id}}">
                <td class="text-center">{{++$i}}</td>
                <td class="text-center">{{$user->name}}</td>
                <td class="text-center">{{$user->email}}</td>
                <td class="text-center">
                    <form action="{{ route('user.destroy',$user->id) }}" method="POST">

                        <a class="btn btn-info" href="{{ route('user.show',$user->id) }}">Show</a>

                        <a class="btn btn-primary" href="{{ route('user.edit',$user->id) }}">Edit</a>

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
    </table>
    {!! $users->links() !!} --}}

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <table id="userTable" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {

          dtable = $('#userTable').DataTable({
              "language": {
                  "lengthMenu": "_MENU_",
              },
              "columnDefs": [ {
                "targets": "_all",
                "orderable": false
              } ],
              responsive: true,
              'serverSide': true, // Feature control DataTables' server-side processing mode.

              "ajax": {
                "url": "{{route('getUsers')}}",
                "type": "POST",
                "data" :function ( data ) {
                    data._token = $('meta[name="csrf_token"]').attr('content');
                },
                "error":function(xhr,error,thrown){
                    console.log("Ajax error:",thrown);
                }
              }
          });

          $('.panel-ctrls').append("<i class='separator'></i>");

          $('.panel-footer').append($(".dataTable+.row"));
          $('.dataTables_paginate>ul.pagination').addClass("pull-right");

          $("#apply_filter_btn").click(function()
          {
            dtable.ajax.reload(null,false);
          });
        });

      </script>


@endsection

