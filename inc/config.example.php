<?php

$Cfg = [
    "Domain" => "https://files.adamscodebox.com", // The domain where upload.php is located. ie https://files.adenp.dev/adamupload. No trailing slash.
    "UploadDir" => "", // Add a trailing slash, unless you want the upload dir to be the current directory.
    "WhitelistedExtensions" => ["jpg", "jpeg", "png", "gif", "zip"],
    "DB" => [
        "Host" => "127.0.0.1",
        "User" => "root",
        "Password" => "",
        "Database" => "adamupload"
    ]
];