<?php
include 'koneksi.php'; // Menghubungkan ke file koneksi database

// Inisialisasi variabel untuk menyimpan pesan alert
$alertMessage = '';

// Pastikan session sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Menangani pengiriman formulir
$submit = @$_POST['submit'];
$fotoid = @$_GET['fotoid'];

// Cek jika tombol simpan ditekan
if ($submit == 'Simpan') {
    // Cek apakah ada album
    $album = mysqli_query($conn, "SELECT * FROM album WHERE UserID='$_SESSION[user_id]'");

    // Jika tidak ada album, tampilkan pesan peringatan
    if (mysqli_num_rows($album) == 0) {
        $alertMessage = '<div class="alert alert-warning">Gagal Upload Foto <a href="?url=album">Tambahkan Album</a> terlebih dahulu.</div>';
    } else {
        // Lanjutkan upload foto
        $judul_foto = @$_POST['judul_foto'];
        $deskripsi_foto = @$_POST['deskripsi_foto'];
        $nama_file = @$_FILES['namafile']['name'];
        $tmp_foto = @$_FILES['namafile']['tmp_name'];
        $tanggal = date('Y-m-d');
        $album_id = @$_POST['album_id'];
        $user_id = @$_SESSION['user_id'];

        // Validasi ekstensi file
        $allowed_extensions = ['jpg', 'png', 'jpeg'];
        $file_extension = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

        // Cek apakah ekstensi file valid
        if (in_array($file_extension, $allowed_extensions)) {
            // Pindahkan file foto ke folder uploads
            if (move_uploaded_file($tmp_foto, "uploads/" . $nama_file)) {
                // Masukkan data foto ke database
                $insert = mysqli_query($conn, "INSERT INTO foto (JudulFoto, DeskripsiFoto, LokasiFile, TanggalUnggah, AlbumID, UserID) VALUES ('$judul_foto', '$deskripsi_foto', '$nama_file', '$tanggal', '$album_id', '$user_id')");
                if ($insert) {
                    $alertMessage = '<div class="alert alert-success">Foto berhasil diupload.</div>';
                } else {
                    $alertMessage = '<div class="alert alert-danger">Gagal upload foto.</div>';
                }
            } else {
                $alertMessage = '<div class="alert alert-danger">Gagal memindahkan file foto.</div>';
            }
        } else {
            $alertMessage = '<div class="alert alert-danger">Ekstensi file tidak diperbolehkan.</div>';
        }
    }
}

// Menangani edit foto
if (isset($_GET['edit']) && $submit == 'Ubah') {
    $judul_foto = @$_POST['judul_foto'];
    $deskripsi_foto = @$_POST['deskripsi_foto'];
    $album_id = @$_POST['album_id'];
    $foto_id = @$_GET['fotoid'];

    // Cek apakah pengguna mengupload file baru
    if (!empty($_FILES['namafile']['name'])) {
        $nama_file = $_FILES['namafile']['name'];
        $tmp_foto = $_FILES['namafile']['tmp_name'];
        $file_extension = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

        // Cek apakah ekstensi file valid
        if (in_array($file_extension, ['jpg', 'png', 'jpeg'])) {
            // Ambil file lama untuk dihapus
            $old_file_query = mysqli_query($conn, "SELECT LokasiFile FROM foto WHERE FotoID='$foto_id'");
            $old_file = mysqli_fetch_assoc($old_file_query)['LokasiFile'];

            // Pindahkan file dan update data di database
            if (move_uploaded_file($tmp_foto, "uploads/" . $nama_file)) {
                // Hapus file lama
                if (file_exists("uploads/" . $old_file)) {
                    unlink("uploads/" . $old_file);
                }

                // Update data foto di database
                $update = mysqli_query($conn, "UPDATE foto SET JudulFoto='$judul_foto', DeskripsiFoto='$deskripsi_foto', LokasiFile='$nama_file', AlbumID='$album_id' WHERE FotoID='$foto_id'");
                if ($update) {
                    header('Location: ?url=upload'); // Arahkan setelah sukses
                    exit();
                } else {
                    $alertMessage = '<div class="alert alert-danger">Gagal memperbarui foto.</div>';
                }
            } else {
                $alertMessage = '<div class="alert alert-danger">Gagal memindahkan file foto.</div>';
            }
        } else {
            $alertMessage = '<div class="alert alert-danger">Ekstensi file tidak diperbolehkan.</div>';
        }
    } else {
        // Update data tanpa mengubah file
        $update = mysqli_query($conn, "UPDATE foto SET JudulFoto='$judul_foto', DeskripsiFoto='$deskripsi_foto', AlbumID='$album_id' WHERE FotoID='$foto_id'");
        if ($update) {
            header('Location: ?url=upload'); // Arahkan setelah sukses
            exit();
        } else {
            $alertMessage = '<div class="alert alert-danger">Gagal memperbarui foto.</div>';
        }
    }
}

