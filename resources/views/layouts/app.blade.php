<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Parkir')</title>
    @stack('styles')
    @livewireStyles
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        @if(auth()->check())
            @include('partials.header')
        @endif
        
        <div class="container mx-auto px-4 py-8">
            @yield('content')
        </div>
        
        @include('partials.footer')
    </div>
    
    @stack('scripts')
    @livewireScripts
</body>
</html>