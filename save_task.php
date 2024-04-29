<?php
// Include your database connection logic
include 'db/functions.php';

// Assuming you have a Database class
$database = new Database();
$connection = $database->connection();

// Get task details from POST data
$userId = $_POST['userId'];
$taskName = $_POST['task_name'];
$task_level = $_POST['task_level'];
$taskDescription = $_POST['task_description'];
$taskDateStart = $_POST['task_date_start'];
$taskDateEnd = $_POST['task_date_end'];
$taskStatus = $_POST['task_status'];

// Sample query to insert a new task
$insertQuery = "INSERT INTO tasktable (user_id, task_name, task_level, task_description, task_date_start, task_date_end, task_status) 
                VALUES (:user_id, :task_name, :task_level, :task_description, :task_date_start, :task_date_end, :task_status)";

// Use prepared statement to prevent SQL injection
$statement = $connection->prepare($insertQuery);

// Bind parameters
$statement->bindParam(':user_id', $userId); // Assuming you have a user ID, replace it with the actual user ID
$statement->bindParam(':task_name', $taskName);
$statement->bindParam(':task_level', $task_level);
$statement->bindParam(':task_description', $taskDescription);
$statement->bindParam(':task_date_start', $taskDateStart);
$statement->bindParam(':task_date_end', $taskDateEnd);
$statement->bindParam(':task_status', $taskStatus);

// Execute the statement
if ($statement->execute()) {
    // Return success and the ID of the inserted task
    $response = ['status' => 'success', 'taskId' => $connection->lastInsertId()];
} else {
    // Return an error message
    $response = ['status' => 'error'];
}

// Send a JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
