<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once './Residences.php';

// proceed with the current session
session_start();

$id_residences = $_GET['id_residences'];

// if id of a student and his residence were prosperously passed 
if (isset($id_residences)) {
    // drive a new connection with the database server 
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // attempt student residence deletion 
    $DBC->deleteStudentTemporaryResidence($id_residences);
} // if
