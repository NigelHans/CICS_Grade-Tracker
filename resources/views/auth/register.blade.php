<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CICS Grade Tracker - Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-600 via-blue-500 to-cyan-400 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-lg">
        <!-- Floating card with glassmorphism effect -->
        <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl p-8 border border-white/20">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-block bg-gradient-to-br from-blue-600 to-cyan-500 p-3 rounded-full mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent mb-2">Create Account</h1>
                <p class="text-gray-500 text-sm">Join CICS Grade Tracker and start tracking your grades</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-6 max-h-32 overflow-y-auto">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-600 text-sm font-medium">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Full Name -->
                <div class="group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                        class="w-full px-4 py-3 bg-gradient-to-r from-blue-50 to-cyan-50 border-2 border-transparent rounded-lg focus:border-blue-500 focus:outline-none transition-all duration-300 placeholder-gray-400"
                        placeholder="John Doe">
                </div>

                <!-- Email -->
                <div class="group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email (GSuite)</label>
                    <input type="email" name="email" value="{{ old('email') }}" required 
                        class="w-full px-4 py-3 bg-gradient-to-r from-blue-50 to-cyan-50 border-2 border-transparent rounded-lg focus:border-blue-500 focus:outline-none transition-all duration-300 placeholder-gray-400"
                        placeholder="you@g.batstate-u.edu.ph">
                </div>

                <!-- SR-Code -->
                <div class="group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">SR-Code</label>
                    <input type="text" name="sr_code" value="{{ old('sr_code') }}" required 
                        class="w-full px-4 py-3 bg-gradient-to-r from-blue-50 to-cyan-50 border-2 border-transparent rounded-lg focus:border-blue-500 focus:outline-none transition-all duration-300 placeholder-gray-400"
                        placeholder="23-12345">
                </div>

                <!-- Program -->
                <div class="group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Program</label>
                    <input type="text" name="program" value="{{ old('program') }}" required 
                        class="w-full px-4 py-3 bg-gradient-to-r from-blue-50 to-cyan-50 border-2 border-transparent rounded-lg focus:border-blue-500 focus:outline-none transition-all duration-300 placeholder-gray-400"
                        placeholder="BS Computer Science">
                </div>

                <!-- Year -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Year</label>
                        <select name="year" required class="w-full px-4 py-3 bg-gradient-to-r from-blue-50 to-cyan-50 border-2 border-transparent rounded-lg focus:border-blue-500 focus:outline-none transition-all duration-300">
                            <option value="">Select Year</option>
                            <option value="1st Year" {{ old('year') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                            <option value="2nd Year" {{ old('year') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                            <option value="3rd Year" {{ old('year') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                            <option value="4th Year" {{ old('year') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                        </select>
                    </div>

                    <!-- Campus -->
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Campus</label>
                        <input type="text" name="campus" value="{{ old('campus') }}" required 
                            class="w-full px-4 py-3 bg-gradient-to-r from-blue-50 to-cyan-50 border-2 border-transparent rounded-lg focus:border-blue-500 focus:outline-none transition-all duration-300 placeholder-gray-400"
                            placeholder="Main Campus">
                    </div>
                </div>

                <!-- Password -->
                <div class="group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" required 
                        class="w-full px-4 py-3 bg-gradient-to-r from-blue-50 to-cyan-50 border-2 border-transparent rounded-lg focus:border-blue-500 focus:outline-none transition-all duration-300 placeholder-gray-400"
                        placeholder="Minimum 6 characters">
                </div>

                <!-- Confirm Password -->
                <div class="group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" required 
                        class="w-full px-4 py-3 bg-gradient-to-r from-blue-50 to-cyan-50 border-2 border-transparent rounded-lg focus:border-blue-500 focus:outline-none transition-all duration-300 placeholder-gray-400"
                        placeholder="Re-enter your password">
                </div>

                <!-- Register Button -->
                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl active:scale-95 mt-6">
                    Create Account
                </button>
            </form>

            <!-- Login Link -->
            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg p-4 text-center border border-blue-100 mt-6">
                <p class="text-gray-600 text-sm mb-2">Already have an account?</p>
                <a href="/login" class="inline-block bg-gradient-to-r from-blue-600 to-cyan-500 text-white font-semibold px-6 py-2 rounded-lg hover:from-blue-700 hover:to-cyan-600 transition-all duration-300 transform hover:scale-105">
                    Sign In
                </a>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-white text-xs mt-8 opacity-75">Secure Registration • BatState-U CICS</p>
    </div>
</body>
</html>