<header class="header">
    <div class="logo">Golinus</div>
    <div class="username">
        Hi, {{ Auth::check() ? Auth::user()->nama : 'Penumpang' }}
    </div>
</header>