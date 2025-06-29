<?php 
    @include "koneksi.php";

    session_start();
    
    $id_penjual = $_SESSION['id_penjual'];
    $id_produk = $_GET['id_produk'];
    $query = "DELETE FROM produk WHERE id_penjual = '$id_penjual' AND id_produk = '$id_produk'";
    $query2 = "DELETE FROM `order` WHERE id_produk = '$id_produk'";
    $result = mysqli_query($conn, $query);
    $result2 = mysqli_query($conn, $query2);
    //  var_dump($id_penjual);

    if ($result && $result2) {
            echo "<script>
            alert('Produk berhasil dihapus!');
            document.location.href = 'penjual.php';
            </script>";
        } else {
            echo "<script>
            alert('Produk gagal dihapus!');
            document.location.href = 'penjual.php';
            </script>";
        }      
?>