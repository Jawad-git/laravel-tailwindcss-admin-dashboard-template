<div class="min-w-fit">
    <!-- Sidebar backdrop (mobile only) -->
    <div
        class="fixed inset-0 bg-gray-900/30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'"
        aria-hidden="true"
        x-cloak></div>

    <!-- Sidebar -->
    <div
        id="sidebar"
        class="flex {{ session('locale', config('app.locale')) != 'ar' ? 'fixed-end rotate-caret' : 'ms-3 fixed-start' }} lg:flex! flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-[100dvh] overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:w-20 lg:sidebar-expanded:!w-64 2xl:w-64! shrink-0 bg-white dark:bg-gray-800 p-4 transition-all duration-200 ease-in-out {{ $variant === 'v2' ? 'border-r border-gray-200 dark:border-gray-700/60' : 'rounded-r-2xl shadow-xs' }}"
        :class="sidebarOpen ? 'max-lg:translate-x-0' : 'max-lg:-translate-x-64'"
        @click.outside="sidebarOpen = false"
        @keydown.escape.window="sidebarOpen = false">

        <!-- Sidebar header -->
        <div class="flex justify-content-center mb-2 pr-3 sm:px-2">

            {{--
        <button class="lg:hidden text-gray-500 hover:text-gray-400" @click.stop="sidebarOpen = !sidebarOpen" aria-controls="sidebar" :aria-expanded="sidebarOpen">
            <span class="">Close sidebar</span>
            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
            </svg>
        </button>
        
        --}}
            <!-- Close button -->
            <!-- Logo -- note: href should point to dashboard here
