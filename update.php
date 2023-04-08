<?php
include 'connect.php';

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get the album id from the URL parameter
    $id = $_GET['updateid'];
    // Get the input values from the form
    $albumName = $_POST['album'];
    $artistName = $_POST['artist'];
    $trackNames = $_POST['track'];

    // Check if the connection was successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Update album data in the albums table
    $sql = "UPDATE albums SET album_name='$albumName', artist_name='$artistName' WHERE album_id=$id";
    if (mysqli_query($conn, $sql)) {
        // Delete existing tracks for the album
        $sql = "DELETE FROM tracks WHERE album_id=$id";
        mysqli_query($conn, $sql);
        // Insert new track data into the tracks table
        foreach ($trackNames as $trackName) {
            $sql = "INSERT INTO tracks (track_name, album_id) VALUES ('$trackName', $id)";
            mysqli_query($conn, $sql);
        }
        mysqli_close($conn);
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
} else {
    // Get the album id from the URL parameter
    $id = $_GET['updateid'];

    // Check if the connection was successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve the album data from the database
    $sql = "SELECT * FROM albums WHERE album_id=$id";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    $row = mysqli_fetch_assoc($result);
    $albumName = $row['album_name'];
    $artistName = $row['artist_name'];

    // Retrieve the track data from the database
    $sql = "SELECT * FROM tracks WHERE album_id=$id";
    $result = mysqli_query($conn, $sql);
    $trackNames = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $trackNames[] = $row['track_name'];
    }

    mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Update <?php echo $albumName; ?></title>
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
        <div class="container">
            <form method="post">
                <div class="row">
                    <label for="album">Album</label>
                    <input type="text" id="album" name="album" required value="<?php echo $albumName; ?>" />
                </div>
                <div class="row artist-wrapper">
                    <label for="artist">Artist</label>
                    <input type="text" id="artist" autocomplete="off" name="artist" required value="<?php echo $artistName; ?>" />
                    <ul id="artistList"></ul>
                </div>
                <ul class="row track-list">
                    <?php foreach ($trackNames as $index => $trackName) { ?>
                        <li>
                            <label for="track<?php echo $index + 1; ?>">Track <?php echo $index + 1; ?>:</label>
                            <input type="text" id="track<?php echo $index + 1; ?>" name="track[]" value="<?php echo $trackName; ?>" required />
                        </li>
                    <?php } ?>
                </ul>
                <div class="action-wrapper"><button class="add-track" type="button">Add Track</button><button class="remove-track" type="button">Remove Track</button> </div>
                <button type="submit" name="submit" class="submit-btn">Submit</button>
            </form>
        </div>
    </main>
    <script>
        // Autocomplete Artist List Functionality
        const artistArr = ["Ed Sheeran", "Taylor Swift", "Adele", "Ariana Grande", "Ellie Goulding", "Justin Bieber", "Dua Lipa"];

        const input = document.getElementById("artist");
        const list = document.getElementById("artistList");

        // Function to display all artists in the list
        function displayAllArtists() {
            list.innerHTML = "";
            artistArr.forEach(function(item) {
                let li = document.createElement("li");
                li.textContent = item;
                list.appendChild(li);
            });
        }

        // Display all artists when the input field is focused
        input.addEventListener("focus", function() {
            displayAllArtists();
        });

        // Filter artists when the user types in the input field
        input.addEventListener("input", function() {
            let value = this.value;
            list.innerHTML = "";
            if (!value) {
                return;
            }
            artistArr.forEach(function(item) {
                if (item.substr(0, value.length).toUpperCase() === value.toUpperCase()) {
                    let li = document.createElement("li");
                    li.textContent = item;
                    list.appendChild(li);
                }
            });
        });

        // Set the value of the input field when a list item is clicked
        list.addEventListener("click", function(e) {
            input.value = e.target.textContent;
            list.innerHTML = "";
        });
        // ADDING AND REMOVING TRACK
        const trackList = document.querySelector(".track-list"); // Get the track list element
        const addTrackBtn = document.querySelector(".add-track"); // Get the add track button
        const removeTrackBtn = document.querySelector(".remove-track"); // Get the remove track button
        const trackInputs = document.querySelectorAll('input[name="track[]"]');
        let trackCount = trackInputs.length;

        addTrackBtn.addEventListener("click", function() {
            trackCount++; // Increment the track count
            const newTrackLabel = document.createElement("label"); // Create a new label element
            newTrackLabel.setAttribute("for", `track${trackCount}`); // Set the label's for attribute
            newTrackLabel.textContent = `Track ${trackCount}:`; // Set the label's text content

            const newTrackInput = document.createElement("input"); // Create a new input element
            newTrackInput.setAttribute("type", "text"); // Set the input's type attribute
            newTrackInput.setAttribute("name", "track[]"); // Set the name attribute as an array to collect multiple values
            newTrackInput.setAttribute("id", `track${trackCount}`);
            newTrackInput.setAttribute("required", "");

            // Set the input's id attribute

            const newTrack = document.createElement("li"); // Create a new list item element
            newTrack.appendChild(newTrackLabel); // Add the label to the list item
            newTrack.appendChild(newTrackInput); // Add the input to the list item

            trackList.appendChild(newTrack); // Add the new track to the track list
        });

        removeTrackBtn.addEventListener("click", function() {
            if (trackCount > 1) {
                // Only remove tracks if there are more than one tracks
                const lastTrack = trackList.lastElementChild; // Get the last track element
                trackList.removeChild(lastTrack); // Remove the last track from the track list
                trackCount--; // Decrement the track count
            }
        });
    </script>
</body>

</html>