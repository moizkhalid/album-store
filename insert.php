<?php
include 'connect.php';

// Check if the form is submitted
if (isset($_POST['submit'])) {

  // Get the input values from the form
  $albumName = $_POST['album'];
  $artistName = $_POST['artist'];
  $trackNames = $_POST['track'];

  // Check if the connection was successful
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Insert album data into the albums table
  $sql = "INSERT INTO albums (album_name, artist_name) VALUES ('$albumName', '$artistName')";
  if (mysqli_query($conn, $sql)) {
    $albumId = mysqli_insert_id($conn); // Get the ID of the newly inserted album
    // Insert track data into the tracks table
    foreach ($trackNames as $trackName) {
      $sql = "INSERT INTO tracks (track_name, album_id) VALUES ('$trackName', '$albumId')";
      mysqli_query($conn, $sql);
    }
    mysqli_close($conn);
    header("Location: index.php");
    exit;
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
}
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
          <input type="text" id="album" name="album" required />
        </div>
        <div class="row">
          <label for="artist">Artist</label>
          <input type="text" id="artist" autocomplete="off" name="artist" required />
          <ul id="artistList"></ul>
        </div>
        <ul class="row track-list">
          <li><label for="track1">Track 1:</label> <input type="text" id="track1" name="track[]" required /></li>
        </ul>
        <div class="action-wrapper"><button class="add-track" type="button">Add</button> or <button class="remove-track" type="button">Remove</button> a Track</div>
        <button type="submit" name="submit">Submit</button>
      </form>
    </div>
  </main>
  <script>
    // Autocomplete Artist List Functionality
    const artistArr = ["artist 1", "artist 2", "artist 3", "artist 4", "artist 5"];

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
    let trackCount = 1; // Set the initial track count to 1
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