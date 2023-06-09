@extends('user.layout')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit User</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('user.index') }}"> Back</a>
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

    <form action="{{ route('user.update',$user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Username:</strong>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Profile Image:</strong>
                    <input type="file" name="image" class="form-control">
                    @if ($user->image)
                        <div class="image-container">
                            <button type="button" class="btn btn-danger" id="delete_image_button" onclick="deleteImage()">X</button>
                            <img src="/{{$user->image}}" alt="User Image" width="300px" id="image">
                        </div>
                    @else
                        <p>Image not Found</p>
                    @endif
                </div>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-5">
                <div class="form-group">
                    <strong>Role:</strong>
                    <select name="roles" class="form-control" id="roles">
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ $userRole == $role ? 'selected' : '' }}>{{ $role }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Email:</strong>
                    <input type="text" name="email" value="{{ $user->email }}" class="form-control">
                </div>
            </div>
            <input hidden type="checkbox" name="delete_image" id="delete_image" value="0">
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
    <script>
        function deleteImage() {
            const deleteImageCheckbox = document.getElementById('delete_image');
            deleteImageCheckbox.checked = true;

            const imageContainer = document.querySelector('.image-container');
            if (imageContainer) {
                imageContainer.remove();
            }
        }
    </script>
@endsection




