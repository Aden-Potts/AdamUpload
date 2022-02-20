<?php

/**
 * A quick response for ShareX.
 * 
 * @param String $url -> The URL to the image; if theres an error just set this to "".
 * @param Int $code -> The response code. Defaults to 200.
 * @param String $error -> The error message. Defaults to an empty string.
 * 
 * @return String -> JSON encoded message, ready for ShareX to try and read it.
 */
function Response($url, $code=200, $error="") {

    $resArray = [
        "url" => $url,
        "error" => $error
    ];

    http_response_code($code);

    return json_encode($resArray);
}

/**
 * Returns a frontend error message.
 * 
 * @param String $e -> The error message.
 * 
 * @return String -> The HTML alert, escaped to prevent XSS.
 */
function f_Error($e) {
    return '<div class="alert alert-danger"><b>Error!</b> '.htmlspecialchars($e).'</div>';
}

/**
 * Returns a frontend success message. Literally the opposite of f_Error
 * 
 * @param String $e -> The message.
 * 
 * @return String -> The HTML alert, escaped to prevent XSS.
 */
function f_Success($e, $escape=true) {
    if($escape == true) {
        $e = htmlspecialchars($e);
    }

    return '<div class="alert alert-success"><b>Success!</b> '.$e.'</div>';
}