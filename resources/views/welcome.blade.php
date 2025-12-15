<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CICS Grade Tracker - Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Smooth fade-in animation */
        .fade-in {
            animation: fadeIn 0.25s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.97); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
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
                        <h2 class="text-blue-600 font-semibold text-lg mb-6">
                            College of Informatics and<br>Computing Sciences
                        </h2>

                        <h1 class="text-4xl md:text-5xl font-bold text-blue-900 mb-2">
                            CICS<br>GRADE TRACKER
                        </h1>

                        <p class="text-gray-600 text-sm mt-4">
                            Track and understand your grades with transparency.
                        </p>
                    </div>

                    <!-- Login Buttons -->
                    <div class="space-y-4 mb-6">
                        <a href="{{ route('login') }}"
                           class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 text-center">
                            Log in as Student
                        </a>

                        <a href="{{ route('lecturer.login') }}"
                           class="block w-full border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-semibold py-3 px-6 rounded-lg transition-all duration-300 text-center">
                            Log in as Lecturer
                        </a>
                    </div>

                    <!-- Terms Link -->
                    <div class="text-center">
                        <a href="javascript:void(0)" onclick="openTerms()" 
                           class="text-blue-600 hover:text-blue-800 text-sm">
                            Terms and Conditions
                        </a>
                    </div>

                </div>
            </div>

            <!-- Right Side Image -->
            <div class="hidden md:block relative bg-cover bg-center"
                style="background-image: url('{{ asset('images/buildings/CICS_BUILDING.png') }}');">
                <div class="absolute inset-0 bg-blue-900/60"></div>

                <div class="absolute bottom-8 right-8">
                    <div class="w-32 h-32 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center border-4 border-white/30">
                        <svg class="w-20 h-20 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"></path>
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



    <!-- ----------------------------------------------------- -->
    <!-- TERMS AND CONDITIONS MODAL -->
    <!-- ----------------------------------------------------- -->
    <div id="termsModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center hidden">
        <div class="bg-white w-full max-w-lg p-8 rounded-lg shadow-lg relative fade-in max-h-[85vh] overflow-y-auto">

            <!-- Close Button -->
            <button onclick="closeTerms()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">
                ✖
            </button>

            <h2 class="text-2xl font-bold text-blue-800 mb-4">Terms and Conditions</h2>

            <p class="text-gray-700 mb-4">
                Welcome to the CICS Grade Tracker. By using this system, you agree to the following terms:
            </p>

            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-6">
                <li>You must log in using your official university credentials.</li>
                <li>Your data will only be used for academic tracking and analysis.</li>
                <li>Unauthorized access or misuse of data is strictly prohibited.</li>
                <li>The system records activity for security and auditing purposes.</li>
            </ul>

            <p class="text-gray-700 mb-6">
                If you do not agree to these terms, please discontinue the use of the system.
            </p>

            <div class="text-right">
                <button onclick="closeTerms()" 
                        class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                    Close
                </button>
            </div>

        </div>
    </div>

    <!-- JavaScript to control modal -->
    <script>
        function openTerms() {
            document.getElementById('termsModal').classList.remove('hidden');
        }

        function closeTerms() {
            document.getElementById('termsModal').classList.add('hidden');
        }
    </script>

</body>
</html>
