<?php

error_reporting(0);
umask(000);

require("inc/includes.php");

$headers = getallheaders();

if(!isset($headers['X-Api-Key'])) {
   die(Response("", 403, "Unauthorized: No key supplied."));
}

$q = $db->query("SELECT * FROM `users` WHERE `apikey` = ?", [$headers['X-Api-Key']]);
if($db->error) 
        die(Response("", 500, "Backend Error"));

if(count($q)== 0)
    die(Response("", 403, "Unauthorized: This key is not valid."));

$user = $q[0]['username']; //$Cfg['Users'][$headers['X-Api-Key']];
if(!is_dir(getcwd()."/{$Cfg['UploadDir']}$user"))
    mkdir(getcwd()."/{$Cfg['UploadDir']}$user", 0777, true);


$File = $_FILES['File'];

$targetDir = getcwd()."/{$Cfg['UploadDir']}$user/";
$filetype = strtolower(pathinfo($File['name'], PATHINFO_EXTENSION));
$fName = bin2hex(random_bytes(10));

if(file_exists($targetDir.$fName.$filetype))
    $fName = bin2hex(random_bytes(10)); // i need a better way to handle this...

$targetFile = $targetDir.basename("$fName.$filetype");

$response = [
    "url" => "{$Cfg['Domain']}/{$Cfg['UploadDir']}$user/$fName.$filetype",
    "error" => "",
];

if(getimagesize($_FILES['File']['tmp_name']) == false) {
    die(Response("", 403, "This file type isn't accepted."));
} else if(!in_array($filetype, $Cfg['WhitelistedExtensions'])) {
    die(Response("", 403, "This file type isn't accepted."));
} else {
    move_uploaded_file($_FILES['File']['tmp_name'], $targetFile);
}


die(json_encode($response));