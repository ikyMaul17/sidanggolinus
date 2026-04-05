<!-- Bottom Navigation (Mobile and Desktop) -->
<nav class="navbar fixed-bottom navbar-light bg-light">
  <div class="container-fluid justify-content-around">
    <a class="nav-link text-center {{ Request::routeIs('home_supir') ? 'active' : '' }}" href="{{ route('home_supir') }}" id="home-link">
      <i class="fas fa-home"></i><br>Dasbor
    </a>
    <a class="nav-link text-center {{ Request::routeIs('history_supir') ? 'active' : '' }}" href="{{ route('history_supir') }}" id="history-link">
      <i class="fas fa-history"></i><br>Histori
    </a>
    <a class="nav-link text-center {{ Request::routeIs('profile_supir') ? 'active' : '' }}" href="{{ route('profile_supir') }}" id="profile-link">
      <i class="fas fa-user"></i><br>Profil
    </a>
    <a class="nav-link text-center {{ Auth::check() && Request::routeIs('logout') ? 'active' : '' }}" href="{{ Auth::check() ? route('logout') : route('login') }}" id="auth-link">
      <i class="fas fa-sign-in"></i><br>
      {{ Auth::check() ? 'Keluar' : 'Masuk' }}
    </a>
  </div>
</nav>
