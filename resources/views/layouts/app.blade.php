<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpus</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50 text-gray-800">
    <nav class="bg-white border-b shadow">
        <div class="container mx-auto flex justify-between items-center px-4 py-3">
            <a href="{{ route('user.home') }}" class="text-xl font-bold text-gray-900" href="#">Perpus</a>
            <div class="flex items-center gap-3">
                <!-- Link ke halaman Profile -->
                <a href="{{ route('profile') }}"
                    class="text-sm font-medium text-gray-700 hover:text-blue-500 transition">
                    <img src="{{ asset('images/profile.png') }}" alt="profile" class="rounded-full shadow w-10">
                </a>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="text-center mt-10 py-5 bg-white border-t">
        <p>&copy; 2024 Perpus. All rights reserved.</p>
    </footer>


    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                alert("{{ session('success') }}");
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                alert("{{ session('error') }}");
            });
        </script>
    @endif
</body>

</html>
