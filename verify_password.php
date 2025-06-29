<?php
// isi File: verify_password.php
$correctPassword = "admin"; // Simpan password debug

// Ambil password dari permintaan POST
$inputPassword = $_POST['password'];

// Cek apakah password sesuai
if ($inputPassword === $correctPassword) {
    echo "success";
} else {
    echo "error";
}
?>
