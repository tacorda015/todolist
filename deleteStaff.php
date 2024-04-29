<?php
// Include your database connection logic here
include 'db/functions.php';
$database = new Database();
$connection = $database->connection();

// Retrieve data from the AJAX request
$deleteAccountId = $_POST['deleteAccountId'];

// Example query using prepared statement
$deleteSectionQuery = "DELETE FROM accounttable WHERE user_id = ?";
$deleteSectionStmt = $connection->prepare($deleteSectionQuery);
$deleteSectionStmt->execute([$deleteAccountId]);

// Check if the query was successful
if ($deleteSectionStmt->rowCount() > 0) {
    $response = array('status' => 'success', 'message' => 'Staff Successfully deleted');
    echo json_encode($response);
} else {
    $response = array('status' => 'error', 'message' => 'Error deleting staff');
    echo json_encode($response);
}
?>
