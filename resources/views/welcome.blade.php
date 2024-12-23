<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Filament Admin Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-screen bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 flex items-center justify-center">
    <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-xl dark:bg-gray-800 dark:border-gray-700">
        <a href="#">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Filament Admin Dashboard
            </h5>
        </a>
        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Here are multi-language dashboard with many plugins
            and packages.</p>
        <a href="/admin"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-blue-500 dark:hover:bg-blue-600">
            Dashboard
            <svg class="w-4 h-4 ms-2 rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M1 5h12m0 0L9 1m4 4L9 9" />
            </svg>
        </a>
        <div class="mt-4 text-sm dark:text-white">
            <p><b>Admin Email:</b> <small>admin@admin.me</small></p>
            <p><b>Admin Password:</b> <small>@dmin123456789</small></p>
        </div>
        <div class="mt-6 text-center">
            <a href="https://github.com/moataz-01" target="_blank"
                class="inline-flex items-center text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.438 9.8 8.207 11.387.6.11.793-.26.793-.577 0-.285-.01-1.04-.016-2.04-3.338.724-4.042-1.61-4.042-1.61-.546-1.39-1.333-1.76-1.333-1.76-1.09-.744.082-.729.082-.729 1.204.084 1.838 1.237 1.838 1.237 1.07 1.835 2.805 1.305 3.49.997.11-.775.418-1.306.76-1.606-2.665-.3-5.466-1.332-5.466-5.932 0-1.31.467-2.383 1.236-3.223-.124-.303-.535-1.523.116-3.176 0 0 1.007-.322 3.3 1.23a11.52 11.52 0 013.003-.403c1.02.005 2.045.137 3.003.403 2.29-1.552 3.296-1.23 3.296-1.23.653 1.653.242 2.873.118 3.176.77.84 1.235 1.913 1.235 3.223 0 4.61-2.805 5.63-5.475 5.92.43.37.812 1.102.812 2.222 0 1.606-.014 2.9-.014 3.293 0 .32.19.694.8.576C20.565 21.796 24 17.298 24 12c0-6.63-5.37-12-12-12z" />
                </svg>
                GitHub
            </a>
        </div>
    </div>
</body>

</html>
