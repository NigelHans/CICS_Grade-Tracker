<h1 class="text-2xl font-bold">Welcome, {{ $user->name }}</h1>

<h2 class="text-xl mt-4">Student Details</h2>
<ul>
    <li>SR-Code: {{ $user->sr_code }}</li>
    <li>Program: {{ $user->program }}</li>
    <li>Year: {{ $user->year }}</li>
    <li>Campus: {{ $user->campus }}</li>
</ul>

<h2 class="text-xl mt-4">Enrolled Classes</h2>

<table class="table-auto border w-full mt-2">
    <tr>
        <th class="border px-2 py-1">Course</th>
        <th class="border px-2 py-1">Title</th>
        <th class="border px-2 py-1">Current Grade</th>
        <th class="border px-2 py-1">Action</th>
    </tr>

    @foreach ($enrollments as $enroll)
    <tr>
        <td class="border px-2 py-1">{{ $enroll->course->course_code }}</td>
        <td class="border px-2 py-1">{{ $enroll->course->course_title }}</td>
        <td class="border px-2 py-1">{{ $enroll->current_grade ?? 'N/A' }}</td>
        <td class="border px-2 py-1">
            <a href="/grades/{{ $enroll->id }}" class="text-blue-500">View Tracker</a>
        </td>
    </tr>
    @endforeach
</table>
