<!-- Navbar -->
<nav class="navbar navbar-primary bg-primary fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand text-light" href="../index.php"><?php include "../judul.php"; ?></a>
    <button class="navbar-toggler text-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" style="color: white;">
      <span class="navbar-toggler-icon" style="color: white;"></span>
    </button>
    <div class="offcanvas offcanvas-end text-bg-primary" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
      <?php
        $fotoNavbar = 'img-default.jpg';
        if (isset($data['foto']) && !empty($data['foto'])) {
          $candidateFoto = basename($data['foto']);
          if (file_exists(__DIR__ . '/../img/' . $candidateFoto)) {
            $fotoNavbar = $candidateFoto;
          }
        }
      ?>
      <?php if(!isset($_SESSION['idUser'])) { ?>
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel"><i class="bi bi-exclamation-circle-fill"></i> Belum Login</h5>
        <?php } else { ?>
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Hallo, <?php echo $data['nama']; ?></h5>
          <img src="../img/<?php echo $fotoNavbar; ?>" alt="ini foto anda" width="50" class="thumbnail float-start rounded" style="margin-right: 80px;">
          <?php } ?>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <hr class="border-5">
      <div class="offcanvas-body text-light">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3" style="color: white;">
        <?php if(!isset($_SESSION['idUser'])) {?>
              <a href='../login.php' class='border text-center text-decoration-none w-100 p-2 text-bg-warning rounded-5'><i class="bi bi-box-arrow-in-right"></i> Login Dulu Bray</a>
            <?php } else { ?>
          <li class="nav-item border text-center">
            <a class="nav-link active text-light" aria-current="page" href="../index.php"><i class="bi bi-house-fill"></i> Home</a>
          </li>
          <br>
          <li class="nav-item border text-center">
            <a class="nav-link text-light" href="transaksi_tiket.php?id=<?php echo $_SESSION['idUser']; ?>"><i class="bi bi-ticket-perforated-fill"></i> Tiket</a>
          </li> 
          <br>
          <li class="nav-item border text-center">
            <a class="nav-link active text-light" aria-current="page" href="edit.php?id=<?php echo $_SESSION['idUser']; ?>"><i class="bi bi-person-fill-gear"></i> Edit Akun</a>
          </li>
          <br>
          
          <li class="nav-item dropdown border text-center">
            <a class="nav-link dropdown-toggle text-light" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Other
            </a>
              <ul class="dropdown-menu">
              <!-- <li><a class="dropdown-item" href="user/edit.php?id=<?php echo $_SESSION['idUser']; ?>">Edit Akun</a></li> -->
              <li>
                <!-- Button trigger modal logout -->
                <button type="button" class="dropdown-item w-100" data-bs-toggle="modal" data-bs-target="#aboutWeb">
                  <i class="bi bi-info-circle-fill"></i> About Web
                </button>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <!-- Button trigger modal logout -->
                <button type="button" class="dropdown-item w-100" data-bs-toggle="modal" data-bs-target="#logout">
                  <i class="bi bi-box-arrow-left"></i> Log Out
                </button>
              </li>
              <?php } ?>
          
        </ul>
        <!-- <form class="d-flex mt-3" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form> -->
      </div>
    </div>
  </div>
</nav>

<!-- About Web Modal -->
<div class="modal fade" id="aboutWeb" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropAboutWeb" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropAboutWeb">About Web</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h3>Tugas Project Akhir Semester 3</h3>
        <hr>
        <h4>Nama Web : E - Tiket Bioskop</h4>
        <b>Matkul: Pemrograman Web 1</b>
              <br>
        <b>Kelompok 3 : </b>
        <br>
        1. Doucure Mohammed Hakeem (<b>17230809</b>)
        <br>
        2. Trisna Almuti (<b>17231043</b>)
        <br>
        3. Muhammad Iqbal (<b>17231017</b>)
        <br>
        4. Dani Putra Ghifari (<b>17230005</b>)

        <legend>Universitas BSI Slipi</legend>
        <h5>2024/2025</h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Mengerti!!!</button>
      </div>
    </div>
  </div>
</div>

<!-- Logout Modal -->
<div class="modal fade" id="logout" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLogout" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLogout">Log Out</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <p>Apakah anda yakin ingin keluar?</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tidak</button>
                      <button type="button" class="btn btn-danger" onclick="location.href='../logout.php'">Ya</button>
                    </div>
                  </div>
              </div>
            </div>
            </ul>
          </li>
