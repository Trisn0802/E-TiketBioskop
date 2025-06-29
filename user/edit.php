<div class="d-none">
<?php
@include '../koneksi.php';
@include '../function.php';

session_start();

  if(isset($_SESSION['idUser'])){
    // $password = password_hash($password, PASSWORD_DEFAULT);
  } else {
    header("Location: ../login.php?pesan=login-dulu-bray");
      exit;
  }

  // Jika tombol submit ditekan
  if (isset($_POST['submit'])) {
  
    // $password = password_hash($password, PASSWORD_DEFAULT);
      // Jika nama belum digunakan, update data ke database
      if (edit($_POST)>0){

        echo "<script>
                  alert('Data berhasil diubah!');
                  document.location.href = '../index.php';
              </script>";
  
      } else {
  
        echo "<script>
                  alert('Data gagal diubah!');
                  document.location.href = '../index.php';
              </script>";
        return false;
      }
    }
  
  // error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
  // ini_set('display_errors', 'Off');
  
    // Ambil data user dari database
    $user = ($_SESSION['idUser']);
    $query = mysqli_query($conn, "SELECT * FROM pelanggan WHERE pelanggan_id='$user'");
    $data = mysqli_fetch_array($query);

    // Buat variabel PHP untuk menyimpan password asli
    $pass = $data['password'];
    // Buat variabel PHP untuk menyimpan hash dari password asli
    // $hash = password_hash($pass, PASSWORD_DEFAULT);

    // var_dump($data)
?>
</div>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <script src="../js/jquery.min.js"></script>
    <script src="-../js/popper.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Edit Akun | <?php echo $data['nama']; ?></title>
</head>
<body>
  <input type="hidden" name="pelanggan_id" value="<?php echo $data['pelanggan_id'] ?>">
  <div class="mt-5">
  <?php 
                    if(isset($error)) {
                        echo $error;
                    } elseif(isset($error_nama)) {
                        echo $error_nama;
                    } elseif(isset($error_email)) {
                        echo $error_email;
                    } 
                ?>
    <center>
    <h1>Hallo, <?php echo $data['nama']; ?></h1>
    <h4><?php // echo $identitas; ?></h4>
    <!-- <p>Mohon isi data diri anda dengan lengkap dibawah</p> -->
    </center>
    <div class="container my-5">
    <div class="card shadow-lg">
        <div class="card-header">
            Edit Akun
        </div>
        <?php // var_dump($perkenalan); ?>
        <div class="card-body">
          <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group text-center align-items-center">
            <center>
            <?php if($data['foto'] === NULL){
              $fotoBelumAda = 'demo_profil.svg';
            ?>
            <img src="../img/<?php echo $fotoBelumAda; ?>" alt="Ini foto profil anda" id="foto-preview-belum" width="300" height="300" class="rounded mb-3">
            <br>
            <label for="formFile" class="form-label">Upload foto anda!!</label>
            <input class="form-control" type="file" name="foto" id="formFile" onchange="showPreviewBelum(event);">
            <script>
              function showPreviewBelum(event){
                if(event.target.files.length > 0){
                  var srcBelum = URL.createObjectURL(event.target.files[0]);
                  var previewBelum = document.getElementById("foto-preview-belum");
                  previewBelum.src = srcBelum;
                  previewBelum.style.display = "block";
                }
              }
            </script>
            <?php } elseif ($data['foto'] === ""){
            $fotoBelumAda = 'demo_profil.svg';
            ?>
            <img src="../img/<?php echo $fotoBelumAda; ?>" alt="Ini foto profil anda" id="foto-preview-belum" width="300" height="300" class="rounded mb-3">
            <br>
            <label for="formFile" class="form-label">Upload foto anda!!</label>
            <input class="form-control" type="file" name="foto" id="formFile" onchange="showPreviewBelum(event);">
            <script>
              function showPreviewBelum(event){
                if(event.target.files.length > 0){
                  var srcBelum = URL.createObjectURL(event.target.files[0]);
                  var previewBelum = document.getElementById("foto-preview-belum");
                  previewBelum.src = srcBelum;
                  previewBelum.style.display = "block";
                }
              }
            </script>

          <?php  } else { ?>
            <img src="../img/<?php echo $data['foto']; ?>" alt="Ini foto profil anda" id="foto-preview-sudah" width="300" height="300" class="rounded mb-3">
            <br>
            <label for="formFile" class="form-label">Upload foto anda!!</label>
            <input class="form-control" type="file" name="foto" id="formFile" onchange="showPreviewSudah(event);">

            <script>
              function showPreviewSudah(event){
                if(event.target.files.length > 0){
                  var srcSudah = URL.createObjectURL(event.target.files[0]);
                  var previewSudah = document.getElementById("foto-preview-sudah");
                  previewSudah.src = srcSudah;
                  previewSudah.style.display = "block";
                }
              }
            </script>

          <?php } ?>

            </center>
              <div class="mb-3">
              <style>
              .center {
                height:100%;
                /* display:flex; */
                align-items:center;
                justify-content:center;
              }
              .form-input {
                width:130px;
                padding:5px;
                background:#fff;
                box-shadow: -3px -3px 7px rgba(94, 104, 121, 0.377),
                            3px 3px 7px rgba(94, 104, 121, 0.377);
              }
              .form-input img {
                width:100%;
                display:none;
                margin-bottom:5px;
                align-content: center;
                justify-content: center;
              }
              </style>  
              <!-- <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.6/dist/cropper.min.js"></script> -->
              <input type="hidden" name="fotoLama" value="<?php echo $data["foto"]?>"/>
            </div>
                </div>
                <div class="form-group mb-2">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="<?= $data['nama'] ?>"/>
                    <input type="hidden" name="namaLama" value="<?php echo $data["nama"]?>" require/>
                </div>
                <div class="form-group mb-2">
                    <label for="email" class="form-label">Email</label>
                    <input type="hidden" name="emailLama" value="<?php echo $data["email"]?>" require/>
                    <input type="email" name="email" class="form-control" value="<?= $data['email'] ?>">
                </div>
                <div class="mb-2">
                <div class="form-group-pw" style="position: relative;">
                  <label for="password" class="form-label">Password</label>
                  <!-- Tampilkan variabel hash di atribut value -->
                  <input type="password" name="password" class="form-control" id="passwordInput" value="<?= $data['password']; ?>" require>
                  <input type="hidden" name="passwordLama" class="form-control" value="<?= $data['password'] ?>" require>
                  <i class="fa fa-eye password-icon" aria-hidden="true" onclick="showPassword()"></i>
                </div>
                </div>
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

                    <style>   
                        .form-group-pw {
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
                
                <div class="form-group mb-2 mt-2">
                    <label for="no_telepon" class="form-label">Nomor HP</label>
                    <span style="color: red;">* Wajib</span>
                    <input type="number" id="no_telepon" placeholder="895....." name="no_telepon" class="form-control" value="<?= $data['no_telepon'] ?>" require pattern="^\+?[0-9]+$">
                    <!-- pattern="^\+?[0-9]+$" -->
                </div>
                <button type="submit" name="submit" class="btn btn-primary mt-4">Simpan Perubahan</button>
                  <button class="btn btn-secondary mt-4" onclick="history.back()">Kembali</button>
                </form>
                </div>
                </div>

      </div>
          <?php
              if(isset($error)){
                  echo $error;
              }
          ?>
          
        </div>
</body>
</html>