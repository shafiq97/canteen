<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
$mysqli = new mysqli("localhost", "root", "", "eaterio");

if ($mysqli->connect_errno) {
    header("location: db_error.php");
    exit(1);
}

define('SITE_ROOT', realpath(dirname(__FILE__)));
date_default_timezone_set('Asia/Kuala_Lumpur');
