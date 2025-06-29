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
        $query = mysqli_query($conn, "SELECT * FROM pelanggan WHERE pelanggan_id = '$user'");
        if (mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_array($query);
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
                    <!-- <h1 class="mt-4">Akun Murid</h1> -->
                    <!-- <div class="container-fluid " > -->
            <div class="row">
        <!-- <div class="col-md-2 bg-light py-3">
            <h3 class="text-center">Menu</h3>
            <ul class="list-group">
                <li class="list-group-item">Home</li>
                <li class="list-group-item">Products</li>
                <li class="list-group-item">Orders</li>
                <li class="list-group-item">Checkout</li>
            </ul>
        </div> -->
        <div class="col-md-12" >
            <!-- Main Content -->
            <?php 
                // $id_penjual = $data['id_penjual'];
                $query = mysqli_query($conn, "SELECT * FROM pelanggan WHERE pelanggan_id AND `role` = 'user'");
                // $data = mysqli_fetch_assoc($query);
                $count = mysqli_num_rows($query);
                $color = "green";
                if($count < 1){
                    $color = "red";
            ?>
            <?php } else {
                    $color;
                } ?>
                <h1 class="text-center my-3">Akun Pelanggan</h1>
            <p>Total ada <?php echo "<span style='font-weight: bold; color: $color;'>$count</span>"; ?> akun</p>
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
                <tr>
                <?php 
                    $query = mysqli_query($conn, "SELECT * FROM pelanggan WHERE pelanggan_id AND `role` = 'user' ORDER BY pelanggan_id DESC ");
                    // $data = mysqli_fetch_assoc($query);
                    $i = 1;
                    $total_harga = 0;
                    if (mysqli_num_rows($query) > 0) {
                        while ($row = mysqli_fetch_assoc($query)) {
                ?>
                    <td style="vertical-align: middle; text-align: center;"><?php echo $i; ?></td>
                    <td style="vertical-align: middle; text-align: center;"><?php echo $row["email"]; ?></td>
                    <td style="vertical-align: middle; text-align: center;"><?php echo $row["nama"]; ?></td>
                    <td align="center" style="vertical-align: middle; text-align: center;">
                    <?php 
                    $belumAdaFoto = "demo_profil.svg";
                    if($row['foto']==NULL){ 
                        $row['foto'] = $belumAdaFoto;
                        ?>
                        <img class="rounded-4" src="../img/<?php echo $row['foto'] ?>" alt="foto produk" width="100" height="100">
                    <?php }elseif($row['foto']==""){?>
                        <img class="rounded-4" src="../img/<?php echo $row['foto'] ?>" alt="foto produk" width="100" height="100">
                        <?php }else { ?>
                            <img class="rounded-4" src="../img/<?php echo $row['foto'] ?>" alt="foto produk" width="100" height="100">
                            <?php } ?>
                    </td>
                    <!-- <td style="vertical-align: middle; text-align: center;">
                        <input type="number" class="form-control" value="1" readonly>
                    </td> -->
                    <?php 
                    // $query2 = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk AND id_penjual");
                    // $count2 = mysqli_num_rows($query2);
                    // // $total_produk = 0;
                    ?>
                    <td style="vertical-align: middle; text-align: center;">
                    <?php 
                    $belumAdaNoHp = "Belum ada no hp";
                    if($row['no_telepon']==NULL){
                        $row['no_telepon'] = $belumAdaNoHp;
                    ?>
                        <span class="btn btn-secondary"><?php echo $row['no_telepon']; ?></span>
                        <?php }elseif($row['no_telepon']==""){ 
                            $row['no_telepon'] = $belumAdaNoHp;
                            ?>
                            <span class="btn btn-secondary"><?php echo $row['no_telepon']; ?></span>
                            <?php } else { ?>
                                <span class="btn btn-warning"><?php echo $row['no_telepon']; ?></span>
                                <?php } ?>
                    </td>
                    <td style="vertical-align: middle; text-align: center;">
                        <!-- <button class="btn btn-danger btn-sm">Delete</button> -->
                        <a class="btn btn-danger" href="../adminpenjualdelete.php?id_pembeli=<?php echo $row['pelanggan_id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus akun (<?php echo $row['nama']; ?>) ?')">Hapus</a>
                    </td>
                </tr>
                <?php
                $i++;
                // $total_harga += $row["harga"];
                    }
                } else {
                    echo "No data found";
                }
                ?>
                </tbody>
            </table>
            <?php // var_dump($count2) ?>
            </div>
        </div>
    </div>
                <!-- Footer -->
                <?php require "../footer_fr.php"; ?>
                </div>
        <!-- bootstrap theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
