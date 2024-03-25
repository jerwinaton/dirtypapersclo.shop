<header class="bg-white flex justify-between items-center border-b px-6 fixed w-full z-50 top-0 py-2">
    <div class="flex items-center space-x-4">
        <button type="button" @click="toggleMenu()">
            <x-hub::icon ref="menu" />
        </button>
        <div class="flex items-center">
            <x-hub::branding.logo iconOnly class="sm:hidden" />
            <x-hub::branding.logo class="hidden sm:block" />
        </div>
    </div>


    <div>
        @include('adminhub::partials.navigation.header-user-dropdown')
    </div>
</header>