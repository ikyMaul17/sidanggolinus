<!-- Bottom Navigation (Mobile and Desktop) -->
<nav class="navbar fixed-bottom navbar-light bg-light">
  <div class="container-fluid justify-content-around">
    <a class="nav-link text-center {{ Request::routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}" id="home-link">
      <i class="fas fa-home"></i><br>Dasbor
    </a>
    <a class="nav-link text-center {{ Request::routeIs('tracking') ? 'active' : '' }}" href="{{ route('tracking') }}" id="tracking-link">
      <i class="fas fa-map-marker-alt"></i><br>Map
    </a>
    <a class="nav-link text-center {{ Request::routeIs('history_penumpang') ? 'active' : '' }}" href="{{ route('history_penumpang') }}" id="history-link">
      <i class="fas fa-history"></i><br>Histori
    </a>
    <a class="nav-link text-center {{ Request::routeIs('profile_penumpang') ? 'active' : '' }}" href="{{ route('profile_penumpang') }}" id="profile-link">
      <i class="fas fa-user"></i><br>Profil
    </a>
    <a class="nav-link text-center {{ Auth::check() && Request::routeIs('logout') ? 'active' : '' }}" href="{{ Auth::check() ? route('logout') : route('login_penumpang') }}" id="auth-link">
      <i class="fas fa-sign-in"></i><br>
      {{ Auth::check() ? 'Keluar' : 'Masuk' }}
    </a>
  </div>
</nav>
