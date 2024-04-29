<?php
session_start();

if (!isset($_SESSION['user_ids'])) {
    header("Location: index.php");
    exit();
}else{
    $user_id = $_SESSION['user_ids'];
    $CurrentSectionId = $_SESSION['CurrentSectionId'];
}

include '../db/functions.php';
$database = new Database();
$connection = $database->connection();
// For Login User Info
$getUserQuery = "SELECT * FROM accounttable WHERE user_id = '$user_id'";
$getUserResult = $connection->query($getUserQuery)->fetch(PDO::FETCH_ASSOC);

$userPosition = $getUserResult['positionId'];
$userSection = $getUserResult['sectionId'];

date_default_timezone_set('Asia/Manila');

$data = array();

$query = "SELECT t.*, a.* FROM tasktable t LEFT JOIN accounttable a ON t.user_id = a.user_id WHERE a.sectionId = '{$CurrentSectionId}' ORDER BY t.task_id";


$statement = $connection->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

foreach ($result as $row) {
    $data[] = array(
        'id'                => $row["task_id"],
        'title'             => $row["task_name"],
        'description'       => $row["task_description"],
        'status'            => $row["task_status"],
        'level'             => $row["task_level"],
        'bg_color'          => $row["bg_color"],
        'start'             => $row["task_date_start"],
        'end'               => $row["task_date_end"]
    );
}

echo json_encode($data);
?>
