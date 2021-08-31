<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../../autoload.php';

// proceed with the session
session_start();

$id_attendances = $_POST['id_attendances'];

// if id of program attendance was prosperously submitted
if (isset($id_attendances)) {
    // return a new PDO object instance that carries connection with the database server 
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // attempt avatar upload
    echo $DBC->uploadAccountAvatar($id_attendances);
} // if
