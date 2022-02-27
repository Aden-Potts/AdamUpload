<?php

require("inc/includes.php");

$apikey = $_GET['key'] ?? null;

if($apikey != null) {
    $user = GetUserData($apikey);
    if(!$user)
        die(Response("", 401, "User doesn't exist."));

    $fname = $_GET['id'];

    $fdata = GetFileData($fname);
    if(!$fdata)
        die(Response("", 401, "This file doesn't exist."));

    $dir = getcwd()."/{$Cfg['UploadDir']}{$user['username']}";
    if((int)$fdata['creator_id'] == (int)$user['uid']) {
        foreach($Cfg['WhitelistedExtensions'] as $key => $val) {
            if(file_exists("$dir/$fname.$val")) {
                $res = unlink("$dir/$fname.$val");

                if($res != false) {
                    $db->query("DELETE FROM `files` WHERE `id` = ?", [$fname]);

                    die(Response("", 200, "File deleted."));
                }
            }
        }

        die(Response("", 500, "Whoops, this wasn't suppose to execute to this point...file deletion failed."));
    }

    die(Response("", 401, "Invalid permission."));
}