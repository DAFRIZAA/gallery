<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-10">
            <center><h1>Halaman Profile</h1></center>
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <h3>Hallo, <?= ucwords($_SESSION['username']) ?></h3>
                 
                    <?php
                    // Ambil data user dari database
                    $user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM user WHERE UserID='{$_SESSION['user_id']}'"));

                    // Jika form edit profile di-submit
                    if (isset($_POST['editprofile'])) {
                        $nama = $_POST['nama'];
                        $email = $_POST['email'];
                        $username = $_POST['username'];
                        $alamat = $_POST['alamat'];

                        // Pastikan semua data tidak kosong
                        if (!empty($username) && !empty($email) && !empty($nama) && !empty($alamat)) {
                            // Periksa apakah data berubah
                            if ($username == $user['Username'] && $email == $user['Email'] && $alamat == $user['Alamat'] && $nama == $user['NamaLengkap']) {
                                $alert = '<div class="alert alert-warning text-center">Tidak ada perubahan data.</div>';
                            } else {
                                // Mulai query update
                                $ubah = mysqli_query($conn, "UPDATE user SET NamaLengkap='$nama', Email='$email', Username='$username', Alamat='$alamat' WHERE UserID='$_SESSION[user_id]'");
                                if ($ubah) {
                                    // Update session dengan data baru
                                    $session = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM user WHERE UserID='$_SESSION[user_id]'"));
                                    $_SESSION['userid'] = $session['UserID'];
                                    $_SESSION['username'] = $session['Username'];
                                    $_SESSION['namalengkap'] = $session['NamaLengkap'];
                                    $_SESSION['email'] = $session['Email'];
                                    $alert = '<div class="alert alert-success text-center">Profil berhasil diperbarui!</div>';
                                    echo '<meta http-equiv="refresh" content="0.8; url=?url=profile&&proses=editprofile">';
                                } else {
                                    $alert = '<div class="alert alert-danger text-center">Gagal memperbarui profil!</div>';
                                }
                            }
                        } else {
                            $alert = '<div class="alert alert-danger text-center">Semua field harus diisi!</div>';
                        }
                    } elseif (isset($_POST['editpassword'])) {
                        // Ubah password
                        $password = md5($_POST['password']);
                        if ($password != $user['Password']) {
                            $ubah = mysqli_query($conn, "UPDATE user SET Password='$password' WHERE UserID='$_SESSION[user_id]'");
                            if ($ubah) {
                                $alert = '<div class="alert alert-success text-center">Password berhasil diperbarui!</div>';
                                echo '<meta http-equiv="refresh" content="0.8; url=?url=profile&&proses=editpassword">';
                            } else {
                                $alert = '<div class="alert alert-danger text-center">Gagal memperbarui password!</div>';
                            }
                        }
                    }

                    // Tampilkan alert jika ada
                    echo @$alert;
                    ?>

                    <?php if (@$_GET['proses'] == 'editprofile') : ?>
                        <form action="?url=profile&&proses=editprofile" method="post">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fa-solid fa-circle-user"></i></span>
                                <input type="text" class="form-control" value="<?= $user['NamaLengkap'] ?>" id="nama" name="nama" required placeholder="Masukan Nama Lengkap">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" class="form-control" value="<?= $user['Email'] ?>" id="email" name="email" required placeholder="Masukan Email Anda">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fa-solid fa-at"></i></span>
                                <input type="text" class="form-control" value="<?= $user['Username'] ?>" id="username" name="username" required placeholder="Masukan Username">
                            </div>
                            <div class="input-group mb-4">
                                <span class="input-group-text"><i class="fa-solid fa-address-book"></i></span>
                                <input type="text" class="form-control" id="alamat" value="<?= $user['Alamat'] ?>" name="alamat" required placeholder="Masukan Alamat Lengkap">
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="?url=profile" class="btn btn-secondary fw-semibold">Kembali</a>
                                <input type="submit" value="Simpan Perubahan" name="editprofile" class="btn btn-primary fw-semibold">
                            </div>
                        </form>
                    <?php elseif (@$_GET['proses'] == 'editpassword') : ?>
                        <form action="?url=profile&&proses=editpassword" method="post">
                            <div class="input-group mb-4">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" required placeholder="Masukan Password Baru">
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="?url=profile" class="btn btn-secondary fw-semibold">Kembali</a>
                                <input type="submit" value="Simpan Perubahan" name="editpassword" class="btn btn-primary fw-semibold">
                            </div>
                        </form>
                    <?php else : ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tr>
                                    <th style="width: 30%;" class="py-3">Nama Lengkap</th>
                                    <td class="py-3"><?= $user['NamaLengkap'] ?></td>
                                </tr>
                                <tr>
                                    <th style="width: 30%;" class="py-3">Email</th>
                                    <td class="py-3"><?= $user['Email'] ?></td>
                                </tr>
                                <tr>
                                    <th style="width: 30%;" class="py-3">Username</th>
                                    <td class="py-3"><?= $user['Username'] ?></td>
                                </tr>
                                <tr>
                                    <th style="width: 30%;" class="py-3">Alamat</th>
                                    <td class="py-3"><?= $user['Alamat'] ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="?url=profile&&proses=editprofile" class="btn btn-success fw-semibold">Edit Profil</a>
                            <a href="?url=profile&&proses=editpassword" class="btn btn-warning fw-semibold">Edit Password</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
