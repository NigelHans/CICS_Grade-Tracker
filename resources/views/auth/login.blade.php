<!DOCTYPE html>
<html>
<head>
    <title>BatStateU Grade Tracker - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-red-600 flex justify-center items-center h-screen">

<div class="bg-white p-8 rounded-xl shadow-lg w-96">
    <h1 class="text-2xl font-bold mb-4 text-center text-red-600">
        BatStateU Grade Tracker
    </h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-600 p-2 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login.submit') }}" method="POST">
        @csrf
        
        <label>Email (GSuite only)</label>
        <input type="email" name="email"
               class="w-full p-2 border rounded mb-3"
               placeholder="example@g.batstate-u.edu.ph">

        <label>Password</label>
        <input type="password" name="password"
               class="w-full p-2 border rounded mb-4">

        <button class="bg-red-600 text-white w-full py-2 rounded hover:bg-red-700">
            Login
        </button>
    </form>
</div>

</body>
</html>
