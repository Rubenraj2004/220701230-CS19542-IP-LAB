<?php
// Connect to MySQL without specifying the database initially
$servername = "localhost";
$username = "root";  // Update with your database username
$password = "";      // Update with your database password

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$dbname = "library_db";
$dbCreationQuery = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($dbCreationQuery) === TRUE) {
    echo "Database '$dbname' created or already exists.<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Create 'books' table if it doesn't exist
$tableCreationQuery = "CREATE TABLE IF NOT EXISTS books (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    genre VARCHAR(100) NOT NULL,
    year INT(4) NOT NULL
)";

if ($conn->query($tableCreationQuery) === TRUE) {
    echo "Table 'books' created or already exists.<br>";
} else {
    die("Error creating table: " . $conn->error);
}

// Get form data
$title = $_POST['title'];
$author = $_POST['author'];
$genre = $_POST['genre'];
$year = $_POST['year'];

// Insert book data into the database
$sql = "INSERT INTO books (title, author, genre, year) VALUES ('$title', '$author', '$genre', '$year')";

if ($conn->query($sql) === TRUE) {
    echo "New book added successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>
