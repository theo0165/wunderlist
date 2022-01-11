@extends('layouts.app')

@section('scripts')
    <script src="{{ asset('js/profile.js') }}"></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-10">
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <img class="rounded-circle" src="{{ $user->profilePicture() }}" style="width:200px;height:200px;object-fit:cover;">
                </div>
            </div>
            <div class="row">
                <div class="col-12 d-flex justify-content-center pt-4">
                    <h3>{{ $user->name }}</h3>
                </div>
            </div>
            <hr class="mb-4 mt-4">
            <div class="row">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <form action="{{ route('profile.patch') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method("PATCH")
                    <h4>Update user information</h4>
                    <div class="form-group row pt-3">
                        <label for="name" class="col-4 col-form-label">Name</label>
                        <div class="col-8">
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name') ?? $user->name }}">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row pt-3">
                        <label for="email" class="col-4 col-form-label">Email</label>
                        <div class="col-8">
                            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email') ?? $user->email }}">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row pt-3">
                        <label for="profile_picture" class="col-4 col-form-label">Profile picture</label>
                        <div class="col-8">
                            <input type="file" name="profile_picture" class="form-control @error('profile_picture') is-invalid @enderror" id="profile_picture">
                            @error('profile_picture')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3" name="user_update">Update</button>
                </form>
                <form action="/profile/update" method="POST" class="pt-5">
                    @csrf
                    @method("PATCH")
                    <h4>Update password</h4>
                    <div class="form-group row pt-3">
                        <label for="oldpassword" class="col-4 col-form-label">Old password</label>
                        <div class="col-8">
                            <input type="password" name="oldpassword" class="form-control @error('oldpassword') is-invalid @enderror" id="oldpassword">
                            @error('oldpassword')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row pt-3">
                        <label for="newpassword" class="col-4 col-form-label">New password</label>
                        <div class="col-8">
                            <input type="password" name="newpassword" class="form-control @error('newpassword') is-invalid @enderror" id="newpassword">
                            @error('newpassword')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row pt-3">
                        <label for="newpassword_confirmation" class="col-4 col-form-label">Confirm new password</label>
                        <div class="col-8">
                            <input type="password" name="newpassword_confirmation" class="form-control @error('newpassword_confirmation') is-invalid @enderror" id="newpassword_confirmation">
                            @error('newpassword_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3" name="password_update">Update</button>
                </form>
                <div class="stats pt-5">
                    <h4>Statistics</h4>
                    <div class="row pt-3">
                        <div class="col-4">Number of lists</div>
                        <div class="col-8">{{ $stats['lists'] }}</div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-4">Completed items</div>
                        <div class="col-8">{{ $stats['completed'] }}</div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-4">Uncompleted items</div>
                        <div class="col-8">{{ $stats['uncompleted'] }}</div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-4">Account created</div>
                        <div class="col-8">{{ $user->created_at->format('j F Y') }}</div>
                    </div>
                </div>
                <div class="actions pt-5">
                    <a class="btn btn-danger" id="delete-btn" role="button" href="{{ route('profile.delete') }}">Delete account</a>
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
