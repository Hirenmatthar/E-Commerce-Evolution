@extends('role.layout')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Roles Data</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('role.create') }}"> Create New Role</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="roleTable" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Role Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">

    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $(document).ready(function() {

      dtable = $('#roleTable').DataTable({
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
            "url": "{{route('getRoles')}}",
            'beforeSend': function (request) {
              request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
          },
            "type": "POST",
            "data" :function ( data ) {

                },
          },
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
