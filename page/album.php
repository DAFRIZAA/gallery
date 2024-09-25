<div class="container">
   <div class="row justify-content-center">
      <div class="col-12">
         <br>
         <center><h2>Halaman Album</h2></center>
         <div class="row">
            <!-- Bagian Form -->
            <div class="col-md-4">
               <div class="card my-4">
                  <div class="card-body">
                     <div class="text-center">
                        <h4>
                           <!-- Tampilkan judul form berdasarkan kondisi -->
                           <?php if (isset($_GET['edit'])): ?>
                              Edit Album
                           <?php else: ?>
                              Tambah Album
                           <?php endif; ?>
                        </h4>
                     </div>
                     <?php 
                     // Ambil data dari POST
                     $submit = @$_POST['submit'];
                     $albumID = @$_GET['albumid'];

                     // Tambah Album
                     if ($submit == 'Simpan') {
                        $nama_album = @$_POST['nama_album'];
                        $deskripsi_album = @$_POST['deskripsi_album'];
                        $tanggal = date('Y-m-d');
                        $user_id = @$_SESSION['user_id'];

                        // Query untuk menambahkan album
                        $insert = mysqli_query($conn, "INSERT INTO album (NamaAlbum, Deskripsi, TanggalDibuat, UserID) 
                                                       VALUES ('$nama_album', '$deskripsi_album', '$tanggal', '$user_id')");
                        if ($insert) {
                           echo 'Berhasil Membuat Album';
                           echo '<meta http-equiv="refresh" content="0.8; url=?url=album">'; // Redirect setelah berhasil
                        } else {
                           echo 'Gagal Membuat Album';
                           echo '<meta http-equiv="refresh" content="0.8; url=?url=album">'; // Redirect jika gagal
                        }
                     } 
                     
                     // Edit Album
                     elseif (isset($_GET['edit'])) {
                        if ($submit == 'Ubah') {
                           $nama_album = @$_POST['nama_album'];
                           $deskripsi_album = @$_POST['deskripsi_album'];

                           // Query untuk mengupdate album
                           $update = mysqli_query($conn, "UPDATE album 
                                                          SET NamaAlbum='$nama_album', Deskripsi='$deskripsi_album' 
                                                          WHERE AlbumID='$albumID'");
                           if ($update) {
                              echo 'Berhasil Mengubah Album';
                              echo '<meta http-equiv="refresh" content="0.8; url=?url=album">'; // Redirect setelah berhasil
                           } else {
                              echo 'Gagal Mengubah Album';
                              echo '<meta http-equiv="refresh" content="0.8; url=?url=album">'; // Redirect jika gagal
                           }
                        }
                     } 
                     
                     // Hapus Album
                     elseif (isset($_GET['hapus'])) {
                        $hapus = mysqli_query($conn, "DELETE FROM album WHERE AlbumID='$albumID'");
                        if ($hapus) {
                           echo 'Berhasil Hapus Album';
                           echo '<meta http-equiv="refresh" content="0.8; url=?url=album">'; // Redirect setelah berhasil
                        } else {
                           echo 'Gagal Hapus Album';
                           echo '<meta http-equiv="refresh" content="0.8; url=?url=album">'; // Redirect jika gagal
                        }
                     }

                     // Fetch data album untuk di-edit
                     $val = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM album WHERE AlbumID='$albumID'"));
                     ?>
                     <div class="text">
                        <?php if (!isset($_GET['edit'])): ?>
                        <!-- Form untuk tambah album -->
                        <form action="?url=album" method="post">
                           <div class="form-group">
                              <label>Nama Album</label>
                              <input type="text" class="form-control" required name="nama_album">
                           </div>
                           <div class="form-group">
                              <label>Deskripsi Album</label>
                              <textarea name="deskripsi_album" class="form-control" required cols="30" rows="5"></textarea>
                           </div>
                           <input type="submit" value="Simpan" name="submit" class="btn btn-primary my-3">
                        </form>
                        <?php elseif (isset($_GET['edit'])): ?>
                        <!-- Form untuk edit album -->
                        <form action="?url=album&edit&albumid=<?= $val['AlbumID'] ?>" method="post">
                           <div class="form-group">
                              <label>Nama Album</label>
                              <input type="text" class="form-control" value="<?= $val['NamaAlbum'] ?>" required name="nama_album">
                           </div>
                           <div class="form-group">
                              <label>Deskripsi Album</label>
                              <textarea name="deskripsi_album" class="form-control" required cols="30" rows="5"><?= $val['Deskripsi'] ?></textarea>
                           </div>
                           <input type="submit" value="Ubah" name="submit" class="btn btn-primary my-3">
                        </form>
                        <?php endif; ?>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Bagian Tabel -->
            <br>
            <div class="col-md-8">
               <div class="card my-4">
                  <div class="card-body">
                     <?php 
                     // Ambil album user berdasarkan session user_id
                     $userid = @$_SESSION['user_id'];
                     $albums = mysqli_query($conn, "SELECT * FROM album WHERE UserID='$userid'");
                     ?>
                     <!-- Tabel untuk menampilkan daftar album -->
                     <table class="table table-hover text-center">
                        <thead>
                           <tr>
                              <th>No</th>
                              <th>Nama Album</th>
                              <th>Deskripsi Album</th>
                              <th>Tanggal Dibuat</th>
                              <th>Aksi</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php 
                           $i = 1;
                           foreach ($albums as $album): ?>
                           <tr>
                              <td><?= $i++ ?></td>
                              <td><?= $album['NamaAlbum'] ?></td>
                              <td><?= $album['Deskripsi'] ?></td>
                              <td><?= $album['TanggalDibuat'] ?></td>
                              <td>
                                 <!-- Tombol simbol Edit, Hapus, dan Lihat Foto -->
                                 <a href="?url=album&edit&albumid=<?= $album['AlbumID'] ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> <!-- Simbol edit -->
                                 </a>
                                 <a href="?url=album&hapus&albumid=<?= $album['AlbumID'] ?>" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash-alt"></i> <!-- Simbol hapus -->
                                 </a>
                                 <a href="?url=kategori&albumid=<?= $album['AlbumID'] ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-image"></i><!-- Tombol untuk melihat foto dalam album -->
                                 </a>
                              </td>
                           </tr>
                           <?php endforeach; ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
