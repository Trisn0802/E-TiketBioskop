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
        <div class="movie-container-top">
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
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginDulu">Pesan Tiket</button>
                        <?php } else if ($has_schedule) { ?>
                            <!-- Film ada dalam jadwal -->
                            <a href="pilih_jadwal.php?film_id=<?php echo $film_id; ?>" class="btn btn-warning">Pesan Tiket</a>
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