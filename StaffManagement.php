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

$userUser_id = $getUserResult['user_id'];
$userPosition = $getUserResult['positionId'];
$userSection = $getUserResult['sectionId'];

// Query for section table
// $getStaffQuery = "SELECT * FROM accounttable LEFT JOIN positiontable ON accounttable.positionId = positiontable.positionId WHERE accounttable.sectionId = '$CurrentSectionId' AND user_id != '$userUser_id'";
$getStaffQuery = "SELECT * FROM accounttable LEFT JOIN positiontable ON accounttable.positionId = positiontable.positionId WHERE EXISTS (SELECT 1 FROM sectiontable WHERE FIND_IN_SET(sectiontable.sectionId, accounttable.sectionId) AND FIND_IN_SET(sectiontable.sectionId, '$CurrentSectionId')) AND user_id != '$userUser_id'";

if($userPosition != 1){
    $getStaffQuery .= " AND accounttable.positionId != 1";
}

$getStaffQuery .= " ORDER BY accounttable.positionId";
$getStaffResult = $connection->query($getStaffQuery);
$getStaffData = $getStaffResult->fetchAll();
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

    <!-- DataTables  -->
    <link rel="stylesheet" href="./node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css" />
    <script src="./node_modules/datatables.net-bs5/js/dataTables.bootstrap5.min.js" defer></script>
    <script src="./node_modules/datatables.net/js/jquery.dataTables.min.js"></script>

