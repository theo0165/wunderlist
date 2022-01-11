@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-4 col-md-6 col-10">
            <h1>Edit task</h1>
            <div class="row">
                <form action="{{ route('task.patch', $task->getHashId()) }}" method="POST">
                    @csrf
                    <input type="hidden" name="function" value="edit">

                    <div class="form-group row pt-3 required">
                        <label for="title" class="col-4 col-form-label">Title</label>
                        <div class="col-8">
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title" value="{{ old('title') ?? $task->title }}" placeholder="My awesome task" autofocus>
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
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" placeholder="Task description">{{ old('description') ?? $task->description }}</textarea>
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
                            @if (old('deadline'))
                                <input type="date" name="deadline" class="form-control @error('deadline') is-invalid @enderror" id="deadline" value="{{ old('deadline') }}" min="{{ date('Y-m-d') }}">
                            @elseif($task->deadline === null || $task->deadline === "null")
                                <input type="date" name="deadline" class="form-control @error('deadline') is-invalid @enderror" id="deadline" min="{{ date('Y-m-d') }}">
                            @else
                                <input type="date" name="deadline" class="form-control @error('deadline') is-invalid @enderror" id="deadline" value="{{ date('Y-m-d', strtotime($task->deadline)) }}" min="{{ date('Y-m-d') }}">
                            @endif

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
                                    <option value="{{ $list->getHashId() }}" {{ ($task->list_id === $list->id) ? "selected" : "" }}>{{$list->title}}</option>
                                @endforeach
                            </select>
                            @error('list')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
