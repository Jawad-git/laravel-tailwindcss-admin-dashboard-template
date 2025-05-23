<header class="fixed top-0 left-0 right-0 z-30 flex items-center justify-center bg-transparent dark:bg-transparent h-16">
    <div class="container">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('website.home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12">
                </a>
            </div>
            <div class="flex items-center">
                <a href="{{ route('website.home') }}" class="text-white dark:text-white hover:text-gray-600 dark:hover:text-gray-400">
                    {{ __('messages.Home') }}
                </a>
                <a href="{{ route('website.about') }}" class="text-white dark:text-white hover:text-gray-600 dark:hover:text-gray-400">
                    {{ __('messages.About Us') }}
                </a>
                <a href="{{ route('website.rooms') }}" class="text-white dark:text-white hover:text-gray-600 dark:hover:text-gray-400">
                    {{ __('messages.Rooms') }}
                </a>
                <a href="{{ route('website.contact') }}" class="text-white dark:text-white hover:text-gray-600 dark:hover:text-gray-400">
                    {{ __('messages.Contact Us') }}
                </a>
            </div>
        </div>
    </div>
</header>

<script>
    window.addEventListener('scroll', function() {
        if (window.scrollY > 0) {
            document.querySelector('header').classList.add('bg-white', 'dark:bg-gray-800');
            document.querySelectorAll('header a').forEach(el => {
                el.classList.remove('text-white', 'dark:text-white');
                el.classList.add('text-gray-800', 'dark:text-gray-100');
            });
        } else {
            document.querySelector('header').classList.remove('bg-white', 'dark:bg-gray-800');
            document.querySelectorAll('header a').forEach(el => {
                el.classList.add('text-white', 'dark:text-white');
                el.classList.remove('text-gray-800', 'dark:text-gray-100');
            });
        }
    });
</script>
