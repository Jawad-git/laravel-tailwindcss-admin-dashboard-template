<!DOCTYPE html>
<html lang="{{ session('locale', config('app.locale')) }}"
    dir="{{ in_array(session('locale', config('app.locale')), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles

    <!-- Font Awesome Icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/js/all.min.js" integrity="sha512-1JkMy1LR9bTo3psH+H4SV5bO2dFylgOy+UJhMus1zF4VEFuZVu5lsi4I6iIndE4N9p01z1554ZDcvMSjMaqCBQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        // Set initial theme based on localStorage or system preference
        if (localStorage.getItem('dark-mode') === 'false' || !('dark-mode' in localStorage)) {
            document.documentElement.classList.remove('dark');
            document.documentElement.style.colorScheme = 'light';
        } else {
            document.documentElement.classList.add('dark');
            document.documentElement.style.colorScheme = 'dark';
        }
    </script>
</head>

<body class="font-inter {{ in_array(session('locale', config('app.locale')), ['ar', 'fa']) ? 'rtl' : 'ltr' }} antialiased bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400">

    <main class="bg-white dark:bg-gray-900">
        <div class="relative flex flex-col md:flex-row">
            <!-- Content -->
            <div class="w-full md:w-1/2 {{ in_array(session('locale', config('app.locale')), ['ar', 'fa']) ? 'md:order-2' : '' }}">
                <div class="min-h-[100dvh] h-full flex flex-col after:flex-1">
                    <!-- Header -->
                    <div class="flex-1">
                        <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 py-1">
                            <!-- Logo -->
                            <a class="block" href="{{ route('dashboard') }}">
                                <img src="{{ asset('images/companyLogos/0002@4x-8.png') }}" class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 lg:w-32 lg:h-32" alt="Company Logo" />
                            </a>
                            <div class="relative ml-4">
                                <x-dropdown-languages />
                            </div>
                        </div>
                    </div>

                    <div class="max-w-sm mx-auto w-full px-4 py-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <!-- Image -->
            <div class="hidden md:block md:fixed top-0 bottom-0 {{ in_array(session('locale', config('app.locale')), ['ar', 'fa']) ? 'left-0' : 'right-0' }} md:w-1/2" aria-hidden="true">
                <img class="object-cover object-center w-full h-full" src="{{ asset('images/auth-image-hotel-aerial-shot.jpeg') }}" width="760" height="1024" alt="Authentication image" />
            </div>
        </div>
    </main>

    @livewireScriptConfig
</body>

</html>