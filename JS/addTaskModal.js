

$(document).ready(function () {
    $('.plus-icon').on('click', function () {
        $('#addTaskModal').modal('show');
    });
    
    // Event listener for the "Save Task" button
    $('#saveTaskBtn').on('click', function () {
        // Gather task details from modal inputs
        var taskUserId = $('#user_id').val();
        var taskName = $('#task_name').val();
        var taskDescription = $('#task_description').val();
        var taskDateStart = $('#task_date_start').val();
        var taskDateEnd = $('#task_date_end').val();
        var task_level = $('#task_level').val();
        var taskStatus = $('[name="task_status"]').val();

        // Simple validation to check if fields are filled
        if (!taskUserId || !taskName || !taskDateStart || !taskDateEnd || !taskStatus || !task_level) {
            Swal.fire({
                icon: 'error',
                title: 'Incomplete Form',
                text: 'Please fill in all required fields.',
            });
            return;
        }

        // Make AJAX request
        $.ajax({
            url: 'save_task.php', // Replace with your server-side script
            method: 'POST',
            data: {
                task_name: taskName,
                task_level: task_level,
                userId: taskUserId,
                task_description: taskDescription,
                task_date_start: taskDateStart,
                task_date_end: taskDateEnd,
                task_status: taskStatus
            },
            success: function (response) {
                // Handle the response
                console.log(response);

                // Check if the task was saved successfully
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Task Saved!',
                        text: 'Your task has been successfully saved.',
                    }).then((result) => {
                        // Reload the page
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while saving the task.',
                    });
                }
            },
            error: function (error) {
                console.error('Error saving task:', error);
            }
        });
    });

    // Event listener for when the modal is shown
    $('#addTaskModal').on('show.bs.modal', function (e) {
        // Get the current date in the user's local time zone
        var currentDate = new Date();
        var offset = currentDate.getTimezoneOffset();
        currentDate.setMinutes(currentDate.getMinutes() - offset);

        // Format the current date
        var formattedDate = currentDate.toISOString().slice(0, 16);

        // Set the min attribute for date inputs to the current date
        $('#task_date_start, #task_date_end').attr('min', formattedDate);

    });

    // Event listener for when the start date and time change
    $('#task_date_start').on('change', function () {
        var startDate = $('#task_date_start').val();

        // Set the end date to be the same as the start date
        $('#task_date_end').attr('min', startDate);
    });
});

    