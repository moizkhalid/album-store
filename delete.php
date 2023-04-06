<?php
include 'connect.php';

if (isset($_GET['deleteid'])) {
    $id = $_GET['deleteid'];

    // Delete the record from the database
    $sql = "DELETE FROM albums WHERE album_id = $id";
    $result = mysqli_query($conn, $sql);

    // Check if the query was successful
    if ($result) {
        header("Location: index.php");
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