// Menangani hapus foto
if (isset($_GET['hapus'])) {
    $fotoid = $_GET['fotoid'];

    // Ambil file untuk dihapus
    $old_file_query = mysqli_query($conn, "SELECT LokasiFile FROM foto WHERE FotoID='$fotoid'");
    $old_file = mysqli_fetch_assoc($old_file_query)['LokasiFile'];

    // Hapus data dari database dan file dari folder
    $delete = mysqli_query($conn, "DELETE FROM foto WHERE FotoID='$fotoid'");
    if ($delete) {
        if (file_exists("uploads/" . $old_file)) {
            unlink("uploads/" . $old_file); // Hapus file dari server
        }
        header('Location: ?url=upload'); // Arahkan setelah sukses
        exit();
    } else {
        $alertMessage = '<div class="alert alert-danger">Gagal menghapus foto.</div>';
    }
}

// Mengambil data album
$album = mysqli_query($conn, "SELECT * FROM album WHERE UserID='$_SESSION[user_id]'");

// Query untuk menampilkan semua foto user
$foto = mysqli_query($conn, "SELECT f.*, a.NamaAlbum FROM foto f JOIN album a ON f.AlbumID = a.AlbumID WHERE f.UserID='$_SESSION[user_id]'");

// Cek apakah query berhasil
if (!$foto) {
    die('Query Error: ' . mysqli_error($conn));
}

$val = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM foto WHERE FotoID='$fotoid'"));
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <br>
            <center>
                <h2>Halaman Upload Foto</h2>
            </center>

            <div class="row">
                <!-- Bagian Form -->
                <div class="col-md-4">
                    <div class="card my-4">
                        <div class="card-body">
                            <div class="text-center">
                                <h4>
                                    <?php if (isset($_GET['edit'])): ?>
                                        Edit Foto
                                    <?php else: ?>
                                        Tambah Foto
                                    <?php endif; ?>
                                </h4>
                            </div>

                            <!-- Tampilkan alert jika ada pesan -->
                            <?php if ($alertMessage): ?>
                                <?= $alertMessage; ?>
                            <?php endif; ?>

                            <!-- Form Upload Foto -->
                            <?php if (!isset($_GET['edit'])): ?>
                                <form action="?url=upload" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label>Judul Foto</label>
                                        <input type="text" class="form-control" required name="judul_foto">
                                    </div>
                                    <div class="form-group">
                                        <label>Deskripsi Foto</label>
                                        <textarea name="deskripsi_foto" class="form-control" required cols="30" rows="2"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Pilih Gambar</label>
                                        <input type="file" name="namafile" class="form-control" required>
                                        <small class="text-danger">File harus berupa: *.jpg, *.png, *.jpeg</small>
                                    </div>
                                    <div class="form-group">
                                        <label>Pilih Album</label>
                                        <select name="album_id" class="form-select">
                                            <?php foreach ($album as $albums): ?>
                                                <option value="<?= $albums['AlbumID'] ?>"><?= $albums['NamaAlbum'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <input type="submit" value="Simpan" name="submit" class="btn btn-primary my-3">
                                </form>
                            <?php elseif (isset($_GET['edit'])): ?>
                                <form action="?url=upload&&edit&&fotoid=<?= $val['FotoID'] ?>" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label>Judul Foto</label>
                                        <input type="text" class="form-control" value="<?= $val['JudulFoto'] ?>" required name="judul_foto">
                                    </div>
                                    <div class="form-group">
                                        <label>Deskripsi Foto</label>
                                        <textarea name="deskripsi_foto" class="form-control" required cols="30" rows="2"><?= $val['DeskripsiFoto'] ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Pilih Gambar</label>
                                        <input type="file" name="namafile" class="form-control">
                                        <small class="text-danger">File harus berupa: *.jpg, *.png, *.jpeg</small>
                                    </div>
                                    <div class="form-group">
                                        <label>Pilih Album</label>
                                        <select name="album_id" class="form-select">
                                            <?php foreach ($album as $albums): ?>
                                                <option value="<?= $albums['AlbumID'] ?>" <?= $albums['AlbumID'] == $val['AlbumID'] ? 'selected' : '' ?>><?= $albums['NamaAlbum'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <input type="submit" value="Ubah" name="submit" class="btn btn-primary my-3">
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Bagian Tabel Foto -->
                <div class="col-md-8">
                    <div class="card my-4">
                        <div class="card-body">
                            <table class="table table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>Foto</th>
                                        <th>Judul</th>
                                        <th>Deskripsi</th>
                                        <th>Album</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($fotos = mysqli_fetch_array($foto)): ?>
                                        <tr>
                                            <td><img src="uploads/<?= $fotos['LokasiFile'] ?>" width="100"></td>
                                            <td><?= $fotos['JudulFoto'] ?></td>
                                            <td><?= $fotos['DeskripsiFoto'] ?></td>
                                            <td><?= $fotos['NamaAlbum'] ?></td>
                                            <td><?= $fotos['TanggalUnggah'] ?></td>
                                            <td>
                                                <a href="?url=upload&&edit&&fotoid=<?= $fotos['FotoID'] ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?url=upload&&hapus&&fotoid=<?= $fotos['FotoID'] ?>" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
