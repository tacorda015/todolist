<?php
// Include your database connection logic here
include 'db/functions.php';
$database = new Database();
$connection = $database->connection();

// Retrieve data from the AJAX request
$updateNameOfUser = $_POST['updateNameOfUser'];
$updatePositionId = $_POST['updatePositionId'];
$updateUserName = $_POST['updateUserName'];
$updateUserPassword = base64_encode($_POST['updateUserPassword']);
$updateAccountId = $_POST['updateAccountId'];
// $updatesectionId = $_POST['updatesectionId'];
$updatesectionId = implode(',', $_POST['updatesectionId']);

// Example query using prepared statement
$insertUserQuery = "UPDATE accounttable SET nameOfUser = ?, userName = ?, userPassword = ?, positionId = ?, sectionId = ? WHERE user_id = ?";
$insertUserStmt = $connection->prepare($insertUserQuery);
$insertUserStmt->execute([$updateNameOfUser, $updateUserName, $updateUserPassword, $updatePositionId, $updatesectionId, $updateAccountId]);

// Check if the query was successful
if ($insertUserStmt) {
    $response = array('status' => 'success', 'message' => 'Staff Information Successfully Update');
    echo json_encode($response);
} else {
    $response = array('status' => 'error', 'message' => 'Error updating Staff Information');
    echo json_encode($response);
}
?>
