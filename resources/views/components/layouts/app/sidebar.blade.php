<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="/" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="bolt" href="/" :current="request()->is('/')" wire:navigate>{{ __('Webhook Test') }}</flux:navlist.item>
                    <flux:navlist.item icon="command-line" disabled>{{ __('API Test') }} <span class="text-xs text-zinc-500 ml-1">({{ __('coming soon') }})</span></flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <!-- Theme Switcher -->
            <div class="px-6 py-3">
                <script>
                    (function () {
                        const setDarkClass = () => {
                            const isDark = localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
                            isDark ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')
                        }
                        setDarkClass()
                        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', setDarkClass)
                    })();
                </script>
                <div
                    class="relative"
                    x-data="{
                        theme: localStorage.theme,
                        darkMode() {
                            this.theme = 'dark'
                            localStorage.theme = 'dark'
                            document.documentElement.classList.add('dark')
                        },
                        lightMode() {
                            this.theme = 'light'
                            localStorage.theme = 'light'
                            document.documentElement.classList.remove('dark')
                        },
                        systemMode() {
                            this.theme = undefined
                            localStorage.removeItem('theme')
                            const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches
                            isDark ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')
                        },
                    }"
                >
                    <div class="flex items-center justify-between text-sm text-zinc-600 dark:text-zinc-400">
                        <span class="font-medium">{{ __('Theme') }}</span>
                        <div class="flex space-x-1">
                            <button
                                @click="lightMode()"
                                :class="theme === 'light' ? 'bg-zinc-200 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-200' : 'hover:bg-zinc-100 dark:hover:bg-zinc-800'"
                                class="p-1.5 rounded transition-colors"
                                title="{{ __('Light mode') }}"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <button
                                @click="darkMode()"
                                :class="theme === 'dark' ? 'bg-zinc-200 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-200' : 'hover:bg-zinc-100 dark:hover:bg-zinc-800'"
                                class="p-1.5 rounded transition-colors"
                                title="{{ __('Dark mode') }}"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                                </svg>
                            </button>
                            <button
                                @click="systemMode()"
                                :class="theme === undefined ? 'bg-zinc-200 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-200' : 'hover:bg-zinc-100 dark:hover:bg-zinc-800'"
                                class="p-1.5 rounded transition-colors"
                                title="{{ __('System mode') }}"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://www.linkedin.com/in/sudiptobain/" target="_blank">
                {{ __('Linkdin') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://github.com/codexindia" target="_blank">
                {{ __('github') }}
                </flux:navlist.item>
            </flux:navlist>

         
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            {{-- <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown> --}}
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
