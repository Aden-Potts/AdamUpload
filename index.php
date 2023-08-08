<?php

require("inc/includes.php");

?>

<html>
    <head>
        <title>AdamUpload</title>

        <!--- Required scripts/stylesheets --->
        <link href="inc/css/bootstrap.css" rel="stylesheet">
        <script src="inc/js/bootstrap.min.js" ></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
    </head>

    <body style='background-image: url("./inc/bg.jpg"); background-repeat: no-repeat; background-size: 100%; height: auto;'>
        <div style="width:750px;position:fixed;top:50%;left:50%;-webkit-transform: translate(-50%, -50%);transform: translate(-50%, -50%)">
            <div class="card text-white card-outline bg-gray-dark">
                <div class="card-header text-center">
                    <h2>AdamUpload</h2>
                </div>

                <div class="card-body">
                    <div id="alertSection"></div><br>
                    <p>
                        Well, hello there. You may have seen someone post an image from this website, https://files.adamscodebox.com/<br>
                        You may be wondering what exactly this is. Well, this is a small PHP-based backend for a ShareX custom uploader.<br>
                        You get sent an invite code, and you can <a href="register.php">register</a>. The website then provides a link for you to download your ShareX config.
                        Simply double click/open the .sxcu file, and it'll auto import into ShareX.
                    </p>
                    <p>
                        Currently, only Image upload is supported. I plan on adding Text and file upload in the future.
                        <a href="https://github.com/Aden-Potts/AdamUpload">This project is open source under MIT license</a>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>