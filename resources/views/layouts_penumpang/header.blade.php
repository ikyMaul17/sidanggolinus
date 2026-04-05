<header class="header">
    <a href="{{ route('home') }}" style="color: white; text-decoration: none;" class="logo">Golinus</a>
    <div class="username">
        Hi, {{ Auth::check() ? Auth::user()->nama : 'Penumpang' }}
    </div>
</header>