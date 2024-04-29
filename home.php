<?php
session_start();

if (!isset($_SESSION['user_ids'])) {
    header("Location: index.php");
    exit();
}else{
    $user_id = $_SESSION['user_ids'];
    $CurrentSectionId = $_SESSION['CurrentSectionId'];  
}

include 'db/functions.php';
$database = new Database();
$connection = $database->connection();

// For Login User Info
$getUserQuery = "SELECT * FROM accounttable WHERE user_id = '$user_id'";
$getUserResult = $connection->query($getUserQuery)->fetch(PDO::FETCH_ASSOC);

$userPosition = $getUserResult['positionId'];
$UserSectionIds = explode(',', $getUserResult['sectionId']);
$numberOfSections = count($UserSectionIds);

// Fetch IT Staff data
// $itStaffListQuery = "SELECT * FROM accounttable WHERE FIND_IN_SET(sectionId, '$CurrentSectionId')";
$itStaffListQuery = "SELECT * FROM accounttable WHERE EXISTS (SELECT 1 FROM sectiontable WHERE FIND_IN_SET(sectiontable.sectionId, accounttable.sectionId) AND FIND_IN_SET(sectiontable.sectionId, '$CurrentSectionId'))";
$itStaffResult = $connection->query($itStaffListQuery)->fetchAll(PDO::FETCH_ASSOC);

$getColumn = "SELECT * FROM columntable WHERE sectionId = '{$CurrentSectionId}' ORDER BY columnId";
$getColumnResult = $connection->query($getColumn);

// Check if $getColumnResult is not empty
$statusData = [];

while ($row = $getColumnResult->fetch(PDO::FETCH_ASSOC)) {
    $columnName = $row['columnName'];
    $columnId = $row['columnId'];
    $bgColor = $row['bg_color'];

    $statusQueries[$columnName] = [
        'query' => "SELECT t.*, a.nameOfUser, a.bg_color FROM tasktable t LEFT JOIN accounttable a ON t.user_id = a.user_id WHERE t.task_status = '{$columnId}' AND a.sectionId = '{$CurrentSectionId}' ORDER BY display_order",
    ];

    // Check if the query was successful
    if ($statusQueries[$columnName]['query']) {
        $result = $connection->query($statusQueries[$columnName]['query'])->fetchAll(PDO::FETCH_ASSOC);
        $statusData[$columnName] = [
            'tasks' => $result,
            'columnId' => $columnId,
            'bgColor' => $bgColor,
        ];
    } else {
        // Handle the case where the query was not successful
        echo "Error in query for column: $columnName";
    }
}

function getBorderColor($taskLevel) {
    switch ($taskLevel) {
        case 1:
            return '#ff2323'; // Red
        case 2:
            return '#ff7f0a'; // Orange
        case 3:
            return '#ffff00'; // Yellow
        case 4:
            return '#00e606'; // Green
        default:
            return '#000000'; // Default color (black) or handle other cases as needed
    }
}

$taskLevels = array(1, 2, 3, 4);

function isColorDark($hexColor, $threshold = 0.5) {
    // Remove the '#' if present
    $hexColor = ltrim($hexColor, '#');

    // Convert hex to RGB
    $r = hexdec(substr($hexColor, 0, 2)) / 255;
    $g = hexdec(substr($hexColor, 2, 2)) / 255;
    $b = hexdec(substr($hexColor, 4, 2)) / 255;

    // Calculate relative luminance
    $luminance = 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;

    // If luminance is less than the threshold, the color is considered dark
    return $luminance < $threshold;
}

$getfirstColumn = "SELECT * FROM columntable WHERE sectionId = '{$CurrentSectionId}' ORDER BY columnId";
$getfirstColumnResult = $connection->query($getfirstColumn);
$getfirstColumnData = $getfirstColumnResult->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>

    <!-- Icon -->
    <link rel="icon" type="image/x-icon" href="./Image/LOGO.png">   

    <!-- ======  Bootstrap  ===== -->
    <link rel="stylesheet" href="./node_modules/bootstrap-icons/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css">

    <!-- ======  JQuery  ===== -->
    <script src="./node_modules/jquery/dist/jquery.min.js"></script>
        
    <!-- ======  Sweet Alert  ===== -->
    <link rel="stylesheet" href="./node_modules/sweetalert2/dist/sweetalert2.min.css">
    <script src="./node_modules/sweetalert2/dist/sweetalert2.min.js"></script>

    <!-- ======  Manual CSS  ===== -->
    <link rel="stylesheet" href="./CSS/index.css">

    <style>
        .drag-item.not-assigned {
            user-select: none;
            cursor: auto !important;
        }
    </style>
