<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CICS Grade Tracker - Lecturer Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex flex-col bg-gray-50">
    <!-- Main Content -->
    <div class="flex-1 flex items-center">
        <div class="w-full grid md:grid-cols-2 min-h-screen">
            <!-- Left Side - Login Form -->
            <div class="flex items-center justify-center p-8 bg-white">
                <div class="w-full max-w-md">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-blue-600 font-semibold text-lg mb-8">College of Informatics and<br>Computing Sciences</h2>
                    </div>

                    <!-- Login Form Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <div class="flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L9 5.414V18a1 1 0 102 0V5.414l6.293 6.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            <span class="text-blue-600 font-semibold">Lecturer Portal</span>
                        </div>
                        <h1 class="text-2xl font-bold text-blue-900 mb-6 text-center">Lecturer Login</h1>

                        <!-- Error Messages -->
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                                @foreach ($errors->all() as $error)
                                    <p class="text-red-600 text-sm">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <!-- Form -->
                        <form action="{{ route('lecturer.login.submit') }}" method="POST" class="space-y-5">
                            @csrf

                            <!-- Email Input -->
                            <div>
                                <label class="block text-sm text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Enter your email">
                            </div>

                            <!-- Password Input -->
                            <div>
                                <label class="block text-sm text-gray-700 mb-2">Password</label>
                                <input type="password" name="password" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Enter your password">
                            </div>

                            <!-- Login Button -->
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300">
                                Login as Lecturer
                            </button>
                        </form>

                        <!-- Back Link -->
                        <div class="text-center mt-6">
                            <p class="text-gray-600 text-sm">
                                <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">← Back to Home</a>
                            </p>
                        </div>

                        <!-- Switch to Student Login -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <p class="text-gray-600 text-sm text-center">
                                Are you a student? 
                                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">Login as Student</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Image -->
            <div class="hidden md:block relative bg-cover bg-center" 
                style="background-image: url('{{ asset('images/buildings/CICS_BUILDING.png') }}');">
                <div class="absolute inset-0 bg-blue-900/60"></div>
                
                <!-- Icon Overlay -->
                <div class="absolute bottom-8 right-8">
                    <div class="w-32 h-32 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center border-4 border-white/30">
                        <svg class="w-20 h-20 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L9 5.414V18a1 1 0 102 0V5.414l6.293 6.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-blue-800 text-white py-4">
        <div class="text-center">
            <p class="text-sm">Leading Innovations, Transforming Lives, Building the Nation</p>
        </div>
    </footer>
</body>
</html>
