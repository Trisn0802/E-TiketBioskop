<?php
    // Koneksi ke database
    include('../koneksi.php'); // Pastikan koneksi.php berisi koneksi ke database

    // Ambil data dari form
    $pelanggan_id = $_POST['pelanggan_id'];
    $film_id = $_POST['film_id'];
    $jadwal_id = $_POST['jadwal_id'];
    $selectedSeats = $_POST['selectedSeats']; // Format: 'A3, A4'

    // Konversi totalHarga dari format "Rp 100.000" menjadi angka desimal
    $totalHargaRaw = $_POST['totalHarga'];
    $totalHarga = floatval(str_replace(['Rp', '.', ' '], '', $totalHargaRaw)); // Mengubah format ke angka

    $tanggal_transaksi = date('Y-m-d');
    $status_transaksi = 'dipesan';

    // Debugging untuk memastikan data diterima
    // var_dump($pelanggan_id);
    // echo "<br>";
    // var_dump($film_id);
    // echo "<br>";
    // var_dump($jadwal_id);
    // echo "<br>";
    // var_dump($selectedSeats);
    // echo "<br>";
    // var_dump($totalHarga); 
    // echo "<br>";
    // var_dump($tanggal_transaksi);
    // echo "<br>";
    // var_dump($status_transaksi);
    // echo "<br>";

    // Query untuk memeriksa apakah pelanggan_id ada di tabel pelanggan
    $query_pelanggan = "SELECT * FROM pelanggan WHERE pelanggan_id = '$pelanggan_id'";
    $result_pelanggan = mysqli_query($conn, $query_pelanggan);

    if (mysqli_num_rows($result_pelanggan) == 0) {
        // Jika pelanggan tidak ditemukan, tampilkan alert dan hentikan eksekusi
        echo "<script>
        alert('Pelanggan dengan ID $pelanggan_id tidak ditemukan. Proses transaksi dibatalkan.');
        window.location.href='pesan_tiket.php?pelanggan_id=$pelanggan_id&film_id=$film_id&jadwal_id=$jadwal_id';
        </script>";
        exit; // Hentikan eksekusi lebih lanjut
    }

    // Simpan data transaksi ke tabel transaksi
    $query_transaksi = "INSERT INTO transaksi (pelanggan_id, jadwal_id, tanggal_transaksi, total_harga, status) 
                        VALUES ('$pelanggan_id', '$jadwal_id', '$tanggal_transaksi', '$totalHarga', '$status_transaksi')";

    if (mysqli_query($conn, $query_transaksi)) {
        $transaksi_id = mysqli_insert_id($conn); // Ambil ID transaksi yang baru saja disimpan

        // Ambil data kursi yang dipilih sebagai array
        $selectedSeatsArray = explode(',', $selectedSeats); // Data seperti "A3, A4"

        foreach ($selectedSeatsArray as $seatNumber) {
            $seatNumber = trim($seatNumber); // Trim whitespace untuk menghindari kesalahan parsing

            // Cari kursi_id berdasarkan nomor_kursi
            $query_get_kursi_id = "SELECT kursi_id FROM kursi WHERE nomor_kursi = '$seatNumber'";
            $result_get_kursi_id = mysqli_query($conn, $query_get_kursi_id);

            if (mysqli_num_rows($result_get_kursi_id) > 0) {
                $row_kursi = mysqli_fetch_assoc($result_get_kursi_id);
                $kursi_id = $row_kursi['kursi_id']; // Dapatkan kursi_id dari hasil query

                // Periksa apakah kursi sudah ada di tabel kursi_jadwal untuk jadwal_id ini
                $query_kursi_jadwal = "SELECT * FROM kursi_jadwal WHERE kursi_id = '$kursi_id' AND jadwal_id = '$jadwal_id'";
                $result_kursi_jadwal = mysqli_query($conn, $query_kursi_jadwal);

                if (mysqli_num_rows($result_kursi_jadwal) == 0) {
                    // Jika kursi belum ada, insert data ke tabel kursi_jadwal
                    $insert_kursi_jadwal = "INSERT INTO kursi_jadwal (kursi_id, jadwal_id, status) 
                                            VALUES ('$kursi_id', '$jadwal_id', 'tersedia')";
                    if (!mysqli_query($conn, $insert_kursi_jadwal)) {
                        echo "<script>
                        alert('Gagal menambahkan kursi $seatNumber ke tabel kursi_jadwal.');
                        window.location.href='pesan_tiket.php?pelanggan_id=$pelanggan_id&film_id=$film_id&jadwal_id=$jadwal_id';
                        </script>";
                        exit;
                    }
                }

                // Update status kursi ke 'dipesan'
                $query_update_kursi = "UPDATE kursi_jadwal SET status = 'dipesan' 
                                    WHERE kursi_id = '$kursi_id' AND jadwal_id = '$jadwal_id'";
                if (!mysqli_query($conn, $query_update_kursi)) {
                    echo "<script>
                        alert('Gagal memperbarui status kursi $seatNumber.');
                        window.location.href='pesan_tiket.php?pelanggan_id=$pelanggan_id&film_id=$film_id&jadwal_id=$jadwal_id';
                        </script>";
                    exit;
                }

                // Insert ke tabel detail_transaksi
                $query_detail = "INSERT INTO detail_transaksi (transaksi_id, kursi_id, harga) 
                                VALUES ('$transaksi_id', '$kursi_id', '$totalHarga')";
                if (!mysqli_query($conn, $query_detail)) {
                    echo "<script>
                    alert('Gagal menyimpan detail transaksi untuk kursi $seatNumber.');
                    window.location.href='pesan_tiket.php?pelanggan_id=$pelanggan_id&film_id=$film_id&jadwal_id=$jadwal_id';
                    </script>";
                    exit;
                }
            } else {
                // Jika kursi tidak ditemukan di tabel kursi
                echo "<script>
                alert('Nomor kursi $seatNumber tidak ditemukan di tabel kursi.');
                window.location.href='pesan_tiket.php?pelanggan_id=$pelanggan_id&film_id=$film_id&jadwal_id=$jadwal_id';
                </script>";
                exit;
            }
        }

        // Redirect ke halaman sukses atau konfirmasi setelah semua berhasil
        echo "<script>
        alert('Pemesanan tiket berhasil.');
        window.location.href='transaksi_tiket.php';
        </script>";

    } else {
        // Jika gagal, tampilkan error
        echo "<script>alert('Terjadi kesalahan saat menyimpan transaksi: " . mysqli_error($conn) . "');
            window.location.href='pesan_tiket.php?pelanggan_id=$pelanggan_id&film_id=$film_id&jadwal_id=$jadwal_id';
        </script>";
        exit;
    }
?>
