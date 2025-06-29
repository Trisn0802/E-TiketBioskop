<?php 
    @include "koneksi.php";

    session_start();

    $id_user = $_GET['pelanggan_id'];
    $query = "DELETE FROM users WHERE id_user = '$id_pembeli'";
    $result = mysqli_query($conn, $query);
    //  var_dump($id_penjual);

    if ($result) {
        if ($result) {
            echo "<script>
            alert('Akun penjual berhasil dihapus!');
            document.location.href = 'sidebar/adminpenjual.php';
            </script>";
        } else {
            echo "<script>
            alert('Akun penjual gagal dihapus!');
            document.location.href = 'sidebar/adminpenjual.php';
            </script>";
        }
    }        
?>