<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CICS Grade Tracker</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .container-fluid {
            display: flex;
            height: 100vh;
        }

        .row {
            width: 100%;
            margin: 0;
        }

        .col-md-2,
        .col-md-3,
        .col-md-4,
        .col-md-6,
        .col-md-8,
        .col-md-10,
        .col-md-12 {
            padding: 0;
        }

        @media (max-width: 768px) {
            .col-md-2 {
                display: none;
            }

            .col-md-10,
            .col-md-12 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    @yield('content')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
