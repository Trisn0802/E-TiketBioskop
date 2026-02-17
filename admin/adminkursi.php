<!-- <div class="d-none"> -->
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

    // Proses penyimpanan kursi baru ke database
    if (isset($_POST['submit_kursi'])) {
        $nomor_kursi = $_POST['nomor_kursi'];
        $status_kursi = $_POST['status_kursi'];

        // Query untuk menyimpan kursi baru ke tabel Kursi
        $query = "INSERT INTO kursi (nomor_kursi, status) VALUES ('$nomor_kursi', '$status_kursi')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Kursi berhasil ditambahkan!');
            document.location.href = 'adminkursi.php';
            </script>";
        } else {
            echo "<script>alert('Gagal menambahkan kursi: " . mysqli_error($conn) . "');</script>";
        }
    }

    // Proses untuk memperbarui status atau nomor kursi
    if (isset($_POST['update_seat'])) {
        $seat_id = $_POST['seat_id'];
        $nomor_kursi = $_POST['nomor_kursi'];
        $seat_status = $_POST['seat_status'];

        $query = "UPDATE kursi SET nomor_kursi = '$nomor_kursi', status = '$seat_status' WHERE kursi_id = $seat_id";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Kursi berhasil diperbarui!'); 
            document.location.href = 'adminkursi.php';
            </script>";
        } else {
            echo "<script>alert('Gagal memperbarui kursi: " . mysqli_error($conn) . "');</script>";
        }
    }

    // Proses untuk menghapus kursi
    if (isset($_POST['delete_seat'])) {
        $seat_id = $_POST['seat_id'];

        $query_kursi = mysqli_query($conn, "DELETE FROM kursi WHERE kursi_id = $seat_id");
        $query_kursi_jadwal = mysqli_query($conn, "DELETE FROM kursi_jadwal WHERE kursi_id = $seat_id");

        if ($query_kursi && $query_kursi_jadwal) {
            echo "<script>alert('Kursi berhasil dihapus!'); document.location.href = 'adminkursi.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus kursi: " . mysqli_error($conn) . "');</script>";
        }
    }

    // Mengambil data kursi untuk edit
    $seat_data = null;
    if (isset($_GET['edit'])) {
        $seat_id = $_GET['edit'];
        $query = "SELECT * FROM kursi WHERE kursi_id = $seat_id";
        $result = mysqli_query($conn, $query);
        $seat_data = mysqli_fetch_assoc($result);
    }

    // Query untuk menampilkan semua kursi
    $query = "SELECT * FROM kursi ORDER BY kursi_id ASC";
    $result = mysqli_query($conn, $query);

?>
<!-- </div> -->

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
                <div>

                <!-- Main Content -->
                <h1 class="text-center my-3">Daftar Kursi Studio</h1>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahKursi">
                    <i class="bi bi-plus-lg"></i> Tambah Kursi
                </button>

                <!-- Modal untuk Mendaftarkan Kursi -->
                <div class="modal fade" id="tambahKursi" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="tambahKursiLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="tambahKursiLabel">Tambah Kursi</h1>
                    </div>
                    <form action="" method="post">
                        <div class="modal-body">
                        <!-- Nomor Kursi -->
                        <div class="mb-3">
                            <label for="nomor_kursi" class="form-label">Nomor Kursi</label>
                            <input type="text" class="form-control" id="nomor_kursi" name="nomor_kursi" placeholder="A1, B2, C3, dll." required>
                        </div>

                        <!-- Status Kursi -->
                        <div class="mb-3">
                            <label for="status_kursi" class="form-label">Status Kursi</label>
                            <select class="form-select" id="status_kursi" name="status_kursi" required>
                            <option value="tersedia" selected>Tersedia</option>
                            <option value="rusak">Rusak</option>
                            <option value="diblokir">Diblokir</option>
                            </select>
                        </div>
                        </div>
                        <div class="modal-footer">
                        <button type="reset" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Close</button>
                        <button type="submit" name="submit_kursi" class="btn btn-success"><i class="bi bi-check-circle"></i> Submit</button>
                        </div>
                    </form>
                    </div>
                </div>
                </div>

                <br>

                <link rel="stylesheet" href="../kursi.css">

                <div>
                    <!-- Garis Layar -->
                    <div class="screen"></div>
                    <div class="screen-label"><h3>Layar Bioskop</h3></div>

                    <!-- Kursi -->
                    <div class="seat-container">
                        <?php
                            $count = 0;
                            while ($row = mysqli_fetch_assoc($result)) {
                                $count++;
                                if ($count > 15) {
                                    echo '<br>';
                                    $count = 1;
                                }
                                echo '<button type="button" class="seat ' . $row['status'] . '" title="' . ucfirst($row['status']) . '" 
                                        data-bs-toggle="modal" data-bs-target="#editSeatModal" 
                                        data-id="' . $row['kursi_id'] . '" 
                                        data-number="' . $row['nomor_kursi'] . '" 
                                        data-status="' . $row['status'] . '">' . $row['nomor_kursi'] . '</button>';

                            }
                        ?>
                    </div>
                </div>
                    <!-- Footer -->
            <?php require "../footer_fr.php"; ?>
            <!-- Modal Bootstrap -->
            <div class="modal fade" id="editSeatModal" tabindex="-1" aria-labelledby="editSeatModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="editSeatModalLabel">Kelola Kursi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="seatId" name="seat_id">
                        <div class="mb-3">
                        <label for="nomor_kursi" class="form-label">Nomor Kursi</label>
                        <input type="text" class="form-control" id="nomor_kursi" name="nomor_kursi" required>
                        </div>
                        <div class="mb-3">
                        <label for="seat_status" class="form-label">Status Kursi</label>
                        <select class="form-select" id="seat_status" name="seat_status" required>
                            <option value="tersedia">Tersedia</option>
                            <option value="dipesan">Dipesan</option>
                            <option value="rusak">Rusak</option>
                            <option value="diblokir">Diblokir</option>
                        </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button 
                            type="submit" 
                            name="delete_seat" 
                            class="btn btn-danger" 
                            onclick="return confirm('Apakah anda yakin ingin menghapus kursi <?php echo $row['nomor_kursi']; ?>?');">
                            <i class="bi bi-trash3-fill"></i> Hapus
                        </button>
                        <button type="submit" name="update_seat" class="btn btn-success"><i class="bi bi-floppy2-fill"></i> Simpan</button>
                    </div>
                    </form>
                </div>
                </div>
            </div>

        <script>
            // Mengisi data ke modal saat kursi diklik
            const editSeatModal = document.getElementById('editSeatModal');
            editSeatModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; // Tombol yang diklik
                const seatId = button.getAttribute('data-id');
                const seatNumber = button.getAttribute('data-number');
                const seatStatus = button.getAttribute('data-status');

                // Isi form di modal dengan data kursi
                editSeatModal.querySelector('#seatId').value = seatId;
                editSeatModal.querySelector('#nomor_kursi').value = seatNumber;
                editSeatModal.querySelector('#seat_status').value = seatStatus;
            });
        </script>
                </div>
        <!-- bootstrap theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
