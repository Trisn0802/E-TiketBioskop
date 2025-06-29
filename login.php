<div style="display: none;">    
<?php
@include "koneksi.php";

session_start();

// Cek apakah tombol submit sudah ditekan
if (isset($_POST['submit'])) {
    // Cek apakah variabel $_POST ada dan tidak kosong
    // if (isset($_POST['id_pembeli']) && isset($_POST['nama_lengkap']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['role'])) {
        // Jika ya, ambil nilai dari variabel $_POST
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
                    $error[] = '<br>
                    <div class="mt-3 align-items-center justify-content-center" style="margin-left: 15%; width: 70%; margin-bottom: -15px;">
                    <div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                    <div>Password salah!</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    </div>';
                }
            } else {
                // Jika tidak, tampilkan pesan error bahwa email dan password salah
                $error[] = '<div class="mt-3 align-items-center justify-content-center" style="margin-left: 15%; width: 70%; margin-bottom: -15px;">
                <div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                <div>Email dan password salah!</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                </div>';
            }    
        } else {
            // Jika tidak, tampilkan pesan error bahwa query gagal
            $error[] = '<div class="mt-3 align-items-center justify-content-center" style="margin-left: 15%; width: 70%; margin-bottom: -15px;">
            <div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
            <div>Query gagal: '.mysqli_error($conn).'</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            </div>';
        }
    // } else {
    //     // Jika tidak, tampilkan pesan error bahwa data tidak lengkap
    //     $error[] = '<div class="align-items-center justify-content-center" style="margin-left: 15%; width: 70%;">
    //     <div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
    //     <div>Data tidak lengkap!</div>
    //     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    //     </div>
    //     </div>';
    // }
};
?>
</div>


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
    
</style>

<body class="bg-dark">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7 col-sm-9">
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
                    if(isset($error)){
                        foreach($error as $error){
                        echo $error;
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
                    <!-- <center> -->
                    <button type="submit" name="submit" value="Login Sekarang" class="btn btn-primary btn-block w-100 mt-3">Login</button>
                    <button type="button" value="kembali" class="btn btn-success btn-block w-100 mt-3" onclick="location.href='index.php'">Home</button>
                    <!-- </center> -->
                    <p class="mt-3 text-center">Belum punya akun? <a class="text-decoration-none" href="register.php">Daftar disini</a></p>
                    <!-- <button id="toggleDraggable" class="btn btn-secondary btn-block mt-3">Fun Mode</button> -->
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require "footer.php"; ?>
</body>
</html>
