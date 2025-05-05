@props([
'align' => 'right'
])

<div class="relative inline-flex" x-data="{ open: false }">
    <button
        class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 lg:hover:bg-gray-200 dark:hover:bg-gray-700/50 dark:lg:hover:bg-gray-800 rounded-full"
        :class="{ 'bg-gray-200 dark:bg-gray-800': open }"
        aria-haspopup="true"
        @click.prevent="open = !open"
        :aria-expanded="open">
        <span class="sr-only">{{__("messages.languages")}}</span>
        <svg class="fill-current text-gray-500/80 dark:text-gray-400/80" width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0Zm0 1.5c.42 0 .833.18 1.21.53-.438.56-.815 1.307-1.12 2.205a9.74 9.74 0 0 0-1.12-2.204c.378-.35.79-.531 1.03-.531ZM5.34 2.41c.63.84 1.14 1.93 1.49 3.09H3.8A6.52 6.52 0 0 1 5.34 2.4Zm5.32 0A6.52 6.52 0 0 1 12.2 5.5H9.17c.35-1.16.86-2.25 1.49-3.09ZM2.02 6.5h4.84a12.6 12.6 0 0 1 .58 3H2.3a6.44 6.44 0 0 1-.28-3Zm6.62 0h5.34c.09.98-.02 2.01-.28 3H8.62a12.6 12.6 0 0 0-.58-3ZM3.8 10.5h3.04c-.35 1.16-.86 2.25-1.49 3.09A6.52 6.52 0 0 1 3.8 10.5Zm5.83 0h3.04a6.52 6.52 0 0 1-1.49 3.09c-.63-.84-1.14-1.93-1.49-3.09ZM8 12.03c.3.68.64 1.27 1.01 1.73A2.9 2.9 0 0 1 8 14.5a2.9 2.9 0 0 1-1.01-.74c.37-.46.7-1.05 1.01-1.73Z" />
        </svg>
    </button>

    <div
        class="origin-top-right z-10 absolute top-full -mr-48 sm:mr-0 min-w-80 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 py-1.5 rounded-lg shadow-lg overflow-hidden mt-1 {{$align === 'right' ? 'right-0' : 'left-0'}}"
        @click.outside="open = false"
        @keydown.escape.window="open = false"
        x-show="open"
        x-transition:enter="transition ease-out duration-200 transform"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-out duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-cloak>
        <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase pt-1.5 pb-2 px-4">{{__("messages.languages")}}</div>
        <ul>
            <li class="border-b border-gray-200 dark:border-gray-700/60 last:border-0">
                <a class="block py-2 px-4 hover:bg-gray-50 dark:hover:bg-gray-700/20" href="{{ route('locale.switch', 'en') }}" @click=" open=false" @focus="open = true" @focusout="open = false">
                    {{ __("messages.english") }}
                </a>
            </li>
            <li class="border-b border-gray-200 dark:border-gray-700/60 last:border-0">
                <a class="block py-2 px-4 hover:bg-gray-50 dark:hover:bg-gray-700/20" href="{{ route('locale.switch', 'fa') }}" @click=" open=false" @focus="open = true" @focusout="open = false">
                    {{ __("messages.farsi") }}
                </a>
            </li>
            <li class="border-b border-gray-200 dark:border-gray-700/60 last:border-0">
                <a class="block py-2 px-4 hover:bg-gray-50 dark:hover:bg-gray-700/20" href="{{ route('locale.switch', 'ar') }}" @click=" open=false" @focus="open = true" @focusout="open = false">
                    {{ __("messages.arabic") }}
                </a>
            </li>
        </ul>
    </div>
</div>