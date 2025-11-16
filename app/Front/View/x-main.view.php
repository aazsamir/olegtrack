<!DOCTYPE html>
<html lang="en">

<head>
    <title>OlegTrack</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png" />
    <link rel="manifest" href="/favicon/site.webmanifest" />

    <link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/vendor/bootstrap-icons/font/bootstrap-icons.min.css">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .full-height-container {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            justify-content: stretch;
        }
    </style>
    <x-slot name="styles" />
</head>

<body>
    <div class="container-fluid full-height-container">
        <x-slot />
    </div>
    <!-- theme toggle button -->
    <button id="theme-toggle" class="btn btn-outline-secondary position-fixed top-0 end-0 m-3" onclick="toggleTheme()">
        <i class="bi bi-moon-fill"></i>Zmień motyw
    </button>
    <!-- <x-footer></x-footer> -->
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/js/main.js"></script>
</body>

</html>
