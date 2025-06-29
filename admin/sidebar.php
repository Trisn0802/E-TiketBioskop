<!-- Link Icon Boostrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- Sidebar-->
<div class="border-end bg-white" id="sidebar-wrapper">
    <div class="sidebar-heading border-bottom bg-light">
        <center>
            <img class="rounded-circle img-thumbnail img" src="../img/admin_profil.jpg" alt="" style="width: 40%; height: 80px;"> <br>
            <span class="btn btn-dark p-2 mt-1">Admin | <?php echo $nama; ?></span>
        </center>    
    </div>
    <div class="list-group list-group-flush">
    <!-- <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Dashboard</a> -->
    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="admin.php??status=<?php echo $_SESSION['status']; ?>&id=<?php echo $_SESSION['idAdmin']; ?>"><i class="bi bi-person-fill-gear"></i> Akun Admin</a>
    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="adminpelanggan.php??status=<?php echo $_SESSION['status']; ?>&id=<?php echo $_SESSION['idAdmin']; ?>"><i class="bi bi-people-fill"></i> Akun Pelanggan</a>
    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="adminmovie.php??status=<?php echo $_SESSION['status']; ?>&id=<?php echo $_SESSION['idAdmin']; ?>"><i class="bi bi-film"></i> Movie</a>
    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="adminjadwal.php??status=<?php echo $_SESSION['status']; ?>&id=<?php echo $_SESSION['idAdmin']; ?>"><i class="bi bi-calendar-week"></i> Jadwal</a>
    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="adminkursi.php??status=<?php echo $_SESSION['status']; ?>&id=<?php echo $_SESSION['idAdmin']; ?>"><i class="bi bi-grid-3x3-gap-fill"></i> Kursi</a>
    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="../logout.php" onclick="return confirm('Apakah kamu yakin ingin keluar!!');"><i class="bi bi-box-arrow-left"></i> Logout</a>
    <!-- <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Profile</a>
    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Status</a> -->
    <!-- Footer -->
    <?php 
    // require "../footer_fr.php"; 
    ?>
    </div>
</div>