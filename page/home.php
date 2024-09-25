<div class="container">
    <div class="py-1 text-black">
        <!-- Bagian untuk menampilkan logo pada halaman -->
        <center><img src="assets/img/3.png" alt="Logo" width="300" height="150"></center>
    </div>
</div>

<div class="container">
    <div class="row">
        <?php
        // Query untuk mengambil data foto beserta username dari pengguna yang mengunggah foto tersebut
        $tampil = mysqli_query($conn, "
            SELECT foto.*, user.Username
            FROM foto 
            INNER JOIN user ON foto.UserID = user.UserID
        ");

        // Perulangan untuk menampilkan setiap foto dan informasi terkait
        foreach ($tampil as $tampils):
            // Query untuk menghitung jumlah suka pada foto
            $likes = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM likefoto WHERE FotoID='{$tampils['FotoID']}'"));

            // Mendapatkan ID user yang login, jika ada
            $user_id = @$_SESSION['user_id']; // Pastikan pengguna sudah login

            // Query untuk memeriksa apakah pengguna sudah menyukai foto ini
            $user_like = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM likefoto WHERE FotoID='{$tampils['FotoID']}' AND UserID='$user_id'"));

            // Query untuk menghitung jumlah komentar yang ada di foto
            $comments = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM komentarfoto WHERE FotoID='{$tampils['FotoID']}'"));
        ?>
            <!-- Membuat kartu untuk menampilkan foto -->
            <div class="col-6 col-md-4 col-lg-3 mb-4" style="border-color: black;">
                <div class="card">
                    <div class="card-header">
                        <!-- Menampilkan judul foto -->
                        <h6 class="card-title"><?= $tampils['JudulFoto'] ?></h6>
                    </div>

                    <!-- Tautan menuju halaman detail foto -->
                    <a href="?url=detail&&id=<?= $tampils['FotoID'] ?>">
                        <img src="uploads/<?= $tampils['LokasiFile'] ?>" class="object-fit-cover" style="aspect-ratio: 1/1; width: 100%;">
                    </a>

                    <div class="card-body">

                        <!-- Bagian untuk menampilkan jumlah suka dan komentar -->
                        <p>
                            <!-- Tombol suka dengan penanda apakah user sudah suka -->
                            <a href="javascript:void(0);"
                                onclick="tanganiLike(<?= $tampils['FotoID'] ?>, <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>)"
                                class="btn btn-sm <?php if ($user_like == 0) {
                                                        echo 'text-dark';
                                                    } else {
                                                        echo 'text-danger';
                                                    } ?>">
                                <i class="fa-solid fa-heart"></i> <?= $likes ?>
                            </a>

                            <!-- Tombol untuk melihat komentar -->
                            <a href="?url=detail&id=<?= $tampils['FotoID'] ?>" class="btn btn-sm text-dark">
                                <i class="fa-regular fa-comment"></i> <?= $comments ?>
                            </a>

                        </p>

                        <!-- Menampilkan username dari pengguna yang mengunggah foto -->
                        <p class="card-text text-muted"><i class="fa-solid fa-user"></i> <?= $tampils['Username'] ?></p>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
</div>

<script>
    // Fungsi untuk menangani klik tombol suka
    function tanganiLike(fotoId, sudahLogin) {
        if (!sudahLogin) {
            // Jika pengguna belum login, arahkan ke halaman login
            window.location.href = "login.php";
            return;
        }

        // Jika pengguna sudah login, lanjutkan proses suka (like)
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "?url=like&id=" + fotoId, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Muat ulang halaman untuk memperbarui jumlah suka
                location.reload();
            }
        };
        xhr.send();
    }
</script>