<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dasbor - GoLinus</title>
  <link rel="icon" type="image/x-icon" href="https://upload.wikimedia.org/wikipedia/commons/a/ab/Aiga_bus_on_blue_circle.svg">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.2/dist/sweetalert2.min.css">

   <style>
    body {
      max-width: 480px;
      margin: 0 auto;
      font-family: 'Montserrat', sans-serif;
    }

    /* Menjaga bottom navigation tetap tampil di bawah */
    .navbar {
      position: fixed;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%); /* Memastikan navbar berada di tengah */
      width: 480px; /* Lebar navbar 480px */
      z-index: 999; /* Untuk memastikan navbar tetap di atas konten */
      padding-left: 45px;
      padding-right: 45px;
      box-sizing: border-box;
      font-size: 14px;
    }

    * Container navbar untuk merapikan item */
    .navbar .container-fluid {
      padding: 0;
      justify-content: space-around;
    }

    /* Gaya tambahan untuk memastikan ukuran font ikon tetap responsif di mobile */
    .navbar .nav-link {
      padding: 2px 2px;
      text-align: center;
    }

    .navbar .nav-link i {
      font-size: 18px;
    }

    .logo {
      font-size: 20px;
      font-weight: 700; /* Bold untuk tampilan lebih kuat */
      letter-spacing: 1px;
    }

    .username {
      font-size: 15px;
      font-weight: 600; /* Bold untuk tampilan lebih kuat */
      letter-spacing: 1px;
      font-family: sans-serif;
    }

    .header {
      height: 50px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 15px;
      background-color: #5cb85c;
      color: white;
      border-radius: 0 0 7px 7px;
    }
    .carousel {
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 10px;
      margin-left: 1.5%;
      margin-right: 1.5%;
      margin-top: 20px;
    }
    .carousel img {
      width: 100%;
      max-width: 450px; /* Fixed width */
      height: auto; /* Maintain aspect ratio */
      border-radius: 10px;
    }
    .card-menu {
      height: 120px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.2s;
    }
    .card-menu:hover {
      transform: scale(1.05);
    }
    .card-menu i {
      font-size: 32px;
      color: #5cb85c;
    }

    .nav-link.active {
      color: #5cb85c; /* Biru */
      font-weight: bold; /* Menebalkan teks untuk menandakan status aktif */
    }
  </style>

    @yield('style')
    @section('css')
    @show

</head>

<body>
  @include('layouts_penumpang.header')

  @yield('content')

<br>
<br>
<br>
<br>
  
  @include('layouts_penumpang.bottom')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.2/dist/sweetalert2.all.min.js"></script>
  <script>
    // Event listeners untuk mengganti status aktif pada klik menu
    document.getElementById('home-link').addEventListener('click', function() {
      setActiveLink('home-link');
    });
    document.getElementById('tracking-link').addEventListener('click', function() {
      setActiveLink('tracking-link');
    });
    document.getElementById('history-link').addEventListener('click', function() {
      setActiveLink('history-link');
    });
    document.getElementById('profile-link').addEventListener('click', function() {
      setActiveLink('profile-link');
    });
    document.getElementById('auth-link').addEventListener('click', function() {
      setActiveLink('auth-link');
    });

    function setActiveLink(activeId) {
      // Hapus kelas active dari semua link
      const links = document.querySelectorAll('.nav-link');
      links.forEach(link => link.classList.remove('active'));

      // Tambahkan kelas active ke link yang dipilih
      document.getElementById(activeId).classList.add('active');
    }
  </script>
        
    @section('js')
    @show
    @yield('script')
</body>