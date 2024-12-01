<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Query untuk mendapatkan gambar berdasarkan ID
    $stmt = $conn->prepare("SELECT foto FROM foto_film WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($foto);
    $stmt->fetch();

    // Atur header untuk gambar
    header("Content-Type: image/jpeg");
    echo $foto;

    $stmt->close();
    $conn->close();
}
?>