</head>
<body class="bg-body-tertiary">
    <div class="container-fluid p-2">
        <div class="row">
            <div class="col-md-4 d-flex justify-content-center align-items-center">
                <h6 class="m-0"><span id="realTimeDate"></span></h6>
            </div>
            <div class="col-md-4">
                <h2 class="text-center mt-2">Staff Management</h2>
            </div>
            <div class="col-md-4 d-flex gap-3 justify-content-end align-items-center">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaff">
                    <i class="bi bi-person-add"></i> Add Staff
                </button>

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
        <div class="row">
            <div class="col-12">
                <div class="border shadow rounded p-3 m-3 mt-0">
                    <table id="sectionTable" class="table table-hover table-striped display nowrap w-100" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th hidden>User ID</th>
                                <th>Name Of User</th>
                                <th>Position</th>
                                <th hidden>Username</th>
                                <th hidden>Password</th>
                                <th hidden>Position</th>
                                <th hidden>Section</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counter = 1; // Initialize the counter
                            foreach ($getStaffData as $staff) {
                                $getUser_id = $staff['user_id'];
                                $getnameOfUser = $staff['nameOfUser'];
                                $getpositionName = $staff['positionName'];
                                $getuserName = $staff['userName'];
                                $getuserPassword = base64_decode($staff['userPassword']);
                                $getpositionId = $staff['positionId'];
                                $getsectionId = $staff['sectionId'];
                                ?>
                                <tr>
                                    <td><?= $counter++ ?></td> <!-- Increment and display the counter -->
                                    <td hidden><?= $getUser_id ?></td>
                                    <td><?= $getnameOfUser ?></td>
                                    <td><?= $getpositionName ?></td>
                                    <td hidden><?= $getuserName ?></td>
                                    <td hidden><?= $getuserPassword ?></td>
                                    <td hidden><?= $getpositionId ?></td>
                                    <td hidden><?= $getsectionId ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-primary updateBtn">Update</button>
                                            <button class="btn btn-danger deleteBtn">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Update Modal -->
    <div class="modal fade" id="updateSectionModal" tabindex="-1" aria-labelledby="updateSectionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateSectionModalLabel">Update Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateSectionForm">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="updateNameOfUser" name="updateNameOfUser" placeholder="Section Name">
                            <label for="updateNameOfUser">Name of User</label>
                        </div>

                        <div class="form-floating mb-3">
                            <select name="updatePositionId" id="updatePositionId" class="form-select" <?= ($getUserResult['positionId'] == 1) ? '' : 'hidden' ?>>
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
                            <label for="updatePositionId" <?= ($getUserResult['positionId'] == 1) ? '' : 'hidden' ?>>Staff Position</label>
                        </div>
                        <div class="form-floating mb-3" <?= ($getUserResult['positionId'] == 1) ? '' : 'hidden' ?>>
                            <span for="updatesectionId">Staff Section</span>
                            <div class="form-check rounded border" id="divsecmainContainer" style="max-height: 200px; padding: 10px 40px; overflow: auto;">
                               <?php
                                $getSectionQuery = "SELECT * FROM sectiontable ORDER BY sectionName";
                                $getSectionResult = $connection->query($getSectionQuery);
                                $getSectionData = $getSectionResult->fetchAll();

                                foreach ($getSectionData as $section) {
                                    $sectionId = $section['sectionId'];
                                    $sectionName = $section['sectionName'];
                                    ?>
                                    <input class="form-check-input" name="updatesectionId[]" id="updatesectionId<?php echo $sectionId ?> " type="checkbox" value="<?php echo $sectionId ?>">
                                    <label class="form-check-label" for="updatesectionId<?php echo $sectionId ?>"><?php echo $sectionName ?></label><br>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="updateUserName" name="updateUserName" placeholder="Section Name">
                            <label for="updateUserName">Username</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="updateUserPassword" name="updateUserPassword" placeholder="Section Name">
                            <label for="updateUserPassword">Password</label>
                        </div>

                        <input type="hidden" id="updateAccountId" name="updateAccountId" value="">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateSection()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteSectionModal" tabindex="-1" aria-labelledby="deleteSectionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSectionModalLabel">Delete Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the user: <strong id="deleteNameOfUser"></strong>?</p>
                    <input type="hidden" id="deleteAccountId" name="deleteAccountId" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="deleteSection()">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Staff Modal -->
    <div class="modal fade" id="addStaff" tabindex="-1" aria-labelledby="addStaffLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addStaffLabel">Add Staff</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="addnameOfUser" name="addnameOfUser" placeholder="Name of Staff">
                        <label for="addnameOfUser">Name of Staff</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="adduserName" name="adduserName" placeholder="Username">
                        <label for="adduserName">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="adduserPassword" name="adduserPassword" placeholder="Password of Staff">
                        <label for="adduserPassword">Password of Staff</label> 
                    </div>

                    <input type="hidden" id="addbg_color" name="addbg_color" value="">

                    <?php if($getUserResult['positionId'] == 1) : ?>
                        <div class="form-floating mb-3">
                            <select name="addpositionId" id="addpositionId" class="form-select">
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
                            <label for="addpositionId">Staff Position</label>
                        </div>
                        <!-- <div class="form-floating mb-3">
                            <select name="addsectionId" id="addsectionId" class="form-select">
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
                            <label for="addsectionId">Staff Section</label>
                        </div> -->
                        <div class="form-floating mb-3">
                            <span for="addsectionId">Staff Section</span>
                            <div class="form-check rounded border" id="divsecmainContainer" style="max-height: 200px; padding: 10px 40px; overflow: auto;">
                               <?php
                                $getSectionQuery = "SELECT * FROM sectiontable ORDER BY sectionName";
                                $getSectionResult = $connection->query($getSectionQuery);
                                $getSectionData = $getSectionResult->fetchAll();

                                foreach ($getSectionData as $section) {
                                    $sectionId = $section['sectionId'];
                                    $sectionName = $section['sectionName'];
                                    ?>
                                    <input class="form-check-input" name="addsectionId[]" id="addsectionId<?php echo $sectionId ?> " type="checkbox" value="<?php echo $sectionId ?>">
                                    <label class="form-check-label" for="addsectionId<?php echo $sectionId ?>"><?php echo $sectionName ?></label><br>
                                <?php } ?>
                            </div>
                        </div>
                        <?php endif; ?>

                    <?php if($getUserResult['positionId'] == 2 || $getUserResult['positionId'] == 3): ?>
                        <input type="hidden" id="addsectionId" name="addsectionId" value="<?php echo $getUserResult['sectionId'] ?>">
                        <input type="hidden" id="addpositionId" name="addpositionId" value="4">
                    <?php endif; ?>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="addStaff()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- OFF Canvas -->
        <?php include "./offCanvas.php" ?>

<script src="./node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="./JS/displayDateAndTIme.js"></script>
<script>
$(document).ready(function(){
    // Initialize DataTable
    var table = $('#sectionTable').DataTable();

    // Handle the click event on "Update" button
    $('#sectionTable tbody').on('click', 'button.updateBtn', function () {
        // Get the clicked row data
        var data = table.row($(this).parents('tr')).data();

        // Populate the update modal with the clicked row data
        $('#updateAccountId').val(data[1]);
        $('#updateNameOfUser').val(data[2]);
        $('#updateUserName').val(data[4]);
        $('#updateUserPassword').val(data[5]);
        // Get the positionId from the database
        var positionIdFromDatabase = data[6];
        var sectionIdFromDatabase = data[7];
        console.log(sectionIdFromDatabase);
        // $('#hiddenSectionId').val(data[7]);

        // Select the option in the dropdown based on the database value
        $('#updatePositionId option').each(function() {
            if ($(this).val() == positionIdFromDatabase) {
                $(this).prop('selected', true);
            }
        });
        // Check the checkboxes based on the database values
        var sectionsArray = sectionIdFromDatabase.split(',');
        $('input[name="updatesectionId[]"]').each(function () {
            var checkboxValue = $(this).val();
            if (sectionsArray.includes(checkboxValue)) {
                $(this).prop('checked', true);
            } else {
                $(this).prop('checked', false); // Ensure other checkboxes are unchecked
            }
        });

        // Show the update modal
        $('#updateSectionModal').modal('show');
    });

    // Handle the click event on "Delete" button
    $('#sectionTable tbody').on('click', 'button.deleteBtn', function () {
        // Get the clicked row data
        var data = table.row($(this).parents('tr')).data();

        // Populate the delete modal with the clicked row data
        $('#deleteAccountId').val(data[1]);
        $('#deleteNameOfUser').text(data[2]);

        // Show the delete modal
        $('#deleteSectionModal').modal('show');
    });
});

// Function to update section data
function updateSection() {
    // Fetch data from the update modal form
    var updateNameOfUser = $('#updateNameOfUser').val();
    var updatePositionId = $('#updatePositionId').val();
    var updateUserName = $('#updateUserName').val();
    var updateUserPassword = $('#updateUserPassword').val();
    var updateAccountId = $('#updateAccountId').val();
    var updatesectionId = $('input[name="updatesectionId[]"]:checked').map(function () {
        return this.value;
    }).get();
    console.log(updatesectionId);

    // Perform the update logic using AJAX or form submission
    $.ajax({
        url: 'updateStaff.php',
        method: 'POST',
        data: { updateNameOfUser: updateNameOfUser, updatePositionId: updatePositionId, updateUserName: updateUserName, updateUserPassword: updateUserPassword, updateAccountId: updateAccountId, updatesectionId: updatesectionId },
        dataType: 'json',
        success: function (response) {
            // Handle the response
            console.log(response);

            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Update Section Name!',
                    text: 'Your update has been successfully saved.',
                }).then((result) => {
                    // Reload the page
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while updating the staff.',
                });
            }
        },
        error: function (error) {
            console.error('Error updating staff:', error);
        }
    });
}

