<?php 
    @include "../koneksi.php";

    session_start();
    
    $jadwal_id = $_GET['jadwal_id'];
    $query = "DELETE FROM jadwal WHERE jadwal_id = '$jadwal_id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
            echo "<script>
            alert('Jadwal berhasil dihapus!');
            document.location.href = 'adminjadwal.php';
            </script>";
        } else {
            echo "<script>
            alert('Jadwal gagal dihapus!');
            document.location.href = 'adminjadwal.php';
            </script>";
        }      
?>