<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../../autoload.php';

// proceed with the current session

session_start();

$id_attendances = $_GET['id_attendances'];
$avatar = $_GET['avatar'];

// if id of program attendance and location of an avatar were prosperously passed via URL query string
if (isset($id_attendances, $avatar)) {
    // return a new PDO object instance that carries connection with the database server 
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // attempt avatar deletion
    echo $DBC->deleteAccountAvatar($id_attendances, $avatar);
} // if
