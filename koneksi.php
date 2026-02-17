<!-- <div style="display: none;"> -->
<?php
require_once __DIR__ . '/config/env.php';
loadEnvFile(__DIR__ . '/.env');

$host = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';
$dbname = getenv('DB_NAME') ?: 'bioskop';
$port = (int) (getenv('DB_PORT') ?: 3306);

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname, $port);

// Check connection
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

?>
<!-- </div> -->
