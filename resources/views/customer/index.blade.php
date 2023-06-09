@extends('customer.layout')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>customer</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('customer.create') }}"> Create New customer</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    {{-- <form action="{{ route('customer.index') }}" method="GET">
        <div class="form-group">
            <input type="text" name="search" id="" class="form-control" placeholder="Search by name" value="{{ $search }}">
            <button class="btn btn-primary">Search</button>
            <a href="{{ url('/admin/customer') }}">
                <button type="button" class="btn btn-primary">Reset</button>
            </a>
        </div>
    </form> --}}
    <table id='empTable' width='100%' border="1" style='border-collapse: collapse;'>
        <thead>
        <tr>
            <th>No</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone No</th>
            <th>Address</th>
            <th>Country</th>
            <th>State</th>
            <th>City</th>
            <th>Postal Code</th>
            <th width="200px">Action</th>
        </tr>
        </thead>
            {{-- <tbody>
                @foreach($customers as $customer)
                <tr class="{{$customer->id}}">
                    <td>{{++$i}}</td>
                    <td>{{$customer->first_name}}</td>
                    <td>{{$customer->last_name}}</td>
                    <td>{{$customer->email}}</td>
                    <td>{{$customer->phone_no}}</td>
                    <td>{{$customer->address}}</td>
                    <td>{{$customer->country}}</td>
                    <td>{{$customer->state}}</td>
                    <td>{{$customer->city}}</td>
                    <td>{{$customer->postal_code}}</td>


                    <td>
                        <form action="{{ route('customer.destroy',$customer->id) }}" method="POST">

                            <a class="btn btn-info" href="{{ route('customer.show',$customer->id) }}"><i class="fa-solid fa-eye"></i> </a>

                            <a class="btn btn-primary" href="{{ route('customer.edit',$customer->id) }}"><i class="fa-solid fa-pen"></i></a>

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody> --}}
    </table>
    {{-- {!!$customers->links()!!} --}}
    <script type="text/javascript">
        $(document).ready(function(){

           // DataTable
           var empTable = $('#empTable').DataTable({
               processing: true,
               serverSide: true,
               ajax: {
                  url:"{{route('getCustomers')}}",
                  data: function(data){
                  }
               },
               columns: [
                  { data: 'first_name' },
                  { data: 'last_name' },
                  { data: 'email' },
                  { data: 'phone_no' },
                  { data: 'address' },
                  { data: 'country' },
                  { data: 'state' },
                  { data: 'city' },
                  { data: 'postal_code' },
               ]
           });

           $('#searchName').keyup(function(){
              empTable.draw();
           });

        });
        </script>

@endsection
