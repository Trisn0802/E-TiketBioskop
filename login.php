<!-- <div style="display: none;">     -->
<?php
@include "koneksi.php";

session_start();
$errors = [];

// Cek apakah tombol submit sudah ditekan
if (isset($_POST['submit'])) {
        $id = $_POST['pelanggan_id'];
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $pass = ($_POST['password']);
        $role = $_POST['role'];

        // Buat query untuk mencari user dengan email dan password yang sesuai
        $select = "SELECT * FROM pelanggan WHERE email = '$email'";
        // Jalankan query dan simpan hasilnya
        $result = mysqli_query($conn, $select);
        // Cek apakah query berhasil
        if ($result) {
            // Cek apakah ada baris yang dikembalikan
            if (mysqli_num_rows($result) > 0) {
                // Jika ya, ambil data user dari baris pertama
                $row = mysqli_fetch_array($result);
                // Gunakan fungsi password_verify untuk memeriksa apakah password cocok dengan hash
                if ($pass == $row['password']) {
                    // Jika ya, cek role user dan set session sesuai role
                    if ($row['role'] == 'user') {
                        $_SESSION['idUser'] = $row['pelanggan_id'];
                        $_SESSION['nama_lengkap'] = $row['nama'];
                        $_SESSION['status'] = 'login_user';
                        header('location: index.php');
                    } elseif ($row['role'] == 'admin') {
                        $_SESSION['idAdmin'] = $row['pelanggan_id'];
                        $_SESSION['nama_lengkap'] = $row['nama'];
                        $_SESSION['status'] = 'login_admin';
                        header('location: admin/admin.php');
                    }
                } else {
                    // Jika tidak, tampilkan pesan error bahwa password salah
                    $errors[] = '<br>
                    <div class="mt-3 align-items-center justify-content-center" style="margin-left: 15%; width: 70%; margin-bottom: -15px;">
                    <div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                    <div>Password salah!</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    </div>';
                }
            } else {
                // Jika tidak, tampilkan pesan error bahwa email dan password salah
                $errors[] = '<div class="mt-3 align-items-center justify-content-center" style="margin-left: 15%; width: 70%; margin-bottom: -15px;">
                <div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                <div>Email dan password salah!</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                </div>';
            }    
        } else {
            // Jika tidak, tampilkan pesan error bahwa query gagal
            $errors[] = '<div class="mt-3 align-items-center justify-content-center" style="margin-left: 15%; width: 70%; margin-bottom: -15px;">
            <div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
            <div>Query gagal: '.mysqli_error($conn).'</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            </div>';
        }
};

