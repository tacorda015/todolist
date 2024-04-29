<?php
include 'db/functions.php';

// Check if the task ID is provided in the POST request
if (isset($_POST['task_id'])) {
    // Sanitize the input to prevent SQL injection
    $taskId = filter_var($_POST['task_id'], FILTER_SANITIZE_NUMBER_INT);

    $database = new Database(); 
    $connection = $database->connection(); // Assuming your Database class has a connection method
    
    try {
        // Use the established connection from your Database class
        $stmt = $connection->prepare("DELETE FROM tasktable WHERE task_id = :task_id");
        $stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
        $stmt->execute();

        $response = [
            'status' => 'success',
            'message' => 'Task deleted successfully.'
        ];
    } catch (PDOException $e) {
        // Log the exception message for debugging purposes
        error_log('Error deleting task: ' . $e->getMessage());

        $response = [
            'status' => 'error',
            'message' => 'An error occurred while deleting the task.'
        ];
    }

    // Return the JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // If task ID is not provided in the request, return an error response
    $response = [
        'status' => 'error',
        'message' => 'Task ID not provided in the request.'
    ];

    // Return the JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
