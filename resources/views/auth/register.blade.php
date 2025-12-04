<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CICS Grade Tracker - Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex flex-col bg-gray-50">
    <!-- Main Content -->
    <div class="flex-1 flex items-center py-8">
        <div class="w-full grid md:grid-cols-2 min-h-screen">
            <!-- Left Side - Register Form -->
            <div class="flex items-center justify-center p-8 bg-white">
                <div class="w-full max-w-md">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-blue-600 font-semibold text-lg mb-8">College of Informatics and<br>Computing Sciences</h2>
                    </div>

                    <!-- Register Form Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h1 class="text-2xl font-bold text-blue-900 mb-6">Create your account</h1>

                        <!-- Error Messages -->
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4 max-h-32 overflow-y-auto">
                                @foreach ($errors->all() as $error)
                                    <p class="text-red-600 text-sm">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <!-- Form -->
                        <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
                            @csrf

                            <!-- Full Name -->
                            <div>
                                <label class="block text-sm text-gray-700 mb-2">Full Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Full Name">
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Email">
                                <p class="text-xs text-gray-500 mt-1">Only @g.batstate-u.edu.ph emails are allowed</p>
                            </div>

                            <!-- SR-Code -->
                            <div>
                                <label class="block text-sm text-gray-700 mb-2">SR-Code</label>
                                <input type="text" name="sr_code" value="{{ old('sr_code') }}" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="SR-Code">
                            </div>

                            <!-- Program -->
                            <div>
                                <label class="block text-sm text-gray-700 mb-2">Program</label>
                                <input type="text" name="program" value="{{ old('program') }}" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Program">
                            </div>

                            <!-- Year and Campus Grid -->
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Year -->
                                <div>
                                    <label class="block text-sm text-gray-700 mb-2">Year</label>
                                    <select name="year" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Select Year</option>
                                        <option value="1st Year" {{ old('year') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                        <option value="2nd Year" {{ old('year') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                        <option value="3rd Year" {{ old('year') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                        <option value="4th Year" {{ old('year') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                    </select>
                                </div>

                                <!-- Campus -->
                                <div>
                                    <label class="block text-sm text-gray-700 mb-2">Campus</label>
                                    <input type="text" name="campus" value="{{ old('campus') }}" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Campus">
                                </div>
                            </div>

                            <!-- Password -->
                            <div>
                                <label class="block text-sm text-gray-700 mb-2">Password</label>
                                <input type="password" name="password" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Password">
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label class="block text-sm text-gray-700 mb-2">Confirm Password</label>
                                <input type="password" name="password_confirmation" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Confirm Password">
                            </div>

                            <!-- Register Button -->
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 mt-6">
                                Register
                            </button>
                        </form>

                        <!-- Login Link -->
                        <div class="text-center mt-6">
                            <p class="text-gray-600 text-sm">
                                Already have an account? 
                                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">Login Here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Image -->
            <div class="hidden md:block relative bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1562774053-701939374585?w=800&q=80');">
                <div class="absolute inset-0 bg-blue-900/60"></div>
                
                <!-- BatState-U Logo Overlay -->
                <div class="absolute bottom-8 right-8">
                    <div class="w-32 h-32 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center border-4 border-white/30">
                        <svg class="w-20 h-20 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
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