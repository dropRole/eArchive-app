<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../../DBC/DBC.php';
require_once '../../Accounts/Accounts.php';

// proceed with the session
session_start();

$id_attendances = $_POST['id_attendances'];
$pass = $_POST['pass'];

// if id of attendance and password are prosperously passed
if (isset($id_attendances, $pass)) {
    // establish a new database connection
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // attempt an account insertion
    echo $DBC->insertAccount($id_attendances, $pass);
} // if