<?php
// Include your database connection logic here
include 'db/functions.php';
$database = new Database();
$connection = $database->connection();

// Retrieve data from the AJAX request
$columnId = $_POST['columnId'];

// $checkTask = "SELECT COUNT(*) AS totalCount FROM tasktable WHERE task_status = '$columnId'";
// $checkTaskResult = $connection->query($checkTask);
// $checkTaskData = $checkTaskResult->fetch(PDO::FETCH_ASSOC);

$deleteTaskQuery = "DELETE FROM tasktable WHERE task_status = '$columnId'";
$deleteTaskResult = $connection->query($deleteTaskQuery);

// if($checkTaskData['totalCount'] == 0){
    // Example query to delete the column
    $deleteColumnQuery = "DELETE FROM columntable WHERE columnId = '$columnId'";
    $deleteColumnStmt = $connection->query($deleteColumnQuery);

    // Check if at least one row was affected
    if ($deleteColumnStmt) {
        $response = array('status' => 'success', 'message' => 'Column successfully deleted');
        echo json_encode($response);
    } else {
        $response = array('status' => 'error', 'message' => 'Error deleting column');
        echo json_encode($response);
    }
// }else{
//     $response = array('status' => 'error', 'message' => 'Column cannot delete while contained a task');
//     echo json_encode($response);
// }
?>
