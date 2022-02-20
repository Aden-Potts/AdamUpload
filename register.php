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

    if((int)$q[0]['uses'] >= (int)$q[0]['max_uses'])
        die(f_Error("This code is expired!"));

    $uses = (int)$q[0]['uses'] + 1;
    $db->query("UPDATE `invitecodes` SET `uses` = ? WHERE `code` = ?", [$uses, $q[0]['code']]);
    if($db->error) 
        die(f_Error($db->errorMsg));

    $q = $db->query("SELECT * FROM `users` WHERE LOWER(`username`) = ?", [strtolower($username)]);
    if($db->error) 
        die(f_Error($db->errorMsg));

    if(count($q) > 0)
        die(f_Error("This username is already taken!"));

    $data = [
        "username" => strtolower($username),
        "apikey" => base64_encode(random_bytes(20))
    ];
 
    $db->insert("users", $data);
    if($db->error) 
        die(f_Error($db->errorMsg));

    $baseData = file_get_contents(getcwd()."/inc/base config.sxcu");
    $baseData = str_replace("{{CFG_API}}", $data['apikey'], $baseData);

    $fName = bin2hex(random_bytes(15));
    $fPath = getcwd()."/tmp/$fName.sxcu";

    $File = fopen($fPath, "w");

    fwrite($File, $baseData);
    fclose($File);

    $db->insert("files", ["filename" => $fName]);

    die(f_Success("You've been registered! Click <a href='download.php?f=$fName'>here</a> to download your config.", false));
}

?>

<html>
    <head>
        <title>AdamUpload - Register</title>

        <!--- Required scripts/stylesheets --->
        <link href="inc/css/bootstrap.css" rel="stylesheet">
        <script src="inc/js/bootstrap.min.js" ></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
        <script>
            //
            $(() => {
                $('#reg_submit').click(() => {
                    let username = $('#reg_username').val();
                    let invitecode = $('#reg_invitecode').val();

                    $.post('register.php', {reg_username: username, reg_invitecode: invitecode}, (data) => {
                        $('#alertSection').html(data);
                    });
                });
            });
        </script>

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
                    <div id="alertSection">

                    </div><br>
                    <label for="reg_username">Enter your desired Username:</label>
                    <input type="text" class="form-control text-white bg-gray-dark" id="reg_username" placeholder="buttpooper9000">
                    <br>
                    <label for="reg_invitecode">Enter the invite code that was sent to you:</label>
                    <input type="password" class="form-control text-white bg-gray-dark" id="reg_invitecode" placeholder="12345">
                    <br>
                    <center>
                        <button type="submit" id="reg_submit" class="btn btn-primary">Register</button>
                    </center>
                </div>
            </div>
        </div>
    </body>
</html>