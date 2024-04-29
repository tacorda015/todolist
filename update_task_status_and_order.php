<?php
include 'db/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['task_id'];
    $newStatus = $_POST['new_status'];
    $newOrder = $_POST['new_order'];
    $displayOrders = $_POST['display_orders'];

    $database = new Database();
    $connection = $database->connection();

    // Update the task status and display order for the dragged task
    $updateQuery = "UPDATE tasktable SET task_status = :new_status, display_order = :new_order WHERE task_id = :task_id";
    $statement = $connection->prepare($updateQuery);
    $statement->bindParam(':new_status', $newStatus, PDO::PARAM_INT);
    $statement->bindParam(':new_order', $newOrder, PDO::PARAM_INT);
    $statement->bindParam(':task_id', $taskId, PDO::PARAM_INT);

    if ($statement->execute()) {
        // Update display orders for all tasks in the same column
        foreach ($displayOrders as $taskOrder) {
            $updateOrderQuery = "UPDATE tasktable SET display_order = :order WHERE task_id = :task_id";
            $orderStatement = $connection->prepare($updateOrderQuery);
            $orderStatement->bindParam(':order', $taskOrder['order'], PDO::PARAM_INT);
            $orderStatement->bindParam(':task_id', $taskOrder['task_id'], PDO::PARAM_INT);
            $orderStatement->execute();
        }

        // echo 'Task status and order updated successfully';
    } else {
        echo 'Error updating task status and order';
    }
}
?>
