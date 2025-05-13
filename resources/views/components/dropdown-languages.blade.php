@props([
'align' => 'right'
])

<div class="relative inline-flex" x-data="{ open: false }">
    <button
        class="w-8 h-8  mr-1 ml-2 flex items-center justify-center hover:bg-gray-100 lg:hover:bg-gray-200 dark:hover:bg-gray-700/50 dark:lg:hover:bg-gray-800 rounded-full"
        :class="{ 'bg-gray-200 dark:bg-gray-800': open }"
        aria-haspopup="true"
        @click.prevent="open = !open"
        :aria-expanded="open">
        <span class="sr-only">{{__("messages.languages")}}</span>
        <i class="fas fa-language"></i>
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