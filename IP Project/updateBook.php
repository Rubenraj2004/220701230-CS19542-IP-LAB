<?php
// Connect to the database
$servername = "localhost";
$username = "root";  // Update with your database username
$password = "";      // Update with your database password
$dbname = "library_db";  // Update with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted to fetch book details
if (isset($_POST['fetch_book'])) {
    $bookId = $_POST['book_id'];

    // Prepare SQL query to fetch the book by ID
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the book details
        $book = $result->fetch_assoc();
        // Show the form with the book details for editing
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Update Book</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <div class="form-container">
                <h1>Update Book</h1>
                <form method="POST" action="updateBook.php">
                    <input type="hidden" name="id" value="<?php echo $book['id']; ?>" />

                    <label for="title">Book Title:</label>
                    <input type="text" id="title" name="title" value="<?php echo $book['title']; ?>" required><br><br>

                    <label for="author">Author:</label>
                    <input type="text" id="author" name="author" value="<?php echo $book['author']; ?>" required><br><br>

                    <label for="genre">Genre:</label>
                    <input type="text" id="genre" name="genre" value="<?php echo $book['genre']; ?>" required><br><br>

                    <label for="year">Publication Year:</label>
                    <input type="number" id="year" name="year" value="<?php echo $book['year']; ?>" required><br><br>

                    <button type="submit" name="update" class="submit-btn">Update Book</button>
                </form>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "No book found with ID: $bookId";
    }

    $stmt->close();
}

// Check if the form is submitted to update book details
if (isset($_POST['update'])) {
    // Get the updated book details from the form
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $year = $_POST['year'];

    // Update the book in the database
    $stmt = $conn->prepare("UPDATE books SET title = ?, author = ?, genre = ?, year = ? WHERE id = ?");
    $stmt->bind_param("sssii", $title, $author, $genre, $year, $id);

    if ($stmt->execute()) {
        echo "Book updated successfully!";
    } else {
        echo "Error updating book: " . $stmt->error;
    }

    $stmt->close();
}

// Close connection
$conn->close();
?>
