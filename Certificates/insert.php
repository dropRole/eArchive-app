<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../autoload.php';

// proceed with the session
session_start();

$id_attendances = $_POST['id_attendances'];
$certificate = $_POST['certificate'];
$defended = $_POST['defended'];
$issued = $_POST['issued'];

// if graduation data was prospeoursly forwarded  
if (isset($id_attendances, $certificate, $defended, $issued)) {
    // return a new PDO object instance that carries connection with the database server 
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // if certificate was uploaded
    if ($certificate !== NULL)
        // attempt a graduation certificate insertion
        $DBC->uploadCertificate($id_attendances, $certificate, (new DateTime($defended)), (new DateTime($issued)));
} // if
