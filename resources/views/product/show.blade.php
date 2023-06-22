@extends('product.layout')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Show Product</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('product.index') }}"> Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                {{ $product->name }}
            </div>
            <div class="form-group">
                <strong>Brand:</strong>
                {{ $product->brand }}
            </div>
            <div class="form-group">
                <strong>Code:</strong>
                {{ $product->code }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Thumbnail:</strong>
                <img src="../{{ $product->thumbnail }}" width="500px">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Price:</strong>
                {{ $product->price }}
            </div>
            <div class="form-group">
                <strong>Description:</strong>
                {{ $product->description }}
            </div>
            <div class="form-group">
                <strong>Quantity:</strong>
                {{ $product->quantity }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Status:</strong>
                <div>
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="status" value="1" {{ $product->status == 1 ? 'checked' : '' }}>
                        Active
                    </label>
                </div>
                <div>
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="status" value="0" {{ $product->status == 0 ? 'checked' : '' }}>
                        Inactive
                    </label>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Category Name:</strong>
                {{ $product->category->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Uploaded Images:</strong>
                <div>
                    @foreach ($productImages as $image)
                        @php
                            $imagePaths = explode(',', $image->product_img);
                        @endphp
                        @foreach ($imagePaths as $imagePath)
                            <img src="/{{ $imagePath }}" width="200px" style="margin-right: 10px;">
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
