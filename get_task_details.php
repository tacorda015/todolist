<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])) {
    // Include your database connection logic
    include 'db/functions.php';
    $database = new Database();
    $connection = $database->connection();

    // Sanitize the input
    $taskId = filter_var($_POST['task_id'], FILTER_SANITIZE_NUMBER_INT);

    // Fetch task details from the database
    $taskDetailsQuery = "SELECT t.*, a.nameOfUser FROM tasktable t LEFT JOIN accounttable a ON t.user_id = a.user_id WHERE task_id = :task_id";
    $stmt = $connection->prepare($taskDetailsQuery);
    $stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $taskDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        // Output task details as JSON (you can customize this based on your needs)
        header('Content-Type: application/json');
        echo json_encode($taskDetails);
    } else {
        // Handle database error
        header('HTTP/1.1 500 Internal Server Error');
        echo 'Error fetching task details';
    }
} else {
    // Invalid request
    header('HTTP/1.1 400 Bad Request');
    echo 'Invalid request';
}
?>
