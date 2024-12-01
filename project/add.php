<?php
// index.php
include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $genre = $_POST['genre'];

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        // Mengambil data file foto
        $foto = file_get_contents($_FILES['foto']['tmp_name']);
        
        // Query SQL untuk memasukkan data ke tabel
        $stmt = $conn->prepare("INSERT INTO foto_film (judul, deskripsi, genre, foto) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $judul, $deskripsi, $genre, $foto);

        // Eksekusi query
        if ($stmt->execute()) {
            header("Location: index.php?success=1");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Error: Tidak ada file foto yang diupload atau terjadi kesalahan.";
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Foto Film</title>
    <style>
        /* Styling Umum */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: white;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #181818;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
        }

        h1 {
            font-size: 24px;
            font-weight: bold;
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #cccccc;
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

        textarea {
            resize: none;
        }

        .btn-submit {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: red;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s ease-in-out;
        }

        .btn-submit:hover {
            background-color: darkred;
        }

        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tambah Foto Film</h1>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="judul">Judul:</label>
                <input type="text" id="judul" name="judul" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" maxlength="225" placeholder="Max 225 karakter" required></textarea>
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
            <div class="form-group">
                <label for="foto">Foto:</label>
                <input type="file" id="foto" name="foto" required>
            </div>
            <button type="submit" class="btn-submit">Simpan</button>
        </form>
    </div>
</body>
</html>
