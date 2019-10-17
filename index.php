<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);
define("LOCAL_FOLDER", __DIR__ );

session_start();

include("libs/base.php");
include("libs/constants.php");
base::init("etc/config");
base::connect();

include_once(LOCAL_FOLDER . "/src/Tasks/Controllers/TaskController.php");
$controller = new TaskController();
echo $controller->displayView();