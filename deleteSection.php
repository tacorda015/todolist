<?php
// Include your database connection logic here
include 'db/functions.php';
$database = new Database();
$connection = $database->connection();

// Retrieve data from the AJAX request
$deleteSectionId = $_POST['deleteSectionId'];

// Example query using prepared statement
$deleteSectionQuery = "DELETE FROM sectiontable WHERE sectionId = ?";
$deleteSectionStmt = $connection->prepare($deleteSectionQuery);
$deleteSectionStmt->execute([$deleteSectionId]);

// Check if the query was successful
if ($deleteSectionStmt->rowCount() > 0) {
    $response = array('status' => 'success', 'message' => 'Section Successfully deleted');
    echo json_encode($response);
} else {
    $response = array('status' => 'error', 'message' => 'Error deleting section');
    echo json_encode($response);
}
?>
