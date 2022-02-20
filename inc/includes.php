<?php

require_once("Database.php");
require_once("functions.php");
require_once("config.php");

$db = new Database($Cfg["DB"]['Host'], $Cfg["DB"]['User'], $Cfg["DB"]['Password'], $Cfg["DB"]['Database']);