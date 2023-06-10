@extends('customer.layout')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit customer</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('customer.index') }}"> Back</a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customer.update',$customer->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-xs-5 col-sm-5 col-md-5">
                <div class="form-group">
                    <strong>First Name:</strong>
                    <input type="text" name="first_name" value="{{ $customer->first_name }}" class="form-control" placeholder="First Name">
                </div>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5">
                <div class="form-group">
                    <strong>Last Name:</strong>
                    <input type="text" name="last_name" value="{{ $customer->last_name }}" class="form-control" placeholder="Last Name">
                </div>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5">
                <div class="form-group">
                    <strong>Email:</strong>
                    <input type="email" name="email" value="{{ $customer->email }}" class="form-control" placeholder="Email">

                </div>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5">
                <div class="form-group">
                    <strong>Phone No:</strong>
                    <input type="tel" name="phone_no" value="{{ $customer->phone_no }}" class="form-control" placeholder="Phone No">

                </div>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5">
                <div class="form-group">
                    <strong>address:</strong>
                    <input type="text" name="address" value="{{ $customer->address }}" class="form-control" placeholder="Address">

                </div>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5">
                <div class="form-group">
                    <strong>Country:</strong>
                    <select name="country" class="form-control" id="country" value="{{ $customer->country }}">
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5">
                <div class="form-group">
                    <strong>State:</strong>
                    <select name="state" class="form-control" id="state"  value="{{ $customer->state }}">

                    </select>
                </div>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5">
                <div class="form-group">
                    <strong>City:</strong>
                    <select name="city" class="form-control" id="city"  value="{{ $customer->city }}">

                    </select>
                </div>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5">
                <div class="form-group">
                    <strong>Postal Code</strong>
                    <input type="number" name="postal_code" value="{{ $customer->postal_code }}" class="form-control" placeholder="Postal Code">
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
@endsection
@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        // Fetch states based on the selected country
        $('#country').on('change', function() {
            var idCountry = this.value;
            $("#state").html('');
            $.ajax({
                url: "{{ route('getStatesByCountry') }}",
                type: "POST",
                data: {
                    cid: idCountry,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    $('#state').html('<option value="">-- Select State --</option>');
                    $.each(result.states, function(key, value) {
                        $("#state").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    $('#city').html('<option value="">-- Select City --</option>');
                }
            });
        });

        // Fetch cities based on the selected state
        $('#state').change(function () {
            var stateId = $(this).val();
            if (stateId) {
                $.ajax({
                    url: '{{ route('cities.getCitiesByState') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        state_id: stateId
                    },
                    success: function (data) {
                        $('#city').html('<option value="">Select City</option>');
                        $.each(data.cities, function (key, value) {
                            $('#city').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#city').html('<option value="">Select City</option>');
            }
        });
    });
</script>
@endpush
