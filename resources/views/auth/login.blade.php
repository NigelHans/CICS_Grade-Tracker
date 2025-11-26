<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CICS Grade Tracker - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-600 via-blue-500 to-cyan-400 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Floating card with glassmorphism effect -->
        <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl p-8 border border-white/20">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-block bg-gradient-to-br from-blue-600 to-cyan-500 p-3 rounded-full mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent mb-2">CICS Grade Tracker</h1>
                <p class="text-gray-500 text-sm">Welcome back! Please login to your account</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-6 animate-pulse">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-600 text-sm font-medium">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email Input -->
                <div class="group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-3.5 w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                            class="w-full pl-10 pr-4 py-3 bg-gradient-to-r from-blue-50 to-cyan-50 border-2 border-transparent rounded-lg focus:border-blue-500 focus:outline-none transition-all duration-300 placeholder-gray-400"
                            placeholder="you@g.batstate-u.edu.ph">
                    </div>
                </div>

                <!-- Password Input -->
                <div class="group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-3.5 w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <input type="password" name="password" required 
                            class="w-full pl-10 pr-4 py-3 bg-gradient-to-r from-blue-50 to-cyan-50 border-2 border-transparent rounded-lg focus:border-blue-500 focus:outline-none transition-all duration-300 placeholder-gray-400"
                            placeholder="Enter your password">
                    </div>
                </div>

                <!-- Login Button -->
                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl active:scale-95">
                    Sign In
                </button>
            </form>

            <!-- Divider -->
            <div class="flex items-center my-6">
                <div class="flex-1 border-t border-gray-300"></div>
                <span class="px-3 text-gray-400 text-xs">OR</span>
                <div class="flex-1 border-t border-gray-300"></div>
            </div>

            <!-- Sign Up Link -->
            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg p-4 text-center border border-blue-100">
                <p class="text-gray-600 text-sm mb-2">Don't have an account yet?</p>
                <a href="{{ route('register') }}" class="inline-block bg-gradient-to-r from-blue-600 to-cyan-500 text-white font-semibold px-6 py-2 rounded-lg hover:from-blue-700 hover:to-cyan-600 transition-all duration-300 transform hover:scale-105">
                    Create Account
                </a>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-white text-xs mt-8 opacity-75">Secure Login • BatState-U CICS</p>
    </div>
</body>
</html>