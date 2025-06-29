<div>
<?php
    include "koneksi.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_user = 0;
        $nama_lengkap = htmlspecialchars($_POST['nama']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $confirm_password = htmlspecialchars($_POST['confirm_password']);
        $role = htmlspecialchars($_POST['role']);
        $no_telp = htmlspecialchars($_POST['no_telepon']);

        if ($password != $confirm_password) {
            $error = '<div class="mt-3 align-items-center justify-content-center" style="margin-left: 15%; width: 70%;">
                        <div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                            <div>Konfirmasi password tidak tepat! coba lagi</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>';
        } else {

            $sql = "SELECT * FROM pelanggan WHERE nama = '$nama_lengkap' OR email = '$email' OR no_telepon = '$no_telp'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_array($result);
                if ($row['nama_lengkap'] == $nama_lengkap) {
                    $error_nama = '<div class="mt-3 align-items-center justify-content-center" style="margin-left: 15%; width: 70%;">
                                    <div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                                        <div>Nama sudah digunakan, silahkan gunakan nama lain.</div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                </div>';
                }

                if ($row['email'] == $email) {
                    $error_email = '<div class="mt-3 align-items-center justify-content-center" style="margin-left: 15%; width: 70%;">
                                    <div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                                        <div>Email sudah digunakan, silahkan gunakan email lain.</div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                </div>';
                }

                if ($row['no_telepon'] == $no_telp) {
                    $error_no_telepon = '<div class="mt-3 align-items-center justify-content-center" style="margin-left: 15%; width: 70%;"> 
                                            <div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                                                <div>Nomor telepon sudah digunakan, silakan gunakan nomor lain.</div>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        </div>';
                }

            } else {

                // Cek jika ID User sudah ada di database
                do {
                    // Generate ID User baru
                    $id_user++;

                    // Query untuk memeriksa apakah ID tersebut sudah ada
                    $sql = "SELECT * FROM pelanggan WHERE pelanggan_id = '$id_user'";
                    $result = mysqli_query($conn, $sql);

                } while (mysqli_num_rows($result) > 0); 

                // Pada titik ini, $id_user adalah ID unik yang belum ada di database

                // Hash password
                // $password = password_hash($password, PASSWORD_DEFAULT);

                // Buat query INSERT
                $query = "INSERT INTO pelanggan (pelanggan_id, nama, email, password, no_telepon, role) VALUES ('$id_user', '$nama_lengkap', '$email', '$password', '$no_telp', '$role')";

                $result = mysqli_query($conn, $query);

                if ($result) {
                    $success = '<div class="mt-3 align-items-center justify-content-center" style="margin-left: 15%; width: 70%; margin-bottom: -15px;">
                        <div class="alert alert-success text-center alert-dismissible fade show" role="alert">
                        <div>Pendaftaran berhasil!</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>';

                    // Buat session untuk menyimpan variabel $success
                    session_start();
                    $_SESSION['success'] = $success;

                    // Redirect ke halaman index.php
                    header("Location: login.php");
                    exit;

                } else {
                    $error = '<div class="mt-3 align-items-center justify-content-center" style="margin-left: 15%; width: 70%;">
                    <div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                    <div>Terjadi kesalahan saat pendaftaran akun!</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    </div>';
                    return false;
                }
            }
        }
    }
    //  else if (){

    // };
?>
</div>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="all.min.css">
    <title>Register Page</title>
</head>
<style>
    
</style>
<body class="bg-dark">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7 col-sm-9">
            <div class="card border-primary mt-3 shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3>Daftar</h3>
                </div>
                <?php 
                    if(isset($error)) {
                        echo $error;
                    } elseif(isset($error_nama)) {
                        echo $error_nama;
                    } elseif(isset($error_email)) {
                        echo $error_email;
                    } elseif(isset($error_no_telepon)) {
                        echo $error_no_telepon;
                    } elseif(isset($success)) {
                        echo $success;
                    }
                ?>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="form-group mb-3">
                            <label for="nama_lengkap">Nama</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama" placeholder="Masukan nama lengkap anda" require>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Masukan email" require>
                        </div>
                        <div class="form-group mb-3">
                            <label for="no_telp">No.Telepon</label>
                            <input type="number" class="form-control" id="no_telp" name="no_telepon" placeholder="0895....." require>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Masukan password" require>
                                <i class="fa fa-eye password-icon" aria-hidden="true" onclick="showPassword()"></i>
                            </div>
                        </div>


                        <script>
                        function showPassword() {
                            var x = document.getElementById("passwordInput");
                            var y = document.getElementById("confirmPasswordInput");
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
                            if (y.type === "password") {
                            y.type = "text";
                            icon.classList.remove("fa-eye");
                            icon.classList.add("fa-eye-slash");
                            } else {
                            y.type = "password";
                            icon.classList.remove("fa-eye-slash");
                            icon.classList.add("fa-eye");
                            }
                        }
                        </script>

                        <style>
                        .form-group .password-input-wrapper {
                        position: relative;
                        }

                        .form-group .password-input-wrapper input {
                        padding-right: 35px; /* Letakkan padding agar input tidak tertutupi oleh ikon */
                        }

                        .form-group .password-icon {
                        position: absolute;
                        top: 50%;
                        right: 10px;
                        transform: translateY(-50%);
                        cursor: pointer;
                        }

                        /* .password-icon {
                            /* position: absolute; 
                            right: 30px;
                            top: 47.2%;
                            transform: translateY(-50%);
                            cursor: pointer;
                        } */
                        </style>
                        <div class="form-group mb-3">
                            <label for="confirm_password">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="confirmPasswordInput" name="confirm_password" placeholder="Masukan password lagi" require>
                        </div>

                        <div id="debugContent" class="alert alert-info fade-in" style="display: none;">
                            <div class="form-group">
                                <center>
                                    <p>(Debugging Mode ON)</p>
                                </center>
                                <label for="role">Akun sebagai</label>
                                <select class="form-select" name="role" id="role" class="form-control">
                                    <option value="user" selected>User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <center>
                                <button type="button" id="turnOffDebugBtn" class="btn btn-danger mt-3">Turn Off Debug Mode</button>
                            </center>
                            <!-- <br> -->
                        </div>
                        <!-- <center> -->
                            <button type="submit" class="btn btn-primary btn-block w-100">Register</button>
                        <!-- </center> -->
                    </form>
                    <p class="mt-3 text-center">Sudah punya akun? <a class="text-decoration-none" href="login.php">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

                        <!-- Modal Bootstrap untuk input password -->
                        <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                                <h5 class="modal-title" id="passwordModalLabel">Debug Mode</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">

                                        <!-- Alert jika password salah -->
                                        <div id="alertWrongPassword" class="alert alert-danger" role="alert" style="display: none;">
                                                Password salah. Silakan coba lagi.
                                        </div>

                                        <!-- Form input password -->
                                        <div class="form-group">
                                                <label for="passwordInputDebug">Password</label>
                                                <input type="password" id="passwordInputDebug" class="form-control" placeholder="Enter your password">
                                        </div>

                                </div>
                                        <div class="modal-footer">
                                                <button type="button" id="submitPassword" class="btn btn-success">Submit</button>
                                        </div>
                                        </div>
                                </div>
                        </div>

    <?php require "footer.php"; ?>
</body>
</html>