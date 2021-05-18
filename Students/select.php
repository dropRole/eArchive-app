<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once './Students.php';

// proceed with the current session
session_start();

$id_students = $_GET['id_students'];

// if id of a student was passed by URL query string
if (isset($id_students)) {
    // create a new PDO interface object instance 
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // select student particulars
    echo $DBC->selectStudent($id_students);
} // if 