</head>
<body class="bg-body-tertiary">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 d-flex justify-content-center align-items-center">
                <h6 class="m-0"><span id="realTimeDate"></span></h6>
            </div>
            <div class="col-md-4">
                <h2 class="text-center mt-2">Task Monitoring</h2>
            </div>
            <div class="col-md-4 d-flex gap-3 justify-content-end align-items-center">
                
                <?php if($getUserResult['positionId'] != 4 ): ?>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addColumn">
                        <i class="bi bi-file-plus"></i> Add Column
                    </button>
                <?php endif; ?>

                <button class="btn btn-primary fs-5" type="button" data-bs-toggle="offcanvas" data-bs-target="#burgerMenu" aria-controls="burgerMenu"><i class="bi bi-list"></i></button>
            </div>
        </div>
        <div class="row mx-2">
            <div class="col-12">
                <ul class="nav nav-tabs">
                    <?php
                    $userSectionIds = explode(',', $getUserResult['sectionId']);
                    $currentSectionId = isset($_SESSION['CurrentSectionId']) ? $_SESSION['CurrentSectionId'] : null;

                    foreach ($userSectionIds as $index => $sectionIdTabs) {
                        $isActive = ($sectionIdTabs == $currentSectionId) ? 'active' : '';
                        $getSectionName = "SELECT sectionName FROM sectiontable WHERE sectionId = $sectionIdTabs";
                        $getSectionNameResult = $connection->query($getSectionName)->fetch(PDO::FETCH_ASSOC);

                        echo '<li class="nav-item">';
                        echo '<a class="nav-link ' . $isActive . '" data-section-id="' . $sectionIdTabs . '">' . $getSectionNameResult['sectionName'] . '</a>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
        <ul class="row align-items-start m-2 mt-0">
            <?php foreach ($statusData as $status => $data) : ?>
                <li class="minWidth p-0 drag-column-<?= strtolower(str_replace(' ', '-', $status)) ?>">
                    <div class="m-2 border border-secondary-subtle shadow-sm rounded">
                        <?php $fontColor = isColorDark($data['bgColor']) ? '#ffffff' : '#000000'; ?>

                        
                            <span class="justify-content-center d-flex p-2 rounded-top" style="background-color: <?php echo $data['bgColor'] ?>;">
                                <h3 class="position-relative w-100 text-center">
                                <button type="button" class="w-100 p-0 border-0 edit-column-btn bg-transparent" data-bs-toggle="modal" data-bs-target="#editColumn" data-column-name="<?= $status ?>" data-column-color="<?= $data['bgColor'] ?>"  data-column-id="<?= $data['columnId'] ?>">
                                    <p style="margin: 0; color: <?php echo $fontColor; ?>"><?= $status ?></p>
                                </button>
                                    
                                    <?php if ($status === 'To-Do' && $getUserResult['positionId'] != 4) : ?>
                                        <i class="bi bi-plus-circle-fill position-absolute top-0 end-0 plus-icon" style="z-index: 999;" data-toggle="modal" data-target="#addTaskModal"></i>
                                    <?php endif; ?>

                                </h3>
                            </span>

                        <ul class="drag-inner-list" id="<?= strtolower(str_replace(' ', '-', $status)) ?>" data-status="<?= $data['columnId'] ?>">
                            <?php foreach ($data['tasks'] as $order => $task) : ?>
                                <?php
                                // Extract initials from nameOfUser
                                $nameParts = explode(' ', $task['nameOfUser']);
                                $initials = '';
                                foreach ($nameParts as $part) {
                                    $initials .= strtoupper($part[0]);
                                }

                                // Check if the task is assigned to the logged-in user
                                $isAssignedToUser = $task['user_id'] == $user_id;
                                ?>

                                <li class="viewIcons drag-item m-2 position-relative rounded-end shadow-sm border-start border-5 <?php echo $isAssignedToUser ? '' : 'not-assigned'; ?>" style="border-color: <?= getBorderColor($task['task_level']) ?> !important; cursor: pointer;" data-task-id="<?= $task['task_id'] ?>" data-order="<?= $order ?>" data-owner-id="<?= $task['user_id'] ?>">
                                    <div class="task-content" style="width: 80%; height: 100%; overflow: hidden; overflow-wrap: break-word;">
                                        <p class="m-0"><?= nl2br($task['task_name']) ?></p>
                                        <p class="m-0"><?= nl2br($task['task_description']) ?></p>
                                        <p class="m-0 fw-bold"><?= nl2br($task['task_remark']) ?></p>
                                    </div>
                                    <!-- <i class="bi bi-pencil-square editIcons"></i> -->
                                    <p class="position-absolute p-3 rounded-circle d-flex justify-content-center align-items-center" style="width: 50px; height: 50px; right:.5rem; bottom: -0.5rem; background-color: <?= $task['bg_color'] ?>;">
                                        <?= $initials ?>
                                    </p>
                                    <p class="m-0">
                                        <?php 
                                            $rawDate = $task['task_date_end'];

                                            // Create a DateTime object
                                            $date = new DateTime($rawDate);
                                            
                                            // Format the date as per your requirement (e.g., "January 13, 2024 15:30")
                                            $formattedDate = $date->format('F j, Y H:i');
                                            
                                            echo nl2br($formattedDate) 
                                        ?>
                                    </p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Modal of View Task Details -->
    <div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header viewModal">
                    <h5 class="modal-title" id="taskModalLabel">Task Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body viewModal">
                    <div class="row">
                        <div class="col-9"><strong>Assigned to:</strong> <p id="assignedTo" class="form-control"></p></div>
                        <div class="col-3"><strong>Status:</strong> <p id="statusLabel" class="form-control"></p></div>
                    </div>
                    <strong>Task Name:</strong> <p id="taskName" class="form-control overflow-wrap"></p>
                    <strong>Description:</strong> <p id="taskDescription" class="form-control"></p>
                    <strong>Remarks:</strong> <p id="taskRemarks" class="form-control"></p>
                    <div class="row">
                        <div class="col-6"><strong>Date Start:</strong> <p id="dateStart" class="form-control"></p></div>
                        <div class="col-6"><strong>Date End:</strong> <p id="dateEnd" class="form-control"></p></div>
                    </div>
                    
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info remarksIconInModal">Remarks</button>

                    <!-- Check The Position of User if NOT MANAGER, SUPERVISOR, OR ADMIN NOT DISPLAY -->
                    <?php if($getUserResult['positionId'] != 4 ): ?>
                        <button type="button" class="btn btn-danger deleteIconInModal">Delete Task</button>
                        <button type="button" class="btn btn-primary editIconInModal">Edit Task</button>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal of Add Task -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTaskModalLabel">New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <select name="user_id" id="user_id" class="form-select">
                        <?php foreach ($itStaffResult as $staff) : ?>
                            <option value="<?= $staff['user_id'] ?>"><?= $staff['nameOfUser'] ?></option>
                        <?php endforeach; ?>
                        </select>
                        <label for="user_id">IT Staff Assigned</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select name="task_level" id="task_level" class="form-select">
                                <option value="4">Low Priority</option>
                                <option value="3">Medium Priority</option>
                                <option value="2">High Priority</option>
                                <option value="1">Urgent</option>
                        </select>
                        <label for="task_level">Task Level</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="task_name" name="task_name" placeholder="Task Title">
                        <label for="task_name">Task Title</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Leave a task description here" id="task_description" name="task_description" style="height: 100px"></textarea>
                        <label for="task_description">Task Description</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="datetime-local" class="form-control" id="task_date_start" placeholder="Task Start">
                        <label for="task_date_start">Task Start</label>
                    </div>
                    <div class="form-floating">
                        <input type="datetime-local" class="form-control" id="task_date_end" placeholder="Task End">
                        <label for="task_date_end">Task End</label>
                    </div>
                    <input type="hidden" name="task_status" value="<?php echo $getfirstColumnData['columnId'] ?>">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveTaskBtn">Save Task</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal of Add Remarks -->
    <div class="modal fade" id="remarksModal" tabindex="-1" role="dialog" aria-labelledby="remarksModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header viewModal">
                    <h5 class="modal-title" id="remarksModalLabel">Add Task Remarks</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body viewModal">
                    <div class="form-floating">
                        <textarea class="form-control" name="remarks" id="remarks" placeholder="Leave a comment here" style="height: 100px"></textarea>
                        <label for="remarks">Remarks</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary addRemarks">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Edit Task -->
    <div class="modal fade" id="editTaskModal" tabindex="-1" role="dialog" aria-labelledby="editTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <select name="updateuser_id" id="updateuser_id" class="form-select">
                        <?php foreach ($itStaffResult as $staff) : ?>
                            <option value="<?= $staff['user_id'] ?>"><?= $staff['nameOfUser'] ?></option>
                        <?php endforeach; ?>
                        </select>
                        <label for="updateuser_id">IT Staff Assigned</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select name="updatetask_level" id="updatetask_level" class="form-select">
                            <option value="1">Urgent</option>
                            <option value="2">High Priority</option>
                            <option value="3">Medium Priority</option>
                            <option value="4">Low Priority</option>
                        </select>
                        <label for="updatetask_level">Task Level</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control overflow-wrap" id="updatetask_name" name="updatetask_name" placeholder="name@example.com">
                        <label for="updatetask_name">Task Title</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Leave a task description here" id="updatetask_description" name="updatetask_description" style="height: 100px"></textarea>
                        <label for="updatetask_description">Task Description</label>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-floating mb-3">
                                <input type="datetime-local" class="form-control" id="updatetask_date_start" placeholder="Task Start">
                                <label for="updatetask_date_start">Task Start</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-floating">
                                <input type="datetime-local" class="form-control" id="updatetask_date_end" placeholder="Task End">
                                <label for="updatetask_date_end">Task End</label>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="updatetask_id" id="updatetask_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updateTaskBtn">Update Task</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addUserLabel">Add User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nameOfUser" name="nameOfUser" placeholder="Name of Staff">
                        <label for="nameOfUser">Name of Staff</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="userName" name="userName" placeholder="Username">
                        <label for="userName">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="userPassword" name="userPassword" placeholder="Password of Staff">
                        <label for="userPassword">Password of Staff</label> 
                    </div>

                    <input type="hidden" id="bg_color" name="bg_color" value="">

                    <?php if($getUserResult['positionId'] == 1) : ?>
                        <div class="form-floating mb-3">
                            <select name="sectionId" id="sectionId" class="form-select">
                                <?php
                                $getSectionQuery = "SELECT * FROM sectiontable ORDER BY sectionName";
                                $getSectionResult = $connection->query($getSectionQuery);
                                $getSectionData = $getSectionResult->fetchAll();

                                foreach ($getSectionData as $section) {
                                    $sectionId = $section['sectionId'];
                                    $sectionName = $section['sectionName'];
                                    ?>
                                    <option value="<?= $sectionId ?>"><?= $sectionName ?></option>
                                <?php } ?>
                            </select>
                            <label for="sectionId">Staff Section</label>
                        </div>

                        <div class="form-floating mb-3">
                            <select name="positionId" id="positionId" class="form-select">
                                <?php
                                $getSectionQuery = "SELECT * FROM positiontable ORDER BY positionId DESC";
                                $getSectionResult = $connection->query($getSectionQuery);
                                $getSectionData = $getSectionResult->fetchAll();

                                foreach ($getSectionData as $section) {
                                    $positionId = $section['positionId'];
                                    $positionName = $section['positionName'];
                                    ?>
                                    <option value="<?= $positionId ?>"><?= $positionName ?></option>
                                <?php } ?>
                            </select>
                            <label for="positionId">Staff Position</label>
                        </div>
                    <?php endif; ?>

                    <?php if($getUserResult['positionId'] == 2 || $getUserResult['positionId'] == 3): ?>
                        <input type="hidden" id="sectionId" name="sectionId" value="<?php echo $CurrentSectionId ?>">
                        <input type="hidden" id="positionId" name="positionId" value="4">
                    <?php endif; ?>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveChanges()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Column Modal -->
    <div class="modal fade" id="addColumn" tabindex="-1" aria-labelledby="addColumnLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addColumnLabel">Add Column</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="addColumnName" name="addColumnName" placeholder="Name of Staff">
                        <label for="addColumnName">Column Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="color" class="form-control form-control-color w-100" id="addColumnBG" name="addColumnBG" placeholder="Name of Staff">
                        <label for="addColumnBG">Background Color</label>
                    </div>
                    <input type="hidden" id="addSectionId" name="addSectionId" value="<?php echo $CurrentSectionId ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="addColumn()">Add Column</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Column Modal -->
    <div class="modal fade" id="editColumn" tabindex="-1" aria-labelledby="editColumnLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editColumnLabel">Column Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="updateColumnName" name="updateColumnName" placeholder="Name of Staff">
                        <label for="updateColumnName">Column Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="color" class="form-control form-control-color w-100" id="updateColumnBG" name="updateColumnBG" placeholder="Name of Staff">
                        <label for="updateColumnBG">Background Color</label>
                    </div>
                    <input type="hidden" id="updateSectionId" name="updateSectionId" value="<?php echo $CurrentSectionId ?>">
                    <input type="hidden" id="updateColumnId" name="updateColumnId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" onclick="deleteColumn()">Delete</button>
                    <button type="button" class="btn btn-success" id="downloadButton">Download</button>
                    <button type="button" class="btn btn-primary" onclick="updateColumn()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <?php include "./offCanvas.php" ?>


<script src="./node_modules/dragula/dist/dragula.min.js"></script>
<script src="./node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- <script src="./JS/dragAndDrop.js"></script> -->
<script src="./JS/viewTaskModal.js"></script>
<script src="./JS/addTaskModal.js"></script>
<script src="./JS/displayDateAndTIme.js"></script>
<script>
// <----- For Tasks ----->
// Initialize dragula with options
var containers = [];

<?php foreach ($statusData as $status => $data) : ?>
    var container<?= $data['columnId'] ?> = document.getElementById('<?= strtolower(str_replace(' ', '-', $status)) ?>');
    containers.push(container<?= $data['columnId'] ?>);
<?php endforeach; ?>

var tasks = dragula(containers, {
    // Set the "moves" option to a function that specifies whether an element can be moved
    moves: function(el, container, handle) {
        // Check if the user is the owner of the task
        var ownerId = el.getAttribute('data-owner-id');
        return ownerId === '<?= $user_id ?>' || '<?= $userPosition ?>' != 4;
    }
});

// Handle drag events
// <----- For Tasks ----->
tasks.on('drag', function(el) {
    el.classList.add('is-moving');
});

// Handle drag events
// <----- For Tasks ----->
tasks.on('dragend', function(el) {
    el.classList.remove('is-moving');

    window.setTimeout(function() {
        el.classList.add('is-moved');
        window.setTimeout(function() {
            el.classList.remove('is-moved');
        }, 600);
    }, 100);

    // Get the task ID, new status, and new order
    var taskId = el.getAttribute('data-task-id');
    var newStatus = el.closest('.drag-inner-list').getAttribute('data-status');
    var newOrder = Array.from(el.parentNode.children).indexOf(el) + 1; // Calculate the new order

    // Get the current display orders of all tasks in the column
    var displayOrders = Array.from(el.parentNode.children).map(function(task) {
        return { task_id: task.getAttribute('data-task-id'), order: Array.from(task.parentNode.children).indexOf(task) + 1 };
    });

    // Only send the AJAX request if taskId, newStatus, and newOrder are not null
    if (taskId !== null && newStatus !== null && newOrder !== null) {
        // Send AJAX request to update task status and display_order
        $.ajax({
            url: 'update_task_status_and_order.php',
            method: 'POST',
            data: { task_id: taskId, new_status: newStatus, new_order: newOrder, display_orders: displayOrders },
            success: function(response) {
                // console.log(response);
            },
            error: function(error) {
                console.error('Error updating task status and order:', error);
            }
        });
    }
});

// Function to add staff data
function addColumn() {
    var addColumnName = $('#addColumnName').val();
    var addSectionId = $('#addSectionId').val();
    var addColumnBG = $('#addColumnBG').val();

    // Check if any required field is empty
    if (!addColumnName) {
        // Highlight the empty fields
        $('input').filter(function () {
            return $(this).val() === '';
        }).addClass('is-invalid');

        // Show an alert for the user to fill in all fields
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Please fill in all required fields.',
        });
        return; // Do not proceed with the AJAX request
    }

    // Perform the delete logic using AJAX or form submission
    $.ajax({
        url: 'addColumn.php',
        method: 'POST',
        data: {
            addColumnName: addColumnName, addSectionId: addSectionId, addColumnBG: addColumnBG
        },
        dataType: 'json',
        success: function (response) {
            // Handle the response
            console.log(response);

            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Add Column!',
                    text: 'The Column has been successfully added.',
                }).then((result) => {
                    // Reload the page or update the DataTable
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message,
                });
            }
        },
        error: function (error) {
            console.error('Error adding Column:', error);
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    var editColumnModal = new bootstrap.Modal(document.getElementById('editColumn'));

    // Attach a click event to all buttons with the class 'edit-column-btn'
    var editColumnButtons = document.querySelectorAll('.edit-column-btn');
    editColumnButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var columnName = button.getAttribute('data-column-name');
            var columnColor = button.getAttribute('data-column-color');
            var columnId = button.getAttribute('data-column-id');

            // Update modal input values
            document.getElementById('updateColumnName').value = columnName;
            document.getElementById('updateColumnBG').value = columnColor;
            document.getElementById('updateColumnId').value = columnId;

            // Show the modal
            editColumnModal.show();
        });
    });
});

