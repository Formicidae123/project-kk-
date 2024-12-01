<?php
include 'koneksi.php';

// Menangani input pencarian dan genre dari formulir
$search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
$genre = isset($_GET['genre']) ? htmlspecialchars($_GET['genre']) : '';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGETFLIX</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        /* CSS Reset */
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

        /* Header */
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 30px;
            background-color: #181818;
            border-bottom: 1px solid #333;
        }

        .navbar .logo {
            font-size: 1.8em;
            font-weight: bold;
            color: red;
        }

        .navbar ul {
            list-style: none;
            display: flex;
        }

        .navbar ul li {
            margin: 0 10px;
        }

        .navbar ul li a {
            text-decoration: none;
            color: white;
            font-size: 1em;
        }

        .navbar ul li a:hover {
            color: red;
        }

        .navbar .search-bar {
            display: flex;
            align-items: center;
        }

        .navbar .search-bar input,
        .navbar .search-bar select {
            padding: 8px;
            border: none;
            border-radius: 20px;
            background-color: #333;
            color: white;
            margin-right: 10px;
            outline: none;
        }

        .navbar .search-bar button {
            background-color: red;
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 20px;
            cursor: pointer;
        }

        .navbar .search-bar button:hover {
            background-color: darkred;
        }

        /* Hero Section */
        .hero {
            position: relative;
            width: 100%;
            height: 60vh;
            background: url('banner.jpg') no-repeat center center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .hero h1 {
            font-size: 3em;
            color: white;
            margin-bottom: 10px;
        }

        .hero p {
            font-size: 1.2em;
            margin-bottom: 20px;
        }

        .hero .btn {
            padding: 10px 20px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 1em;
        }

        .hero .btn:hover {
            background-color: darkred;
        }

        /* Movie Cards */
        .container {
            padding: 20px;
        }

        .movies-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 5px;
            padding: 20px;
            justify-items: left;
        }

        .movie-card {
            background-color: #1e1e1e;
            border-radius: 10px;
            overflow: hidden;
            width: 100%;
            max-width: 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .movie-card img {
            width: 100%;
            height: 450px;
            object-fit: cover;
            border-bottom: 3px solid #444;
        }

        .movie-card h3 {
            font-size: 1.3rem;
            color: #ffff;
            margin: 5px;
            text-align: center;
        }

        .movie-card p {
            font-size: 0.9rem;
            color: #cccccc;
            margin: 5px;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header class="navbar">
        <div class="logo">NGETFLIX</div>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">Movies</a></li>
            <li><a href="favorite.php">Favorite</a></li>
        </ul>
        <!-- Form Search & Filter Genre -->
        <div class="search-bar">
            <form action="" method="GET">
                <input type="text" name="search" placeholder="Search..." value="<?php echo $search; ?>">
                <select name="genre">
                    <option value="">All Genres</option>
                    <option value="Action" <?php echo ($genre == 'Action') ? 'selected' : ''; ?>>Action</option>
                    <option value="Drama" <?php echo ($genre == 'Drama') ? 'selected' : ''; ?>>Drama</option>
                    <option value="Comedy" <?php echo ($genre == 'Comedy') ? 'selected' : ''; ?>>Comedy</option>
                    <option value="Horror" <?php echo ($genre == 'Horror') ? 'selected' : ''; ?>>Horror</option>
                    <option value="Sci-Fi" <?php echo ($genre == 'Sci-Fi') ? 'selected' : ''; ?>>Sci-Fi</option>
                </select>
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div>
            <h2>Tambahkan Film - Film Favorite Anda</h2>
        </div>
    </section>

</body>

</html>
