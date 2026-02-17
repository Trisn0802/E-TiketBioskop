<!-- <div class="d-none"> -->
<?php
include("../koneksi.php");

session_start();

if (!isset($_SESSION['status']) || !isset($_SESSION['idAdmin'])) {
    header("Location: ../login.php");
    exit;
}

$idAdmin = (int) $_SESSION['idAdmin'];
$userAdmin = mysqli_query($conn, "SELECT * FROM pelanggan WHERE pelanggan_id = '$idAdmin' AND role = 'admin'");
if (!$userAdmin || mysqli_num_rows($userAdmin) < 1) {
    header("Location: ../login.php");
    exit;
}
$dataAdmin = mysqli_fetch_assoc($userAdmin);
$nama = $dataAdmin['nama'];

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS pengumuman (
    pengumuman_id TINYINT NOT NULL DEFAULT 1,
    judul VARCHAR(255) NOT NULL,
    isi LONGTEXT NOT NULL,
    status ENUM('aktif','nonaktif') NOT NULL DEFAULT 'nonaktif',
    dibuat_pada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    diupdate_pada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (pengumuman_id),
    CONSTRAINT chk_single_pengumuman CHECK (pengumuman_id = 1)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

// Migrasi untuk database lama yang belum punya kolom diupdate_pada
$cekKolomUpdate = mysqli_query($conn, "SHOW COLUMNS FROM pengumuman LIKE 'diupdate_pada'");
if ($cekKolomUpdate && mysqli_num_rows($cekKolomUpdate) === 0) {
    mysqli_query($conn, "ALTER TABLE pengumuman
        ADD COLUMN diupdate_pada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
}

mysqli_query($conn, "INSERT INTO pengumuman (pengumuman_id, judul, isi, status)
    SELECT 1, 'Pengumuman', '<p>Belum ada pengumuman.</p>', 'nonaktif'
    WHERE NOT EXISTS (SELECT 1 FROM pengumuman WHERE pengumuman_id = 1)");

if (isset($_POST['simpan_pengumuman'])) {
    $judul = mysqli_real_escape_string($conn, trim($_POST['judul']));
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);

    if ($judul !== '' && trim(strip_tags($isi)) !== '') {
        mysqli_query($conn, "UPDATE pengumuman SET judul = '$judul', isi = '$isi' WHERE pengumuman_id = 1");
        echo "<script>alert('Pengumuman berhasil diperbarui.'); location.href='adminpengumuman.php';</script>";
        exit;
    } else {
        echo "<script>alert('Judul dan isi pengumuman wajib diisi.');</script>";
    }
}

if (isset($_POST['reset_pengumuman'])) {
    mysqli_query($conn, "UPDATE pengumuman
        SET judul = 'Pengumuman',
            isi = '<p>Belum ada pengumuman.</p>',
            status = 'nonaktif'
        WHERE pengumuman_id = 1");
    echo "<script>alert('Pengumuman berhasil direset.'); location.href='adminpengumuman.php';</script>";
    exit;
}

if (isset($_GET['toggle'])) {
    $aksi = $_GET['toggle'] === 'aktif' ? 'aktif' : 'nonaktif';
    mysqli_query($conn, "UPDATE pengumuman SET status = '$aksi' WHERE pengumuman_id = 1");
    echo "<script>location.href='adminpengumuman.php';</script>";
    exit;
}

$pengumuman = mysqli_query($conn, "SELECT * FROM pengumuman WHERE pengumuman_id = 1 LIMIT 1");
$dataPengumuman = mysqli_fetch_assoc($pengumuman);
$dibuatAt = !empty($dataPengumuman['dibuat_pada']) ? date('d-m-Y H:i', strtotime($dataPengumuman['dibuat_pada'])) : '-';
$diupdateAt = !empty($dataPengumuman['diupdate_pada']) ? date('d-m-Y H:i', strtotime($dataPengumuman['diupdate_pada'])) : '-';
?>
<!-- </div> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link rel="stylesheet" href="../css/bootstrap.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../all.min.css">

    <link rel="stylesheet" href="../summernote/summernote-bs5.min.css">
    <script src="../summernote/summernote-bs5.min.js"></script>

    <link href="css/styles.css" rel="stylesheet" />
    <title>Admin | <?php echo $nama; ?></title>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <?php require "sidebar.php"; ?>

        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="navbar-toggler d-block" id="sidebarToggle">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <span class="navbar-text bg-warning rounded-4 p-2 text-bg-warning">
                        <strong><?php require "../judul.php"; ?></strong>
                    </span>
                </div>
            </nav>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="text-center my-3">Pengumuman</h1>

                        <div class="card shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <strong>Pengumuman Utama (Single)</strong>
                                <span class="badge bg-<?php echo $dataPengumuman['status'] === 'aktif' ? 'success' : 'secondary'; ?>">
                                    <?php echo strtoupper($dataPengumuman['status']); ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <div class="mb-3">
                                        <label for="judul" class="form-label">Judul</label>
                                        <input type="text" class="form-control" id="judul" name="judul" value="<?php echo htmlspecialchars($dataPengumuman['judul']); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="isi_pengumuman" class="form-label">Isi Pengumuman (Summernote)</label>
                                        <textarea id="isi_pengumuman" name="isi" required><?php echo $dataPengumuman['isi']; ?></textarea>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="submit" name="simpan_pengumuman" class="btn btn-success">
                                            <i class="bi bi-floppy-fill"></i> Simpan/Edit
                                        </button>

                                        <button type="submit" name="reset_pengumuman" class="btn btn-danger" onclick="return confirm('Reset pengumuman ke default?');">
                                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                                        </button>

                                        <?php if ($dataPengumuman['status'] === 'aktif') { ?>
                                            <a class="btn btn-secondary" href="adminpengumuman.php?toggle=nonaktif">
                                                <i class="bi bi-pause-circle-fill"></i> Nonaktifkan
                                            </a>
                                        <?php } else { ?>
                                            <a class="btn btn-primary" href="adminpengumuman.php?toggle=aktif">
                                                <i class="bi bi-play-circle-fill"></i> Aktifkan
                                            </a>
                                        <?php } ?>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-muted small">
                                Dibuat: <?php echo $dibuatAt; ?> |
                                Diupdate: <?php echo $diupdateAt; ?>
                            </div>
                        </div>

                        <div class="card mt-3 shadow-sm">
                            <div class="card-header"><strong>Preview</strong></div>
                            <div class="card-body">
                                <h5><?php echo htmlspecialchars($dataPengumuman['judul']); ?></h5>
                                <div><?php echo $dataPengumuman['isi']; ?></div>
                            </div>
                        </div>

                        <?php require "../footer_fr.php"; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function uploadPengumumanImage(file, editor) {
            var data = new FormData();
            data.append('file', file);

            $.ajax({
                url: 'upload_pengumuman_image.php',
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (result) {
                    if (result.status === 'success') {
                        $(editor).summernote('insertImage', result.url);
                    } else {
                        alert(result.message || 'Upload gambar gagal.');
                    }
                },
                error: function () {
                    alert('Upload gambar gagal.');
                }
            });
        }

        $(document).ready(function () {
            $('#isi_pengumuman').summernote({
                placeholder: 'Tulis isi pengumuman...',
                height: 320,
                dialogsInBody: true,
                tabsize: 2,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video', 'hr']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    onImageUpload: function(files) {
                        for (var i = 0; i < files.length; i++) {
                            uploadPengumumanImage(files[i], this);
                        }
                    }
                }
            });
        });
    </script>

    <script src="js/scripts.js"></script>
</body>
</html>