// Pastikan tabel pengumuman tersedia
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS pengumuman (
    pengumuman_id TINYINT NOT NULL DEFAULT 1,
    judul VARCHAR(255) NOT NULL,
    isi LONGTEXT NOT NULL,
    status ENUM('aktif','nonaktif') NOT NULL DEFAULT 'nonaktif',
    dibuat_pada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    diupdate_pada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (pengumuman_id),
    CONSTRAINT chk_single_pengumuman CHECK (pengumuman_id = 1)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

mysqli_query($conn, "INSERT INTO pengumuman (pengumuman_id, judul, isi, status)
    SELECT 1, 'Pengumuman', '<p>Belum ada pengumuman.</p>', 'nonaktif'
    WHERE NOT EXISTS (SELECT 1 FROM pengumuman WHERE pengumuman_id = 1)");

$pengumuman_login = mysqli_query($conn, "SELECT judul, isi, dibuat_pada, status FROM pengumuman WHERE pengumuman_id = 1 LIMIT 1");
$item = null;
$isPengumumanAktif = false;
$isIsiPengumumanKosong = true;

if ($pengumuman_login && mysqli_num_rows($pengumuman_login) > 0) {
    $item = mysqli_fetch_assoc($pengumuman_login);
    $isPengumumanAktif = isset($item['status']) && $item['status'] === 'aktif';
    $isiPolos = trim(strip_tags((string) $item['isi']));
    $isIsiPengumumanKosong = ($isiPolos === '' || $isiPolos === 'Belum ada pengumuman.');
}
?>
<!-- </div> -->


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Login Page</title>
</head>

<style>
    .pengumuman-content {
        line-height: 1.5;
        word-break: break-word;
    }

    .pengumuman-content p:last-child {
        margin-bottom: 0;
    }

    .pengumuman-content img {
        max-width: 100%;
        height: auto;
        display: inline-block;
    }

    .pengumuman-content img.note-float-left {
        float: left;
        margin-right: 10px;
    }

    .pengumuman-content img.note-float-right {
        float: right;
        margin-left: 10px;
    }

    .pengumuman-content img.note-float-none {
        float: none;
    }
</style>

<body class="bg-dark">
    <div class="container mt-5">
        <div class="row align-items-center justify-content-center">
            <div class="<?php echo $isPengumumanAktif ? 'col-lg-6 col-md-12' : 'col-lg-5 col-md-7 col-sm-9'; ?> mb-4">

                <div class="card border-primary shadow-lg" id="draggableCard">
                    <div class="card-header bg-primary text-white text-center">
                    <h3>Login</h3>
                    <div style="background: #212121; color: White; border-radius: 10px;">
                    <script type='text/javascript'>
                                    var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                    var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum&#39;at', 'Sabtu'];
                                    var date = new Date();
                                    var day = date.getDate();
                                    var month = date.getMonth();
                                    var thisDay = date.getDay(),
                                        thisDay = myDays[thisDay];
                                    var yy = date.getYear();
                                    var year = (yy < 1000) ? yy + 1900 : yy;
                                    document.write(thisDay + ', ' + day + ' ' + months[month] + ' ' + year);
                    </script>
                    </div>
                </div>
                <?php 
                    // Periksa apakah ada variable error
                    if(isset($errors) && is_array($errors) && count($errors) > 0){
                        foreach($errors as $errorMessage){
                        echo $errorMessage;
                        };
                    }; 
                    // untuk mengambil pesan (dari berbagai variable) dari register.php 
                    // dan kesalahan user saat memaksa mengganti url dan tidak login
                    if (isset($_SESSION['success'])) {
                        $success = $_SESSION['success'];
                        echo $success;
                        unset($_SESSION['success']);
                        // session_destroy();
                    } elseif (isset($_SESSION['logingagal'])) {
                        $login_gagal = $_SESSION['logingagal'];
                        echo $login_gagal;
                        unset($_SESSION['logingagal']);
                        // session_destroy();
                    }
                    ?>
                <div class="card-body">
                    <form method="post">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Masukan email anda" required>
                    </div>
                    <br>
                    <div class="form-group" style="position: relative;">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Masukan password anda" required>
                        <i class="fa fa-eye password-icon" aria-hidden="true" onclick="showPassword()"></i>
                    </div>

                    <style>
                        .form-group {
                            display: flex;
                            flex-direction: column;
                        }

                        .password-icon {
                            margin-left: auto;
                            margin-top: -26px;
                            margin-right: 10px;
                            cursor: pointer;
                        }
                    </style>

                    <script>
                        function showPassword() {
                            var x = document.getElementById("passwordInput");
                            var icon = document.querySelector(".password-icon");
                            if (x.type === "password") {
                            x.type = "text";
                            icon.classList.remove("fa-eye");
                            icon.classList.add("fa-eye-slash");
                            } else {
                            x.type = "password";
                            icon.classList.remove("fa-eye-slash");
                            icon.classList.add("fa-eye");
                            }
                        }
                    </script>
                    <br>
                    <button type="submit" name="submit" value="Login Sekarang" class="btn btn-primary btn-block w-100 mt-3">Login</button>
                    <button type="button" value="kembali" class="btn btn-success btn-block w-100 mt-3" onclick="location.href='index.php'">Home</button>
                    <p class="mt-3 text-center">Belum punya akun? <a class="text-decoration-none" href="register.php">Daftar disini</a></p>
                    </form>
                    </div>
                </div>
            </div>

            <?php if ($isPengumumanAktif) { ?>
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card border-warning shadow-lg h-100">
                    <div class="card-header bg-warning text-dark text-center">
                        <h3 class="mb-0"><?php echo htmlspecialchars($item['judul']); ?></h3>
                    </div>
                    <div class="card-body bg-light" style="max-height: 580px; overflow-y: auto;">
                        <div class="p-3 mb-3">
                            <small class="badge bg-secondary text-bg-secondary"><?php echo date('d-m-Y H:i', strtotime($item['dibuat_pada'])); ?></small>
                            <?php if ($isIsiPengumumanKosong) { ?>
                                <div class="alert alert-info mt-2 mb-0">Belum ada pengumuman!</div>
                            <?php } else { ?>
                                <div class="pengumuman-content mt-2"><?php echo $item['isi']; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

        </div>
    </div>
    <?php require "footer.php"; ?>
</body>
</html>
