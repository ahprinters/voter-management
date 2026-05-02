<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky collapsible="mobile"
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-900 dark:bg-zinc-900">

        <flux:sidebar.header>
            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.search placeholder="Search..." />


        <flux:sidebar.nav>
            {{-- Platform --}}
            <flux:sidebar.group :heading="__('Platform')" class="grid">
                <flux:sidebar.item icon="home"
                    :href="route('dashboard')"
                    :current="request()->routeIs('dashboard')"
                    wire:navigate>
                    {{ __('Dashboard') }}
                </flux:sidebar.item>
            </flux:sidebar.group>

            {{-- ভোটার ম্যানেজার --}}
            <flux:sidebar.group expandable heading="{{ __('ভোটার ম্যানেজার') }}" icon="users">
                <flux:navlist.item icon="user-plus"
                    :href="route('voters.create')"
                    :current="request()->routeIs('voters.create')"
                    wire:navigate>
                    {{ __('নতুন ভোটার যুক্ত করুন') }}
                </flux:navlist.item>

                <flux:navlist.item icon="user"
                    :href="route('voters.voter-list')"
                    :current="request()->routeIs('voters.voter-list')"
                    wire:navigate>
                    {{ __('ভোটার তালিকা') }}
                </flux:navlist.item>
            </flux:sidebar.group>

            {{-- প্রতিষ্ঠান --}}
            <flux:sidebar.group expandable heading="{{ __('প্রতিষ্ঠান') }}" icon="building-office">
                <flux:navlist.item icon="building-library"
                    :href="route('mosque.create')"
                    :current="request()->routeIs('mosque.create')"
                    wire:navigate>
                    {{ __('মসজিদ তথ্য') }}
                </flux:navlist.item>

                <flux:navlist.item icon="building-office"
                    :href="route('primary-school.create')"
                    :current="request()->routeIs('primary-school.create')"
                    wire:navigate>
                    {{ __('প্রাইমারি স্কুল তথ্য') }}
                </flux:navlist.item>

                <flux:navlist.item icon="home-modern"
                    :href="route('temple.create')"
                    :current="request()->routeIs('temple.create')"
                    wire:navigate>
                    {{ __('মন্দির তথ্য') }}
                </flux:navlist.item>
            </flux:sidebar.group>

            {{-- ভৌগোলিক --}}
            <flux:sidebar.group expandable heading="{{ __('ভৌগোলিক') }}" icon="map">
                <flux:navlist.item icon="map-pin" :href="route('division.create')" :current="request()->routeIs('division.create')" wire:navigate>
                    {{ __('বিভাগ তথ্য') }}
                </flux:navlist.item>
                <flux:navlist.item icon="map-pin" :href="route('district.create')" :current="request()->routeIs('district.create')" wire:navigate>
                    {{ __('জেলা তথ্য') }}
                </flux:navlist.item>
                <flux:navlist.item icon="map-pin" :href="route('upazila.create')" :current="request()->routeIs('upazila.create')" wire:navigate>
                    {{ __('উপজেলা তথ্য') }}
                </flux:navlist.item>
                <flux:navlist.item icon="map-pin" :href="route('union.create')" :current="request()->routeIs('union.create')" wire:navigate>
                    {{ __('ইউনিয়ন তথ্য') }}
                </flux:navlist.item>
                <flux:navlist.item icon="map-pin" :href="route('ward.create')" :current="request()->routeIs('ward.create')" wire:navigate>
                    {{ __('ওয়ার্ড তথ্য') }}
                </flux:navlist.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:spacer />

        <flux:sidebar.nav>
            <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit"
                target="_blank">
                {{ __('Repository') }}
            </flux:sidebar.item>

            <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire"
                target="_blank">
                {{ __('Documentation') }}
            </flux:sidebar.item>
        </flux:sidebar.nav>

        <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
    </flux:sidebar>

    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        <flux:spacer />
        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <div class="p-0 text-sm font-normal">
                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                        <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />
                        <div class="grid flex-1 text-start text-sm leading-tight">
                            <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                            <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                        </div>
                    </div>
                </div>

                <flux:menu.separator />

                <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                    {{ __('Settings') }}
                </flux:menu.item>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer">
                        {{ __('Log out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{-- মূল কন্টেন্ট এখানে রেন্ডার হবে --}}
    <main class="p-4 lg:p-8">
        {{ $slot }}
    </main>

    @fluxScripts
</body>
</html>
