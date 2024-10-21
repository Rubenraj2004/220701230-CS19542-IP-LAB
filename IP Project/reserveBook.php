<?php
// Connect to the MySQL server
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
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) !== TRUE) {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Create reservations table if it doesn't exist
$table_sql = "CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    user_name VARCHAR(255) NOT NULL,
    user_email VARCHAR(255) NOT NULL,
    reservation_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($table_sql) !== TRUE) {
    die("Error creating table: " . $conn->error);
}

// Get form data
$book_id = $_POST['book_id'];
$user_name = $_POST['user_name'];
$user_email = $_POST['user_email'];
$reservation_date = $_POST['reservation_date'];

// Check if the book is already reserved
$check_sql = "SELECT * FROM reservations WHERE book_id='$book_id'";
$result = $conn->query($check_sql);

if ($result->num_rows > 0) {
    echo "Sorry, this book is already reserved.";
} else {
    // Insert the reservation into the database
    $sql = "INSERT INTO reservations (book_id, user_name, user_email, reservation_date) VALUES ('$book_id', '$user_name', '$user_email', '$reservation_date')";

    if ($conn->query($sql) === TRUE) {
        echo "Book reserved successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>
