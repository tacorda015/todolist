// For Random Color for each User
$(document).ready(function() {
    $('#addUser').on('shown.bs.modal', function () {
        // Randomly choose a background color
        var colors = ["#f0f8ff", "#ffbf00", "#a4c639", "#00ffff", "#89cff0", "#ffe135", "#ace5ee", "#bf94e4", "#ed872d", "#8c92ac"];
        var randomColor = colors[Math.floor(Math.random() * colors.length)];

        // Set the background color and update the hidden input value
        $('#bg_color').val(randomColor);

        // Log the chosen background color inside the event handler
        console.log('Chosen Background Color:', $('#bg_color').val());
    });
});

$('input, select').on('input change', function () {
    $(this).removeClass('is-invalid');
}); 
// For Add User 
function saveChanges() {
    // Get values from input fields
    var nameOfUser = $('#nameOfUser').val();
    var userName = $('#userName').val();
    var userPassword = $('#userPassword').val();
    var bgColor = $('#bg_color').val();
    var sectionId = $('#sectionId').val();
    var positionId = $('#positionId').val();

    // Check if any required field is empty
    if (!nameOfUser || !userPassword || !bgColor || !sectionId || !positionId || !userName) {
        // Highlight the empty fields
        // $('input, select').filter(function () {
        //     return $(this).val() === '';
        // }).addClass('is-invalid');
        $('input, select').on('input change', function () {
            // Exclude DataTables search input
            if (!$(this).hasClass('dataTables_filter')) {
                $(this).removeClass('is-invalid');
            }
        });
        
        
        

        // Show an alert for the user to fill in all fields
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Please fill in all required fields.',
        });
        return; // Do not proceed with the AJAX request
    }

    // If all fields are filled, proceed with the AJAX request
    $.ajax({
        url: 'save_user.php', // Replace with the actual server-side script URL
        method: 'POST',
        data: {
            nameOfUser: nameOfUser,
            userName: userName,
            userPassword: userPassword,
            bgColor: bgColor,
            sectionId: sectionId,
            positionId: positionId
        },
        success: function(response) {
            // Handle the success response
            console.log(response);

            // Parse the JSON response
            var jsonResponse = JSON.parse(response);

            // Check the status in the response
            if (jsonResponse.status === 'success') {
                // If successful, show a success alert
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: jsonResponse.message,
                }).then((result) => {
                    // Optionally, close the modal or perform other actions
                    location.reload();

                });
            } else {
                // If there is an error, show an error alert
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: jsonResponse.message,
                });
            }
        },
        error: function(error) {
            // Handle the error
            console.error('Error:', error);

            // Show an error alert
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while processing your request.',
            });
        }
    });
}
