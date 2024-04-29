<?php
session_start();
// Include your database connection file here
include 'db/functions.php';

// Initialize the database connection
$database = new Database();
$connection = $database->connection();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user input
    $userName = $_POST['userName'];
    $password = base64_encode($_POST['password']);

    // Validate user input (you may add more validation)
    if (empty($userName) || empty($password)) {
        // Return an error message
        echo json_encode(['status' => 'error', 'message' => 'Username and password are required']);
        exit;
    }

    // Perform database query to check credentials
    $query = "SELECT * FROM accounttable WHERE userName = :userName AND userPassword = :password";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':userName', $userName);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    // Check if the user exists
    if ($stmt->rowCount() > 0) {
        // User authentication successful
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $sectionIds = explode(',', $user['sectionId']);
        $firstSectionId = isset($sectionIds[0]) ? $sectionIds[0] : null;

        // Set session variables
        $_SESSION['user_ids'] = $user['user_id'];
        $_SESSION['userName'] = $user['userName'];
        $_SESSION['CurrentSectionId'] = $firstSectionId;

        // Return success message
        echo json_encode(['status' => 'success', 'message' => 'Login successful']);
    } else {
        // User authentication failed
        echo json_encode(['status' => 'error', 'message' => 'Invalid userName or password']);
    }
} else {
    // If the request is not a POST request, return an error
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
