<?php
error_reporting(0);

umask(000);

require("inc/config.php");
$headers = getallheaders();

if(!isset($headers['X-Api-Key'])) {
    http_response_code(401);
    die(json_encode(["error" => "Unauthorized: No key supplied."]));
} else if(!isset($Cfg['Users'][$headers['X-Api-Key']])) {
    http_response_code(401);
    die(json_encode(["error" => "Unauthorized: Key isn't valid."]));
}

$user = $Cfg['Users'][$headers['X-Api-Key']];
if(!is_dir(getcwd()."/{$Cfg['UploadDir']}$user"))
    mkdir(getcwd()."/{$Cfg['UploadDir']}$user", 0777);


$targetDir = getcwd()."/{$Cfg['UploadDir']}$user/";
$targetFile = $targetDir.basename($_FILES['File']['name']);

$response = [
    "url" => "{$Cfg['Domain']}/{$Cfg['UploadDir']}$user/{$_FILES['File']['name']}",
    "error" => "",
];

if(getimagesize($_FILES['File']['tmp_name']) == false) {
    http_response_code(401);

    $reponse['error'] = "This is not an image.";
} else {
    move_uploaded_file($_FILES['File']['tmp_name'], $targetFile);
}


die(json_encode($response));