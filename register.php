<?php

require("inc/includes.php");

if(isset($_POST['reg_username'])) {
    $username = $_POST['reg_username'];
    $inviteCode = $_POST['reg_invitecode'];

    $q = $db->select("invitecodes", "*", array("code" => $inviteCode));
    if($db->error) {
        die(f_Error($db->errorMsg));
    }

    if(count($q)== 0) {
        die(f_Error("This code is not valid!"));
    }

    die(f_Success("You've been registered!"));
}

?>

<html>
    <head>
        <title>AdamUpload - Register</title>

        <!--- Required scripts/stylesheets --->
        <link href="inc/css/bootstrap.css" rel="stylesheet">
        <script src="inc/js/bootstrap.min.js" ></script>

        <style>
            /* Set placeholder text to white so it doesn't blend in with the bg because bootstrap is kinda goofy and doesn't have a css class to do it for you. */
            input[type="text"]::-webkit-input-placeholder {
                color: rgba(175, 175, 175, 255);
            }
        </style>
    </head>

    <body style='background-image: url("./inc/bg.jpg"); background-repeat: no-repeat; background-size: 100% 100%; height: auto;'>
        <div style="width:750px;position:fixed;top:50%;left:50%;-webkit-transform: translate(-50%, -50%);transform: translate(-50%, -50%)">
            <div class="card text-white card-outline bg-gray-dark">
                <div class="card-header text-center">
                    <h2>AdamUpload - Register</h2>
                </div>

                <div class="card-body">
                    <form action="register.php" method="POST">
                        <label for="reg_username">Enter your desired Username:</label>
                        <input type="text" class="form-control text-white bg-gray-dark" id="reg_username" placeholder="buttpooper9000">
                        <br>
                        <label for="reg_invitecode">Enter the invite code that was sent to you:</label>
                        <input type="text" class="form-control bg-gray-dark" id="reg_invitecode" placeholder="12345">
                        <br>
                        <center>
                            <button type="submit" class="btn btn-primary">Register</button>
                        </center>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>