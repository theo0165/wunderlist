@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <p>Click <a href="{{ route('password.show', $token) }}">here</a> to reset your password.</p>
            <br>
            <p>Or paste this link to your browser:</p>
            <br>
            <p>{{ route('password.show', $token) }}</p>
        </div>
    </div>
</div>
@endsection
