@extends('product.layout')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Product</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('product.index') }}"> Back</a>
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

    <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" class="form-control" value="{{$product->name}}">
                </div>
                <div class="form-group">
                    <strong>Brand:</strong>
                    <input type="text" name="brand" class="form-control" value="{{$product->brand}}">
                </div>
                <div class="form-group">
                    <strong>Code:</strong>
                    <input type="text" name="code" class="form-control" value="{{$product->code}}">
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Image:</strong>
                        <input type="file" name="image" class="form-control" placeholder="image">
                        @if ($product->image)
                            <img src="/{{$product->image}}" alt="Product Image" width="300px">
                        @else
                            <p>Image not Found</p>
                        @endif
                    </div>
                </div>
                @if($product->image)
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Delete Image:</strong>
                        <input type="checkbox" name="delete_image" value="1">
                    </div>
                </div>
                @endif
                <div class="form-group">
                    <strong>Price:</strong>
                    <input type="number" name="price" class="form-control" value="{{$product->price}}">
                </div>
                <div class="form-group">
                    <strong>Description:</strong>
                    <input type="text" name="description" class="form-control" value="{{$product->description}}">
                </div>
                <div class="form-group">
                    <strong>Quantity:</strong>
                    <input type="number" name="quantity" class="form-control" value="{{$product->quantity}}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Status:</strong>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="active" value="1" {{ $product->status == 1 ? 'checked' : '' }} required>
                        <label class="form-check-label" for="active">
                            Active
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="inactive" value="0" {{ $product->status == 0 ? 'checked' : '' }} required>
                        <label class="form-check-label" for="inactive">
                            Inactive
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Category Name:</strong>
                    <input
                     type="text" name="cat_name" class="form-control" value="{{$product->cat_name}}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection
