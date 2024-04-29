<?php
include 'db/functions.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from the POST request
    $remarks = $_POST['remarks'];
    $task_id = $_POST['task_id'];

    $database = new Database(); 
    $connection = $database->connection(); 

    // Assuming you have a tasks table with appropriate columns
    $updateTaskQuery = "UPDATE tasktable SET 
                        task_remark = :remarks
                        WHERE task_id = :task_id";

    $statement = $connection->prepare($updateTaskQuery);

    // Bind parameters
    $statement->bindParam(':remarks', $remarks);
    $statement->bindParam(':task_id', $task_id);

    // Execute the update
    if ($statement->execute()) {
        $response = ['status' => 'success', 'message' => 'Task Remarks added successfully'];
    } else {
        $errorInfo = $statement->errorInfo();
        $errorMessage = isset($errorInfo[2]) ? $errorInfo[2] : 'Unknown error';

        // Log the error to a file or another logging mechanism
        error_log('Error adding task remark: ' . $errorMessage);

        $response = ['status' => 'error', 'message' => 'Error adding task remarks'];
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
