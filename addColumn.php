<?php
// Include your database connection logic here
include 'db/functions.php';
$database = new Database();
$connection = $database->connection();

// Retrieve data from the AJAX request
$addColumnName = $_POST['addColumnName'];
$addSectionId = $_POST['addSectionId'];
$addColumnBG = $_POST['addColumnBG'];

$insertCOlumnQuery = "INSERT INTO columntable (columnName, sectionId, bg_color) VALUES (:columnName, :sectionId, :bg_color)";
$insertCOlumnStmt = $connection->prepare($insertCOlumnQuery);
$insertCOlumnStmt->bindParam(':columnName', $addColumnName);
$insertCOlumnStmt->bindParam(':sectionId', $addSectionId);
$insertCOlumnStmt->bindParam(':bg_color', $addColumnBG);
$insertCOlumnStmt->execute();

// Check if the query was successful
if ($insertCOlumnStmt->rowCount() > 0) {
    $response = array('status' => 'success', 'message' => 'Column added successfully');
    echo json_encode($response);
} else {
    $response = array('status' => 'error', 'message' => 'Error adding user');
    echo json_encode($response);
}

?>
