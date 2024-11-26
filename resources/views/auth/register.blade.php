<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Register</title>
</head>

<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-2">Register</h2>
        <p class="text-gray-500 text-sm mb-6">Lorem ipsum dolor sit amet, adipiscing elit.</p>
        @if (session('msg'))
            <div class="text-blue-500 text-sm">
                {{ session('msg') }}
            </div>
        @endif
        <form action="{{ route('auth.register') }}" method="POST" class="space-y-2">
            @csrf
            <div>
                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required>
            </div>

            <div>
                <input type="text" id="no_identitas" name="no_identitas" value="{{ old('no_identitas') }}"
                    placeholder="No Identitas"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required>
            </div>

            <div>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Email"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required>
            </div>

            <div class="mb-4 relative">
                <input type="password" id="password" name="password" placeholder="Password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required>
                <button type="button" class="absolute right-2 top-2.5 text-blue-500 text-sm"
                    onclick="togglePasswordVisibility()">
                    Show
                </button>
            </div>

            <div class="mb-4 relative">
                <input type="password" id="password_confirmation" name="password_confirmation"
                    placeholder="Confirm Password"
                    class="w-full px-3 py-2 mb-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required>
                <button type="button" class="absolute right-2 top-2.5 text-blue-500 text-sm"
                    onclick="toggleConfirmPasswordVisibility()">
                    Show
                </button>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">
                Register
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-4">
            Already have an account? <a href="/" class="text-blue-500 hover:underline">Login</a>
        </p>
    </div>
    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
        }

        function toggleConfirmPasswordVisibility() {
            const passwordField = document.getElementById('password_confirmation');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
        }
    </script>
</body>

</html>
