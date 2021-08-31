<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../../autoload.php';

// proceed with the session
session_start();

$id_attendances = $_POST['id_attendances'];
$index = $_POST['index'];
$pass = $_POST['pass'];

// if id of attendance and password are prosperously passed
if (isset($id_attendances, $index, $pass)) {
    // establish a new database connection
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // attempt an account insertion
    $DBC->insertStudentAccount($id_attendances, $index, $pass);
} // if