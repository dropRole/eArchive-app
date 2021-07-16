<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../autoload.php';

// proceed with the session
session_start();

$id_attendances = $_POST['id_attendances'];
$certificate = $_POST['certificate'];
$issued = $_POST['issued'];
$defended = $_POST['defended'];

// if id of an attendance and certificate are prosperously passed
if (isset($id_attendances, $certificate, $issued, $defended)) {
    // establish a new database connection
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // attempt an insertion of graduation data 
    echo $DBC->insertGraduation($id_attendances, $certificate, (new DateTime($issued)), (new DateTime($defended)));
} // if
