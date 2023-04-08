<?php
include 'connect.php';

// Get the album id from the URL parameter
$id = $_GET['detailid'];

// Retrieve the album details from the database
$sql = "SELECT * FROM albums WHERE album_id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Check if the album exists
if (!$row) {
    echo "Album not found";
    exit;
}

// Retrieve the track details from the database
$sql = "SELECT * FROM tracks WHERE album_id = $id";
$trackResult = mysqli_query($conn, $sql);

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Detail: <?php echo $row['album_name']; ?></title>
    <!-- css Links -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="./style.css" type="text/css" />
</head>

<body>
    <!-- header Start -->
    <header>
        <div class="container">
            <nav>
                <div class="logo"><a href="index.php">Album Store</a></div>
                <div class="nav-link-wrapper">
                    <a href="./index.php">Home</a>
                    <a href="./insert.php">Add New Album</a>
                </div>
            </nav>
        </div>
    </header>
    <!-- header End -->
    <main>
        <div class="container detail-container">
            <div><span>Album Name: </span><?php echo $row['album_name']; ?></div>
            <div><span>Artist Name: </span><?php echo $row['artist_name']; ?></div>
            <span>Track List</span>
            <ul class="track-list">
                <?php while ($trackRow = mysqli_fetch_assoc($trackResult)) { ?>
                    <li><?php echo $trackRow['track_name']; ?></li>
                <?php } ?>
            </ul>
            <a class="submit-btn" href="./update.php?updateid=<?php echo $id; ?>">Update</a>
        </div>
    </main>
</body>

</html>