<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Login</title>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-sm">
        <!-- Title -->
        <h2 class="text-2xl font-semibold text-gray-800 mb-2">Login</h2>
        <p class="text-gray-500 text-sm mb-6">Lorem ipsum dolor sit amet, adipiscing elit.</p>

        <!-- Form -->
        <form action="{{ route('auth.verify') }}" method="post">
            @csrf
            <!-- Email Input -->
            <div class="mb-4">
                <input type="email" id="email" name="email"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Email" />
            </div>

            <!-- Password Input -->
            <div class="mb-4 relative">
                <input type="password" id="password" name="password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Password" />
                <button type="button" class="absolute right-2 top-2.5 text-blue-500 text-sm"
                    onclick="togglePasswordVisibility()">
                    Show
                </button>
                @if (session('msg'))
                    <div class="text-red-500 text-sm">
                        {{ session('msg') }}
                    </div>
                @endif
            </div>
            <!-- Sign In Button -->
            <button type="submit"
                class="w-full bg-blue-500 text-white py-2 rounded-md text-center hover:bg-blue-600 focus:ring-2 focus:ring-blue-300 focus:outline-none">
                Login
            </button>
        </form>

        <!-- Footer -->
        <p class="text-sm text-gray-500 text-center mt-6">
            Don't have an account? 
            <a href="{{ route('auth.registerForm') }}" class="text-blue-500 hover:underline">Register</a>
        </p>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
        }
    </script>
</body>

</html>
