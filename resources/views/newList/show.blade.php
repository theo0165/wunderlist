@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-3">
            <h1>New list</h1>
            <div class="row">
                <form action="{{ route('newList.store') }}" method="POST">
                    @csrf

                    <div class="form-group row pt-3 required">
                        <label for="title" class="col-4 col-form-label">Title</label>
                        <div class="col-8">
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title" value="{{ old('title') }}" placeholder="My awesome list" autofocus>
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

<!--
Needed:
Title
Save
-->
