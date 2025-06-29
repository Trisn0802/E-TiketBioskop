<div class="d-none">
<?php 
    include ("../koneksi.php");

    session_start();

    if(isset($_SESSION['status']) && isset($_SESSION['idAdmin'])) {
        //cek nama lengkap user
        $perkenalan = $_SESSION['nama'];
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
            <?php 
                // $id_penjual = $data['id_penjual'];
                $query = mysqli_query($conn, "SELECT COUNT(*) AS count FROM pelanggan WHERE role = 'admin'");
                // $data = mysqli_fetch_assoc($query);
                $row = mysqli_fetch_array($query);
                $color = "green";
                if($row["count"] < 1){
                    $color = "red";
            ?>
            <?php } else {
                    $color;
                } ?>
                <h1 class="text-center my-3">Akun Admin</h1>
            <p>Total ada <?php echo "<span style='font-weight: bold; color: ".$color."'>".$row['count']."</span>"; ?> akun</p>
            <div class="overflow-auto" style="max-height:67vh;">
            <table class="table table-dark table-bordered table-striped">
                <thead>
                    <tr align="center">
                        <th>No</th>
                        <th>Email</th>
                        <th>Nama</th>
                        <th>Foto</th>
                        <th>Nomor HP</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody align="center">
                    <?php 
                    $query = mysqli_query($conn, "SELECT * FROM pelanggan WHERE `role` = 'admin' ORDER BY pelanggan_id DESC");
                    $i = 1;
                    
                    if (mysqli_num_rows($query) > 0) {
                        while ($row = mysqli_fetch_assoc($query)) {
                    ?>
                        <tr>
                            <td style="vertical-align: middle; text-align: center;"><?php echo $i; ?></td>
                            <td style="vertical-align: middle; text-align: center;"><?php echo $row["email"]; ?></td>
                            <td style="vertical-align: middle; text-align: center;"><?php echo $row["nama"]; ?></td>
                            <td align="center" style="vertical-align: middle; text-align: center;">
                                <?php 
                                $belumAdaFoto = "admin_profil.jpg";
                                $foto = $row['foto'] ? $row['foto'] : $belumAdaFoto;
                                ?>
                                <img class="rounded-4" src="../img/<?php echo $foto; ?>" alt="foto produk" width="100" height="100">
                            </td>
                            <td style="vertical-align: middle; text-align: center;">
                                <?php 
                                $noTelepon = $row['no_telepon'] ? $row['no_telepon'] : "Belum ada no hp";
                                ?>
                                <span class="btn btn-<?php echo $row['no_telepon'] ? 'warning' : 'secondary'; ?>"><?php echo $noTelepon; ?></span>
                            </td>
                            <td style="vertical-align: middle; text-align: center;">
                                <?php 
                                if ($row["pelanggan_id"] == $idAdmin) { ?>
                                    <button class="btn btn-primary">You</button>
                                <?php } else { ?> 
                                    <a class="btn btn-danger" href="../adminpelanggandelete.php?pelanggan_id=<?php echo $row['pelanggan_id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus akun (<?php echo $row['nama']; ?>) ?')">Hapus</a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php
                        $i++;
                        }
                    } else {
                        echo "<tr><td colspan='5'>No data found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
                    <!-- Footer -->
                <?php require "../footer_fr.php"; ?>
                </div>

        <!-- bootstrap theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
