<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pendaftaran</title>
</head>
<body>
    <h1>Selamat, {{ $name }}!</h1>
    <p>Anda telah berhasil mendaftar. Berikut adalah detail akun Anda:</p>
    <p>Nomor Registrasi Anda : {{ $nomor_registrasi }}</p> <!-- Tampilkan nomor registrasi -->
    <p><strong>Username:</strong> {{ $email }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>
    <p>Silakan login untuk melanjutkan proses Berikutnya. <a href="http://127.0.0.1:8000/login"> Login</a></p>
</body>
</html>