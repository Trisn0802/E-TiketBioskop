<div class="d-none">
<?php
@include '../function.php';
@include '../koneksi.php';

session_start();

if(isset($_SESSION['status']) && isset($_SESSION['idUser'])){
    //cek nama lengkap user
    $nama_pelanggan = $_SESSION['nama'];

    // Ambil data user dari database
    $user = $_SESSION['idUser'];
    $query = mysqli_query($conn, "SELECT * FROM pelanggan WHERE pelanggan_id='$user'");
    $data = mysqli_fetch_array($query);
    } else {
      // header("Location: login.php?pesan=login-dulu-bray");
      // exit;
    }

?>
</div>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="../css/bootstrap.css">
  <link rel="stylesheet" href="../card.css">
  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <title><?php include "../judul.php"; ?></title>
</head>
<body>

  <!-- Navbar -->
  <?php 
      include "navbar_user.php"; 
    ?> 
        <br>
<!-- </div> -->

        <?php
        // Ambil data film berdasarkan film_id
        $film_id = $_GET['film_id'];
        $film_query = mysqli_query($conn, "SELECT * FROM film WHERE film_id = $film_id");
        $film_data = mysqli_fetch_assoc($film_query);

        // Ambil data jadwal berdasarkan film_id
        $jadwal_query = mysqli_query($conn, "
            SELECT jadwal.jadwal_id, jadwal.tanggal, jadwal.jam_mulai, jadwal.harga
            FROM jadwal 
            WHERE jadwal.film_id = $film_id
            ORDER BY jadwal.tanggal ASC, jadwal.jam_mulai ASC
        ");
        ?>

        <div class="container my-5">
            <!-- Info Film -->
            <h1 class="text-center mb-4 bg-dark text-bg-dark p-2 rounded-4"><i class="bi bi-info-square-fill"></i> Deskripsi Film</h1>
            <div class="film-container">
                <div class="card text-bg-dark mb-3 mx-auto" style="max-width: 80%;">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="../movie/<?php echo $film_data['foto']; ?>" class="img-fluid rounded-start" alt="Poster Film">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h2 class="card-title bg-warning p-2 rounded-2 text-bg-warning"><?php echo $film_data['judul']; ?></h2>
                                <h6 class="card-text"><strong>Rating:</strong> ⭐ <?php echo $film_data['rating']; ?></h6>
                                <h6 class="card-text"><strong>Genre:</strong> <?php echo $film_data['genre']; ?></h6>
                                <h6 class="card-text">
                                    <strong>Durasi:</strong> <?php echo formatDurasi($film_data['durasi']); ?>
                                </h6>
                                <p class="card-text"><strong>Sinopsis:</strong><br><small class="text-body-dark"><?php echo nl2br($film_data['sinopsis']); ?></small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Warna Garis Horizontal -->
            <style>
              .hrline {
                height: 1px;
                border: 5px solid black;
                margin: 1em 0;
                width: auto;
                height: 5px;
              }
            </style>

            <!-- Pilih Jadwal -->
            <!-- <div class="hrline bg-black bg-gradient rounded-pill"></div> -->
            
            <div class="text-center p-3 mb-2 bg-warning-subtle text-warning-emphasis"><h1>Pilih Jadwal Tayang</h1></div>
            <div class="jadwal-container mt-3">
                <?php 
                $current_date = null;
                while ($jadwal = mysqli_fetch_assoc($jadwal_query)) { 
                    $tanggal = date("d-m-Y", strtotime($jadwal['tanggal']));
                    $jam_mulai = date("H:i", strtotime($jadwal['jam_mulai']));
                    $harga = number_format($jadwal['harga'], 0, ',', '.');

                    // Tampilkan tanggal baru jika berbeda dari sebelumnya
                    if ($current_date !== $tanggal) {
                        if ($current_date !== null) {
                            echo '<div class="hrline rounded-pill"></div>';
                        }
                        echo "<h3><strong>Tanggal: $tanggal</strong></h3>";
                        $current_date = $tanggal;
                    }
                ?>
                <!-- Button untuk Jam Tayang -->
                <button class="btn btn-warning m-2" style="overflow: auto;" onclick="document.location.href='pesan_tiket.php?jadwal_id=<?php echo $jadwal['jadwal_id']; ?>&film_id=<?php echo $film_data['film_id']; ?>';">
                    <?php echo "$jam_mulai - Rp $harga"; ?>
                </button>
                <?php } ?>
                <div class="hrline rounded-pill"></div>
            </div>
        </div>
                    <br>

  <!-- Footer -->
  <footer class="bg-light py-3 shadow-lg border fixed-bottom" style="position: fixed;
  right: 0;
  bottom: 0;
  left: 0;
  height: 50px;
  z-index: 11;">
    <div class="container">
    <p class="text-center">
    <?php include "../copyright.php"; ?>
        </p>
    </div>
</footer>

  </html>

