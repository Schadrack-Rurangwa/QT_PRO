<?php
// Database connection
$servername = "your_server_name";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination variables
$itemsPerPage = 5; // Number of items per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number

// Calculate the starting point for the query
$start = ($page - 1) * $itemsPerPage;

// Fetch items from the database using prepared statement
$sqlSelectProject = $conn->prepare("SELECT id, TaskName FROM projects LIMIT ?, ?");
$sqlSelectProject->bind_param("ii", $start, $itemsPerPage);
$sqlSelectProject->execute();
$result = $sqlSelectProject->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagination Example</title>
</head>
<body>

    <!-- Display items -->
    <ul>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<li>{$row['id']} - {$row['TaskName']}</li>";
        }
        ?>
    </ul>

    <!-- Pagination links -->
    <div>
        <?php
        // Count total number of items
        $totalItemsSql = "SELECT COUNT(*) as total FROM projects";
        $totalItemsResult = $conn->query($totalItemsSql);
        $totalItems = $totalItemsResult->fetch_assoc()['total'];

        // Calculate the total number of pages
        $totalPages = ceil($totalItems / $itemsPerPage);

        // Display pagination links
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<a href='?page=$i'>$i</a> ";
        }
        ?>
    </div>

    <?php
    // Close the prepared statement and database connection
    $sqlSelectProject->close();
    $conn->close();
    ?>

</body>
</html>
