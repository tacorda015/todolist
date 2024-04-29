<?php
include 'db/functions.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from the POST request
    $userId = $_POST['user_id'];
    $task_id = $_POST['task_id'];
    $task_level = $_POST['task_level'];
    $taskName = $_POST['task_name'];
    $taskDescription = $_POST['task_description'];
    $taskDateStart = $_POST['task_date_start'];
    $taskDateEnd = $_POST['task_date_end'];

    $database = new Database(); 
    $connection = $database->connection(); 

    // Assuming you have a tasks table with appropriate columns
    $updateTaskQuery = "UPDATE tasktable SET 
                        user_id = :user_id,
                        task_level = :task_level,
                        task_name = :task_name,
                        task_description = :task_description,
                        task_date_start = :task_date_start,
                        task_date_end = :task_date_end
                        WHERE task_id = :task_id";

    $statement = $connection->prepare($updateTaskQuery);

    // Bind parameters
    $statement->bindParam(':user_id', $userId);
    $statement->bindParam(':task_level', $task_level);
    $statement->bindParam(':task_name', $taskName);
    $statement->bindParam(':task_description', $taskDescription);
    $statement->bindParam(':task_date_start', $taskDateStart);
    $statement->bindParam(':task_date_end', $taskDateEnd);
    $statement->bindParam(':task_id', $task_id);

    // Execute the update
    if ($statement->execute()) {
        $response = ['status' => 'success', 'message' => 'Task updated successfully'];
    } else {
        $errorInfo = $statement->errorInfo();
        $errorMessage = isset($errorInfo[2]) ? $errorInfo[2] : 'Unknown error';

        // Log the error to a file or another logging mechanism
        error_log('Error updating task: ' . $errorMessage);

        $response = ['status' => 'error', 'message' => 'Error updating task'];
    }

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // If the request method is not POST, handle accordingly
    http_response_code(405); // Method Not Allowed
    echo 'Method Not Allowed';
}
?>
