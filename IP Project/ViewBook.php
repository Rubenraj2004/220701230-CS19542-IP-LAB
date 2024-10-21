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

// Initialize the book variable
$book = null;
$reservedBook = null;

// Check if the form was submitted for book details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_id'])) {
    // Get book ID from the form submission
    $bookId = $_POST['book_id'];  

    // Prepare the SQL statement to fetch the book details
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->bind_param("i", $bookId);  // "i" indicates the parameter is an integer

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a book is found
    if ($result->num_rows > 0) {
        // Fetch the book details
        $book = $result->fetch_assoc();
    } else {
        $book = false;  // No book found
    }

    // Close the statement
    $stmt->close();
}

// Check if the form was submitted for reserved book details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reservation_id'])) {
    // Get reservation ID from the form submission
    $reservationId = $_POST['reservation_id'];

    // Prepare the SQL statement to fetch the reserved book details
    $stmt = $conn->prepare("SELECT * FROM reservations WHERE id = ?");
    $stmt->bind_param("i", $reservationId);  // "i" indicates the parameter is an integer

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a reserved book is found
    if ($result->num_rows > 0) {
        // Fetch the reserved book details
        $reservedBook = $result->fetch_assoc();
    } else {
        $reservedBook = false;  // No reserved book found
    }

    // Close the statement
    $stmt->close();
}

// Fetch all existing books for display
$booksQuery = "SELECT * FROM books";
$booksResult = $conn->query($booksQuery);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Book Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Light background color */
        }
        nav {
            background-color: #343a40; /* Dark background for navbar */
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .submit-btn {
            width: 100%;
        }
        .table-container {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Library Management System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="AddForm.html">Add Book</a></li>
                <li class="nav-item"><a class="nav-link" href="UpdateForm.html">Update Book</a></li>
                <li class="nav-item"><a class="nav-link" href="Reservation.html">Reservation</a></li>
                <li class="nav-item"><a class="nav-link" href="ViewBook.html">View Book</a></li>
                <li class="nav-item"><a class="nav-link" href="FineCal.html">Fine Calculation</a></li>
            </ul>
        </div>
    </nav>

    <div class="form-container">
        <h1>View Book Details</h1>
        <form action="viewBook.php" method="POST">
            <div class="form-group">
                <label for="book_id">Enter Book ID:</label>
                <input type="number" id="book_id" name="book_id" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary submit-btn">View Book</button>
        </form>
    </div>

    <div class="form-container">
        <h2>View Reserved Book</h2>
        <form action="viewBook.php" method="POST">
            <div class="form-group">
                <label for="reservation_id">Enter Reservation ID:</label>
                <input type="number" id="reservation_id" name="reservation_id" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary submit-btn">View Reserved Book</button>
        </form>
    </div>

    <div class="table-container">
        <div class="container">
            <h2>Existing Books</h2>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Genre</th>
                        <th>Year</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($booksResult && $booksResult->num_rows > 0): ?>
                        <?php while ($row = $booksResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['author']); ?></td>
                                <td><?php echo htmlspecialchars($row['genre']); ?></td>
                                <td><?php echo htmlspecialchars($row['year']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No books found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Display the reserved book details if found -->
    <?php if ($reservedBook): ?>
        <div class="table-container">
            <div class="container">
                <h2>Reserved Book Details</h2>
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Reservation ID</th>
                            <th>Book ID</th>
                            <th>Member ID</th>
                            <th>Reserved Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($reservedBook['id']); ?></td>
                            <td><?php echo htmlspecialchars($reservedBook['book_id']); ?></td>
                            <td><?php echo htmlspecialchars($reservedBook['member_id']); ?></td>
                            <td><?php echo htmlspecialchars($reservedBook['reserved_date']); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php elseif ($reservedBook === false): ?>
        <div class="alert alert-danger text-center">No reserved book found for the given Reservation ID.</div>
    <?php endif; ?>
</body>
</html>
