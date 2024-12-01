@extends('layouts.appStaff')

@section('content')
    <div class="max-w-7xl mx-auto p-6">
        <!-- Profile Information -->
        <div class="bg-white shadow sm:rounded-lg mb-8">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/profile.png') }}" alt="Profile Image" class="w-16 h-16 rounded-full">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ Auth::user()->name }}</h3>
                        <p class="text-sm text-gray-500">{{ Auth::user()->no_identitas }}</p>
                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                        <p class="text-sm text-gray-500">Role: <span class="font-semibold">{{ Auth::user()->role }}</span></p>
                    </div>
                </div>
                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-700 mt-4 inline-block px-6 py-2 text-white rounded-md">
                        Logout
                    </button>
                </form>
            </div>

        </div>
    </div>

@endsection
