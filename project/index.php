<?php
include 'koneksi.php';

// Ambil input dari form search dan genre
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$genre = isset($_GET['genre']) ? mysqli_real_escape_string($conn, $_GET['genre']) : '';

if (isset($_POST['favorite']) && isset($_POST['id'])) {
    $favorite = $_POST['favorite'] == '1' ? 1 : 0;
    $id = $_POST['id'];
    // Update status favorit
    $query = "UPDATE foto_film SET favorit = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $favorite, $id);
    $stmt->execute();
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGETFLIX</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> <!-- icon -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> <!-- icon -->
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

        .card-actions {
            display: flex;
            justify-content: start;
            gap: 5px;
            justify-content: space-between;
            padding: 10px;
            border-top: 1px solid #444;
            background-color: #2a2a2a;
        }

        .card-actions a {
            padding: 8px 15px;
            font-size: 0.9rem;
            text-decoration: none;
            border-radius: 5px;
            color: white;
            transition: background-color 0.3s ease;
        }

        .edit-del {
            display: flex;
            justify-content: start;
            gap: 5px;
            justify-content: space-between;
            padding: 10px;
        }

        .btn-edit {
            background-color: #28a745;
        }

        .btn-edit:hover {
            background-color: #218838;
        }

        .btn-delete {
            background-color: #dc3545;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .movie-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.4);
        }

        .con-like {
            --red: rgb(255, 50, 50);
            position: relative;
            width: 30px;
            height: 30px;
            justify-content: end;

        }

        .con-like .like {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            z-index: 20;
            cursor: pointer;
        }

        .con-like .checkmark {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: end;
            align-items: end;
        }

        .con-like .outline,
        .con-like .filled {
            fill: var(--red);
            position: absolute;
        }

        .con-like .filled {
            animation: kfr-filled 0.5s;
            display: none;
        }

        .con-like .celebrate {
            position: absolute;
            animation: kfr-celebrate 0.5s;
            animation-fill-mode: forwards;
            display: none;
        }

        .con-like .poly {
            stroke: var(--red);
            fill: var(--red);
        }

        .con-like .like:checked~.checkmark .filled {
            display: block
        }

        /* .con-like .like:checked~.checkmark .celebrate {
            display: block
        } */

        @keyframes kfr-filled {
            0% {
                opacity: 0;
                transform: scale(0);
            }

            50% {
                opacity: 1;
                transform: scale(1.2);
            }
        }

        @keyframes kfr-celebrate {
            0% {
                transform: scale(0);
            }

            50% {
                opacity: 0.8;
            }

            100% {
                transform: scale(1.2);
                opacity: 0;
                display: none;
            }
        }

        .btn-like_fav {
            display: flex;
            justify-content: start;
            gap: 5px;
            justify-content: space-between;
            padding: 10px;
            background-color: #2a2a2a;
        }

        /* Favorite */
        .ui-bookmark {
            --icon-size: 24px;
            --icon-secondary-color: rgb(77, 77, 77);
            --icon-hover-color: rgb(97, 97, 97);
            --icon-primary-color: gold;
            --icon-circle-border: 1px solid var(--icon-primary-color);
            --icon-circle-size: 35px;
            --icon-anmt-duration: 0.3s;
        }

        .ui-bookmark input {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            display: none;
        }

        .ui-bookmark .bookmark {
            width: var(--icon-size);
            height: auto;
            fill: var(--icon-secondary-color);
            cursor: pointer;
            -webkit-transition: 0.2s;
            -o-transition: 0.2s;
            transition: 0.2s;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            position: relative;
            -webkit-transform-origin: top;
            -ms-transform-origin: top;
            transform-origin: top;
        }

        .bookmark::after {
            content: "";
            position: absolute;
            width: 10px;
            height: 10px;
            -webkit-box-shadow: 0 30px 0 -4px var(--icon-primary-color),
                30px 0 0 -4px var(--icon-primary-color),
                0 -30px 0 -4px var(--icon-primary-color),
                -30px 0 0 -4px var(--icon-primary-color),
                -22px 22px 0 -4px var(--icon-primary-color),
                -22px -22px 0 -4px var(--icon-primary-color),
                22px -22px 0 -4px var(--icon-primary-color),
                22px 22px 0 -4px var(--icon-primary-color);
            box-shadow: 0 30px 0 -4px var(--icon-primary-color),
                30px 0 0 -4px var(--icon-primary-color),
                0 -30px 0 -4px var(--icon-primary-color),
                -30px 0 0 -4px var(--icon-primary-color),
                -22px 22px 0 -4px var(--icon-primary-color),
                -22px -22px 0 -4px var(--icon-primary-color),
                22px -22px 0 -4px var(--icon-primary-color),
                22px 22px 0 -4px var(--icon-primary-color);
            border-radius: 50%;
            -webkit-transform: scale(0);
            -ms-transform: scale(0);
            transform: scale(0);
        }

        .bookmark::before {
            content: "";
            position: absolute;
            border-radius: 50%;
            border: var(--icon-circle-border);
            opacity: 0;
        }

        /* actions */

        .ui-bookmark:hover .bookmark {
            fill: var(--icon-hover-color);
        }

        .ui-bookmark input:checked+.bookmark::after {
            -webkit-animation: circles var(--icon-anmt-duration) cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
            animation: circles var(--icon-anmt-duration) cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
            -webkit-animation-delay: var(--icon-anmt-duration);
            animation-delay: var(--icon-anmt-duration);
        }

        .ui-bookmark input:checked+.bookmark {
            fill: var(--icon-primary-color);
            -webkit-animation: bookmark var(--icon-anmt-duration) forwards;
            animation: bookmark var(--icon-anmt-duration) forwards;
            -webkit-transition-delay: 0.3s;
            -o-transition-delay: 0.3s;
            transition-delay: 0.3s;
        }

        .ui-bookmark input:checked+.bookmark::before {
            -webkit-animation: circle var(--icon-anmt-duration) cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
            animation: circle var(--icon-anmt-duration) cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
            -webkit-animation-delay: var(--icon-anmt-duration);
            animation-delay: var(--icon-anmt-duration);
        }

        @-webkit-keyframes bookmark {
            50% {
                -webkit-transform: scaleY(0.6);
                transform: scaleY(0.6);
            }

            100% {
                -webkit-transform: scaleY(1);
                transform: scaleY(1);
            }
        }

        @keyframes bookmark {
            50% {
                -webkit-transform: scaleY(0.6);
                transform: scaleY(0.6);
            }

            100% {
                -webkit-transform: scaleY(1);
                transform: scaleY(1);
            }
        }

        @-webkit-keyframes circle {
            from {
                width: 0;
                height: 0;
                opacity: 0;
            }

            90% {
                width: var(--icon-circle-size);
                height: var(--icon-circle-size);
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }

        @keyframes circle {
            from {
                width: 0;
                height: 0;
                opacity: 0;
            }

            90% {
                width: var(--icon-circle-size);
                height: var(--icon-circle-size);
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }

        @-webkit-keyframes circles {
            from {
                -webkit-transform: scale(0);
                transform: scale(0);
            }

            40% {
                opacity: 1;
            }

            to {
                -webkit-transform: scale(0.8);
                transform: scale(0.8);
                opacity: 0;
            }
        }

        @keyframes circles {
            from {
                -webkit-transform: scale(0);
                transform: scale(0);
            }

            40% {
                opacity: 1;
            }

            to {
                -webkit-transform: scale(0.8);
                transform: scale(0.8);
                opacity: 0;
            }
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
                <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
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
            <h1>Welcome to NGETFLIX</h1>
            <p>Explore the latest movies and TV shows</p>
            <a href="add.php" class="btn">Add New + </a>
        </div>
    </section>

    <!-- Content Section -->
    <div class="container">
        <h2>Latest Releases</h2>
        <div class="movies-container">
            <?php
            // Query SQL untuk pencarian dan genre
            $sql = "SELECT * FROM foto_film";
            $conditions = [];

            if (!empty($search)) {
                $conditions[] = "(judul LIKE '%$search%' OR deskripsi LIKE '%$search%')";
            }

            if (!empty($genre)) {
                $conditions[] = "genre = '$genre'";
            }

            if (!empty($_GET['favorit']) && $_GET['favorit'] == '1') {
                $conditions[] = "favorit = 1";  // Filter hanya film yang difavoritkan
            }

            if (count($conditions) > 0) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }

            // Debugging query untuk memastikan query SQL yang terbentuk benar
            // echo $sql; // Uncomment this line to see the query in the browser

            $result = mysqli_query($conn, $sql);


            // Tampilkan hasil pencarian
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                    <div class="movie-card">
                        <img src="get_image.php?id=<?php echo $row['id']; ?>" alt="<?php echo $row['judul']; ?>">
                        <h3><?php echo $row['judul']; ?></h3>
                        <p><?php echo $row['deskripsi']; ?></p>

                        <div class="card-actions">
                            <div class="edit-del">
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn-edit">
                                    <i class='bx bx-edit'></i> <!-- Edit icon -->
                                </a>
                                <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this movie?');">
                                    <i class='bx bxs-trash'></i> <!-- Trash icon for delete -->
                                </a>
                            </div>

                            <div href="favorite.php?id=<?php echo $row['id']; ?>" class="btn-like_fav">
                                <label class="ui-bookmark">
                                    <input type="checkbox">
                                    <div class="bookmark">
                                        <svg viewBox="0 0 32 32">
                                            <g>
                                                <path d="M27 4v27a1 1 0 0 1-1.625.781L16 24.281l-9.375 7.5A1 1 0 0 1 5 31V4a4 4 0 0 1 4-4h14a4 4 0 0 1 4 4z"></path>
                                            </g>
                                        </svg>
                                    </div>
                                </label>
                                
                                <!-- like button -->
                                <div class="con-like">
                                    <input class="like" type="checkbox" title="like">
                                    <div class="checkmark">

                                        <!-- SVG content for like button -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="outline" viewBox="0 0 24 24">
                                            <path d="M17.5,1.917a6.4,6.4,0,0,0-5.5,3.3,6.4,6.4,0,0,0-5.5-3.3A6.8,6.8,0,0,0,0,8.967c0,4.547,4.786,9.513,8.8,12.88a4.974,4.974,0,0,0,6.4,0C19.214,18.48,24,13.514,24,8.967A6.8,6.8,0,0,0,17.5,1.917Zm-3.585,18.4a2.973,2.973,0,0,1-3.83,0C4.947,16.006,2,11.87,2,8.967a4.8,4.8,0,0,1,4.5-5.05A4.8,4.8,0,0,1,11,8.967a1,1,0,0,0,2,0,4.8,4.8,0,0,1,4.5-5.05A4.8,4.8,0,0,1,22,8.967C22,11.87,19.053,16.006,13.915,20.313Z"></path>
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="filled" viewBox="0 0 24 24">
                                            <path d="M17.5,1.917a6.4,6.4,0,0,0-5.5,3.3,6.4,6.4,0,0,0-5.5-3.3A6.8,6.8,0,0,0,0,8.967c0,4.547,4.786,9.513,8.8,12.88a4.974,4.974,0,0,0,6.4,0C19.214,18.48,24,13.514,24,8.967A6.8,6.8,0,0,0,17.5,1.917Z"></path>
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" height="100" width="100" class="celebrate">
                                            <polygon class="poly" points="10,10 20,20"></polygon>
                                            <polygon class="poly" points="10,50 20,50"></polygon>
                                            <polygon class="poly" points="20,80 30,70"></polygon>
                                            <polygon class="poly" points="90,10 80,20"></polygon>
                                            <polygon class="poly" points="90,50 80,50"></polygon>
                                            <polygon class="poly" points="80,80 70,70"></polygon>
                                        </svg>
                                    </div>


                                </div>

                            </div>

                        </div>
                    </div>

            <?php
                }
            } else {
                echo "<p>No results found for '<strong>" . htmlspecialchars($search) . "</strong>'</p>";
            }
            ?>




        </div>
    </div>
</body>

</html>