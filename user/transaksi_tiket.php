<!-- <div class="d-none"> -->
<?php
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
<!-- </div> -->

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
              <?php
                // Ambil pelanggan_id dari sesi pengguna yang login
                $pelanggan_id = $data['pelanggan_id'];

                // Query untuk mendapatkan transaksi pengguna
                $query_transaksi = "
                    SELECT t.transaksi_id, t.jadwal_id, t.tanggal_transaksi, t.total_harga, t.status,
                          j.tanggal AS tanggal_tayang, t.tanggal_pembatalan AS tanggal_pembatalan, j.jam_mulai, j.harga AS harga_jadwal,
                          f.judul, f.foto, f.durasi, f.genre
                    FROM transaksi t
                    JOIN jadwal j ON t.jadwal_id = j.jadwal_id
                    JOIN film f ON j.film_id = f.film_id
                    WHERE t.pelanggan_id = $pelanggan_id
                    ORDER BY t.tanggal_transaksi DESC
                ";

                $result_transaksi = mysqli_query($conn, $query_transaksi);
                ?>

                <div class="container-fluid mx-auto my-5">
                    <h1 class="text-center mb-4 bg-dark text-bg-dark p-2 rounded-4"><i class="bi bi-clock-history"></i> History Transaksi</h1>
                    <div class="d-flex flex-wrap m-3 justify-content-center">

                        <?php while ($transaksi = mysqli_fetch_assoc($result_transaksi)) : ?>
                            <?php
                            // Ambil kursi yang dipesan dalam transaksi ini
                            $transaksi_id = $transaksi['transaksi_id'];
                            $query_kursi = "
                                SELECT k.nomor_kursi
                                FROM detail_transaksi dt
                                JOIN kursi k ON dt.kursi_id = k.kursi_id
                                WHERE dt.transaksi_id = $transaksi_id
                            ";
                            $result_kursi = mysqli_query($conn, $query_kursi);
                            ?>

                            <div class="card text-bg-dark mb-3" style="max-width: 47%; margin-right: 5px; margin-left: 5px;">
                                <div class="row g-0">
                                    <div class="col-md-4">
                                        <img src="../movie/<?php echo $transaksi['foto']; ?>" class="img-fluid rounded-start" alt="Poster Film">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $transaksi['judul']; ?></h5>
                                            <small><p class="card-text"><strong>Genre:</strong> <?php echo $transaksi['genre']; ?></p></small>
                                            <small><p class="card-text"><strong>Tanggal Tayang:</strong> <?php echo date("d-m-Y", strtotime($transaksi['tanggal_tayang'])); ?></p></small>
                                            <small><p class="card-text"><strong>Jam Tayang:</strong> <?php echo date("H:i", strtotime($transaksi['jam_mulai'])); ?></p></small>
                                            <small><p class="card-text"><strong>Durasi:</strong> <?php echo floor($transaksi['durasi'] / 60) . " Jam " . ($transaksi['durasi'] % 60) . " Menit"; ?></p></small>
                                            <small><p class="card-text"><strong>Total Harga:</strong> Rp <?php echo number_format($transaksi['total_harga'], 0, ',', '.'); ?></p></small>
                                            <small><p class="card-text"><strong>Kursi:</strong>
                                                <div>
                                                    <?php while ($kursi = mysqli_fetch_assoc($result_kursi)) : ?>
                                                        <button class="btn btn-primary mt-1"><?php echo $kursi['nomor_kursi']; ?></button>
                                                    <?php endwhile; ?>
                                                </div>
                                            </p>
                                            </small>
                                            <p class="card-text">
                                                <small class="text-body-dark"><strong>Tanggal Transaksi:</strong> <?php echo date("d-m-Y", strtotime($transaksi['tanggal_transaksi'])); ?></small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-secondary">
                                    <center>
                                    <?php if ($transaksi['status'] === 'dipesan') : ?>
                                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#batalPesanan-<?php echo $transaksi['transaksi_id']; ?>">Batalkan Pemesanan</button>
                                    <?php elseif ($transaksi['status'] === 'dibatalkan') : ?>
                                        <button class="btn btn-secondary" disabled>
                                            Dibatalkan pada: <?php echo date("d-m-Y", strtotime($transaksi['tanggal_pembatalan'])); ?>
                                        </button>
                                    <?php else : ?>
                                        <button class="btn btn-success" disabled>
                                            <?php echo ucfirst($transaksi['status']); ?>
                                        </button>
                                    <?php endif; ?>
                                    </center>
                                </div>
                            </div>

                            <!-- Modal batal pesanan -->
                            <div class="modal fade" id="batalPesanan-<?php echo $transaksi['transaksi_id']; ?>" tabindex="-1" aria-labelledby="batalPesananLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="batalPesananLabel">Batalkan Pemesanan</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Apakah Anda yakin ingin membatalkan transaksi ini?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            <form action="../batal_transaksi.php" method="post">
                                                <input type="hidden" name="transaksi_id" value="<?php echo $transaksi['transaksi_id']; ?>">
                                                <button type="submit" class="btn btn-danger">Batalkan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

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

