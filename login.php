<?php
include 'koneksi.php';  // Mengimpor file koneksi.php untuk menghubungkan ke database
session_start();  // Memulai sesi untuk menyimpan informasi pengguna
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">  <!-- Set karakter encoding ke UTF-8 -->
   <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Mengatur agar tampilan responsif pada perangkat -->
   <title>Gallery Login</title>  <!-- Judul halaman -->
   <link rel="stylesheet" href="assets/css/bootstrap.min.css">  <!-- Mengimpor CSS Bootstrap -->
   <link rel="stylesheet" href="assets/css/style.css">  <!-- Mengimpor CSS kustom -->
</head>

<body class="bg-custom">  <!-- Mengatur kelas latar belakang -->
   <div class="container">  <!-- Membuat kontainer untuk mengatur tata letak -->
      <div class="row justify-content-center align-items-center vh-100">  <!-- Membuat baris dengan pengaturan tengah vertikal -->
         <div class="col-md-6 col-lg-5">  <!-- Mengatur kolom untuk ukuran layar medium dan besar -->
            <div class="card shadow-lg border-2">  <!-- Membuat kartu dengan bayangan -->
               <div class="card-body p-4">  <!-- Mengatur isi kartu dengan padding -->
                  <img src="assets/img/3.png" alt="Logo" class="logo img-fluid mb-3">  <!-- Menampilkan logo -->
                  <h4 class="card-title text-center">Halaman Login</h4>  <!-- Judul kartu -->

                  <?php
                  // Cek apakah form dikirimkan melalui metode POST
                  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                     // Mengamankan input username agar tidak terjadi SQL Injection
                     $username = mysqli_real_escape_string($conn, $_POST['username']);
                     $password = $_POST['password'];  // Mengambil password dari form

                     // Menyiapkan dan menjalankan query SQL yang aman menggunakan prepared statement
                     $stmt = $conn->prepare("SELECT * FROM user WHERE Username = ?");  // Menyiapkan query
                     $stmt->bind_param("s", $username);  // Mengikat parameter (string) untuk query
                     $stmt->execute();  // Menjalankan query
                     $result = $stmt->get_result();  // Mengambil hasil dari query

                     // Cek apakah username ditemukan di database
                     if ($result->num_rows > 0) {
                        $user = $result->fetch_assoc();  // Mengambil data pengguna dari hasil query

                        // Memverifikasi apakah password yang dimasukkan sesuai dengan yang ada di database
                        if (password_verify($password, $user['Password'])) {
                           session_regenerate_id();  // Mencegah serangan session fixation dengan meregenerasi session ID

                           // Menyimpan informasi pengguna dalam sesi
                           $_SESSION['username'] = $user['Username'];
                           $_SESSION['user_id'] = $user['UserID'];
                           $_SESSION['email'] = $user['Email'];
                           $_SESSION['nama_lengkap'] = $user['NamaLengkap'];

                           echo '<div class="alert alert-success">Login Berhasil!</div>';  // Menampilkan pesan sukses
                           echo '<meta http-equiv="refresh" content="1; url=./">';  // Mengarahkan pengguna ke halaman utama setelah 1 detik
                        } else {
                           echo '<div class="alert alert-danger">Password Salah!</div>';  // Menampilkan pesan kesalahan jika password salah
                        }
                     } else {
                        echo '<div class="alert alert-danger">Username Tidak Ditemukan!</div>';  // Menampilkan pesan jika username tidak ditemukan
                     }

                     $stmt->close();  // Menutup prepared statement
                  }
                  ?>

                  <form action="login.php" method="post">  <!-- Form untuk login pengguna -->
                     <div class="form-group mb-3">
                        <label for="username">Username</label>  <!-- Label untuk input username -->
                        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username" required>  <!-- Input untuk username -->
                     </div>
                     <div class="form-group mb-3">
                        <label for="password">Password</label>  <!-- Label untuk input password -->
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>  <!-- Input untuk password -->
                     </div>
                     <input type="submit" value="Login" class="btn btn-primary my-3" name="submit">  <!-- Tombol untuk mengirimkan form -->
                     <p class="mt-3 text-center">Belum Punya Akun? <a href="register.php" class="link-primary">Registrasi Sekarang</a> Atau <a href="index.php">Kembali</a></p>
                     <p class="mt-3 text-center"> </p>  <!-- Tautan ke halaman registrasi -->
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
   <script src="assets/js/bootstrap.bundle.min.js"></script>  <!-- Mengimpor JS Bootstrap -->
</body>

</html>
