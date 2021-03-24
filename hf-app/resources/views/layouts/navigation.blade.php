 <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
     <a class="navbar-brand" href="{{ url('/') }}">
         {{ config('app.name', 'Laravel') }}
     </a>
     <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
     </button>
     <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
         <!-- Left Side Of Navbar -->
         <div class="navbar-nav">
             <a class="nav-item nav-link active" href="{{ route('dashboard') }}">{{ __('Dashboard') }} <span class="sr-only">(current)</span></a>
             <a class="nav-item nav-link" href="{{ route('measurements.index') }}">{{ __('Measurements') }}</a>
             <a class="nav-item nav-link" href="{{ route('contacts.index') }}">{{ __('Contacts') }}</a>
         </div>

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
                     {{ Auth::user()->name }}
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
