<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk - Golinus</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.2/dist/sweetalert2.min.css">
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.2/dist/sweetalert2.all.min.js"></script>

  <style>
    body {
      max-width: 480px;
      margin: 0 auto;
      font-family: 'Montserrat', sans-serif;
    }
    .logo {
      font-size: 20px;
      font-weight: 700;
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

  </style>
</head>
<body>

  <!-- Header -->
  <header class="header">
    <a href="{{ route('home') }}" style="color: white; text-decoration: none;" class="logo">Golinus</a>
  </header>

  <!-- Login Form -->
  <section class="container mt-3">
    <div class="text-center">
      <h3>Masuk Penumpang</h3>
      <img src="https://cdni.iconscout.com/illustration/premium/thumb/login-illustration-download-in-svg-png-gif-file-formats--select-an-account-join-the-forum-password-digital-marketing-pack-business-illustrations-8333958.png" alt="Login Banner" class="img-fluid" style="max-width: 60%; border-radius: 10px;">
    </div>

    <form method="POST" action="{{ route('authenticate') }}" class="mt-3">
     @csrf
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Kata Sandi</label>
        <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password" required>
      </div>
      <button type="submit" class="btn btn-success w-100 mb-2">Masuk</button>
      <a href="{{ route('login_supir') }}" class="btn btn-success w-100">Masuk Supir</a>
      <small>Belum punya akun? <a href="{{ route('register_penumpang') }}">Daftar</a></small>
      <br>
      <small>Lupa Kata Sandi? <a href="{{ route('page_reset_password') }}">Reset Kata Sandi</a></small>
    </form>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  @if(session('success'))
  <script>
      Swal.fire({
          title: 'Pemberitahuan!',
          text: "{{ session('success') }}",
          icon: 'success',
          confirmButtonText: 'OK'
      });
  </script>
  @endif
</body>
</html>
