<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Gallery Register</title>
   <link rel="stylesheet" href="assets/css/bootstrap.min.css">
   <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="bg-custom"> <!-- Mengatur kelas latar belakang -->
   <div class="container"> <!-- Membuat kontainer untuk mengatur tata letak -->
      <div class="row justify-content-center align-items-center vh-100"> <!-- Membuat baris dengan pengaturan tengah vertikal -->
         <div class="col-md-6 col-lg-5"> <!-- Mengatur kolom untuk ukuran layar medium dan besar -->
            <div class="card shadow-lg border-2"> <!-- Membuat kartu dengan bayangan -->
               <div class="card-body p-4"> <!-- Mengatur isi kartu dengan padding -->
                  <img src="assets/img/3.png" alt="Logo" class="logo img-fluid"> <!-- Menampilkan logo -->
                  <h4 class="card-title text-center">Halaman Registrasi</h4> <!-- Judul kartu -->
                  <?php
                  // Memeriksa apakah metode request adalah POST
                  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                     // Mengambil data dari form dan membersihkannya untuk mencegah SQL injection
                     $username = mysqli_real_escape_string($conn, $_POST['username']);
                     $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Mengenkripsi password
                     $email = mysqli_real_escape_string($conn, $_POST['email']);
                     $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
                     $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

                     // Memeriksa apakah username atau email sudah terdaftar
                     $cek = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM user WHERE Username='$username' OR Email='$email' "));
                     if ($cek == 0) {
                        // Menyimpan data pengguna baru ke database
                        mysqli_query($conn, "INSERT INTO user (Username, Password, Email, NamaLengkap, Alamat) VALUES ('$username','$password','$email','$nama_lengkap','$alamat')");
                        // Menampilkan pesan sukses dan mengalihkan ke halaman login
                        echo '<div class="alert alert-success">Registrasi Berhasil, Silahkan Login!</div>';
                        echo '<meta http-equiv="refresh" content="1; url=login.php">';
                     } else {
                        // Menampilkan pesan kesalahan jika username atau email sudah ada
                        echo '<div class="alert alert-danger">Maaf, Username atau Email Sudah Terdaftar.</div>';
                     }
                  }
                  ?>
                  <!-- Form untuk registrasi pengguna -->
                  <form action="register.php" method="post" onsubmit="return validateForm()">
                     <div class="form-group mb-3">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username" required>
                     </div>
                     <div class="form-group mb-3">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>
                     </div>
                     <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="example@gmail.com" required>
                     </div>
                     <div class="form-group mb-3">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan Nama Lengkap" required>
                     </div>
                     <div class="form-group mb-3">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukkan Alamat"required>
                     </div>
                     <!-- Tombol untuk mengirimkan form -->
                     <input type="submit" value="Daftar" class="btn btn-primary my-3" name="submit">
                     <!-- Tautan ke halaman login jika sudah punya akun -->
                     <p class="mt-3 text-center">Sudah Punya Akun? <a href="login.php" class="link-primary">Login Sekarang.</a> Atau <a href="index.php">Kembali</a></p>
                  </form>

                  <script>
                     function validateForm() {
                        var username = document.getElementById("username").value;
                        var password = document.getElementById("password").value;
                        var email = document.getElementById("email").value;
                        var nama_lengkap = document.getElementById("nama_lengkap").value;
                        var alamat = document.getElementById("alamat").value;

                        // Cek apakah ada field yang kosong
                        if (username === "" || password === "" || email === "" || nama_lengkap === "" || alamat === "") {
                           alert("Semua field harus diisi!");
                           return false; // Menghentikan pengiriman form jika ada yang kosong
                        }
                        return true; // Melanjutkan pengiriman form jika semua terisi
                     }
                  </script>

               </div>
            </div>
         </div>
      </div>
   </div>
   <script src="assets/js/bootstrap.bundle.min.js"></script> <!-- Mengimpor JS Bootstrap -->
</body>

</html>