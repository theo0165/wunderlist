@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-9">
            <h1>New task</h1>
            <div class="row">
                @if (count($lists) === 0)
                    <h4>Please create a todo list <a href="{{ route('newList.show') }}">here</a> before creating a task.</h4>
                @else
                    <form action="{{ route('newTask.store') }}" method="POST">
                        @csrf

                        <div class="form-group row pt-3 required">
                            <label for="title" class="col-4 col-form-label">Title</label>
                            <div class="col-8">
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title" value="{{ old('title') }}" placeholder="My awesome task" autofocus>
                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row pt-3">
                            <label for="description" class="col-4 col-form-label">Description</label>
                            <div class="col-8">
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" placeholder="Task description">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row pt-3">
                            <label for="deadline" class="col-4 col-form-label">Deadline</label>
                            <div class="col-8">
                                <input type="date" name="deadline" class="form-control @error('deadline') is-invalid @enderror" id="deadline" value="{{ old('deadline') }}" min="{{ date('Y-m-d') }}">
                                @error('deadline')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row pt-3 required">
                            <label for="list" class="col-4 col-form-label">List</label>
                            <div class="col-8">
                                <select name="list" id="list" class="form-control @error('list') is-invalid @enderror">
                                    @foreach ($lists as $list)
                                        <option value="{{ $list->getHashId() }}" {{ (isset($selectedList) && $selectedList === $list->getHashId()) ? "selected" : "" }}>{{$list->title}}</option>
                                    @endforeach
                                </select>
                                @error('list')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Create</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
