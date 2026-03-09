<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    @php
        $currentUser = auth()->user();
    @endphp
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                    <div class="shrink-0 flex items-center">
                    <a href="{{ $currentUser ? route('dashboard') : route('welcome') }}">
                    <img class="h-12 w-50" src="{{ asset('images/skillconnect-logo.png') }}" alt="SkillConnect Logo">
                    </a>
                </div>


                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="$currentUser ? route('dashboard') : route('welcome')" :active="request()->routeIs('dashboard') || request()->routeIs('welcome')">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('discover')" :active="request()->routeIs('discover')">
                        {{ __('Discover') }}
                    </x-nav-link>
                    @auth
                        <x-nav-link :href="route('messages.index')" :active="request()->routeIs('messages.index')">
                            {{ __('Messages') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <a href="{{ route('projects.create') }}" @click.prevent="openCreatePostModal()" class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-full font-medium transition duration-300 text-sm mr-4 inline-flex items-center">
                        <i class="fas fa-plus mr-1"></i> New Post
                    </a>
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                @if ($currentUser->avatar_path)
                                    <img class="h-8 w-8 rounded-full object-cover mr-2" src="{{ $currentUser->avatar_url }}" alt="{{ $currentUser->name }}'s avatar">
                                @else
                                    <img class="h-8 w-8 rounded-full mr-2" src="https://ui-avatars.com/api/?name={{ urlencode($currentUser->name) }}" alt="{{ $currentUser->name }}'s avatar">
                                @endif
                                
                                <div>{{ $currentUser->name }}</div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.show', $currentUser)">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Settings') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Log in</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
                            Join SkillConnect
                        </a>
                    </div>
                @endauth
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="$currentUser ? route('dashboard') : route('welcome')" :active="request()->routeIs('dashboard') || request()->routeIs('welcome')">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('discover')" :active="request()->routeIs('discover')">
                {{ __('Discover') }}
            </x-responsive-nav-link>
            @auth
                <x-responsive-nav-link :href="route('messages.index')" :active="request()->routeIs('messages.index')">
                    {{ __('Messages') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ $currentUser->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ $currentUser->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.show', $currentUser)">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Settings') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-4 border-t border-gray-200 space-y-1">
                <x-responsive-nav-link :href="route('login')">
                    {{ __('Log in') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">
                    {{ __('Register') }}
                </x-responsive-nav-link>
            </div>
        @endauth
    </div>
</nav>
