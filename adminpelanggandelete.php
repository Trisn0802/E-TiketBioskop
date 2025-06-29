<?php 
    @include "koneksi.php";

    session_start();

    $id_user = $_GET['pelanggan_id'];
    $query = "DELETE FROM pelanggan WHERE pelanggan_id = '$id_user'";
    $result = mysqli_query($conn, $query);
    //  var_dump($id_penjual);

    if ($result) {

        if ($result) {
            echo "<script>
            alert('Akun murid berhasil dihapus!');
            document.location.href = 'sidebar/admin.php';
            </script>";
        } else {
            echo "<script>
            alert('Akun murid gagal dihapus!');
            document.location.href = 'sidebar/admin.php';
            </script>";
        }
    }        
?>