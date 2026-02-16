<?php
// Endpoint ini harus mengembalikan JSON murni untuk Summernote.
if (ob_get_level()) {
    ob_end_clean();
}
header("Content-Type: application/json");

if (!isset($_FILES["file"])) {
    echo json_encode([
        "status" => "error",
        "message" => "File tidak ditemukan."
    ]);
    exit;
}

$file = $_FILES["file"];

if ($file["error"] !== 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Terjadi error saat upload."
    ]);
    exit;
}

$allowedExt = ["jpg", "jpeg", "png", "webp", "gif"];
$ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

if (!in_array($ext, $allowedExt)) {
    echo json_encode([
        "status" => "error",
        "message" => "Format gambar tidak didukung."
    ]);
    exit;
}

if ($file["size"] > 5000000) {
    echo json_encode([
        "status" => "error",
        "message" => "Ukuran gambar maksimal 5MB."
    ]);
    exit;
}

$targetDir = "../img/pengumuman/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$newFileName = uniqid("pengumuman_", true) . "." . $ext;
$targetPath = $targetDir . $newFileName;

if (!move_uploaded_file($file["tmp_name"], $targetPath)) {
    echo json_encode([
        "status" => "error",
        "message" => "Gagal menyimpan file."
    ]);
    exit;
}

$scriptDir = str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"]));
$basePath = preg_replace("#/admin$#", "", $scriptDir);
$imageUrl = rtrim($basePath, "/") . "/img/pengumuman/" . $newFileName;

echo json_encode([
    "status" => "success",
    "url" => $imageUrl
]);
exit;
?>
