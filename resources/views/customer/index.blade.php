@extends('customer.layout')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Customer Data</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('customer.create') }}"> Create New Customer</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="customerTable" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Phone No</th>
                                <th>Address</th>
                                <th>Country</th>
                                <th>State</th>
                                <th>City</th>
                                <th>Postal Code</th>
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

          dtable = $('#customerTable').DataTable({
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
                "url": "{{route('getCustomers')}}",
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
