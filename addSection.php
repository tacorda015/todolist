<?php
// Include your database connection logic here
include 'db/functions.php';
$database = new Database();
$connection = $database->connection();

// Retrieve data from the AJAX request
$addSectionName = $_POST['addSectionName'];

// Example query using prepared statement
$insertSectionQuery = "INSERT INTO sectiontable (sectionName) VALUES (?)";
$insertSectionStmt = $connection->prepare($insertSectionQuery);
$insertSectionStmt->execute([$addSectionName]);

// Check if the query was successful
if ($insertSectionStmt->rowCount() > 0) {

    $lastInsertId = $connection->lastInsertId();
    $insertColumn = "INSERT INTO columntable (columnName, sectionId, bg_color) VALUES (?, ?, ?)";
    $insertColumnResult = $connection->prepare($insertColumn);

    // Check if the column insertion was successful
    if ($insertColumnResult->execute(['To-Do', $lastInsertId, '#3f99f2'])) {
        $response = array('status' => 'success', 'message' => 'Section Successfully Added');
        echo json_encode($response);
    } else {
        // Handle the case where the column insertion failed
        $response = array('status' => 'error', 'message' => 'Error adding column for the section');
        echo json_encode($response);
    }
} else {
    $response = array('status' => 'error', 'message' => 'Error adding section');
    echo json_encode($response);
}
?>
