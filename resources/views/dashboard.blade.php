<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CICS Grade Tracker - Dashboard</title>
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
                    <a href="{{ route('profile') }}" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-300 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>My Profile</span>
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
    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h2 class="text-4xl font-bold text-gray-800 mb-2">Welcome back, <span class="bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">{{ $user->name }}</span>!</h2>
            <p class="text-gray-600">Here's your academic progress and enrolled courses</p>
        </div>

        <!-- Student Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <!-- SR-Code Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border-t-4 border-blue-500 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">SR-Code</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $user->sr_code }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Program Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border-t-4 border-cyan-500 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Program</p>
                        <p class="text-lg font-bold text-gray-800 mt-1 truncate">{{ $user->program }}</p>
                    </div>
                    <div class="bg-cyan-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Year Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border-t-4 border-purple-500 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Year Level</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $user->year }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5.951-1.429 5.951 1.429a1 1 0 001.169-1.409l-7-14z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Campus Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border-t-4 border-indigo-500 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Campus</p>
                        <p class="text-lg font-bold text-gray-800 mt-1 truncate">{{ $user->campus }}</p>
                    </div>
                    <div class="bg-indigo-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrolled Classes Section -->
        <div class="bg-white rounded-2xl shadow-lg p-8 border border-cyan-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Enrolled Classes</h3>
                    <p class="text-gray-600 text-sm mt-1">Your current courses and grades</p>
                </div>
                <div class="bg-gradient-to-br from-blue-100 to-cyan-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 4.804C9 4.393 9.448 4 10 4s1 .393 1 .804v4.392h4.392c.411 0 .804.448.804 1 0 .552-.393 1-.804 1H11v4.392c0 .411-.448.804-1 .804s-1-.393-1-.804V11H4.608c-.411 0-.804-.448-.804-1 0-.552.393-1 .804-1H9V4.804z"></path>
                    </svg>
                </div>
            </div>

            @if($enrollments && $enrollments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="text-left py-4 px-4 font-semibold text-gray-700">Course Code</th>
                                <th class="text-left py-4 px-4 font-semibold text-gray-700">Course Title</th>
                                <th class="text-left py-4 px-4 font-semibold text-gray-700">Current Grade</th>
                                <th class="text-center py-4 px-4 font-semibold text-gray-700">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($enrollments as $enroll)
                            <tr class="border-b border-gray-100 hover:bg-blue-50 transition-colors duration-200">
                                <td class="py-4 px-4">
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                                        {{ $enroll->course->course_code ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="text-gray-800 font-medium">{{ $enroll->course->course_title ?? 'N/A' }}</p>
                                </td>
                                <td class="py-4 px-4">
                                    @if($enroll->current_grade)
                                        <div class="flex items-center space-x-2">
                                            <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-cyan-400 rounded-full flex items-center justify-center">
                                                <span class="text-white font-bold text-lg">{{ $enroll->current_grade }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">No grade yet</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <a href="/grades/{{ $enroll->id }}" class="inline-block bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-semibold px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105">
                                        View Tracker
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mb-4">
                        <svg class="w-16 h-16 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-lg font-medium">No enrolled classes found</p>
                    <p class="text-gray-400 text-sm">Please contact your administrator to enroll in courses</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>