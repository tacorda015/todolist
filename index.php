<?php
session_start();

if (isset($_SESSION['user_ids'])) {
    $user_id = $_SESSION['user_ids'];
    header("Location: home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>

    <!-- Icon -->
    <link rel="icon" type="image/x-icon" href="./Image/LOGO.png">
    
    <!-- ----------------------- Boostratp ----------------------- -->
    <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./node_modules/bootstrap-icons/font/bootstrap-icons.min.css">

    <!-- ----------------------- Customized CSS ----------------------- -->
    <link rel="stylesheet" href="./CSS/index.css">

    <!-- ----------------------- JQuery ----------------------- -->
    <script src="./node_modules/jquery/dist/jquery.min.js"></script>

    <!-- ----------------------- Sweet Alert 2 ----------------------- -->
    <link rel="stylesheet" href="./node_modules/sweetalert2/dist/sweetalert2.min.css">
    <script src="./node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>

</head>
<body>
<!-- ----------------------- MAIN CONTENT ----------------------- -->
<div class="container" style="height: 100vh; width: 100vw;">
    <div class="extensionBtn position-absolute bottom-0 end-0 m-5 d-flex flex-column gap-2">
        <!-- <button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#exampleModal">
        View Manual
        </button> -->
        <a href="http://localhost/request" class="btn btn-primary">Requesting System</a>
    </div>
    <div class="col-lg-10 col-md-11 col-12 mx-auto">
        <div class="row py-4">
            <h1 class="text-center">Task Board Monitoring</h1>
        </div>
        <div class="row m-0 p-0 border rounded shadow">
            <div class="col-md-6 border border-start-0 p-4">
                <div class="row d-flex justify-content-center">
                    <img src="./Image/LOGO.png" class="w-50" alt="LOGO">
                </div>
                <div class="row">
                    <form id="loginForm">
                        <div class="input-group mb-3">
                            <span class="input-group-text fs-3" id="basic-addon1"><i class="bi bi-person-circle"></i></span>
                            <input type="text" class="form-control fs-5" name="userName" placeholder="Username" aria-label="userName" aria-describedby="basic-addon1" autocomplete="off" autofocus>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text fs-3" id="basic-addon1"><i class="bi bi-person-fill-lock"></i></span>
                            <input type="password" class="form-control fs-5" name="password" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" autocomplete="off">
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-primary" id="loginBtn">LOG IN</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6 border border-end-0 p-4">
                <div class="row">
                    <img src="./Image/loginLogo.png" alt="Logo">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ----------------------- AJAX Script ----------------------- -->
<script>
    $('#loginForm input').keypress(function (e) {
        if (e.which === 13) {
            $('#loginBtn').click(); // Trigger the click event of the login button
        }
    });

    $(document).ready(function () {
        $('#loginBtn').on('click', function () {
            // Get values from input fields
            var userName = $('input[name="userName"]').val();
            var password = $('input[name="password"]').val();

            // Perform AJAX request
            $.ajax({
                url: 'login_process.php', // Replace with the actual server-side script URL
                method: 'POST',
                data: {
                    userName: userName,
                    password: password
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    console.log(data);
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            // Optionally, redirect or perform other actions after successful login
                            window.location.href = 'home.php';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
            });
        });
    });
</script>

<!-- ----------------------- Boostratp ----------------------- -->
<script src="./node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>