// Function to delete section data
function deleteSection() {
    var deleteAccountId = $('#deleteAccountId').val();

    // Perform the delete logic using AJAX or form submission
    $.ajax({
        url: 'deleteStaff.php',
        method: 'POST',
        data: { deleteAccountId: deleteAccountId },
        dataType: 'json',
        success: function (response) {
            // Handle the response
            console.log(response);

            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Delete Staff!',
                    text: 'The staff has been successfully deleted.',
                }).then((result) => {
                    // Reload the page or update the DataTable
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while deleting the staff.',
                });
            }
        },
        error: function (error) {
            console.error('Error deleting section:', error);
        }
    });
}

// Function to add staff data
function addStaff() {
    var nameOfUser = $('#addnameOfUser').val();
    var userName = $('#adduserName').val();
    var userPassword = $('#adduserPassword').val();
    var bgColor = $('#addbg_color').val();
    var positionId = $('#addpositionId').val();
    var sectionId = $('input[name="addsectionId[]"]:checked').map(function () {
        return this.value;
    }).get();
    console.log(sectionId);

    // Check if any required field is empty
    if (!nameOfUser || !userName || !userPassword || !bgColor || !sectionId || !positionId) {
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
        url: 'addStaff.php',
        method: 'POST',
        data: {
            nameOfUser: nameOfUser,
            userName: userName,
            userPassword: userPassword,
            bgColor: bgColor,
            sectionId: sectionId,
            positionId: positionId
        },
        dataType: 'json',
        success: function (response) {
            // Handle the response
            console.log(response);

            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Add Staff!',
                    text: 'The staff has been successfully added.',
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
            console.error('Error adding staff:', error);
        }
    });
}

$(document).ready(function() {
    $('#addStaff').on('shown.bs.modal', function () {
        // Randomly choose a background color
        var colors = ["#f0f8ff", "#ffbf00", "#a4c639", "#00ffff", "#89cff0", "#ffe135", "#ace5ee", "#bf94e4", "#ed872d", "#8c92ac"];
        var randomColor = colors[Math.floor(Math.random() * colors.length)];

        // Set the background color and update the hidden input value
        $('#addbg_color').val(randomColor);

        // Log the chosen background color inside the event handler
        console.log('Chosen Background Color:', $('#addbg_color').val());
    });

    // NavBar
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