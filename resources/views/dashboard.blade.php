<div class="p-6 max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Welcome, {{ $user->name }}</h1>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                Logout
            </button>
        </form>
    </div>

    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-200 rounded p-4">
            <p class="text-gray-600 text-sm">SR-Code</p>
            <p class="text-lg font-semibold">{{ $user->sr_code }}</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded p-4">
            <p class="text-gray-600 text-sm">Program</p>
            <p class="text-lg font-semibold">{{ $user->program }}</p>
        </div>
        <div class="bg-yellow-50 border border-yellow-200 rounded p-4">
            <p class="text-gray-600 text-sm">Year</p>
            <p class="text-lg font-semibold">{{ $user->year }}</p>
        </div>
        <div class="bg-purple-50 border border-purple-200 rounded p-4">
            <p class="text-gray-600 text-sm">Campus</p>
            <p class="text-lg font-semibold">{{ $user->campus }}</p>
        </div>
    </div>

    <h2 class="text-2xl font-bold mb-4">Enrolled Classes</h2>

    @if($enrollments && $enrollments->count() > 0)
        <div class="overflow-x-auto shadow rounded-lg">
            <table class="w-full border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 px-4 py-3 text-left">Course Code</th>
                        <th class="border border-gray-300 px-4 py-3 text-left">Course Title</th>
                        <th class="border border-gray-300 px-4 py-3 text-left">Current Grade</th>
                        <th class="border border-gray-300 px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($enrollments as $enroll)
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-300 px-4 py-3">{{ $enroll->course->course_code ?? 'N/A' }}</td>
                        <td class="border border-gray-300 px-4 py-3">{{ $enroll->course->course_title ?? 'N/A' }}</td>
                        <td class="border border-gray-300 px-4 py-3">
                            @if($enroll->current_grade)
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $enroll->current_grade }}
                                </span>
                            @else
                                <span class="text-gray-500 italic">N/A</span>
                            @endif
                        </td>
                        <td class="border border-gray-300 px-4 py-3 text-center">
                            <a href="/grades/{{ $enroll->id }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                View Tracker
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 rounded p-4 text-center">
            <p class="text-gray-600">No enrolled classes found.</p>
        </div>
    @endif
</div>