$('#downloadButton').on('click', function(){
    var columnId = document.getElementById('updateColumnId').value;

    if (columnId.trim() !== '') {
        var downloadUrl = './downLoadTask.php?columnId=' + encodeURIComponent(columnId);
        
        // Create an invisible anchor element
        var link = document.createElement('a');
        link.href = downloadUrl;
        link.download = 'your_file_name.pdf'; // Set the desired file name
        link.style.display = 'none';

        // Append the anchor element to the body
        document.body.appendChild(link);

        // Trigger a click on the anchor element
        link.click();

        // Remove the anchor element from the body
        document.body.removeChild(link);
    } else {
        alert('Column ID is empty. Unable to download.');
    }
});

function updateColumn(){
    var updateColumnName = $('#updateColumnName').val();
    var updateColumnBG = $('#updateColumnBG').val();
    var updateColumnId = $('#updateColumnId').val();

    // Check if any required field is empty
    if (!updateColumnName) {
        // Highlight the empty fields
        $('input').filter(function () {
            return $(this).val() === '';
        }).addClass('is-invalid');

        // Show an alert for the user to fill in all fields
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Please fill in all required fields.',
        });
        return; // Do not proceed with the AJAX request
    }

    // Perform the delete logic using AJAX or form submission
    $.ajax({
        url: 'updateColumn.php',
        method: 'POST',
        data: {
            updateColumnName: updateColumnName, updateColumnBG: updateColumnBG, updateColumnId: updateColumnId
        },
        dataType: 'json',
        success: function (response) {
            // Handle the response
            console.log(response);

            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Update Column!',
                    text: 'The Column has been successfully updated.',
                }).then((result) => {
                    // Reload the page or update the DataTable
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message,
                });
            }
        },
        error: function (error) {
            console.error('Error updating Column:', error);
        }
    });
}

