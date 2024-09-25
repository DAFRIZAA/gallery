<?php
// Mengambil data 'id' foto dari URL
$foto_id = @$_GET["id"];

// Mengambil data 'user_id' dari session pengguna yang login
$user_id = @$_SESSION["user_id"];

// Mengambil 'komentar_id' dari URL, ini digunakan untuk menentukan komentar yang akan dihapus
$komen_id = @$_GET['komentar_id'];

// Mengecek apakah komentar yang akan dihapus benar-benar milik user yang login
$cek = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM komentarfoto WHERE UserID='$user_id' AND FotoID='$foto_id' AND KomentarID='$komen_id'"));

if ($cek > 0) {
   // Jika komentar yang dihapus adalah milik user yang login
   // Menjalankan query untuk menghapus komentar berdasarkan 'KomentarID'
   $delete = mysqli_query($conn, "DELETE FROM komentarfoto WHERE KomentarID='$komen_id'");

   // Menampilkan pesan alert bahwa komentar berhasil dihapus
   echo '<script>alert("Anda berhasil menghapus komentar ini");</script>';

   // Me-refresh halaman dan mengarahkan user kembali ke halaman detail dengan ID foto yang sama
   echo '<meta http-equiv="refresh" content="0; url=?url=detail&&id=' . @$foto_id . '">';
} else {
   // Jika komentar bukan milik user yang login, maka tampilkan pesan gagal hapus
   // Menampilkan pesan alert bahwa user tidak berhak menghapus komentar ini
   $alert = 'Gagal hapus komentar';
   echo '<script>alert("Anda tidak berhak menghapus komentar ini");</script>';

   // Me-refresh halaman dan mengarahkan user kembali ke halaman detail dengan ID foto yang sama
   echo '<meta http-equiv="refresh" content="0; url=?url=detail&&id=' . @$foto_id . '">';
}
