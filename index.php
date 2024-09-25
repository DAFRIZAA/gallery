<?php
include 'koneksi.php';  
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Gallery</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">  
    <link rel="stylesheet" href="assets/css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> 
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white">  <!-- Membuat navbar -->
        <div class="container">
            <!-- Brand (Logo) -->
            <a class="navbar-brand" href="?url=home">  <!-- Logo yang mengarah ke halaman home -->
                <img src="assets/img/3.png" alt="Logo" width="120" height="60">  <!-- Menampilkan logo -->
            </a>

            <!-- Tombol Toggler untuk layar kecil -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>  <!-- Ikon toggle -->
            </button>

            <!-- Tautan Navbar (dapat disembunyikan) -->
            <div class="collapse navbar-collapse justify-content-left" id="navbarNav">
                <div class="navbar-nav">
                    <a class="nav-link" href="?url=home">Home</a>  <!-- Tautan ke halaman home -->
                    <?php if (isset($_SESSION['user_id'])): ?>  <!-- Mengecek apakah pengguna sudah login -->
                        <a class="nav-link" href="?url=upload">Upload</a>  <!-- Tautan untuk mengunggah gambar -->
                        <a class="nav-link" href="?url=album">Album</a>  <!-- Tautan untuk melihat album -->
                        <a class="nav-link" href="?url=profile">Profile</a>  <!-- Tautan untuk melihat profil pengguna -->
                    <?php else: ?>  <!-- Jika pengguna belum login -->
                        <a class="nav-link" href="login.php">Login</a>  <!-- Tautan untuk login -->
                        <a class="nav-link" href="register.php">Registrasi</a>  <!-- Tautan untuk registrasi -->
                    <?php endif; ?>
                </div>

                <?php if (isset($_SESSION['user_id'])): ?>  <!-- Jika pengguna sudah login -->
                    <!-- Tombol Logout di bagian kanan -->
                    <div class="ms-auto">  <!-- Mengatur margin kiri otomatis untuk posisi kanan -->
                        <a href="?url=logout" class="btn btn-outline-danger"><b>Logout</b></a>  <!-- Tautan untuk logout -->
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <?php 
        $url = @$_GET["url"];  // Mengambil parameter URL
        if ($url == 'home') {
            include 'page/home.php';  // Menyertakan halaman home
        } elseif ($url == 'profile') {
            include 'page/profil.php';  // Menyertakan halaman profil
        } else if ($url == 'upload') {
            include 'page/upload.php';  // Menyertakan halaman upload
        } else if ($url == 'album') {
            include 'page/album.php';  // Menyertakan halaman album
        } else if ($url == 'like') {
            include 'page/like.php';  // Menyertakan halaman like
        } else if ($url == 'komentar') {
            include 'page/komentar.php';  // Menyertakan halaman komentar
        } else if ($url == 'detail') {
            include 'page/detail.php';  // Menyertakan halaman detail
        } else if ($url == 'kategori') {
            include 'page/kategori.php';  // Menyertakan halaman kategori
        } else if ($url == 'logout') {
            session_destroy();  // Menghancurkan sesi untuk logout
            header("Location: ?url=home");  // Mengarahkan ke halaman home setelah logout
        } else {
            include 'page/home.php';  // Menyertakan halaman home sebagai default
        }
    ?>

    <script src="assets/js/bootstrap.bundle.min.js"></script>  <!-- Mengimpor JS Bootstrap -->
</body>

</html>
