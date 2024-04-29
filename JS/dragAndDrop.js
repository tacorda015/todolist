// Initialize Dragula
// <----- For Tasks ----->
var tasks = dragula([
    document.getElementById('to-do'),
    document.getElementById('on-going'),
    document.getElementById('done'),
]);

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

    // Check if the user is the owner of the task
    var ownerId = el.getAttribute('data-owner-id');
    if (ownerId !== '<?= $user_id ?>') {
        // Prevent further processing if the user is not the owner
        return;
    }

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
                console.log(response);
            },
            error: function(error) {
                console.error('Error updating task status and order:', error);
            }
        });
    }
});