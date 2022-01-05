<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/form.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container pt-5">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-6">
                <h1>Reset Password</h1>
                <p>Click <a href="{{ route('password.show', $token) }}">here</a> to reset your password.</p>
                <p>Or paste this link to your browser:</p>
                <a href="{{ route('password.show', $token) }}" style="overflow-wrap: anywhere;">{{ route('password.show', $token) }}</a>
            </div>
        </div>
    </div>
</body>
</html>
