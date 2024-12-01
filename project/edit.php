<?php
include "koneksi.php";
$id = $_GET["id"];

if (isset($_POST["submit"])) {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];

    // Proses untuk gambar
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        $foto = file_get_contents($_FILES['foto']['tmp_name']);
        
        // Update dengan gambar baru
        $sql = "UPDATE foto_film SET judul=?, deskripsi=?, foto=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $judul, $deskripsi, $foto, $id);
    } else {
        // Update tanpa mengubah gambar jika tidak ada gambar baru diupload
        $sql = "UPDATE foto_film SET judul=?, deskripsi=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $judul, $deskripsi, $id);
    }

    // Eksekusi query
    if ($stmt->execute()) {
        header("Location: index.php?msg=Data updated successfully");
    } else {
        echo "Failed: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Film</title>

  <style>
    /* Styling umum */
    body {
      font-family: Arial, sans-serif;
      background-color: #121212; /* Dark mode */
      color: white;
      margin: 0;
      padding: 0;
    }

    /* Container untuk form */
    .container {
      width: 100%;
      max-width: 600px;
      margin: 50px auto;
      padding: 30px;
      background-color: #181818; /* Background form */
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    }

    /* Judul */
    .text-center h3 {
      font-size: 24px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 10px;
      color: #e50914; /* Warna merah khas Netflix */
    }

    /* Form styling */
    .form-label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .form-control {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 16px;
      background-color: #333;
      color: white;
    }

    /* Tombol */
    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      text-align: center;
    }

    .btn-success {
      background-color: #28a745;
      color: white;
    }

    .btn-danger {
      background-color: #dc3545;
      color: white;
    }

    .btn:hover {
      opacity: 0.9;
    }

    /* Gambar */
    img {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 16px;
      background-color: #333;
      color: white;
    }

    /* Petunjuk file */
    .text-muted {
      font-size: 12px;
      color: #777;
    }

    input[type="text"], textarea, input[type="file"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: white;
            font-size: 16px;
        }

  </style>
</head>
<body>
  <div class="container">
    <div class="text-center mb-4">
      <h3>Edit Film Information</h3>
      <p class="text-muted">Update film details and click "Update"</p>
    </div>

    <?php
    $sql = "SELECT * FROM foto_film WHERE id = $id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    ?>

    <form action="" method="post" enctype="multipart/form-data">
      <!-- Judul -->
      <div class="mb-3">
        <label class="form-label" for="judul">Judul:</label>
        <input type="text" class="form-control" name="judul" value="<?php echo $row['judul'] ?>" required>
      </div>

      <!-- Deskripsi -->
      <div class="mb-3">
        <label class="form-label" for="deskripsi">Deskripsi:</label>
        <textarea class="form-control" name="deskripsi" rows="4" required><?php echo $row['deskripsi'] ?></textarea>
      </div>


      <div class="form-group">
                <label for="genre">Genre:</label>
                <select id="genre" name="genre" required>
                    <option value="" disabled selected>Pilih Genre</option>
                    <option value="Action">Action</option>
                    <option value="Drama">Drama</option>
                    <option value="Comedy">Comedy</option>
                    <option value="Horror">Horror</option>
                    <option value="Sci-Fi">Sci-Fi</option>
                </select>
            </div>

      <!-- Foto -->
      <div class="mb-3">
        <label class="form-label" for="foto">Foto:</label>
        <?php if ($row['foto']) { ?>
          <div>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['foto']); ?>" alt="Foto Film">
          </div>
        <?php } ?>
        <input type="file" class="form-control" name="foto">
        <small class="text-muted">Pilih gambar baru untuk mengganti, atau biarkan kosong untuk mempertahankan gambar saat ini.</small>
      </div>

      

      <!-- Tombol -->
      <div class="mb-3">
        <button type="submit" class="btn btn-success" name="submit">Update</button>
        <a href="index.php" class="btn btn-danger">Cancel</a>
      </div>
    </form>
  </div>

          
</body>
</html>

<?php
$conn->close();
?>
