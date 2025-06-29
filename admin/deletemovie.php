<?php 
    @include "../koneksi.php";

    session_start();
    
    $film_id = $_GET['film_id'];
    $query = "DELETE FROM film WHERE film_id = '$film_id'";
    $result = mysqli_query($conn, $query);

    $query_jadwal = "DELETE FROM jadwal WHERE film_id = '$film_id'";
    $result_jadwal = mysqli_query($conn, $query);

    if ($result && $result_jadwal) {
            echo "<script>
            alert('Film berhasil dihapus!');
            document.location.href = 'adminmovie.php';
            </script>";
        } else {
            echo "<script>
            alert('Film gagal dihapus!');
            document.location.href = 'adminmovie.php';
            </script>";
        }      
?>