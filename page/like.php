<?php
// cek apakah foto yang ada di halaman detail.php sudah di-like oleh user yang sedang login
$cek = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM likefoto WHERE FotoID='$_GET[id]' AND UserID='$_SESSION[user_id]'"));

if ($cek == 0) {
   // Jika belum di-like, maka lakukan proses penyimpanan data like ke database

   // Ambil data FotoID dari parameter URL
   $foto_id = @$_GET['id'];

   // Ambil data UserID dari sesi user yang sedang login
   $user_id = @$_SESSION['user_id'];

   // Ambil tanggal saat ini dalam format 'Y-m-d' (tahun-bulan-hari)
   $tanggal = date('Y-m-d');

   // Query untuk menambahkan data like ke dalam tabel likefoto
   $like = mysqli_query($conn, "INSERT INTO likefoto VALUES('','$foto_id','$user_id','$tanggal')");

   // Setelah data like dimasukkan, arahkan pengguna kembali ke halaman detail.php dengan parameter id foto
   header("Location: ?url=detail&&id=$foto_id");

} else {
   // Jika user yang login sudah like foto ini, maka lakukan proses dislike (hapus like)

   // Ambil data FotoID dari parameter URL
   $foto_id = @$_GET['id'];

   // Ambil data UserID dari sesi user yang sedang login
   $user_id = @$_SESSION['user_id'];

   // Query untuk menghapus data like dari tabel likefoto berdasarkan FotoID dan UserID
   $dislike = mysqli_query($conn, "DELETE FROM likefoto WHERE FotoID='$foto_id' AND UserID='$user_id'");

   // Setelah data dislike dihapus, arahkan pengguna kembali ke halaman detail.php dengan parameter id foto
   header("Location: ?url=detail&&id=$foto_id");
}

