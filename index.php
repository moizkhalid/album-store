<?php
include 'connect.php';
// Retrieve data from the database
$sql = 'SELECT album_id,album_name, artist_name FROM albums';
$result = mysqli_query($conn, $sql);

// Generate HTML code for each row in the table
$table_data = '';
while ($row = mysqli_fetch_assoc($result)) {
  $table_data .= '<tr>';
  $table_data .= '<td class="text-center">' . $row['album_name'] . '</td>';
  $table_data .= '<td class="text-center">' . $row['artist_name'] . '</td>';
  $table_data .= '<td class="text-center"><a href="./detail.php?detailid=' . $row['album_id'] . '" class="tbl-action detail-link">Details</a></td>';
  $table_data .= '<td class="text-center"><a href="./update.php?updateid=' . $row['album_id'] . '" class="tbl-action update-link">Update</a></td>';
  $table_data .= '<td class="text-center"><a href="./delete.php?deleteid=' . $row['album_id'] . '" class="tbl-action delete-btn">Delete</a></td>';
  $table_data .= '</tr>';
}

// Close the database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Album</title>
  <!-- css Links -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="./style.css" type="text/css" />
</head>

<body>
  <!-- Header Start -->
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
  <!-- Header End -->
  <main>
    <div class="container">
      <!-- Table Section Start -->
      <table>
        <thead>
          <tr>
            <th class="album-th">Album</th>
            <th class="artist-th">Artist</th>
            <th class="action-td"></th>
            <th class="action-td"></th>
            <th class="action-td"></th>
          </tr>
        </thead>
        <tbody>
          <?php echo $table_data; ?>

        </tbody>
      </table>
      <!-- Table Section End -->
    </div>
  </main>
</body>

</html>