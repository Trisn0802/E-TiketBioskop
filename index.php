<!-- <div class="d-none"> -->
<?php
@include 'koneksi.php';

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
<!-- </div> -->

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="card.css">
  <link rel="stylesheet" href="crausel.css">
  <script src="js/jquery.min.js"></script>
  <script src="js/popper.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <title><?php include "judul.php"; ?></title>
</head>
<body>
    <!-- Navbar -->
    <?php 
      include "navbar_utama.php"; 
    ?> 
              <br>
              <?php
                // Query untuk mengambil data film, diurutkan berdasarkan rating tertinggi
                $query = "SELECT * FROM film ORDER BY rating DESC LIMIT 5;";
                $result = mysqli_query($conn, $query);
              ?>

              <!-- Container for carousel -->
              <div class="carousel-container mx-auto">
                <div id="caraouselPosterFilm" class="carousel slide my-5" data-bs-ride="carousel">
                  <div class="carousel-inner">
                    <?php
                    $isActive = true; // Untuk menandai item pertama sebagai "active"
                    while ($row = mysqli_fetch_assoc($result)) {
                        $judul = $row['judul'];
                        $genre = $row['genre'];
                        $poster = $row['foto']; // Path gambar poster
                    ?>
                    <div class="carousel-item <?php echo $isActive ? 'active' : ''; ?>">
                      <img src="movie/<?php echo $poster; ?>" class="d-block w-100" alt="<?php echo $judul; ?>" sty>
                      <div class="carousel-caption d-md-block">
                        <h5><?php echo $judul; ?></h5>
                        <p><?php echo $genre; ?></p>
                      </div>
                    </div>
                    <?php
                        $isActive = false; // Setelah iterasi pertama, tidak ada lagi item "active"
                    }
                    ?>
                  </div>

                  <!-- Control buttons -->
                  <button class="carousel-control-prev" type="button" data-bs-target="#caraouselPosterFilm" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                  </button>
                  <button class="carousel-control-next" type="button" data-bs-target="#caraouselPosterFilm" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                  </button>
                </div>
              </div>

  <!-- Tampilan Card Top Film -->
  <div class="container my-5" style="margin-bottom: 10%;">
  <!-- <br style="margin-top: 100px;"> -->
    <h1 class="border rounded text-center p-3 text-white bg-dark border rounded-3">Top Film</h1>
    <!-- <hr> -->
    <?php 
    // Query film dengan Film rating tinggi
    $query = mysqli_query($conn, "SELECT * FROM film ORDER BY rating DESC LIMIT 5");
    ?>

    <?php if(mysqli_num_rows($query) > 0) { ?>
    <!-- Tampilan Card film -->
    <div class="container my-5" style="margin-bottom: 10%; max-width: 150%">
        <div class="movie-container">
            <?php while($data = mysqli_fetch_array($query)) { ?>
            <!-- Cek apakah film ada dalam jadwal -->
            <?php 
            $film_id = $data['film_id'];
            $jadwal_query = mysqli_query($conn, "SELECT * FROM jadwal WHERE film_id = $film_id");
            $has_schedule = mysqli_num_rows($jadwal_query) > 0; 
            ?>
            <!-- Movie Card -->
            <div class="card movie-card position-relative">
                <img src="movie/<?php echo $data['foto']; ?>" class="card-img-top" alt="Movie Poster">
                <!-- Rating di pojok kanan atas rounded-start -->
                <div class="card-rating position-absolute top-0 end-0 bg-dark text-white p-2">
                    ⭐ <?php echo $data['rating']; ?>
                </div>
                <div class="card-body text-center bg-dark">
                    <h5 class="card-title"><?php echo $data['judul']; ?></h5>
                    <p class="card-durasi text-dark bg-light rounded-3 p-2"><?php echo $data['durasi']; ?> Menit</p>
                </div>
                <div class="card-footer bg-secondary">
                    <center>
                        <?php 
                        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
                        if (!$user) 
                        { ?>
                            <!-- User belum login -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginDulu"><i class="bi bi-ticket-perforated"></i> Pesan Tiket</button>
                        <?php } else if ($has_schedule) { ?>
                            <!-- Film ada dalam jadwal -->
                            <a href="user/pilih_jadwal.php?film_id=<?php echo $film_id; ?>" class="btn btn-warning"><i class="bi bi-ticket-perforated"></i> Pesan Tiket</a>
                        <?php } else { ?>
                            <!-- Film belum ada dalam jadwal -->
                            <button class="btn btn-secondary" disabled>Coming Soon</button>
                        <?php } ?>
                    </center>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php } else { ?>
    <marquee behavior="scroll" direction="left" scrollamount="15" scrolldelay="50">
        <h1 class="text-center m-5">--------------- Belum ada film yang tersedia! ---------------</h1>
    </marquee>
    <?php } ?>

    <!-- Modal -->
    <div class="modal fade" id="loginDulu" tabindex="-1" aria-labelledby="loginDulu" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Nu Uh ... 🙅‍♂️</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Kalo kamu mau pesan tiket login dulu dong
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-warning" onclick="document.location.href='login.php'"><i class="bi bi-box-arrow-in-right"></i> Login Sekarang!</button>
          </div>
        </div>
      </div>
    </div>
</div>

<!-- Tampilan Card Film Terbaru -->
<div class="container my-5" style="margin-bottom: 10%;">
  <!-- <br style="margin-top: 100px;"> -->
    <h1 class="border rounded text-center p-3 text-white bg-dark border rounded-3">Film Terbaru</h1>
    <!-- <hr> -->
    <?php 
    // Query film dengan rating tertinggi
    $query = mysqli_query($conn, "SELECT * FROM film ORDER BY film_id ASC");
    ?>

    <?php if(mysqli_num_rows($query) > 0) { ?>
    <!-- Tampilan Card Movie -->
    <div class="container my-5" style="margin-bottom: 10%;">
        <div class="movie-container">
            <?php while($data = mysqli_fetch_array($query)) { ?>
            <!-- Cek apakah film ada dalam jadwal -->
            <?php 
            $film_id = $data['film_id'];
            $jadwal_query = mysqli_query($conn, "SELECT * FROM jadwal WHERE film_id = $film_id");
            $has_schedule = mysqli_num_rows($jadwal_query) > 0; 
            ?>
            <!-- Movie Card -->
            <div class="card movie-card position-relative">
                <img src="movie/<?php echo $data['foto']; ?>" class="card-img-top" alt="Movie Poster">
                <!-- Rating di pojok kanan atas rounded-start -->
                <div class="card-rating position-absolute top-0 end-0 bg-dark text-white p-2">
                    ⭐ <?php echo $data['rating']; ?>
                </div>
                <div class="card-body text-center bg-dark">
                    <h5 class="card-title"><?php echo $data['judul']; ?></h5>
                    <p class="card-durasi text-dark bg-light rounded-3 p-2"><?php echo $data['durasi']; ?> Menit</p>
                </div>
                <div class="card-footer bg-secondary">
                    <center>
                        <?php 
                        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
                        if (!$user) 
                        { ?>
                            <!-- User belum login -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginDulu"><i class="bi bi-ticket-perforated"></i> Pesan Tiket</button>
                        <?php } else if ($has_schedule) { ?>
                            <!-- Film ada dalam jadwal -->
                            <a href="user/pilih_jadwal.php?film_id=<?php echo $film_id; ?>" class="btn btn-warning"><i class="bi bi-ticket-perforated"></i> Pesan Tiket</a>
                        <?php } else { ?>
                            <!-- Film belum ada dalam jadwal -->
                            <button class="btn btn-secondary" disabled>Coming Soon</button>
                        <?php } ?>
                    </center>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php } else { ?>
    <marquee behavior="scroll" direction="left" scrollamount="15" scrolldelay="50">
        <h1 class="text-center m-5">--------------- Belum ada film yang tersedia! ---------------</h1>
    </marquee>
    <?php } ?>

    <!-- Modal -->
    <div class="modal fade" id="loginDulu" tabindex="-1" aria-labelledby="loginDulu" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Nu Uh ... 🙅‍♂️</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Kalo kamu mau pesan tiket login dulu dong
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-warning" onclick="document.location.href='login.php'"><i class="bi bi-box-arrow-in-right"></i> Login Sekarang!</button>
          </div>
        </div>
      </div>
    </div>
</div>

<?php // var_dump($id_penjual) ?>


    <?php // var_dump($pelanggan_id) ?>
  <!-- <br> -->
  <!-- Footer -->
  <footer class="bg-light py-3 shadow-lg border fixed-bottom" style="position: fixed;
  right: 0;
  bottom: 0;
  left: 0;
  height: 50px;
  z-index: 11;">
    <div class="container">
      <p class="text-center">
      <?php include "copyright.php"; ?>
      </p>
      
    </div>
  </footer>

  </html>