var pdfDownloadSuccessful = false;
function deleteColumn() {
    // Retrieve the column ID from the hidden input field
    var columnId = document.getElementById('updateColumnId').value;

    // Show a confirmation dialog using SweetAlert
    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // User clicked "Yes", initiate PDF download first
            downloadPDF(columnId);

            if(pdfDownloadSuccessful){
                // After the download is initiated, wait for 2 seconds (adjust as needed)
                setTimeout(function () {
                    // Perform AJAX request to delete the column
                    $.ajax({
                        url: 'deleteColumn.php', // Adjust the URL to your server-side script
                        method: 'POST',
                        data: { columnId: columnId, pdfDownloadSuccessful: pdfDownloadSuccessful },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Delete Column!',
                                    text: 'The Column has been successfully deleted.',
                                }).then((result) => {
                                    // Reload the page or update the DataTable
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message,
                                });
                            }
                        },
                        error: function(error) {
                            // Handle the error
                            console.error('Error deleting column:', error);
                        }
                    });
                }, 2000);
            }
        }
    });
}

function downloadPDF(columnId) {
    // Construct the download URL for the PDF
    var downloadUrl = './downLoadTask.php?columnId=' + encodeURIComponent(columnId);

    // Create an invisible anchor element
    var link = document.createElement('a');
    link.href = downloadUrl;
    link.download = 'your_file_name.pdf'; // Set the desired file name
    link.style.display = 'none';

    // Append the anchor element to the body
    document.body.appendChild(link);

    // Trigger a click on the anchor element
    link.click();

    pdfDownloadSuccessful = true;
    // Remove the anchor element from the body
    document.body.removeChild(link);
}


$(document).ready(function () {
    $('.nav-link').on('click', function (e) {
        e.preventDefault();

        // Get the section ID from the clicked tab
        var sectionId = $(this).data('section-id');

        // Make an AJAX request to update the session value
        $.ajax({
            type: 'POST',
            url: 'updateSession.php', // Specify the path to your server-side script
            data: { sectionId: sectionId },
            success: function (response) {
                location.reload();
                console.log(response);
            },
            error: function (xhr, status, error) {
                // Handle errors if any
                console.error(error);
            }
        });
    });
});
</script>
</body>
</html>