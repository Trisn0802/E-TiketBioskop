<?php
    include 'koneksi.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $transaksi_id = $_POST['transaksi_id'];

        // Query untuk mendapatkan total_harga dari transaksi
        $query_trx = "SELECT total_harga FROM transaksi WHERE transaksi_id = '$transaksi_id'";
        $result = mysqli_query($conn, $query_trx);

        if ($result && mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_assoc($result); // Mengambil hasil query
            $refund = $data['total_harga']; // Mendapatkan nilai total_harga

            // Update status transaksi menjadi 'dibatalkan' dan set jumlah_refund
            $query_batal = "
                UPDATE transaksi
                SET status = 'dibatalkan', tanggal_pembatalan = NOW(), jumlah_refund = '$refund'
                WHERE transaksi_id = $transaksi_id
            ";
            $query_update_trx = mysqli_query($conn, $query_batal);

            if ($query_update_trx) {
                // Update status kursi kembali menjadi 'tersedia'
                $query_kursi = "
                    UPDATE kursi_jadwal
                    SET status = 'tersedia'
                    WHERE kursi_id IN (
                        SELECT kursi_id
                        FROM detail_transaksi
                        WHERE transaksi_id = $transaksi_id
                    )
                ";
                $query_update_kursi = mysqli_query($conn, $query_kursi);

                if ($query_update_kursi) {
                    echo "<script>alert('Transaksi berhasil dibatalkan.'); 
                    window.location.href='user/transaksi_tiket.php';</script>";
                } else {
                    echo "<script>alert('Gagal memperbarui status kursi.');</script>";
                }
            } else {
                echo "<script>alert('Gagal memperbarui status transaksi.');</script>";
            }
        } else {
            echo "<script>alert('Transaksi tidak ditemukan atau gagal mengambil data.');</script>";
        }
    }
?>
