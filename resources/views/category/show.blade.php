@extends('category.layout')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Show Category</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('category.index') }}"> Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                {{ $category->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Status:</strong>
                <div>
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="status" value="1" {{ $category->status == 1 ? 'checked' : '' }}>
                        Active
                    </label>
                </div>
                <div>
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="status" value="0" {{ $category->status == 0 ? 'checked' : '' }}>
                        Inactive
                    </label>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Image:</strong>
                <img src="../{{$category->image}}" width="500px">
            </div>
        </div>
    </div>
@endsection
