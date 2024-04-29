<?php
// Include your database connection logic here
include 'db/functions.php';
$database = new Database();
$connection = $database->connection();

// Retrieve data from the AJAX request
$updateSectionId = $_POST['updateSectionId'];
$updateSectionName = $_POST['updateSectionName'];

// Example query using prepared statement
$insertUserQuery = "UPDATE sectiontable SET sectionName = ? WHERE sectionId = ?";
$insertUserStmt = $connection->prepare($insertUserQuery);
$insertUserStmt->execute([$updateSectionName, $updateSectionId]);

// Check if the query was successful
if ($insertUserStmt) {
    $response = array('status' => 'success', 'message' => 'Section Name Successfully Update');
    echo json_encode($response);
} else {
    $response = array('status' => 'error', 'message' => 'Error updating section name');
    echo json_encode($response);
}
?>
