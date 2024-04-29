<?php
session_start();

if (!isset($_SESSION['user_ids'])) {
    header("Location: index.php");
    exit();
}else{
    $user_id = $_SESSION['user_ids'];
}

include 'db/functions.php';
$database = new Database();
$connection = $database->connection();

// For Login User Info
$getUserQuery = "SELECT * FROM accounttable WHERE user_id = '$user_id'";
$getUserResult = $connection->query($getUserQuery)->fetch(PDO::FETCH_ASSOC);

$userPosition = $getUserResult['positionId'];

// Query for section table
$getSectionQuery = "SELECT * FROM sectiontable ORDER BY sectionName";
$getSectionResult = $connection->query($getSectionQuery);
$getSectionData = $getSectionResult->fetchAll();
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
                <h2 class="text-center mt-2">Section Management</h2>
            </div>
            <div class="col-md-4 d-flex gap-3 justify-content-end align-items-center">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="bi bi-building-add"></i> Add Section
                </button>

                <button class="btn btn-primary fs-5" type="button" data-bs-toggle="offcanvas" data-bs-target="#burgerMenu" aria-controls="burgerMenu"><i class="bi bi-list"></i></button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="border shadow rounded p-3 m-3">
                    <table id="sectionTable" class="table table-hover table-striped display nowrap w-100" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th hidden>Section ID</th>
                                <th>Section Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counter = 1; // Initialize the counter
                            foreach ($getSectionData as $section) {
                                $sectionId = $section['sectionId'];
                                $sectionName = $section['sectionName'];
                                ?>
                                <tr>
                                    <td><?= $counter++ ?></td> <!-- Increment and display the counter -->
                                    <td hidden><?= $sectionId ?></td>
                                    <td><?= $sectionName ?></td>
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
                            <input type="text" class="form-control" id="updateSectionName" name="updateSectionName" placeholder="Section Name">
                            <label for="updateSectionName">Section Name</label>
                        </div>

                        <input type="hidden" id="updateSectionId" name="updateSectionId" value="">
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
                    <p>Are you sure you want to delete the section: <strong id="deleteSectionName"></strong>?</p>
                    <input type="hidden" id="deleteSectionId" name="deleteSectionId" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="deleteSection()">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Section Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Section</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="updateSectionForm">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="addSectionName" name="addSectionName" placeholder="Section Name">
                    <label for="addSectionName">Section Name</label>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="addSection()"><i class="bi bi-plus-circle"></i> Add</button>
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
        $('#updateSectionId').val(data[1]); // Assuming Section ID is in the second column
        $('#updateSectionName').val(data[2]); // Assuming Section Name is in the third column

        // Show the update modal
        $('#updateSectionModal').modal('show');
    });

    // Handle the click event on "Delete" button
    $('#sectionTable tbody').on('click', 'button.deleteBtn', function () {
        // Get the clicked row data
        var data = table.row($(this).parents('tr')).data();

        // Populate the delete modal with the clicked row data
        $('#deleteSectionId').val(data[1]); // Assuming Section ID is in the second column
        $('#deleteSectionName').text(data[2]); // Assuming Section Name is in the third column

        // Show the delete modal
        $('#deleteSectionModal').modal('show');
    });
});

// Function to update section data
function updateSection() {
    // Fetch data from the update modal form
    var updateSectionId = $('#updateSectionId').val();
    var updateSectionName = $('#updateSectionName').val();

    // Perform the update logic using AJAX or form submission
    $.ajax({
        url: 'updateSection.php',
        method: 'POST',
        data: { updateSectionId: updateSectionId, updateSectionName: updateSectionName },
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
                    text: 'An error occurred while updating the section name.',
                });
            }
        },
        error: function (error) {
            console.error('Error updating section name:', error);
        }
    });
}

// Function to delete section data
function deleteSection() {
    var deleteSectionId = $('#deleteSectionId').val();

    // Perform the delete logic using AJAX or form submission
    $.ajax({
        url: 'deleteSection.php',
        method: 'POST',
        data: { deleteSectionId: deleteSectionId },
        dataType: 'json',
        success: function (response) {
            // Handle the response
            console.log(response);

            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Delete Section!',
                    text: 'The section has been successfully deleted.',
                }).then((result) => {
                    // Reload the page or update the DataTable
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while deleting the section.',
                });
            }
        },
        error: function (error) {
            console.error('Error deleting section:', error);
        }
    });
}

// Function to add section data
function addSection() {
    var addSectionName = $('#addSectionName').val();

    // Perform the delete logic using AJAX or form submission
    $.ajax({
        url: 'addSection.php',
        method: 'POST',
        data: { addSectionName: addSectionName },
        dataType: 'json',
        success: function (response) {
            // Handle the response
            console.log(response);

            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Add Section!',
                    text: 'The section has been successfully added.',
                }).then((result) => {
                    // Reload the page or update the DataTable
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while adding the section.',
                });
            }
        },
        error: function (error) {
            console.error('Error deleting section:', error);
        }
    });
}
</script>
</body>
</html>