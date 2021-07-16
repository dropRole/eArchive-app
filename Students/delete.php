<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../autoload.php';

// proceed with the current session
session_start();

$id_attendances = $_GET['id_attendances'];
$id_students = $_GET['id_students'];
$index = $_GET['index'];


// if id of a student and its program attendance id were forwarded by URL query string
if (isset($id_attendances, $id_students, $index)) {
    // create a new PDO interface object instance 
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // attemt a deletion
    echo $DBC->deleteStudent($id_attendances, $id_students, $index);
} // if 
