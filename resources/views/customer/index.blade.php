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

    <table id='datatable' class="display">
        <thead>
            <tr align="left">
                <th>No</th>
                <th data-sortable="true">First Name</th>
                <th data-sortable="true">Last Name</th>
                <th data-sortable="false">Email</th>
                <th data-sortable="false">Phone No</th>
                <th data-sortable="false">Address</th>
                <th data-sortable="true">Country</th>
                <th data-sortable="true">State</th>
                <th data-sortable="true">City</th>
                <th data-sortable="false">Postal Code</th>
                <th width="200px">Action</th>
            </tr>
        </thead>
        <tbody></tbody>
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
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            processing:true,
            serverSide:true,
            order:[[0,"desc"]],
            ajax:"{{url('users-data')}}",
            columns:[
                {data:'id'},
                {data:'first_name'},
                {data:'last_name'},
                {data:'email'},
                {data:'phone_no'},
                {data:'address'},
                {data:'country'},
                {data:'state'},
                {data:'city'},
                {data:'postal_code'},
            ]
        });
    });
</script>
@endpush
