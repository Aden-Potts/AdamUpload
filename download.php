<?php

require("inc/includes.php");

if(isset($_GET['f'])) {
    $f = $_GET['f'];
    $q = $db->query("SELECT * FROM `files` WHERE `filename` = ?", [$f]);
    if($db->error) {
        die("Backend error.");
    } else if(count($q)== 0) {
        die("This isn't a valid download action. Stop being a dick head.");
    }

   $dlcount = (int)$q[0]['download_count'] + 1;
   $db->query("UPDATE `files` SET `download_count` = ? WHERE `filename` = ?", [$dlcount, $f]);

    $path = getcwd()."/tmp/$f.sxcu";
    if(file_exists($path)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: 0");
        header('Content-Disposition: attachment; filename="'.basename($path).'"');
        header('Content-Length: ' . filesize($path));
        header('Pragma: public');
        
        flush(); // clear buffer
        readfile($path);

        exit;
    } else {
        die("File is deleted.");
    }
} else {
    die("<h1>Downloading ../../../etc/passwd <br>oops we alerted the nsa</h1>");
}