-->
            <a class="block" href="#">
                <img src="{{ asset('images/companyLogos/0002@4x-8.png') }}" class="h-32 w-32" alt="Company Logo" />
            </a>
        </div>

        <!-- Links -->
        <div class="space-y-8">
            <!-- Pages group -->
            <div>
                <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">
                    <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6" aria-hidden="true">•••</span>
                </h3>
                <ul class="mt-3 pl-0" style="padding-left: 0 !important;">
                    <!-- Dashboard -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if(in_array(Request::segment(1), ['pool'])){{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!in_array(Request::segment(1), ['pool'])){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('pool') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(in_array(Request::segment(1), ['dashboard'])){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M5.936.278A7.983 7.983 0 0 1 8 0a8 8 0 1 1-8 8c0-.722.104-1.413.278-2.064a1 1 0 1 1 1.932.516A5.99 5.99 0 0 0 2 8a6 6 0 1 0 6-6c-.53 0-1.045.076-1.548.21A1 1 0 1 1 5.936.278Z" />
                                    <path d="M6.068 7.482A2.003 2.003 0 0 0 8 10a2 2 0 1 0-.518-3.932L3.707 2.293a1 1 0 0 0-1.414 1.414l3.775 3.775Z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">{{__("messages.Dashboard")}}</span>
                            </div>
                        </a>
                    </li>
                    <!-- Users -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if(in_array(Request::segment(1), ['users'])){{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif" x-data="{ open: {{ in_array(Request::segment(1), ['users']) ? 1 : 0 }} }">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!in_array(Request::segment(1), ['users'])){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="#0" @click.prevent="open = !open; sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img class="shrink-0" src="{{ asset('images/sidebarIcons/user.png') }}" width="16" height="16" alt="User icon">
                                    <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">{{ __("messages.users") }}</span>
                                </div>
                                <!-- Icon -->
                                <div class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <svg class=" h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500" :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                            <ul class="pl-8! mt-1 @if(!in_array(Request::segment(1), ['users'])){{ 'hidden' }}@endif" :class="open ? 'block!' : 'hidden'">
                                <li class="mb-1 last:mb-0">
                                    <a class="block flex items-center gap-2 text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('roles')){{ 'text-violet-500!' }}@endif" href="{{ route('roles') }}">
                                        <img class="shrink-0" src="{{ asset('images/sidebarIcons/admin.png') }}" width="16" height="16" alt="Roles icon">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">{{__("messages.Manage Roles")}}</span>
                                    </a>
                                </li>
                                <li class="mb-1 last:mb-0">
                                    <a class="block flex items-center gap-2 text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('admins')){{ 'text-violet-500!' }}@endif" href="{{ route('admins') }}">
                                        <img class="shrink-0" src="{{ asset('images/sidebarIcons/administrator.png') }}" width="16" height="16" alt="Admin icon">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">{{__("messages.Manage Admins")}}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!-- Rooms -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if(in_array(Request::segment(1), ['accomodation'])){{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif" x-data="{ open: {{ in_array(Request::segment(1), ['accomodation']) ? 1 : 0 }} }">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!in_array(Request::segment(1), ['accomodation'])){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="#0" @click.prevent="open = !open; sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img class="shrink-0" src="{{ asset('images/sidebarIcons/hotel.png') }}" width="16" height="16" alt="Hotel icon">
                                    <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">{{__("messages.Accomodation")}}</span>
                                </div>
                                <!-- Icon -->
                                <div class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <svg class=" h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500" :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                            <ul class="pl-8! mt-1 @if(!in_array(Request::segment(1), ['accomodation'])){{ 'hidden' }}@endif" :class="open ? 'block!' : 'hidden'">
                                <li class="mb-1 last:mb-0">
                                    <a class="block flex items-center gap-2 text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('rooms')){{ 'text-violet-500!' }}@endif" href="{{ route('rooms') }}">
                                        <img class="shrink-0" src="{{ asset('images/sidebarIcons/bed.png') }}" width="16" height="16" alt="Bed icon">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">{{__("messages.Manage Rooms")}}</span>
                                    </a>
                                </li>
                                <li class="mb-1 last:mb-0">
                                    <a class="block flex items-center gap-2 text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('categories')){{ 'text-violet-500!' }}@endif" href="{{ route('categories') }}">
                                        <img class="shrink-0" src="{{ asset('images/sidebarIcons/category.png') }}" width="16" height="16" alt="Category icon">

                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">{{__("messages.Manage Categories")}}</span>
                                    </a>
                                </li>
                                <li class="mb-1 last:mb-0">
                                    <a class="block flex items-center gap-2 text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('amenities')){{ 'text-violet-500!' }}@endif" href="{{ route('amenities') }}">
                                        <img class="shrink-0" src="{{ asset('images/sidebarIcons/wifi.png') }}" width="16" height="16" alt="amenities icon">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">{{__("messages.Manage Amenities")}}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!-- Restaurant -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if(in_array(Request::segment(1), ['restaurant'])){{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif" x-data="{ open: {{ in_array(Request::segment(1), ['restaurant']) ? 1 : 0 }} }">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!in_array(Request::segment(1), ['restaurant'])){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="#0" @click.prevent="open = !open; sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img class="shrink-0" src="{{ asset('images/sidebarIcons/restaurant.png') }}" width="16" height="16" alt="Restaurant icon">
                                    <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">{{__("messages.Restaurant")}}</span>
                                </div>
                                <!-- Icon -->
                                <div class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <svg class=" h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500" :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                            <ul class="pl-8! mt-1 @if(!in_array(Request::segment(1), ['restaurant'])){{ 'hidden' }}@endif" :class="open ? 'block!' : 'hidden'">
                                <li class="mb-1 last:mb-0">
                                    <a class="block flex items-center gap-2 text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('foods')){{ 'text-violet-500!' }}@endif" href="{{ route('foods') }}">
                                        <img class="shrink-0" src="{{ asset('images/sidebarIcons/cutlery.png') }}" width="16" height="16" alt="food plate icon">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">{{__("messages.Manage Foods")}}</span>
                                    </a>
                                </li>
                                <li class="mb-1 last:mb-0">
                                    <a class="block flex items-center gap-2 text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('menus')){{ 'text-violet-500!' }}@endif" href="{{ route('menus') }}">
                                        <img class="shrink-0" src="{{ asset('images/sidebarIcons/menu (1).png') }}" width="16" height="16" alt="food category icon">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">{{__("messages.Manage Menus")}}</span>
                                    </a>
                                </li>
                                <li class="mb-1 last:mb-0">
                                    <a class="block flex items-center gap-2 text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('restaurant')){{ 'text-violet-500!' }}@endif" href="{{ route('restaurant') }}">
                                        <img class="shrink-0" src="{{ asset('images/sidebarIcons/owner.png') }}" width="16" height="16" alt="restaurant management icon">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">{{__("messages.Manage Restaurant")}}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!-- Swimming Pool -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if(in_array(Request::segment(1), ['pool'])){{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!in_array(Request::segment(1), ['pool'])){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('pool') }}">
                            <div class="flex items-center">
                                <img class="shrink-0" src="{{ asset('images/sidebarIcons/swimming.png') }}" width="16" height="16" alt="Swimming icon">
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">{{ __("messages.swimming_pool") }}</span>
                            </div>
                        </a>
                    </li>
                    <!-- About Us -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if(in_array(Request::segment(1), ['about'])){{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!in_array(Request::segment(1), ['about'])){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('about') }}">
                            <div class="flex items-center">
                                <img class="shrink-0" src="{{ asset('images/sidebarIcons/info.png') }}" width="16" height="16" alt="About us icon">
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">{{ __("messages.about_us") }}</span>
                            </div>
                        </a>
                    </li>
                    <!-- Social Media Management -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if(in_array(Request::segment(1), ['socials'])){{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!in_array(Request::segment(1), ['socials'])){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('socials') }}">
                            <div class="flex items-center">
                                <img class="shrink-0" src="{{ asset('images/sidebarIcons/social-media.png') }}" width="16" height="16" alt="Social Media icon">
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">{{ __("messages.social_media") }}</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Expand / collapse button -->
        <div class="pt-3 hidden lg:inline-flex 2xl:hidden justify-end mt-auto">
            <div class="w-12 pl-4 pr-3 py-2">
                <button class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 transition-colors" @click="sidebarExpanded = !sidebarExpanded">
                    <span class="sr-only">Expand / collapse sidebar</span>
                    <svg class="shrink-0 fill-current text-gray-400 dark:text-gray-500 sidebar-expanded:rotate-180" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M15 16a1 1 0 0 1-1-1V1a1 1 0 1 1 2 0v14a1 1 0 0 1-1 1ZM8.586 7H1a1 1 0 1 0 0 2h7.586l-2.793 2.793a1 1 0 1 0 1.414 1.414l4.5-4.5A.997.997 0 0 0 12 8.01M11.924 7.617a.997.997 0 0 0-.217-.324l-4.5-4.5a1 1 0 0 0-1.414 1.414L8.586 7M12 7.99a.996.996 0 0 0-.076-.373Z" />
                    </svg>
                </button>
            </div>
        </div>

    </div>
</div>