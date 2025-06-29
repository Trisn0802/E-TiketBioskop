<div style="display: none;">
<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "bioskop";

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
} else {
    echo "<center>";
    echo "<h1 style='padding: 10px; background: green; color: white; margin-top: 5em;'>";
    echo "Koneksi Berhasil";
    echo "</h1>";
    echo "</center>";
}

?>
</div>
