@extends('dashboard.Main.index')
@section('profile-content')
<section class="py-5 my-5">
    <div class="container">
        <h1 class="mb-5">Account Settings</h1>
        <div class="bg-white shadow rounded-lg d-block d-sm-flex">
            <div class="profile-tab-nav border-right">
                <div class="p-4">
                    <div class="img-circle text-center mb-3">
                        @if(Auth::user()->image)
                            <img src="/{{Auth::user()->image}}" alt="User Image" width="300px" id="image">
                        @else
                            <span class="profile-image">{{substr(Auth::user()->name,0,1)}}</span>
                        @endif
                    </div>
                    <h4 class="text-center">{{strtoupper(Auth::user()->name)}}</h4>
                </div>
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="account-tab" data-toggle="pill" href="#account" role="tab" aria-controls="account" aria-selected="true">
                        <i class="fa fa-home text-center mr-1"></i>
                        Account
                    </a>
                    <a class="nav-link" id="password-tab" data-toggle="pill" href="#password" role="tab" aria-controls="password" aria-selected="false">
                        <i class="fa fa-key text-center mr-1"></i>
                        Password
                    </a>
                </div>
            </div>
            <div class="tab-content p-4 p-md-5" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
                    <h3 class="mb-4">Account Settings</h3>
                    <form action="{{ route('edit_profile',Auth::user()->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <strong>Username</strong>
                            <input type="text" id="name" name="name" class="form-control" value="{{ Auth::user()->name }}">
                        </div>
                        <div class="form-group">
                            <strong>Role:</strong>
                            <select name="roles" class="form-control" id="roles">
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ $userRole == $role ? 'selected' : '' }}>{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <strong>Profile Image:</strong>
                            <input type="file" name="image" class="form-control">
                            @if (Auth::user()->image)
                                <div class="image-container">
                                    <button type="button" class="btn btn-danger" id="delete_image_button" onclick="deleteImage()">X</button>
                                    <img src="/{{Auth::user()->image}}" alt="User Image" width="300px" id="image">
                                </div>
                            @else
                                <p>Image not Found</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Email</strong>
                            <input type="text" id="email" name="email" class="form-control" value="{{ Auth::user()->email }}">
                        </div>
                        <input hidden type="checkbox" name="delete_image" id="delete_image" value="0">
                        <div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                    <h3 class="mb-4">Password Settings</h3>
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
                    <form id="set_password_form" action="{{route('set_password')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                      <strong>Old password</strong>
                                      <input type="password" id="old_password" name="old_password" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                      <strong>New password</strong>
                                      <input type="password" id="new_password" name="new_password" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                      <strong>Confirm new password</strong>
                                      <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    function deleteImage() {
        const deleteImageCheckbox = document.getElementById('delete_image');
        deleteImageCheckbox.checked = true;

        const imageContainer = document.querySelector('.image-container');
        if (imageContainer) {
            imageContainer.remove();
        }
    }
    document.addEventListener('DOMContentLoaded', function () {
        document.addEventListener('submit', function (event) {
            if (event.target.id === 'set_password_form') {
            event.preventDefault(); // Prevent form submission

            // Perform form submission using Axios
            axios.post(event.target.action, new FormData(event.target))
                .then(function (response) {
                // Handle successful form submission
                swal("Updated!", "Profile has been successfully updated.", "success");
                event.target.submit();
                })
                .catch(function (error) {
                // Handle form submission error
                swal("Error!", "An error occurred while updating the profile.", "error");
                });
            }
        });
    });
</script>
@endsection
@section('styles')
    <style>
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

