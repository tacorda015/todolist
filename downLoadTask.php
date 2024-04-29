<?php
require_once 'tcpdf/tcpdf.php';
include 'db/functions.php';
$db = new Database();
$pdo = $db->connection();

if (isset($_GET['columnId'])) {
    $columnId = $_GET['columnId'];

    // Replace the following with your actual database query
    $query = "SELECT t.*, a.nameOfUser, GROUP_CONCAT(s.sectionName) AS sectionNames
    FROM tasktable t 
    LEFT JOIN accounttable a ON t.user_id = a.user_id 
    LEFT JOIN sectiontable s ON FIND_IN_SET(s.sectionId, a.sectionId)
    WHERE task_status = :columnId
    GROUP BY t.task_id, a.user_id;    
    ";

    // Execute the query using your database connection
    $stmt = $pdo->prepare($query);

    // Bind parameters and execute the query
    $stmt->bindParam(':columnId', $columnId, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch all rows from the result set
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // $getSuperior = "SELECT c.columnName, a.nameOfUser AS managerName, b.nameofUser AS supervisorName 
    // FROM columntable c 
    // LEFT JOIN accounttable a ON FIND_IN_SET(c.sectionId, a.sectionId)
    // LEFT JOIN accounttable b ON FIND_IN_SET(c.sectionId, b.sectionId)
    // WHERE c.columnId = :column
    // AND a.positionId = 2 AND b.positionId = 3";

    $getSuperior = "SELECT c.columnName, COALESCE(a.nameOfUser, 'N/A') AS managerName, COALESCE(b.nameOfUser, 'N/A') AS supervisorName
    FROM columntable c
    LEFT JOIN accounttable a ON c.sectionId = a.sectionId AND a.positionId = 2
    LEFT JOIN accounttable b ON c.sectionId = b.sectionId AND b.positionId = 3
    WHERE c.columnId = :column
    ";

$getSuperiorstmt = $pdo->prepare($getSuperior);
$getSuperiorstmt->bindParam(':column', $columnId, PDO::PARAM_INT);
$getSuperiorstmt->execute();
$getSuperiorResult = $getSuperiorstmt->fetchAll(PDO::FETCH_ASSOC);
}

$taskLevels = [
    1 => 'Low Priority',
    2 => 'Medium Priority',
    3 => 'High Priority',
    4 => 'Urgent'
];

$taskLevelValue = $result['task_level'];
$taskLevelDescription = isset($taskLevels[$taskLevelValue]) ? $taskLevels[$taskLevelValue] : 'Unknown Priority';

if ($taskLevelDescription === 'Unknown Priority') {
    // Print the task level value for debugging
    error_log("Debug: Unknown Task Level Value - $taskLevelValue<br>") ;
}

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetTitle($getSuperiorResult[0]['columnName']);
$pdf->setPrintHeader(false);
$pdf->AddPage();
$html = '<h4>Hayakawa Electrnics (Phils.) Corp.</h4>';


if (!empty($getSuperiorResult)) {
    $firstRow = $getSuperiorResult[0];

    $html1 .= '
        <table cellspacing="1" cellpadding="5">
            <thead>
                <tr>
                    <th colspan="16" style="text-align: center; font-size:15px;"><b>' . $firstRow['columnName'] . '</b></th>
                </tr>
                <tr>
                    <th colspan="3"><b>Manager: </b></th>
                    <th colspan="5" style="border-bottom: 1px solid black; font-size:12px;"> ' . ($firstRow['managerName'] ? $firstRow['managerName'] : 'N/A') . '</th>
                    <th colspan="3"><b>Supervisor: </b></th>
                    <th colspan="5" style="border-bottom: 1px solid black; font-size:12px;"> ' . ($firstRow['supervisorName'] ? $firstRow['supervisorName'] : 'N/A') . '</th>
                </tr>
            </thead>
        </table>';
} else {
    $html1 = '<p>No results found.</p>';
}


    $html2 = "";
    if (isset($results) && is_array($results)) {
        foreach ($results as $result) {
            // Modify this section to replace placeholders with actual database values
            $taskLevelValue = $result['task_level'];
            $taskLevelDescription = isset($taskLevels[$taskLevelValue]) ? $taskLevels[$taskLevelValue] : 'Unknown Priority';
    
            $html2 .= '
            <table cellspacing="2" cellpadding="6">
                <tr>
                    <td colspan="4"><b>Name Of User: </b>' . $result['nameOfUser'] . '</td>
                    <td colspan="4"><b>User Department: </b>' . $result['sectionNames'] . '</td>
                </tr>
                <tr>
                    <td colspan="4"><b>Start of Task: </b>' . date("F j, Y, g:i a", strtotime($result['task_date_start'])) . '</td>
                    <td colspan="4"><b>End of Task: </b>' . date("F j, Y, g:i a", strtotime($result['task_date_end'])) . '</td>
                </tr>
                <tr>
                    <td colspan="8"><b>Level of Task: </b>' . $taskLevelDescription . '</td>
                </tr>
                <tr>
                    <td colspan="8"><b>Name of Task: </b>' . $result['task_name'] . '</td>
                </tr>
                <tr>
                    <td colspan="8"><b>Description of Task: </b>' . $result['task_description'] . '</td>
                </tr>
                <tr>
                    <td colspan="8"><b>Remarks of Task: </b>' . $result['task_remark'] . '</td>
                </tr>
            </table>
            <hr>';
        }
    } else {
        // Handle the case where there are no results (optional)
        $html2 = '<p>No results found.</p>';
    }
    

$pdf->Image('Image/LOGO.jpg', '', '', '', 15, 'JPG', false);
$pdf->writeHTMLCell(0, 0, 28, 14, $html, 0, 1);
$pdf->writeHTMLCell(0, 0, 10, 28, $html1, 1, 1);
$pdf->writeHTML($html2, true, false, true, false, '');
ob_start();
$pdf->Output($getSuperiorResult[0]['columnName'].'.pdf', 'I');
ob_end_flush();
?>
