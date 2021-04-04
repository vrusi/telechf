 @if (Auth::check())

 <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
     <a class="navbar-brand" href="{{ url('/') }}">
         {{ config('app.name', 'Laravel') }}
     </a>

     <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
     </button>

     <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
         <!-- Left Side Of Navbar -->

         @if (!Auth::user()->is_coordinator)
         <ul class="navbar-nav">
             <li class="{{ Request::is('dashboard*') ? 'nav-item active' : 'nav-item' }}">
                 <a class="nav-link" href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
             </li>

             <li class="{{ Request::is('profile*') ? 'nav-item active' : 'nav-item' }}">
                 <a class="nav-link" href="{{ route('profile') }}">{{ __('Profile') }}</a>
             </li>

             <li class="{{ Request::is('therapy*') ? 'nav-item active' : 'nav-item' }}">
                 <a class="nav-link" href="{{ route('therapy') }}">{{ __('Therapy') }}</a>
             </li>

             <li class="{{ Request::is('measurements*') ? 'nav-item active' : 'nav-item' }}">
                 <a class="nav-link" href="{{ route('measurements.create') }}">{{ __('Measurements') }}</a>
             </li>

             <li class="{{ Request::is('contacts*') ? 'nav-item active' : 'nav-item' }}">
                 <a class="nav-link" href="{{ route('contacts.index') }}">{{ __('Contacts') }}</a>
             </li>

             <li class="{{ Request::is('charts*') ? 'nav-item active' : 'nav-item' }}">
                 <a class="nav-link" href="{{ route('charts') }}">{{ __('Charts') }}</a>
             </li>
         </ul>
         @else
         <ul class="navbar-nav">

             <li class="{{ Request::is('coordinator/dashboard*') ? 'nav-item active' : 'nav-item' }}">
                 <a class="nav-link" href="{{ route('coordinator.dashboard') }}">{{ __('Dashboard') }}</a>
             </li>
         </ul>

         @endif

         <!-- Right Side Of Navbar -->
         <ul class="navbar-nav ml-auto">
             <!-- Authentication Links -->
             @guest
             @if (Route::has('login'))
             <li class="nav-item">
                 <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
             </li>
             @endif

             @if (Route::has('register'))
             <li class="nav-item">
                 <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
             </li>
             @endif
             @else
             <li class="nav-item dropdown">
                 <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                     @if(Auth::user()->is_coordinator)
                     Coordinator: {{ Auth::user()->name.' '.Auth::user()->surname }}

                     @else
                     Patient: {{ Auth::user()->name.' '.Auth::user()->surname }}
                     @endif
                 </a>

                 <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                     <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                         {{ __('Logout') }}
                     </a>

                     <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                         @csrf
                     </form>
                 </div>
             </li>
             @endguest
         </ul>
     </div>
 </nav>
 @endif
