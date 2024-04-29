<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ======  Bootstrap  ===== -->
    <link rel="stylesheet" href="./node_modules/bootstrap-icons/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css">

    <!-- ======  Sweet Alert  ===== -->
    <link rel="stylesheet" href="./node_modules/sweetalert2/dist/sweetalert2.min.css">
    <script src="./node_modules/sweetalert2/dist/sweetalert2.min.js"></script>

    <!-- ======  JQuery  ===== -->
    <script src="./node_modules/jquery/dist/jquery.min.js"></script>
    <title>To-Do List</title>
    <style>
        /* Define styles for the custom class */
        .dragged {
            /* opacity: 0.7; */
            border: 2px dashed #000;
            background-color: #f0f0f0;
        }
        /* Add this to your existing styles */
.drop-zone {
    border: 2px dashed #007bff; /* Customize the border color as needed */
    background-color: #e0f2ff; /* Customize the background color as needed */
}

.task-placeholder {
            border: 2px dashed #000;
            background-color: #f0f0f0;
            height: 40px; /* Adjust the height as needed */
        }

    </style>
</head>
<body>
    <?php require 'modals/addITStaff.php'; ?> 
    <div class="container-fluid">       
        <div class="row">
            <h2 class="text-center p-2">To-do List</h2>
        </div>
        <div class="row mb-5">
            <div class="col-md-3 p-0 d-flex justify-content-center" style="height: 100vh;">
                <div id="it-staff-column" class="col-11 px-2 py-3 border rounded shadow" style="height: 100vh;">
                    <h3 class="text-center">IT STAFF LIST</h3>
                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Add IT Staff
                    </button>
                </div>
            </div>
            <div id="todo-column-container" class="col-md-3 p-0 d-flex justify-content-center" style="height: 100vh;" ondrop="drop(event, 1)" ondragover="allowDrop(event)">
                <div class="col-11 px-2 py-3 border rounded shadow" style="height: auto; width: 97%;">    
                <h3 class="text-center">TO-DO</h3>
                <div id="todo-column"></div>
                <!-- Content loaded dynamically through AJAX -->
                </div>
            </div>
            <div id="ongoing-column-container" class="col-md-3 p-0 d-flex justify-content-center" style="height: 100vh;" ondrop="drop(event, 2)" ondragover="allowDrop(event)">
                <div class="col-11 px-2 py-3 border rounded shadow" style="height: auto; width: 97%;">
                <h3 class="text-center">On-Going</h3>
                <div id="ongoing-column"></div>
                <!-- Content loaded dynamically through AJAX -->
                </div>
            </div>
            <div id="done-column-container" class="col-md-3 p-0 d-flex justify-content-center" style="height: 100vh;" ondrop="drop(event, 3)" ondragover="allowDrop(event)">
                <div class="col-11 px-2 py-3 border rounded shadow" style="height: auto; width: 97%;">
                <h3 class="text-center">Done</h3>
                <div id="done-column"></div>
                <!-- Content loaded dynamically through AJAX -->
                </div>
            </div>
        </div>
    </div>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script>
        var draggedTaskId;
    var draggedTaskDisplayOrder;
    var draggedTaskIndex;

    document.addEventListener('dragstart', function(event) {
        if (event.target.getAttribute('draggable')) {
            event.target.classList.add('dragged');
            draggedTaskId = event.target.dataset.taskId;
            draggedTaskDisplayOrder = event.target.dataset.displayOrder;
            draggedTaskIndex = Array.from(event.target.parentElement.children).indexOf(event.target);
        }
    });

    document.addEventListener('dragend', function(event) {
        if (event.target.getAttribute('draggable')) {
            event.target.classList.remove('dragged');
            draggedTaskId = null;
            draggedTaskDisplayOrder = null;
            draggedTaskIndex = null;
        }
    });

    function allowDrop(event) {
        event.preventDefault();

        if (event.type === 'dragover') {
            document.querySelectorAll('.col-11').forEach(function(column) {
                column.classList.remove('drop-zone');
            });

            if (event.target.classList.contains('col-11')) {
                event.target.classList.add('drop-zone');
            }

            // Add a class to the task where the dragged task is hovering
            var tasks = Array.from(event.target.parentElement.children);
            var targetIndex = Array.from(event.target.parentElement.children).indexOf(event.target);

            tasks.forEach(function(task, index) {
                if (index === targetIndex) {
                    task.classList.add('hovered-task');
                } else {
                    task.classList.remove('hovered-task');
                }
            });
        } else if (event.type === 'drop') {
            document.querySelectorAll('.col-11').forEach(function(column) {
                column.classList.remove('drop-zone');
            });

            // Remove the class from all tasks after dropping
            document.querySelectorAll('.col-11 .row').forEach(function(task) {
                task.classList.remove('hovered-task');
            });

            // Update the display order of the dragged task and rearrange tasks
            updateDisplayOrderAndRearrange(event.target);
        }
    }

    function updateDisplayOrderAndRearrange(target) {
        // Use AJAX to update the display_order in the database
        $.ajax({
            type: 'POST',
            url: 'update_task_status.php',
            data: { task_id: draggedTaskId, task_status: getStatusFromColumnId(target.parentElement.id), display_order: draggedTaskDisplayOrder },
            success: function(response) {
                console.log(response);

                // After updating the task status, refresh the content of each column
                refreshColumnContent('it-staff-column', 'get_it_staff.php');
                refreshColumnContent('todo-column', 'get_column_content.php?status=1');
                refreshColumnContent('ongoing-column', 'get_column_content.php?status=2');
                refreshColumnContent('done-column', 'get_column_content.php?status=3');
            },
            error: function(error) {
                console.error(error);
            }
        });

        // Rearrange tasks in the column
        var tasks = Array.from(target.parentElement.children);
        var movedDown = target.dataset.taskIndex > draggedTaskIndex;

        tasks.forEach(function(task, index) {
            if (movedDown && index >= draggedTaskIndex && index < target.dataset.taskIndex) {
                // Move tasks down to make space for the dragged task
                target.parentElement.insertBefore(task.cloneNode(true), tasks[draggedTaskIndex]);
            } else if (!movedDown && index > target.dataset.taskIndex && index <= draggedTaskIndex) {
                // Move tasks up to make space for the dragged task
                target.parentElement.insertBefore(task.cloneNode(true), tasks[draggedTaskIndex]);
            }
        });

        // Remove the task-placeholder
        target.parentElement.removeChild(target);
    }


        function drag(event) {
            if (event.target.getAttribute('draggable')) {
                event.target.classList.add('dragged');
                event.dataTransfer.setData('text', event.target.dataset.taskId);
                event.dataTransfer.setData('parent', event.target.parentElement.id);
                event.dataTransfer.setData('index', Array.from(event.target.parentElement.children).indexOf(event.target));
                event.dataTransfer.setData('displayOrder', event.target.dataset.displayOrder);
            }
        }

        
        function drop(event, status) {
            event.preventDefault();

            // Check if the dropped task is inside an h3 element (column title)
            if (event.target.tagName.toLowerCase() === 'h3') {
                // Prevent the drop action if the target is a column title
                return;
            }

            var taskId = event.dataTransfer.getData('text');
            var originalParentId = event.dataTransfer.getData('parent');
            var originalIndex = parseInt(event.dataTransfer.getData('index'), 10);
            var originalDisplayOrder = event.dataTransfer.getData('displayOrder');
            

            // Determine the status of the target column based on its ID
            var targetStatus = getStatusFromColumnId(event.target.parentElement.id);
            console.log(targetStatus);
            // Use AJAX to update the task status and display_order in the database
            $.ajax({
                type: 'POST',
                url: 'update_task_status.php',
                data: { task_id: taskId, task_status: targetStatus, display_order: originalDisplayOrder },
                success: function(response) {
                    console.log(response);

                    // After updating the task status, refresh the content of each column
                    refreshColumnContent('it-staff-column', 'get_it_staff.php');
                    refreshColumnContent('todo-column', 'get_column_content.php?status=1');
                    refreshColumnContent('ongoing-column', 'get_column_content.php?status=2');
                    refreshColumnContent('done-column', 'get_column_content.php?status=3');

                    // Insert the dragged element at the calculated position
                    var draggedElement = document.querySelector(`[data-task-id="${taskId}"]`);
                    var targetParent = event.target;
                    var targetIndex = Array.from(targetParent.children).indexOf(event.target);

                    // Remove the dragged element from its original position
                    document.getElementById(originalParentId).removeChild(draggedElement);

                    // Remove the drop-zone class after dropping the task
                    document.querySelectorAll('.col-11').forEach(function(column) {
                        column.classList.remove('drop-zone');
                    });

                },
                error: function(error) {
                    console.error(error);
                }
            });
        }


        // Helper function to get the status from the column ID
        function getStatusFromColumnId(columnId) {
            switch (columnId) {
                case 'todo-column-container':
                    return 1;
                case 'ongoing-column-container':
                    return 2;
                case 'done-column-container':
                    return 3;
                // Add cases for other columns if needed
                default:
                    return 0; // Default status (you can customize this)
            }
        }

        // Initial load of the columns
        $(document).ready(function() {
            refreshColumnContent('it-staff-column', 'get_it_staff.php');
            refreshColumnContent('todo-column', 'get_column_content.php?status=1');
            refreshColumnContent('ongoing-column', 'get_column_content.php?status=2');
            refreshColumnContent('done-column', 'get_column_content.php?status=3');
        });

        // Function to refresh the content of a specific column
        function refreshColumnContent(columnId, url) {
        // Use AJAX to fetch and update the content of the specified column
            $.ajax({
                type: "GET",
                url: url,
                success: function(response) {
                    // Update the content of the column with the new data
                    $("#" + columnId).html(response);
                    
                    // Update the display order based on the current position
                    updateDisplayOrder(columnId);
                },
                error: function(error) {
                    // Handle error
                    console.error(error);
                }
            });
        }

        function updateDisplayOrder(columnId) {
            // Get all tasks in the column and update display_order based on their position
            $("#" + columnId + " .row[draggable=true]").each(function(index) {
                var taskId = $(this).data("task-id");

                // Use AJAX to update the display_order in the database
                $.ajax({
                    type: "POST",
                    url: "update_display_order.php", // Create a new PHP file for updating display_order
                    data: { task_id: taskId, display_order: index },
                    success: function(response) {
                        // Handle success response
                        console.log(response);
                    },
                    error: function(error) {
                        // Handle error
                        console.error(error);
                    }
                });
            });
        }

    </script>
</body>
</html>
