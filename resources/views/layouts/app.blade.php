<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar --}}
            @include('partials.sidebar')

            {{-- Main Content --}}
            <div class="p-4" style="margin-left:16.6667%; width:83.3333%;">
                @yield('content')
            </div>
        </div>
    </div>
</body>

</html>
