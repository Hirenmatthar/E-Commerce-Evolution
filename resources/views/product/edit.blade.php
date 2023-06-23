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
    <form action="{{ route('product.image.store', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="image">Product Image</label>
            <input type="file" name="image" id="image" class="form-control" accept="image" multiple>
            @error('image')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Upload Image</button>
    </form>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Product Images:</strong>
            @foreach($productImages as $image)
            <form action="{{ route('delete.image', ['image' => $image->id]) }}" method="POST">
                <button class="btn btn-danger">X</button>
                @csrf
                @method('DELETE')
            </form>
            <div class="image-container">
                <img src="{{ asset($image['product_img']) }}" id="other_image" alt="Product Image" width="200px">
            </div>

            @endforeach
        </div>
    </div>
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
                        <strong>Thumbnail:</strong>
                        <input type="file" name="thumbnail" class="form-control">
                        @if ($product->thumbnail)
                            <div class="thumbnail-container">
                                <button type="button" class="btn btn-danger" id="delete_thumbnail_button" onclick="deleteThumbnail()">X</button>
                                <img src="/{{$product->thumbnail}}" alt="Product Thumbnail" width="300px" id="thumbnail_image">
                            </div>
                        @else
                            <p>Thumbnail not Found</p>
                        @endif
                    </div>
                </div>
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
                        <input class="form-check-input" type="radio" name="status" id="active" value="1" {{ $product->status == 1 ? 'checked' : '' }} >
                        <label class="form-check-label" for="active">
                            Active
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="inactive" value="0" {{ $product->status == 0 ? 'checked' : '' }}>
                        <label class="form-check-label" for="inactive">
                            Inactive
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <strong>Category Name:</strong>
                <select name="cat_name" class="form-control" id="category">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id = $product->cat_id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Add the following line after your existing form fields -->
            <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>

<script>
    function deleteThumbnail() {
        const deleteThumbnailCheckbox = document.getElementById('delete_thumbnail');
        deleteThumbnailCheckbox.checked = true;

        const thumbnailContainer = document.querySelector('.thumbnail-container');
        if (thumbnailContainer) {
            thumbnailContainer.remove();
        }
    }
</script>
@endsection
@section('styles')
    <style>
        .thumbnail-container {
            position: relative;
            display: inline-block;
        }

        .thumbnail-container img {
            width: 300px;
        }

        .thumbnail-container button {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .image-container {
            position: relative;
            display: inline-block;
        }

        .image-container img {
            width: 300px;
        }

        .image-container button {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
@endsection
