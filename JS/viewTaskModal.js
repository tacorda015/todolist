function mapStatusToLabel(status) {
    switch (status) {
        case 1:
            return "Todo";
        case 2:
            return "On-Going";
        case 3:
            return "Done";
        default:
            return "Unknown"; // or handle other cases as needed
    }
}

document.addEventListener('DOMContentLoaded', function () {

    let currentTaskId;

    document.body.addEventListener('click', function (event) {
        var clickedElement = event.target;
        // if (event.target.classList.contains('viewIcons')) {
        if (clickedElement.classList.contains('viewIcons') || clickedElement.closest('.viewIcons')) {
            var taskId = event.target.closest('.drag-item').getAttribute('data-task-id');

            currentTaskId = taskId;

            $.ajax({
                url: 'get_task_details.php',
                method: 'POST',
                data: { task_id: taskId },
                success: function (response) {
                    var statusLabel = mapStatusToLabel(response.task_status);

                    // Update modal content dynamically
                    $('#assignedTo').text(response.nameOfUser || 'N/A');
                    $('#taskName').text(response.task_name || 'N/A');
                    $('#taskDescription').text(response.task_description || 'N/A');
                    $('#taskRemarks').text(response.task_remark || 'N/A');
                    $('#statusLabel').text(statusLabel || 'N/A');
                    $('#dateStart').text(response.task_date_start || 'N/A');
                    $('#dateEnd').text(response.task_date_end || 'N/A');


                    var deleteButton = $('#taskModal .deleteIconInModal');
                    var editButton = $('#taskModal .editIconInModal');
                    var remarksButton = $('#taskModal .remarksIconInModal');

                    if(response.task_status === 3){
                        // remarksButton.show();
                        // deleteButton.hide();
                        // editButton.hide();
                    }else{
                        // remarksButton.hide();
                        // deleteButton.show();
                        // editButton.show();
                    }
                    
                    // Show the modal
                    $('#taskModal').modal('show');

                    // Handle the click event for the edit icon in the modal
                    $('#taskModal .editIconInModal').on('click', function () {
                        $('#taskModal').modal('hide');
                        // Implement your edit logic here using response object
                        // For example, you can open another modal for editing
                        // and pre-fill the form fields with response data
                        var editModal = $('#editTaskModal');
                        // Update form fields in the edit modal using response object
                        editModal.find('#updateuser_id').val(response.user_id);
                        editModal.find('#updatetask_name').val(response.task_name);
                        editModal.find('#updatetask_description').val(response.task_description);
                        editModal.find('#updatetask_date_start').val(response.task_date_start);
                        editModal.find('#updatetask_date_end').val(response.task_date_end);
                        editModal.find('#updatetask_id').val(response.task_id);
                        editModal.find('#updatetask_level').val(response.task_level);

                        // Show the edit modal
                        editModal.modal('show');
                    });

                    // Handle the click event for the edit icon in the modal
                    $('#taskModal .remarksIconInModal').on('click', function () {
                        $('#taskModal').modal('hide');

                        var remarksModal = $('#remarksModal');
                        // Update form fields in the edit modal using response object
                        remarksModal.find('#remarks').val(response.task_remark);

                        // Show the edit modal
                        remarksModal.modal('show');
                    });
                },
                error: function (error) {
                    console.error('Error fetching task details:', error);
                }
            });
        }
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

    // Handle the cick event for the remarks button in the modal
    $('#remarksModal .addRemarks').on('click', function(){
        var remarksValue = $('#remarks').val();
        console.log(currentTaskId);

        $.ajax({
            url: 'add_remarks.php',
            method: 'POST',
            data: {
                remarks: remarksValue,
                task_id: currentTaskId,
            },
            success: function(addRemarksResponse){
                if (addRemarksResponse.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Task Remarks Added!',
                        text: 'Your task remarks has been successfully added.',
                    }).then((deleteResult) => {
                        if (deleteResult.isConfirmed) {
                            // Reload the page or perform any other action
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while adding the task remarks.',
                    });
                }
            },
            error: function(AddRemarksError){
                console.error('Error Adding Remarks:', AddRemarksError);
            }
        });
    });
    
    // Handle the click event for the delete button in the modal
    $('#taskModal .deleteIconInModal').on('click', function () {
       
        // Show Conformation Dialog
        if (currentTaskId) {
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
                    // Perform the delete operation using AJAX
                    $.ajax({
                        url: 'delete_task.php', // Replace with your server-side script
                        method: 'POST',
                        data: {
                            task_id: currentTaskId
                        },
                        success: function (deleteResponse) {
                            if (deleteResponse.status === 'success') {
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
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'An error occurred while deleting the task.',
                                });
                            }
                        },
                        error: function (deleteError) {
                            console.error('Error deleting task:', deleteError);
                        }
                    });
                }
            });
        }
    });
});

