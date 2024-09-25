<div class="container">
   <div class="row">
      <!-- Bagian header yang berisi tombol kembali ke halaman album -->
      <div class="col-12 py-4 d-flex justify-content-between align-items-center">
         <a href="?url=album" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke album <!-- Ikon panah kiri dan teks untuk kembali ke halaman album -->
         </a>
      </div>

      <?php 
      // Mengambil data foto berdasarkan AlbumID dari URL
      $kategori = mysqli_query($conn, "SELECT foto.*, album.NamaAlbum FROM foto INNER JOIN album ON foto.AlbumID=album.AlbumID WHERE foto.AlbumID='{$_GET['albumid']}'");

      // Cek apakah ada foto dalam album tersebut
      if (mysqli_num_rows($kategori) > 0):
         // Jika ada, tampilkan data foto yang didapatkan
         foreach($kategori as $kat):
      ?>

      <div class="col-6 col-md-4 col-lg-3 mb-4">
         <!-- Setiap foto ditampilkan dalam sebuah kartu -->
         <div class="card shadow-sm h-100">
            <!-- Menampilkan gambar dengan style modern (rounded corners dan aspect ratio 1:1) -->
            <img src="uploads/<?= $kat['LokasiFile'] ?>" alt="<?= $kat['JudulFoto'] ?>" class="object-fit-cover card-img-top" style="aspect-ratio: 1/1; width: 100%; border-radius: 8px 8px 0 0;">
            
            <!-- Bagian body kartu untuk menampilkan judul foto dan nama album -->
            <div class="card-body">
               <h5 class="card-title mb-2"><?= $kat['JudulFoto'] ?></h5> <!-- Judul foto -->
               <p class="card-text text-muted small">Album: <?= $kat['NamaAlbum'] ?></p> <!-- Nama album -->
               
               <!-- Tautan untuk melihat detail foto -->
               <a href="?url=detail&&id=<?= $kat['FotoID'] ?>" class="btn btn-outline-primary btn-sm mt-2 w-100">Lihat Detail</a>
            </div>
         </div>
      </div>

      <?php 
         endforeach; 
      else:
      ?>
      <!-- Jika tidak ada foto dalam album, tampilkan pesan peringatan -->
      <div class="col-12 py-5 text-center">
         <div class="alert alert-warning" role="alert">
            Tidak ada foto dalam album ini.
         </div>
      </div>

      <?php endif; ?>
   </div>
</div>

<!-- Memuat pustaka ikon dari FontAwesome -->
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
