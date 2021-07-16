<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../autoload.php';

// proceed with the session
session_start();

$id_attendances = $_GET['id_attendances'];
$source = $_GET['source'];

// if ids of an attendance and certificate were forwarded by URL query string 
if (isset($id_attendances, $source)) {
    // establish a new database connection
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // try to select certificate particulars
    echo $DBC->deleteGraduation($id_attendances, $source);
} // if
