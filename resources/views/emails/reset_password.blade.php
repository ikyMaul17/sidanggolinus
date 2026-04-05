<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background: #007BFF;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 2px;
        }
        .content {
            padding: 20px;
        }
        .content h1 {
            font-size: 20px;
            color: #333;
        }
        .content p {
            font-size: 16px;
            line-height: 1.6;
        }
        .content ul {
            list-style-type: none;
            padding: 0;
        }
        .content ul li {
            margin: 10px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
            color: #555;
        }
        .footer {
            text-align: center;
            padding: 10px;
            background: #007BFF;
            color: #fff;
            font-size: 14px;
        }
        .footer a {
            color: #ffd700;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Golinus</h1>
        </div>
        <div class="content">
            <h1>Halo, {{ $user->nama }}</h1>
            <p>Kami menerima permintaan untuk reset password akun Anda.</p>
            <p>Klik tautan di bawah ini untuk melanjutkan proses reset password:</p>
            <a href="{{ url('input_reset_password')}}">Link Reset Paswword</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Golinus. 
            <br> Hubungi kami di <a href="mailto:rizkymmaulana17@gmail.com">rizkymmaulana17@gmail.com</a>
        </div>
    </div>
</body>
</html>
