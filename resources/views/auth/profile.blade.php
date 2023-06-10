@extends('dashboard.Main.index')
@section('profile-content')
<section class="py-5 my-5">
    <div class="container">
        <h1 class="mb-5">Account Settings</h1>
        <div class="bg-white shadow rounded-lg d-block d-sm-flex">
            <div class="profile-tab-nav border-right">
                <div class="p-4">
                    <div class="img-circle text-center mb-3">
                        <span class="profile-image">{{substr(session('username'),0,1)}}</span>
                    </div>
                    <h4 class="text-center">{{strtoupper(session('username'))}}</h4>
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
                    <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                      <label>Username</label>
                                      <input type="text" id="username" name="name" class="form-control" value="{{ $user->username }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                      <label>Email</label>
                                      <input type="text" id="email" name="email" class="form-control" value="{{ $user->email }}">
                                </div>
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="submit" class="btn btn-light">Cancel</button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                    <h3 class="mb-4">Password Settings</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                  <label>Old password</label>
                                  <input type="password" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                  <label>New password</label>
                                  <input type="password" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                  <label>Confirm new password</label>
                                  <input type="password" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-primary">Update</button>
                        <button class="btn btn-light">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
