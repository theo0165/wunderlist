@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="row">
                <div class="col-4 d-flex justify-content-center">
                    <img class="rounded-circle" src="{{ $user->profile_picture ?? "https://via.placeholder.com/100x100" }}" style="width:100px;object-fit:cover;">
                </div>
                <div class="col-8 d-flex align-items-center">
                    <h3>{{ $user->name }}</h3>
                </div>
            </div>
            <hr>
            <div class="row">
                <form action="" action="POST" enctype="multipart/form-data">
                    <h4>Update user information</h4>
                    <div class="form-group row pt-3">
                        <label for="name" class="col-4 col-form-label">Name</label>
                        <div class="col-8">
                            <input type="text" class="form-control" id="name" value="{{ $user->name }}">
                        </div>
                    </div>
                    <div class="form-group row pt-3">
                        <label for="email" class="col-4 col-form-label">Email</label>
                        <div class="col-8">
                            <input type="text" class="form-control" id="email" value="{{ $user->email }}">
                        </div>
                    </div>
                    <div class="form-group row pt-3">
                        <label for="profile_picture" class="col-4 col-form-label">Profile picture</label>
                        <div class="col-8">
                            <input type="file" class="form-control" id="profile_picture">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Update</button>
                </form>
                <form action="" action="POST" class="pt-5">
                    <h4>Update password</h4>
                    <div class="form-group row pt-3">
                        <label for="oldpassword" class="col-4 col-form-label">Old password</label>
                        <div class="col-8">
                            <input type="text" class="form-control" id="oldpassword">
                        </div>
                    </div>
                    <div class="form-group row pt-3">
                        <label for="newpassword" class="col-4 col-form-label">New password</label>
                        <div class="col-8">
                            <input type="text" class="form-control" id="newpassword">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Update</button>
                </form>
                <div class="stats pt-5">
                    <h4>Statistics</h4>
                    <div class="row pt-3">
                        <div class="col-4">Number of lists</div>
                        <div class="col-8">123</div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-4">Completed items</div>
                        <div class="col-8">1</div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-4">Uncompleted items</div>
                        <div class="col-8">2</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!--
Needed:

Profile image
Name
Email
Number of lists?
Completed/uncompleted items
Change password
-->
