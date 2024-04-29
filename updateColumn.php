<?php
// Include your database connection logic here
include 'db/functions.php';
$database = new Database();
$connection = $database->connection();

// Retrieve data from the AJAX request
$updateColumnName = $_POST['updateColumnName'];
$updateColumnBG = $_POST['updateColumnBG'];
$updateColumnId = $_POST['updateColumnId'];

// Example query using prepared statement
$insertUserQuery = "UPDATE columntable SET columnName = ?, bg_color = ? WHERE columnId = ?";
$insertUserStmt = $connection->prepare($insertUserQuery);
$insertUserStmt->execute([$updateColumnName, $updateColumnBG, $updateColumnId]);

// Check if the query was successful
if ($insertUserStmt) {
    $response = array('status' => 'success', 'message' => 'Section Name Successfully Update');
    echo json_encode($response);
} else {
    $response = array('status' => 'error', 'message' => 'Error updating section name');
    echo json_encode($response);
}
?>
