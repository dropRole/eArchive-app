<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../../DBC/DBC.php';
require_once '../../Accounts/Accounts.php';

// proceed with the session
session_start();

$id_attendances = $_GET['id_attendances'];

// if id of attendance is prosperously passed
if (isset($id_attendances)) {
    // establish a new database connection
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // attempt an account deletion
    echo $DBC->deleteStudentAccount($id_attendances);
} // if