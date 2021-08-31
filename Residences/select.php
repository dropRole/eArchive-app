<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../autoload.php';

// proceed with the current session
session_start();

$id_students = $_GET['id_students'];

// if id of a student was passed by URL query string
if (isset($id_students)) {
    // retrieve a PDO instance holding database connection
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // select student residences
    echo $DBC->selectStudentResidences($id_students);
} // if 
