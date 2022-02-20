<?php

$Cfg = [
    "Domain" => "https://files.adamscodebox.com",
    "UploadDir" => "", // Add a trailing slash, unless you want the upload dir to be the current directory.
    "Users" => [
        "apikey" => "username here" // will be removed soon in favor for an actual logon system.
    ],
    "WhitelistedExtensions" => ["jpg", "jpeg", "png", "gif"],
    "DB" => [
        "Host" => "127.0.0.1",
        "User" => "root",
        "Password" => "",
        "Database" => "adamupload"
    ]
];