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
?>
<!DOCTYPE html>
    <html>

    <head>
        <title>Task Calendar</title>

        <!-- Icon -->
        <link rel="icon" type="image/x-icon" href="./Image/LOGO.png">

        <!-- ======  Moment JS  ===== -->
        <script src="./JS/moment.min.js"></script>

        <!-- ======  JQuery  ===== -->
        <script src="./node_modules/jquery/dist/jquery.min.js"></script>

        <!-- ======  Full Calendar JS  ===== -->
        <link rel="stylesheet" href="./CSS/fullcalendar.css">
        <script src="./JS//fullcalendar.min.js"></script>

        <!-- ======  Sweet Alert  ===== -->
        <link rel="stylesheet" href="./node_modules/sweetalert2/dist/sweetalert2.min.css">
        <script src="./node_modules/sweetalert2/dist/sweetalert2.min.js"></script>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="./node_modules/bootstrap-icons/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css">

        <script>
            var userPosition = <?php echo json_encode($userPosition); ?>;
            
            $(document).ready(function () {
                var calendar = $('#calendar').fullCalendar({
                    // header: {
                    //     left: 'prev,next today',
                    //     center: 'title',
                    //     right: 'month,agendaWeek,agendaDay'
                    // },
                    events: './Calendar/load.php',
                    selectable: true,
                    selectHelper: true,
                    timezone: 'Asia/Manila',
                    eventRender: function (event, element) {
                        if (event.bg_color) {
                            element.css('background-color', event.bg_color);
                        }
                    },
                    dayClick: function (date, jsEvent, view) {
                        // Open the Bootstrap modal and set the selected date details
                        var formattedDate = date.format('YYYY-MM-DD');
                        $('#selectedDate').html('<h5 class="text-center">' + formattedDate + '</h5>');

                        // Fetch events for the selected date
                        var start = view.intervalStart.format('YYYY-MM-DD');
                        var end = view.intervalEnd.format('YYYY-MM-DD');

                        $.ajax({
                            url: './Calendar/load.php',
                            type: 'GET',
                            data: { start: start, end: end },
                            success: function (events) {
                                events = JSON.parse(events); // Parse the JSON string
                                console.log(events);
                                displayEventsInModal(events, formattedDate);
                            },
                            error: function () {
                                console.log('Error fetching events for the selected date');
                            }
                        });

                        $('#dateDetailsModal').modal('show');
                    },
                });

                function displayEventsInModal(events, selectedDate) {
                    var filteredEvents = events.filter(function (event) {
                        // Convert event start and end dates to YYYY-MM-DD format for accurate comparison
                        var eventStartDate = moment(event.start).format('YYYY-MM-DD');
                        var eventEndDate = moment(event.end).format('YYYY-MM-DD');

                        // Check if the selected date is within the event range
                        return selectedDate >= eventStartDate && selectedDate <= eventEndDate;
                    });

                    if (filteredEvents.length === 0) {
                        // If there are no events for the selected date, display a message
                        $('#selectedDateDetails').html('<p class="fs-5 fw-bold text-center">No tasks for this day</p>');
                    } else {
                        // If there are events, display them in a list
                        var eventList = '<ul class="p-0">';
                        // filteredEvents.forEach(function (event) {
                        //     eventList += '<li class="m-2 row border rounded p-2"><div class="col-md-8 p-0">' + event.title + ' - ' + event.description +
                        //         ' </div><div class="col-md-4 p-0"><button class="edit-event btn btn-primary" data-id="' + event.id + '">Edit</button>' +
                        //         ' <button class="delete-event btn btn-danger" data-id="' + event.id + '">Delete</button></div></li>';
                        // });
                        filteredEvents.forEach(function (event) {
                            eventList += '<li class="m-2 row border rounded p-2"><div class="col-md-8 p-0">' + event.title + ' - ' + event.description +
                                ' </div><div class="col-md-4 p-0">';

                            // Check user's position and include buttons accordingly
                            if (userPosition == 4) {
                                // User's position is 4, hide both "Edit" and "Delete" buttons
                                eventList += '</div></li>';
                            } else {
                                // User's position is not 4, include "Edit" and "Delete" buttons
                                eventList += '<button class="edit-event btn btn-primary" data-id="' + event.id + '">Edit</button>' +
                                    ' <button class="delete-event btn btn-danger" data-id="' + event.id + '">Delete</button></div></li>';
                            }
                        });
                        eventList += '</ul>';
                        $('#selectedDateDetails').html(eventList);

                        $('.edit-event').click(function () {
                            var eventId = $(this).data('id');
                            var selectedEvent = filteredEvents.find(function (event) {
                                return event.id === eventId;
                            });

                            // Close the date details modal
                            $('#dateDetailsModal').modal('hide');

                            // Fill the edit modal with the selected event details
                            // Assuming your edit modal has id "editTaskModal"
                            $('#updatetask_id').val(selectedEvent.id);
                            $('#updateuser_id').val(selectedEvent.user);
                            $('#updatetask_level').val(selectedEvent.level);
                            $('#updatetask_name').val(selectedEvent.title);
                            $('#updatetask_description').val(selectedEvent.description);
                            $('#updatetask_date_start').val(selectedEvent.start); // Assuming start is a valid datetime string
                            $('#updatetask_date_end').val(selectedEvent.end); // Assuming end is a valid datetime string

                            // Show the edit modal
                            $('#editTaskModal').modal('show');
                        });

                        $('#updateTaskBtn').on('click', function () {
                            var userId = $('#editTaskModal #updateuser_id').val();
                            var taskName = $('#editTaskModal #updatetask_name').val();
                            var taskDescription = $('#editTaskModal #updatetask_description').val();
                            var taskDateStart = $('#editTaskModal #updatetask_date_start').val();
                            var taskDateEnd = $('#editTaskModal #updatetask_date_end').val();
                            var updatetask_id = $('#editTaskModal #updatetask_id').val();
                            var updatetask_level = $('#editTaskModal #updatetask_level').val();
                        
                            $.ajax({
                                url: 'update_task.php',
                                method: 'POST',
                                data: {
                                    user_id: userId,
                                    task_id: updatetask_id,
                                    task_level: updatetask_level,
                                    task_name: taskName,
                                    task_description: taskDescription,
                                    task_date_start: taskDateStart,
                                    task_date_end: taskDateEnd
                                },
                                success: function (response) {
                                    if (response.status === 'success') {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: response.message,
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                // Reload the page
                                                location.reload();
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: response.message,
                                        });
                                    }
                                },
                            });
                        });

                         // Event listener for when the modal is shown
                        $('#editTaskModal').on('show.bs.modal', function (e) {
                            // Get the current date in the user's local time zone
                            var currentDate = new Date();
                            var offset = currentDate.getTimezoneOffset();
                            currentDate.setMinutes(currentDate.getMinutes() - offset);

                            // Format the current date
                            var formattedDate = currentDate.toISOString().slice(0, 16);

                            // Set the min attribute for date inputs to the current date
                            $('#updatetask_date_start, #updatetask_date_end').attr('min', formattedDate);
                        });

                        // Event listener for when the start date changes
                        $('#updatetask_date_start').on('change', function () {
                            var startDate = $('#updatetask_date_start').val();

                            // Set the end date to be the same as the start date
                            $('#updatetask_date_end').attr('min', startDate);
                        });


                        $('.delete-event').click(function () {
                            var eventId = $(this).data('id');
                            var selectedEvent = filteredEvents.find(function (event) {
                                return event.id === eventId;
                            });

                            // Show a confirmation dialog using SweetAlert2
                            Swal.fire({
                                title: 'Are you sure?',
                                text: 'You are about to delete this event. This action cannot be undone.',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#3085d6',
                                confirmButtonText: 'Yes, delete it!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // If the user clicks "Yes, delete it!", proceed with the deletion
                                    deleteEvent(eventId);
                                }
                            });
                        });
                    }
                }


                function deleteEvent(eventId) {
                    // Perform an AJAX request to delete the task on the server
                    $.ajax({
                        type: 'POST',
                        url: 'delete_task.php',
                        data: { task_id: eventId },
                        success: function (response) {
                            if (response.status === 'success') {
                                console.log('Event deleted successfully:', eventId);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Task Deleted!',
                                    text: 'Your task has been successfully deleted.',
                                }).then((deleteResult) => {
                                    if (deleteResult.isConfirmed) {
                                        // Reload the page or perform any other action
                                        location.reload();
                                    }
                                });
                            } else {
                                // Handle the error case, show an alert, log the error, etc.
                                console.error('Error deleting event:', response.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            // Handle AJAX error
                            console.error('AJAX error:', error);
                        }
                    });
                }

                function updateEvent(event) {
                    var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                    var title = event.title;
                    var id = event.id;
                    $.ajax({
                        url: "update.php",
                        type: "POST",
                        data: { title: title, start: start, end: end, id: id },
                        success: function () {
                            calendar.fullCalendar('refetchEvents');
                            alert('Event Update');
                        }
                    });
                }
            });
        </script>
    </head>

    <body>
        <div class="container-fluid">
            <div class="row">
            <div class="col-12 position-relative d-flex justify-content-between align-items-center">
                <a href="./home.php" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Back</a>
                <h1 class="text-center my-4">Task Calendar</h1>
                <button class="btn btn-primary fs-5" type="button" data-bs-toggle="offcanvas" data-bs-target="#burgerMenu" aria-controls="burgerMenu">
                        <i class="bi bi-list"></i>
                </button>
            </div>
            </div>
            <div class="container">
                <div id="calendar"></div>
            </div>

            <!-- For Date Show Details -->
            <div class="modal fade" id="dateDetailsModal" tabindex="-1" role="dialog" aria-labelledby="dateDetailsModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="dateDetailsModalLabel">Date Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Add your content here for date details -->
                            <p id="selectedDate"></p>
                            <p id="selectedDateDetails"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- For Edit Task Details -->
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
                                <input type="text" class="form-control" id="updatetask_name" name="updatetask_name" placeholder="name@example.com">
                                <label for="updatetask_name">Task Title</label>
                            </div>
                            <div class="form-floating mb-3">
                                <textarea class="form-control" placeholder="Leave a task description here" id="updatetask_description" name="updatetask_description" style="height: 100px"></textarea>
                                <label for="updatetask_description">Task Description</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="datetime-local" class="form-control" id="updatetask_date_start" placeholder="Task Start">
                                <label for="updatetask_date_start">Task Start</label>
                            </div>
                            <div class="form-floating">
                                <input type="datetime-local" class="form-control" id="updatetask_date_end" placeholder="Task End">
                                <label for="updatetask_date_end">Task End</label>
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
        </div>
        <!-- OFF Canvas -->
        <?php include "./offCanvas.php" ?>
        <script src="./node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    </body>

    </html>
