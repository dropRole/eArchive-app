<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../autoload.php';

// proceed with the session
session_start();

$id_certificates = $_POST['id_certificates'];
$defended = $_POST['defended'];
$issued = $_POST['issued'];

// if graduation certificate data to be updated was successfully submitted
if (isset($id_certificates, $defended, $issued)) {
    // return a new PDO object instance that carries connection with the database server 
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // update date of issuing 
    echo $DBC->updateGradCertIssuingDate($id_certificates, (new DateTime($issued))) . PHP_EOL;
    // update date of defenense
    echo $DBC->updateGradDefDate($id_certificates, (new DateTime($defended)));
} // if
