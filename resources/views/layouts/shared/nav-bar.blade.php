<nav class="main-header navbar navbar-expand navbar-light d-flex justify-content-between"
    style="background-color: #708090">
    <!-- Left navbar links -->
    

    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button"><i
                    class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item   d-sm-inline-block">
            <img src="{{ asset('assets/web-images/main-logo.png') }}" height="35" alt="">
            {{-- <a href="index3.html" class="nav-link">Home</a> --}}
        </li>
        {{--    <li class="nav-item d-none d-sm-inline-block">
      <a href="#" class="nav-link">Contact</a>
    </li> --}}
    </ul>
    @auth
    <div class="ms-auto ">
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button
                    class="inline-flex items-center  border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-[#708090] hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                    <div>{{ Auth::user()->name }}</div>

                    <div class="ml-1">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
            </x-slot>


            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit', app()->getLocale())">
                    {{ __('Profile') }}
                </x-dropdown-link>


                @foreach (config('app.available_locales') as $locale)
                <x-dropdown-link :href="route(request()->route()->getName(),[$locale,''])">
                    <span @if (app()->getLocale() == $locale) 
                        style="font-weight: bold; text-decoration: underline" 
                        @endif>
                        {{ strtoupper($locale) }}
                    </span>
                </x-dropdown-link>
                @endforeach

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout', app()->getLocale()) }}">
                    @csrf

                    <x-dropdown-link :href="route('logout', app()->getLocale())"
                        onclick="event.preventDefault();
                                    this.closest('form').submit(); localStorage.clear()">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>

    </div>
    @endauth
 


</nav>
<!-- /.navbar -->
