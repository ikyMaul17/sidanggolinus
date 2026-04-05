
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.2/dist/sweetalert2.min.css">

    <title>Login | Golinus</title>
    <style>

      body{
          font-family: 'Montserrat', sans-serif;
          background: #ececec;
      }

      /*------------ Login container ------------*/

      .box-area{
          width: 930px;
      }

      /*------------ Right box ------------*/

      .right-box{
          padding: 40px 30px 40px 40px;
      }

      /*------------ Custom Placeholder ------------*/

      ::placeholder{
          font-size: 16px;
      }

      .rounded-4{
          border-radius: 20px;
      }
      .rounded-5{
          border-radius: 30px;
      }


      /*------------ For small screens------------*/

      @media only screen and (max-width: 768px){

          .box-area{
              margin: 0 10px;

          }
          .left-box{
              height: 100px;
              overflow: hidden;
          }
          .right-box{
              padding: 20px;
          }

      }
    </style>
</head>
<body>

    <!----------------------- Main Container -------------------------->

     <div class="container d-flex justify-content-center align-items-center min-vh-100">

    <!----------------------- Login Container -------------------------->

       <div class="row border rounded-5 p-3 bg-white shadow box-area">

    <!--------------------------- Left Box ----------------------------->

       <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #5cb85c;">
           <div class="featured-image mb-3">
            <img class="img-fluid" src="https://www.skoolbeep.com/blog/wp-content/uploads/2020/12/HOW-DO-TRACKING-APPS-HELP-MANAGE-SCHOOL-BUSES-768x394.png" alt="">
           </div>
           
           <p class="text-white fs-2" style="font-family: 'Courier New', Courier, monospace; font-weight: 600;">Golinus Admin</p>
       </div> 

    <!-------------------- ------ Right Box ---------------------------->
        
       <div class="col-md-6 right-box">
          <div class="row align-items-center">
         
                &nbsp;<br>

                <div class="header-text mb-4">
                     <h2>Login Form</h2>
                </div>

                <form method="POST" action="{{ route('authenticate_admin') }}">
                  @csrf

                <div class="input-group mb-3">
                    <input required type="email" name="email" class="form-control form-control-lg bg-light fs-6" placeholder="Email">
                </div>
                @error('username')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="input-group mb-1">
                    <input required type="password" name="password" class="form-control form-control-lg bg-light fs-6" placeholder="Password">
                </div>
                @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <br>

                <div class="input-group mb-3">
                    <button class="btn btn-lg btn-success w-100 fs-6" type="submit">Login</button>
                </div>
                </form>

                &nbsp;<br>
                &nbsp;<br>
               
          </div>
       </div> 

      </div>
    </div>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.2/dist/sweetalert2.all.min.js"></script>

    @if(session('success'))
    <script>
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'OK'
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            title: 'Peringatan!',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonText: 'OK'
        });
    </script>
    @endif

</body>
</html>