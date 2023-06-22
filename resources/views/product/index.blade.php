@extends('product.layout')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Product Data</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('product.create') }}"> Create New Product</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="productTable" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Brand</th>
                                <th>Code</th>
                                <th>Thumbnail</th>
                                <th>Price</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Category Name</th>
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

          dtable = $('#productTable').DataTable({
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
                "url": "{{route('getProducts')}}",
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
