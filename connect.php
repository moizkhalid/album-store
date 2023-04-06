<?php
// Replace the values with your own database credentials
$host = "localhost";
$username = "root";
$password = "";
$database = "sohaib";

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
