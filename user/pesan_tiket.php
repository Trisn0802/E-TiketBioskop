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
        // Ambil data kursi berdasarkan jadwal_id
        $jadwal_id = $_GET['jadwal_id']; // Ambil jadwal_id dari parameter URL
        $film_id = $_GET['film_id']; // Ambil jadwal_id dari parameter URL

        $jadwal_query = mysqli_query($conn, "SELECT * FROM jadwal WHERE jadwal_id = '$jadwal_id';");
        $jadwal_data = mysqli_fetch_array($jadwal_query);

        $film_query = mysqli_query($conn, "SELECT * FROM film WHERE film_id = '$film_id';");
        $film_data = mysqli_fetch_array($film_query);

        // new query 3
        $kursi_query = mysqli_query($conn, "
            SELECT 
                k.kursi_id, 
                k.nomor_kursi, 
                COALESCE(kj.status, k.status) AS status, -- Prioritaskan status di kursi, fallback ke kursi_jadwal
                j.harga 
            FROM kursi k
            LEFT JOIN kursi_jadwal kj ON k.kursi_id = kj.kursi_id AND kj.jadwal_id = '$jadwal_id'
            LEFT JOIN jadwal j ON j.jadwal_id = '$jadwal_id'
            ORDER BY k.kursi_id ASC;
        ");
        ?>

        <div class="container my-5">
            <!-- Garis Horizontal -->
            <style>
                .hrline {
                    height: 1px;
                    border: 5px solid black;
                    margin: 1em 0;
                    width: auto;
                }
            </style>
            <!-- <div class="hrline bg-black bg-gradient rounded-pill"></div> -->

            <!-- CSS Kursi -->
            <link rel="stylesheet" href="../kursi.css">

            <!-- Pilih Kursi -->
            <div class="text-center p-3 mb-2 bg-success-subtle text-success-emphasis"><h1><i class="bi bi-check2-square"></i> Pilih Kursi</h1></div>
            <form action="proses_transaksi.php" method="post" id="formTransaksi">

                <!-- Garis Layar -->
                <div class="screen mt-4"></div>
                <div class="screen-label text-center mt-2">
                    <h4 class="fw-bold">Layar Bioskop</h4>
                </div> 

                <!-- Kursi -->
                <div class="seat-container text-center mt-4">
                    <?php
                      $count = 0;
                      while ($kursi_jadwal_data = mysqli_fetch_assoc($kursi_query)) {
                        $status = $kursi_jadwal_data['status']; // Ambil status kursi
                        $tooltip = ucfirst($status); // Tooltip berdasarkan status
                        $disabled = ($status == 'dipesan' || $status == 'rusak' || $status == 'diblokir') ? 'disabled' : '';
                        $data_price = $kursi_jadwal_data['harga']; // Harga kursi
                    
                        echo '<button type="button" 
                          class="seat ' . $status . '" 
                          data-id="' . $kursi_jadwal_data['kursi_id'] . '" 
                          data-number="' . $kursi_jadwal_data['nomor_kursi'] . '" 
                          data-price="' . $data_price . '"
                          title="'. $tooltip .'"
                          ' . $disabled . '>' . $kursi_jadwal_data['nomor_kursi'] . '</button>';

                
                        // Tambahkan baris baru setiap 15 kursi
                        $count++;
                        if ($count % 15 == 0) {
                            echo '<br>';
                        }
                      }
                    ?>
                </div>

                <!-- Form Transaksi -->
                <div class="mt-5">
                    <h4 class="text-center">Form Transaksi</h4>
                    <div class="row g-3">
                        <!-- Kursi yang Dipilih -->
                        <div class="col-md-6">
                            <label for="selectedSeats" class="form-label">Kursi yang Dipilih</label>
                            <input type="text" name="selectedSeats" id="selectedSeats" class="form-control" readonly required>
                        </div>
                        <!-- Total Harga -->
                        <div class="col-md-6">
                            <label for="totalHarga" class="form-label">Total Harga</label>
                            <input type="text" name="totalHarga" id="totalHarga" class="form-control" readonly required>
                        </div>
                    </div>
                    <!-- <hr> -->
                    <div class="hrline rounded-pill"></div>
                    <div class="row g-3 mt-3">
                      <!-- Informasi User -->
                        <!-- Nama -->
                        <div class="col-md-6">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" name="nama" id="nama" class="form-control" placeholder="<?php echo $data['nama']; ?>" readonly>
                        </div>
                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="<?php echo $data['email']; ?>" readonly>
                        </div>

                        <!-- Jadwal Tayang -->
                        <div class="col-md-6">
                            <label for="jam_mulai" class="form-label">Jadwal Tayang</label>
                            <input type="jam_mulai" name="jam_mulai" id="jam_mulai" class="form-control" placeholder="<?php echo date("H:i", strtotime($jadwal_data['jam_mulai'])); ?>" readonly>
                        </div>
                        <!-- Tanggal Film -->
                        <div class="col-md-6">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="tanggal" name="tanggal" id="tanggal" class="form-control" placeholder="<?php echo date("d-m-Y", strtotime($jadwal_data['tanggal'])); ?>" readonly>
                        </div>
                      <!-- Judul Film -->
                      <div class="col-md-6">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="judul" name="judul" id="judul" class="form-control" placeholder="<?php echo $film_data['judul']; ?>" readonly>
                      </div>
                      <!-- Durasi Film -->
                      <div class="col-md-6">
                          <label for="durasi" class="form-label">Durasi Film</label>
                          <input type="durasi" name="durasi" id="durasi" class="form-control" placeholder="<?php echo formatDurasi($film_data['durasi']); ?>" readonly>
                        </div>
                      </div>                      
                    </div>

                    <!-- hidden id untuk transaksi -->
                    <div class="col-md-6">
                          <input type="hidden" id="pelanggan_id" name="pelanggan_id" value="<?php echo $data['pelanggan_id']; ?>" placeholder="ID Pelanggan">
                          <input type="hidden" id="film_id" name="film_id" value="<?php echo $film_data['film_id']; ?>" placeholder="ID Film">
                          <input type="hidden" id="ticketPrice" value="<?php echo $kursi_jadwal_data['harga']; ?>" placeholder="Harga Film">
                          <input type="hidden" id="jadwal_id" name="jadwal_id" value="<?= $jadwal_id ?>" placeholder="ID Jadwal">
                        </div>
                      </div>

                <!-- Tombol Submit -->
                <div class="text-center mt-5">
                    <button type="button"  class="btn btn-success btn-lg" id="btnPesanTiket"><i class="bi bi-ticket-perforated"></i> Pesan Tiket</button>
                </div>
            </form>
        </div>
        <!-- <hr>
        <hr> -->
        <br>
        <br>
        <br>

        <!-- Modal untuk konfirmasi jika kursi belum dipilih -->
        <div class="modal fade" id="modalKursiBelumDipilih" tabindex="-5" aria-labelledby="modalKursiBelumDipilihLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalKursiBelumDipilihLabel"><i class="bi bi-exclamation-triangle"></i> Peringatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                Anda belum memilih kursi. Silakan pilih kursi terlebih dahulu sebelum melanjutkan.
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Konfirmasi -->
        <div class="modal fade" id="modalKonfirmasi" tabindex="-5" aria-labelledby="modalKonfirmasiLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalKonfirmasiLabel">Konfirmasi Pemesanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin dengan kursi yang Anda pilih?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Nanti aja</button>
                        <button type="submit" class="btn btn-warning" id="confirmPesanan">Ya, Pesan Tiket</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
          // Menangani pemilihan kursi
          const selectedSeatsInput = document.getElementById('selectedSeats');
          const totalHargaInput = document.getElementById('totalHarga');
          let selectedSeats = [];
          let totalHarga = 0;

          // Ambil harga tiket dari jadwal (diberikan di elemen HTML)
          const ticketPrice = parseInt("<?= $jadwal_data['harga']; ?>");

          document.querySelectorAll('.seat.tersedia').forEach(seat => {
              seat.addEventListener('click', function () {
                  const seatNumber = this.getAttribute('data-number');

                  if (this.classList.contains('selected')) {
                      // Jika sudah dipilih, hapus dari array
                      this.classList.remove('selected');
                      selectedSeats = selectedSeats.filter(num => num !== seatNumber);
                      totalHarga -= ticketPrice; // Kurangi harga tiket
                  } else {
                      // Tambahkan ke array dan ubah warna
                      this.classList.add('selected');
                      selectedSeats.push(seatNumber);
                      totalHarga += ticketPrice; // Tambahkan harga tiket
                  }

                  // Perbarui input kursi dan total harga
                  selectedSeatsInput.value = selectedSeats.join(', ');
                  totalHargaInput.value = "Rp " + totalHarga.toLocaleString('id-ID');
              });
          });

          // Fungsi untuk mengecek sebelum submit
          document.getElementById('btnPesanTiket').addEventListener('click', function () {
              const selectedSeats = document.getElementById('selectedSeats').value;
              const totalHarga = document.getElementById('totalHarga').value;

              if (!selectedSeats || !totalHarga) {
                  const modalKursiBelumDipilih = new bootstrap.Modal(document.getElementById('modalKursiBelumDipilih'));
                  modalKursiBelumDipilih.show();
                  return false;
              } else {
                  const modalKonfirmasi = new bootstrap.Modal(document.getElementById('modalKonfirmasi'));
                  modalKonfirmasi.show();
              }
          });

          document.getElementById('confirmPesanan').addEventListener('click', function () {
              document.getElementById('formTransaksi').submit();
          });
      </script>

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

