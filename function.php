<!-- <div style="display: none;"> -->
<?php
    @include "koneksi.php";

        function edit() {
        global $conn;

        // Mendapatkan pelanggan_id dari session
        $pelanggan_id = htmlspecialchars($_SESSION['idUser']);

        // Mendapatkan input pengguna
        $nama = htmlspecialchars($_POST['nama']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $passwordLama = htmlspecialchars($_POST['passwordLama']);
        $no_telepon = htmlspecialchars($_POST['no_telepon']);
        $namaLama = htmlspecialchars($_POST['namaLama']);
        $emailLama = htmlspecialchars($_POST['emailLama']);
        $fotoLama = htmlspecialchars($_POST['fotoLama']);

        if ($password !== $passwordLama) {

            $stmt = $conn->prepare('SELECT * FROM pelanggan WHERE password = "$password"');
            // $stmt->bind_param('ss', $nama, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($row['password'] == NULL) {
                    // Jika nama sudah digunakan oleh pengguna lain, tampilkan pesan kesalahan dan kembali ke halaman edit.php
                    echo '<script>alert("Password tidak boleh kosong!!"); window.location.href = "edit.php";</script>';
                    exit();
                }

                elseif ($row['password'] == "") {
                    // Jika nama sudah digunakan oleh pengguna lain, tampilkan pesan kesalahan dan kembali ke halaman edit.php
                    echo '<script>alert("Password tidak boleh kosong!!"); window.location.href = "edit.php";</script>';
                    exit();
                }
            }
        }

        // Cek apakah nama dan email sudah digunakan oleh user lain
        if ($nama !== $namaLama || $email !== $emailLama) {
            // Jika pengguna mengubah nama atau email, cek apakah sudah digunakan oleh pengguna lain
            $stmt = $conn->prepare('SELECT * FROM pelanggan WHERE nama = ? OR email = ?');
            $stmt->bind_param('ss', $nama, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($row['nama'] == $nama) {
                    // Jika nama sudah digunakan oleh pengguna lain, tampilkan pesan kesalahan dan kembali ke halaman edit.php
                    echo '<script>alert("Nama sudah dipakai oleh user lain"); window.location.href = "edit.php";</script>';
                    exit();
                }

                if ($row['email'] == $email) {
                    // Jika email sudah digunakan oleh pengguna lain, tampilkan pesan kesalahan dan kembali ke halaman edit.php
                    echo '<script>alert("Email sudah dipakai oleh user lain"); window.location.href = "edit.php";</script>';
                    exit();
                }
            }
        }

        // cek apakah user pilih foto baru atau tidak
        if ($_FILES['foto']['error'] === 4) {
            // Jika pengguna tidak memilih foto baru, gunakan foto lama
            $foto = $fotoLama;
        } else {
            // Jika pengguna memilih foto baru, upload foto baru
            $foto = upload();
            if (!$foto) {
                return -1;
            }
        }

        // cek apakah user mengganti nomor HP atau tidak
        $stmt = $conn->prepare('SELECT * FROM pelanggan WHERE pelanggan_id = ?');
        $stmt->bind_param('i', $pelanggan_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user['no_telepon'] !== $no_telepon) {
            // Jika pengguna mengubah nomor HP, cek apakah nomor HP sudah digunakan oleh pengguna lain
            $stmt = $conn->prepare('SELECT * FROM pelanggan WHERE no_telepon = ?');
            $stmt->bind_param('s', $no_telepon);
            $stmt->execute();
            $result = $stmt->get_result();

            // Jika nomor HP sudah digunakan oleh pengguna lain, tampilkan pesan kesalahan
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($row['pelanggan_id'] !== $pelanggan_id) {
                    echo '<script>alert("Nomor HP sudah dipakai oleh user lain"); window.location.href = "edit.php";</script>';
                    exit();
                }
            }
        }

        // Update data pengguna ke database
        $stmt = $conn->prepare('UPDATE pelanggan SET 
        nama=?, 
        email=?, 
        password=?, 
        no_telepon=?, 
        foto=? 
        WHERE pelanggan_id=?');
        
        // Bind parameter ke statement
        $stmt->bind_param('sssssi',
        $nama,
        $email,
        $password,
        $no_telepon,
        $foto,
        $pelanggan_id);
        
        // Check apakah nomor HP sudah digunakan oleh pengguna lain sebelum menyimpan perubahan ke database
        $stmt_phone = $conn->prepare('SELECT * FROM pelanggan WHERE no_telepon = ? AND pelanggan_id != ?');
        $stmt_phone->bind_param('si', $no_telepon, $pelanggan_id);
        $stmt_phone->execute();
        $result_phone = $stmt_phone->get_result();
        
        // Jika nomor HP sudah digunakan oleh pengguna lain, tampilkan pesan kesalahan
        if ($result_phone->num_rows > 0) {
            $row_phone = $result_phone->fetch_assoc();
                if ($row_phone['no_telepon'] == $no_telepon) {
                echo '<script>alert("Nomor HP sudah digunakan oleh orang lain"); window.location.href = "edit.php";</script>';
                exit();
            }
        }
        
        // Eksekusi statement untuk menyimpan perubahan ke database
        $stmt->execute();
        
        // cek apakah query berhasil mempengaruhi satu baris data
        if ($stmt->affected_rows === 1) {
            return $stmt->affected_rows; // jika berhasil, kembalikan jumlah baris yang terpengaruh
        } else {
            return -1; // jika gagal atau tidak ada data yang terpengaruh, kembalikan kode error -1
        }
    }

    function upload() {
        $namaFile = $_FILES['foto']['name'];
        $ukuranFile = $_FILES['foto']['size'];
        $error = $_FILES['foto']['error'];
        $tmpName = $_FILES['foto']['tmp_name'];

        // cek apakah tidak ada foto yang diupload 
        if($error === 4){
            echo "<script>
                alert('Pilih foto terlebih dahulu');
                </script>";
            return false;
        }

        // cek apakah yang diupload adalah foto atau bukan
        $ekstensifotoValid = ['jpg','jpeg','png','webp', 'svg'];
        $ekstensifoto = explode('.', $namaFile);
        $ekstensifoto = strtolower(end($ekstensifoto));

        if( !in_array($ekstensifoto, $ekstensifotoValid) ) {
            echo "<script>
                alert('Anda hanya bisa upload foto 
                dengan Ekstensi .jpg | .jpeg | .png | .webp | .svg
                ');
                </script>";
            return false;
        }

        // validasi MIME agar file benar-benar gambar
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $tmpName);
        finfo_close($finfo);
        $mimeValid = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'];
        if (!in_array($mimeType, $mimeValid)) {
            echo "<script>
                alert('File yang diupload bukan gambar yang valid.');
                </script>";
            return false;
        }

        // cek jika ukuran file nya terlalu besar
        if($ukuranFile > 5000000 ) {
            echo "<script>
                alert('Ukuran foto terlalu besar | MAX (5MB)');
                </script>";
            return false;
        }

        // lolos pengecekan, foto siap diupload
        // generate nama foto baru 
        $namaFileBaru = uniqid();
        $namaFileBaru .= '.';
        $namaFileBaru .= $ekstensifoto;

        if (!is_uploaded_file($tmpName) || !move_uploaded_file($tmpName, '../img/' . $namaFileBaru)) {
            echo "<script>
                alert('Gagal mengupload foto, coba lagi.');
                </script>";
            return false;
        }

        return $namaFileBaru;
        // Pada awalnya, function ini menerima file foto yang diupload oleh user. Kemudian, function 
        // ini melakukan validasi terhadap file tersebut untuk memastikan bahwa file yang diupload adalah foto dengan format yang valid. 
        // Jika file yang diupload tidak memenuhi syarat, 
        // maka function akan menampilkan pesan error dan mengembalikan nilai false. 
        // Jika file yang diupload valid, function akan menghasilkan nama file baru yang di-generate dengan menggunakan fungsi uniqid(). 
        // Nama file baru ini kemudian digunakan untuk menyimpan file foto di direktori img/ pada server. 
        // Akhirnya, function mengembalikan nilai namaFileBaru untuk digunakan dalam proses update data.
    }

    // Upload Foto Poster Film
    function uploadFoto() {

        $namaFile = $_FILES['foto']['name'];
        $ukuranFile = $_FILES['foto']['size'];
        $error = $_FILES['foto']['error'];
        $tmpName = $_FILES['foto']['tmp_name'];

        // cek apakah tidak ada foto yang diupload 
        if($error === 4){
            echo "<script>
                alert('Pilih poster film terlebih dahulu');
                </script>";
            return false;
        }

        // cek apakah yang diupload adalah foto atau bukan
        $ekstensifotoValid = ['jpg','jpeg','png','webp', 'svg'];
        $ekstensifoto = explode('.', $namaFile);
        $ekstensifoto = strtolower(end($ekstensifoto));

        if( !in_array($ekstensifoto, $ekstensifotoValid) ) {
            echo "<script>
                alert('Anda hanya bisa upload foto
                dengan Ekstensi .jpg | .jpeg | .png | .webp | .svg
                ');
                </script>";
            return false;
        }

        // cek jika ukuran file nya terlalu besar
        if($ukuranFile > 8000000 ) {
            echo "<script>
                alert('Ukuran foto produk terlalu besar | MAX (8MB)');
                </script>";
            return false;
        }

        // lolos pengecekan, foto siap diupload
        // generate nama foto baru 
        $namaFileBaru = uniqid();
        $namaFileBaru .= '.';
        $namaFileBaru .= $ekstensifoto;

        move_uploaded_file($tmpName, '../movie/' . $namaFileBaru);

        return $namaFileBaru;
    }

    // Upload Foto Poster Film
    function tambahMovie(){
        global $conn;
        
        // Mendapatkan input dari pengguna
        $judul = htmlspecialchars($_POST['judul']);
        $genre = htmlspecialchars($_POST['genre']);
        $durasi = htmlspecialchars($_POST['durasi']);
        $rating = htmlspecialchars($_POST['rating']);
        $sinopsis = htmlspecialchars($_POST['sinopsis']);
        
        // Upload Gambar
        $foto = uploadFoto();
        if (!$foto) {
            return false;
        }
        
        $query = "INSERT INTO film (judul, genre, durasi, rating, sinopsis, foto) VALUES ('$judul', '$genre', '$durasi', '$rating', '$sinopsis','$foto')";
        
        $sql = mysqli_query($conn, $query);
        return mysqli_affected_rows($conn); 
    }

    function formatDurasi($durasi) {
        $jam = floor($durasi / 60); // Hitung jam
        $menit = $durasi % 60; // Sisa menit
        return ($jam > 0 ? $jam . " Jam " : "") . $menit . " Menit";
    }
    
// error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
// ini_set('display_errors', 'Off');

?>
<!-- </div> -->
