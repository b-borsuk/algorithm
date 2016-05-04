<!DOCTYPE html>
<html>
    <head>
        @if (!empty($active_project))
            <title>{{ $active_project['name'] }}</title>
        @else
            <title>Algo</title>
        @endif

        <base href="{{ url('/') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <link rel="stylesheet" type="text/css" href="/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="/css/c3.css">
        <link rel="stylesheet" type="text/css" href="/css/app.css">
    </head>
    <body>
        @include('header')

        <div class="container">
            @if (count($errors) > 0)
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        {{ $error }}
                    </div>
                @endforeach
            @endif

            @if (session('messages_success'))
                @foreach (session('messages_success') as $message)
                    <div class="alert alert-success" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        {{ $message }}
                    </div>
                @endforeach
            @endif

            @yield('content')

        </div>

        <script src="/js/jquery.min.js"></script>
        <script src="/js/bootstrap.js"></script>
        <script src="/js/d3.min.js" charset="utf-8"></script>
        <script src="/js/c3.js"></script>
        <script src="/js/app.js"></script>
    </body>
</html>
