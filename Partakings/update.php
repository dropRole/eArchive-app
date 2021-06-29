<?php

// namespace and class import declaration
use DBC\DBC;

// script import declaration
require_once '../DBC/DBC.php';
require_once '../Partakings/Partakings.php';

// proceed with the session
session_start();

$id_partakings = $_POST['id_partakings'];
$partaker = $_POST['partakers'];

// if id and part of the partaking were successfully submitted
if (isset($id_partakings, $partaker)) {
    // return a new PDO object instance that carries connection with the database server 
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // update the part
    echo $DBC->updatePartInScientificPaper($id_partakings, $partaker[0]['part']);
} // if
