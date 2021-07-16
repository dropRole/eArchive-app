<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../../autoload.php';

// proceed with the session
session_start();

$id_attendances = $_GET['id_attendances'];
$index = $_GET['index'];

// if id of attendance is prosperously passed
if (isset($id_attendances, $index)) {
    // establish a new database connection
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // attempt an account deletion
    echo $DBC->deleteStudtAcct($id_attendances, $index);
} // if