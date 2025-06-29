<div class="d-none">
<?php 
    include ("../koneksi.php");
    include ("../function.php");

    session_start();

    if(isset($_SESSION['status']) && isset($_SESSION['idAdmin'])) {
        //cek nama lengkap user
        // $perkenalan = $_SESSION['nama'];
        $idAdmin = $_SESSION['idAdmin'];
    
        // Ambil data user dari database
        $user = $idAdmin;
        $query = mysqli_query($conn, "SELECT * FROM pelanggan");
        if (mysqli_num_rows($query) > 0) {
            $data1 = mysqli_fetch_array($query);
        } else {
            header("Location: ../login.php");
            exit;
        }
    } 

?>
</div>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <link rel="stylesheet" href="../css/bootstrap.css">
        <script src="../js/jquery.min.js"></script>
        <script src="-../js/popper.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="../all.min.css">
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <title>Admin | <?php
            if (isset($_SESSION['idAdmin'])) {
                $id = $_SESSION['idAdmin'];
                $query = mysqli_query($conn, "SELECT * FROM pelanggan WHERE pelanggan_id = '$id' AND role = 'admin'");
                $data = mysqli_fetch_array($query);
                $nama = $data['nama'];
                echo $nama;
              // proses informasi ID seperti mengambil data dari database
            } else {
                echo "(Belum Login)";
            }
            ?></title>
    </head>
    <body>
        <div class="d-flex" id="wrapper">
            <!-- Sidebar-->
            <?php require "sidebar.php"; ?>

            <!-- Page content wrapper-->
            <div id="page-content-wrapper">
                <!-- Top navigation-->
                <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                    <div class="container-fluid">
                        <button class="navbar-toggler d-block" id="sidebarToggle">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <span class="navbar-text bg-warning rounded-4 p-2 text-bg-warning">
                            <strong>
                                <?php require "../judul.php"; ?>
                            </strong>
                        </span>
                    </div>
                </nav>
                <!-- Page content-->
                <div class="container-fluid">
            <div class="row">
        <div class="col-md-12" >

            <!-- Main Content -->
            <h1 class="text-center my-3">Daftar Jadwal Film</h1>
            <!-- Button buat jadwal baru -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahFilm">
            <i class="bi bi-plus-lg"></i> Tambah Jadwal
            </button>

            <!-- Modal Jadwal -->
            <div class="modal fade" id="tambahFilm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="tambahFilmLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="tambahFilmLabel">Jadwal Baru</h1>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                        <!-- Dropdown untuk Memilih Film -->
                        <div class="mb-3">
                            <label for="film" class="form-label">Pilih Film</label>
                            <select class="form-select" id="film" name="film_id" required>
                            <option value="" disabled selected>-- Pilih Film --</option>
                            <?php
                                // Query untuk mengambil daftar film dari database
                                $query = "SELECT * FROM film ORDER BY judul ASC";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="' . $row['film_id'] . '">' . $row['judul'] . '</option>';
                                }
                            ?>
                            </select>
                        </div>

                        <!-- Input untuk Memilih Tanggal -->
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal Tayang</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                        </div>

                        <!-- Input untuk Memilih Jam -->
                        <div class="mb-3">
                            <label for="jam_mulai" class="form-label">Jam Tayang</label>
                            <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" required>
                        </div>

                        <!-- Input untuk Menentukan Harga -->
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga Tiket</label>
                            <input type="number" class="form-control" id="harga" name="harga" placeholder="Masukkan harga contoh (50000)" value="50000" required>
                        </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="returnShow(event);"><i class="bi bi-x-circle"></i> Close</button>
                        <button type="submit" name="submit_jadwal" class="btn btn-success"><i class="bi bi-check-circle"></i> Submit</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>

            <script>
                function returnShow(event){

                                // Reset form inputs
                                document.getElementById("film").value = ""; // Reset film
                                document.getElementById("tanggal").value = ""; // Reset jam mulai
                                document.getElementById("jam_mulai").value = ""; // Reset tanggal
                                document.getElementById("harga").value = "50000"; // Reset tanggal
                            }
            </script>

            <!-- Proses untuk query penambahan jadwal -->
            <?php
            if (isset($_POST['submit_jadwal'])) {
                $film_id = $_POST['film_id'];
                $tanggal = $_POST['tanggal'];
                $jam_mulai = $_POST['jam_mulai'];
                $harga = $_POST['harga'];

                // Hitung waktu selesai berdasarkan durasi film
                $query_durasi = "SELECT durasi FROM film WHERE film_id = $film_id";
                $result_durasi = mysqli_query($conn, $query_durasi);
                $durasi_film = mysqli_fetch_assoc($result_durasi)['durasi']; // Durasi dalam menit
                $jam_selesai = date("H:i:s", strtotime($jam_mulai) + ($durasi_film * 60)); // Hitung jam selesai

                // Cek apakah ada bentrok dengan jadwal lain
                $query_bentrok = "
                    SELECT jadwal.jadwal_id, jadwal.jam_mulai, 
                        DATE_ADD(jadwal.jam_mulai, INTERVAL film.durasi MINUTE) AS jam_selesai,
                        film.judul
                    FROM jadwal 
                    JOIN film ON jadwal.film_id = film.film_id
                    WHERE jadwal.tanggal = '$tanggal'
                    HAVING ('$jam_mulai' BETWEEN jadwal.jam_mulai AND jam_selesai OR 
                            '$jam_selesai' BETWEEN jadwal.jam_mulai AND jam_selesai OR 
                            jadwal.jam_mulai BETWEEN '$jam_mulai' AND '$jam_selesai')
                ";
                $result_bentrok = mysqli_query($conn, $query_bentrok);

                if (mysqli_num_rows($result_bentrok) > 0) {
                    // Ada bentrok
                    $bentrok = mysqli_fetch_assoc($result_bentrok);
                    echo "<script>
                        alert('Jadwal bentrok! Jadwal lain pada tanggal $tanggal untuk film \"{$bentrok['judul']}\" dari {$bentrok['jam_mulai']} hingga {$bentrok['jam_selesai']}');
                    </script>";
                } else {
                    // Tidak ada bentrok, masukkan jadwal
                    $query_insert = "INSERT INTO jadwal (film_id, tanggal, jam_mulai, harga) 
                                    VALUES ($film_id, '$tanggal', '$jam_mulai', $harga)";
                    if (mysqli_query($conn, $query_insert)) {
                        echo "<script>
                            alert('Jadwal berhasil ditambahkan!'); 
                            window.location.href = 'adminjadwal.php';
                        </script>";
                    } else {
                        echo "<script>alert('Gagal menambahkan jadwal!');</script>";
                    }
                }
            }
            ?>



            <br>

            <?php
            // Query untuk mendapatkan data jadwal
            $query = mysqli_query($conn, "
                SELECT jadwal.jadwal_id, 
                    film.judul, 
                    film.genre, 
                    film.durasi, 
                    film.rating, 
                    jadwal.tanggal, 
                    jadwal.jam_mulai, 
                    jadwal.harga
                FROM jadwal 
                JOIN film ON jadwal.film_id = film.film_id 
                ORDER BY jadwal.tanggal ASC, jadwal.jam_mulai ASC;
            ");
            ?>

            <!-- Cek apakah ada data jadwal -->
            <?php if(mysqli_num_rows($query) > 0) { ?>
            <div class="container my-5 table-responsive" style="max-height: 55vh;">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Judul Film</th>
                            <th scope="col">Genre</th>
                            <th scope="col">Durasi</th>
                            <th scope="col">Rating</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Jam Tayang</th>
                            <th scope="col">Jam Selesai</th>
                            <th scope="col">Harga Tiket</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1; // Penomoran baris
                        while($data = mysqli_fetch_array($query)) { 
                            // Hitung Jam Selesai
                            $jam_mulai = strtotime($data['jam_mulai']);
                            $durasi = $data['durasi'] * 60; // Konversi durasi dari menit ke detik
                            $jam_selesai = date("H:i", $jam_mulai + $durasi); // Tambahkan durasi ke jam_mulai
                        ?>
                        <tr>
                            <th scope="row"><?php echo $no++; ?></th>
                            <td><?php echo $data['judul']; ?></td>
                            <td><?php echo $data['genre']; ?></td>
                            <td><?php echo formatDurasi($data['durasi']); ?></td>
                            <td>⭐ <?php echo $data['rating']; ?></td>
                            <td><?php echo date("d-m-Y", strtotime($data['tanggal'])); ?></td>
                            <td><?php echo date("H:i", strtotime($data['jam_mulai'])); ?></td>
                            <td><?php echo $jam_selesai; ?></td> <!-- Jam Selesai -->
                            <td>Rp <?php echo number_format($data['harga'], 0, ',', '.'); ?></td>
                            <td>
                                <a href="hapusjadwal.php?jadwal_id=<?php echo $data['jadwal_id']; ?>" class="btn btn-danger" 
                                onclick="return confirm('Apakah anda yakin ingin menghapus jadwal <?php echo $data['judul']; ?>, Tanggal : <?php echo date('d-m-Y', strtotime($data['tanggal'])); ?> Jam : <?php echo date('H:i', strtotime($data['jam_mulai'])); ?>')">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } else { ?>
            <!-- Tampilkan pesan jika tidak ada jadwal -->
            <div class="text-center my-5">
                <h3 class="text-danger">Belum ada jadwal yang tersedia!</h3>
            </div>
            <?php } ?>

            <!-- Footer -->
            <?php require "../footer_fr.php"; ?>
            </div>
                </div>
        <!-- bootstrap theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
