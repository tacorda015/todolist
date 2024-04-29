<?php
// update_session.php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming the sectionId is passed in the POST data
    if (isset($_POST['sectionId'])) {
        $sectionId = $_POST['sectionId'];

        // Validate or sanitize the sectionId if needed

        // Update the session variable
        $_SESSION['CurrentSectionId'] = $sectionId;

        // Return a response (you can customize the response based on your needs)
        echo json_encode(['status' => 'success', 'message' => 'Session updated successfully']);
        exit;
    }
}

// If the request is not a POST or if the sectionId is not provided, return an error response
echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
exit;
?>
