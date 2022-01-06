@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/list.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/list.js') }}"></script>
@endsection

@section('content')
<div class="container d-flex justify-content-center">
    <div class="col-6">
        <div class="row">
            <h1>Deadline today</h1>
        </div>
        <hr class="m-1">
        <div class="col-12 pt-4">
            @if (count($tasks) === 0)
                <h4 class="pt-3 d-flex justify-content-center">No tasks with a deadline today.</h4>
            @else
                @foreach ($tasks as $task)
                    <div class="row mb-3 pb-3 task-container {{ $task->completed ? "completed" : "" }}">
                        <div class="col-8 d-flex align-items-center">
                            <div class="row left-container">
                                <div class="col-2 pr-0 d-flex justify-content-center align-items-center checkbox-container">
                                    <form action="/task/{{ $task->uuid }}/edit" method="POST" class="complete-form">
                                        @csrf
                                        <input type="hidden" name="uuid" value="{{ Hashids::encode($task->id) }}">
                                        <input type="hidden" name="function" value="complete">
                                        <input type="checkbox" name="completed" {{ $task->completed ? "checked" : "" }}>
                                    </form>
                                </div>
                                <div class="col-10 pl-0 task-details">
                                    <h5 class="task-name d-inline-block mb-0">{{ $task->title }}</h5>
                                    @if ($task->description != null)
                                        <p class="task-description mb-0 mt-1">{{ $task->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-4 pr-0 d-flex align-items-center justify-content-end">
                            @if ($task->deadline != null)
                                <p class="task-deadline d-inline-block mb-0">{{ date("d/m/y", strtotime($task->deadline)) }}</p>
                            @endif
                            <div class="task-actions d-inline-block d-flex align-items-center">
                                <a href="/task/{{Hashids::encode($task->id)}}/delete">
                                    <svg class="task-delete" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3.75 18.4375C3.74813 18.6839 3.84404 18.9209 4.01671 19.0967C4.18937 19.2724 4.42469 19.3725 4.67105 19.375H15.3289C15.5753 19.3725 15.8106 19.2724 15.9833 19.0967C16.156 18.9209 16.2519 18.6839 16.25 18.4375V5.9375H3.75V18.4375ZM5 7.1875H15V18.125H5V7.1875Z" fill="currentColor"/>
                                        <path d="M6.5625 8.4375H7.8125V16.25H6.5625V8.4375Z" fill="currentColor"/>
                                        <path d="M9.375 8.4375H10.625V16.25H9.375V8.4375Z" fill="currentColor"/>
                                        <path d="M12.1875 8.4375H13.4375V16.25H12.1875V8.4375Z" fill="currentColor"/>
                                        <path d="M12.8125 3.4375V1.5625C12.8125 1.0368 12.4419 0.625 11.9687 0.625H8.03125C7.55813 0.625 7.1875 1.0368 7.1875 1.5625V3.4375H2.5V4.6875H17.5V3.4375H12.8125ZM8.4375 1.875H11.5625V3.4375H8.4375V1.875Z" fill="currentColor"/>
                                    </svg>
                                </a>
                                <a href="/task/{{ $task->uuid }}/edit">
                                    <svg class="task-edit" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13.7283 4.50667L15.4933 6.27083L13.7283 4.50667ZM14.8633 2.9525L10.0908 7.725C9.84424 7.97125 9.67606 8.28499 9.6075 8.62667L9.16666 10.8333L11.3733 10.3917C11.715 10.3233 12.0283 10.1558 12.275 9.90917L17.0475 5.13667C17.1909 4.99325 17.3047 4.823 17.3823 4.63562C17.4599 4.44824 17.4999 4.2474 17.4999 4.04458C17.4999 3.84177 17.4599 3.64093 17.3823 3.45355C17.3047 3.26617 17.1909 3.09592 17.0475 2.9525C16.9041 2.80909 16.7338 2.69532 16.5464 2.61771C16.3591 2.54009 16.1582 2.50014 15.9554 2.50014C15.7526 2.50014 15.5518 2.54009 15.3644 2.61771C15.177 2.69532 15.0067 2.80909 14.8633 2.9525V2.9525Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M15.8333 12.5V15C15.8333 15.442 15.6577 15.866 15.3452 16.1785C15.0326 16.4911 14.6087 16.6667 14.1667 16.6667H5C4.55797 16.6667 4.13405 16.4911 3.82149 16.1785C3.50893 15.866 3.33333 15.442 3.33333 15V5.83333C3.33333 5.39131 3.50893 4.96738 3.82149 4.65482C4.13405 4.34226 4.55797 4.16667 5 4.16667H7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
