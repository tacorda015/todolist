<?php
// Include your database connection logic here
include 'db/functions.php';
$database = new Database();
$connection = $database->connection();

// Retrieve data from the AJAX request
$nameOfUser = $_POST['nameOfUser'];
$userName = $_POST['userName'];
$userPassword = base64_encode($_POST['userPassword']);
$bgColor = $_POST['bgColor'];
$positionId = $_POST['positionId'];
$sectionId = implode(',', $_POST['sectionId']);

// Check if the username already exists
$checkUserNameQuery = "SELECT COUNT(*) FROM accounttable WHERE userName = :userName";
$checkUserNameStmt = $connection->prepare($checkUserNameQuery);
$checkUserNameStmt->bindParam(':userName', $userName);
$checkUserNameStmt->execute();
$checkUserNameResult = $checkUserNameStmt->fetchColumn();

if ($checkUserNameResult > 0) {
    $response = array('status' => 'error', 'message' => 'Username already in use');
    echo json_encode($response);
} else {
    $insertUserQuery = "INSERT INTO accounttable (nameOfUser, userName, userPassword, bg_color, sectionId, positionId) VALUES (:nameOfUser, :userName, :userPassword, :bgColor, :sectionId, :positionId)";
    $insertUserStmt = $connection->prepare($insertUserQuery);
    $insertUserStmt->bindParam(':nameOfUser', $nameOfUser);
    $insertUserStmt->bindParam(':userName', $userName);
    $insertUserStmt->bindParam(':userPassword', $userPassword);
    $insertUserStmt->bindParam(':bgColor', $bgColor);
    $insertUserStmt->bindParam(':sectionId', $sectionId);
    $insertUserStmt->bindParam(':positionId', $positionId);
    $insertUserStmt->execute();

    // Check if the query was successful
    if ($insertUserStmt->rowCount() > 0) {
        $response = array('status' => 'success', 'message' => 'User added successfully');
        echo json_encode($response);
    } else {
        $response = array('status' => 'error', 'message' => 'Error adding user');
        echo json_encode($response);
    }
}
?>
