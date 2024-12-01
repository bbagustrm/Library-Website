<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50 text-gray-800">
    <nav class="bg-white border-b">
        <div class="container mx-auto flex justify-between items-center px-4 py-3">
            <a href="{{ route('staff.dashboard') }}" class="text-xl font-bold text-gray-900" href="#">Perpus</a>
            <div class="flex items-center gap-3">
                <!-- Link ke halaman Profile -->
                <a href="{{ route('staff.profile') }}"
                    class="text-sm font-medium text-gray-700 hover:text-blue-500 transition">
                    <img src="{{ asset('images/profile.png') }}" alt="profile" class="rounded-full w-10">
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
