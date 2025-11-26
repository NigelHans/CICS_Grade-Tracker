<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CICS Grade Tracker - My Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 via-cyan-50 to-blue-100 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-blue-600 to-cyan-500 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="bg-white/20 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-white">CICS Grade Tracker</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-300 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 9l-7-4m0 0V5m0 0h12v4"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-all duration-300 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-4xl font-bold text-gray-800 mb-2">My Profile</h2>
            <p class="text-gray-600">View and manage your profile information</p>
        </div>

        <!-- Profile Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-cyan-100 overflow-hidden mb-8">
            <!-- Header Background -->
            <div class="h-32 bg-gradient-to-r from-blue-600 to-cyan-500"></div>

            <!-- Profile Content -->
            <div class="px-8 pb-8">
                <!-- Avatar and Name -->
                <div class="flex flex-col md:flex-row md:items-end md:space-x-6 -mt-16 mb-8">
                    <div class="bg-gradient-to-br from-blue-400 to-cyan-400 w-24 h-24 rounded-full border-4 border-white shadow-lg flex items-center justify-center mb-4 md:mb-0">
                        <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $user->name }}</h3>
                        <p class="text-gray-600 mt-1">Student Account</p>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Email -->
                    <div class="border-b-2 border-gray-100 pb-6">
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Email Address</p>
                        <p class="text-gray-800 text-lg font-semibold mt-2">{{ $user->email }}</p>
                    </div>

                    <!-- SR-Code -->
                    <div class="border-b-2 border-gray-100 pb-6">
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">SR-Code</p>
                        <p class="text-gray-800 text-lg font-semibold mt-2">{{ $user->sr_code }}</p>
                    </div>

                    <!-- Program -->
                    <div class="border-b-2 border-gray-100 pb-6">
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Program</p>
                        <p class="text-gray-800 text-lg font-semibold mt-2">{{ $user->program }}</p>
                    </div>

                    <!-- Year -->
                    <div class="border-b-2 border-gray-100 pb-6">
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Year Level</p>
                        <p class="text-gray-800 text-lg font-semibold mt-2">{{ $user->year }}</p>
                    </div>

                    <!-- Campus -->
                    <div class="col-span-1 md:col-span-2 pb-6">
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Campus</p>
                        <p class="text-gray-800 text-lg font-semibold mt-2">{{ $user->campus }}</p>
                    </div>
                </div>

                <!-- Edit Button -->
                <div class="mt-8 flex space-x-4">
                    <a href="{{ route('profile.edit') }}" class="bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>Edit Profile</span>
                    </a>
                    
                    <a href="{{ route('dashboard') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold px-6 py-3 rounded-lg transition-all duration-300 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l-7 7m0 0h16"></path>
                        </svg>
                        <span>Back to Dashboard</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Enrolled Classes Quick View -->
        <div class="bg-white rounded-2xl shadow-lg border border-cyan-100 p-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Your Enrolled Courses</h3>
            
            @if($enrollments && $enrollments->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($enrollments as $enroll)
                    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-lg p-5 border-l-4 border-blue-600 hover:shadow-md transition-all duration-300">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="text-sm text-gray-600 font-medium">{{ $enroll->course->course_code ?? 'N/A' }}</p>
                                <p class="text-lg font-bold text-gray-800 mt-1">{{ $enroll->course->course_title ?? 'N/A' }}</p>
                            </div>
                            @if($enroll->current_grade)
                                <div class="bg-gradient-to-br from-green-400 to-cyan-400 rounded-full w-12 h-12 flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">{{ $enroll->current_grade }}</span>
                                </div>
                            @endif
                        </div>
                        <a href="/grades/{{ $enroll->id }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm inline-flex items-center space-x-1 mt-2">
                            <span>View Details</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">No enrolled courses yet</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>