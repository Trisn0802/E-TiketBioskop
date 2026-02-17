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

    $succsesUpload = "<div class='alert alert-success d-flex align-items-center' role='alert'>
                    <svg class='bi flex-shrink-0 me-2' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/>
                    </svg>
                    <div>
                        Film berhasil ditambahkan!
                    </div>
                </div>";

    // Jika tombol submit ditekan
    if (isset($_POST['submit'])) {
        if (tambahMovie($_POST)>0){
    
            echo "<script>
                    alert('Film berhasil ditambah!');
                    document.location.href = 'adminmovie.php';
                </script>";
    
        } else {
    
            echo "<script>
                    alert('Film gagal ditambah!');
                    
                </script>";
                // document.location.href = 'adminmovie.php';
            return false;
        }
    }

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
            <h1 class="text-center my-3">Daftar Film</h1>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahFilm">
            <i class="bi bi-plus-lg"></i> Tambah Film
            </button>

            <!-- Modal -->
            <div class="modal fade" id="tambahFilm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="tambahFilmLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="tambahFilmLabel">Tambah Film</h1>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="container-fluid mx-auto">
                        <center>
                            <img src="../produk/Movie Upload E-TiketBioskop.gif " alt="Ini foto produk anda" id="foto-preview-sudah" class="rounded mx-auto d-block img-thumbnail mt-3" style="height: 25%;">
                        </center>
                    </div>
                    <div class="modal-body">
                        <script>
                            function showPreviewSudah(event){
                                if(event.target.files.length > 0){
                                var srcSudah = URL.createObjectURL(event.target.files[0]);
                                var previewSudah = document.getElementById("foto-preview-sudah");
                                previewSudah.src = srcSudah;
                                previewSudah.style.display = "block";
                                }
                            }

                            function returnShow(event){
                                // Mengembalikan image preview ke upload your movie
                                var previewSudah = document.getElementById("foto-preview-sudah");
                                previewSudah.src = "../produk/Movie Upload E-TiketBioskop.gif"; // Ganti dengan path default gambar Anda
                                previewSudah.style.display = "block";

                                // Reset form inputs
                                document.getElementById("foto").value = ""; // Reset file input
                                document.getElementById("judul").value = ""; // Reset judul
                                document.getElementById("genre").value = ""; // Reset genre
                                document.getElementById("durasi").value = ""; // Reset durasi
                                document.getElementById("rating").value = ""; // Reset rating
                            }
                        </script>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Upload Poster Film!! MAX (8MB)*</label>
                            <input class="form-control" type="file" id="foto" name="foto" onchange="showPreviewSudah(event);" require>
                        </div>
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="judul" name="judul" placeholder="Masukkan judul film" require>
                        </div>
                        <div class="mb-3">
                            <label for="genre" class="form-label">Genre</label>
                            <input type="text" class="form-control" id="genre" name="genre" placeholder="Action, Drama, ..." require>
                        </div>
                        <div class="mb-3">
                            <label for="durasi" class="form-label">Durasi (Menit)</label>
                            <input type="number" class="form-control" id="durasi" name="durasi" placeholder="126" require>
                        </div>
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating</label>
                            <input type="number" class="form-control" id="rating" name="rating" placeholder="10.0" step="0.1" min="0" max="10" require>
                        </div>
                        <div class="mb-3">
                            <label for="rating" class="form-label">Sinopsis</label>
                            <textarea class="form-control" id="sinopsis" name="sinopsis" rows="3" placeholder="Masukkan ringkasan singkat yang memberikan gambaran umum tentang alur cerita, poin utama, dan faktor penentu lain dari film tersebut!"></textarea>
                        </div>
                        <!-- Cek input rating -->
                        <script>
                            document.getElementById('rating').addEventListener('input', function (e) {
                                const value = e.target.value;
                                if (!/^\d{1,2}(\.\d{1})?$/.test(value) || value > 10 || value < 0) {
                                    e.target.setCustomValidity('Rating harus berupa angka desimal dengan format (0.0 - 10.0)');
                                } else {
                                    e.target.setCustomValidity('');
                                }
                            });
                        </script>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="returnShow(event);"><i class="bi bi-x-circle"></i> Close</button>
                        <button type="submit" name="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Submit</button>
                    </div>
                    </div>
                </form>
            </div>
            </div>
            <br>

            <?php 
            $query = mysqli_query($conn, "SELECT * FROM film");
            ?>

            <?php if(mysqli_num_rows($query) > 0) { ?>
            <link rel="stylesheet" href="../card.css">
            <hr>
            <!-- Tampilan Card Movie -->
            <div class="my-4" style="margin-bottom: 10%;">
                <div class="movie-container konten">
                    <?php while($data = mysqli_fetch_array($query)) { ?>
                    <!-- Movie Card -->
                    <div class="card movie-card position-relative">
                        <img src="../movie/<?php echo $data['foto']; ?>" class="card-img-top" alt="Movie Poster">
                        <!-- Rating di pojok kanan atas -->
                        <div class="card-rating position-absolute top-0 end-0 bg-dark text-white p-2">
                            ⭐ <?php echo $data['rating']; ?>
                        </div>
                        <div class="card-body text-center bg-dark">
                            <h5 class="card-title"><?php echo $data['judul']; ?></h5>
                            <p class="card-durasi text-dark bg-light rounded-3 p-2"><?php echo $data['durasi']; ?> Menit</p>
                        </div>
                        <div class="card-footer bg-secondary">
                            <center>
                            <a href="deletemovie.php?film_id=<?php echo $data['film_id']; ?>" class="btn btn-danger btn-hapus-movie" onclick="return confirm('Apakah anda yakin ingin menghapus movie (<?php echo $data['judul']; ?>)');"><i class="bi bi-trash-fill"></i> Hapus Film</a>
                            <!-- Button modal sinopsis -->
                            <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#sinopsisModal<?php echo $data['film_id']; ?>">
                                Sinopsis
                            </button>
                            <p class="card-durasi text-dark bg-light rounded-3 p-2">Genre: <?php echo $data['genre']; ?></p>                            
                            </center>
                        </div>
                    </div>
                    <!-- Modal Sinopsis -->
                    <div class="modal fade" id="sinopsisModal<?php echo $data['film_id']; ?>" tabindex="-1" aria-labelledby="sinopsisModalLabel<?php echo $data['film_id']; ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="sinopsisModalLabel<?php echo $data['film_id']; ?>">Sinopsis</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <?php echo nl2br($data['sinopsis']); // Mengubah baris baru menjadi <br> ?>
                                </div>
                            </div>
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
            <hr>
                </div>
        <!-- bootstrap theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
