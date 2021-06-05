<?php

// namespace and class import declaration
use DBC\DBC;

// script import declaration
require_once '../DBC/DBC.php';
require_once '../Partakings/Partakings.php';

// proceed with the session
session_start();

$id_partakings = $_GET['id_partakings'];

// if id of partakings was successfully passed
if (isset($id_partakings)) {
    // establish a new database connection
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // attempt deletion of a partaker
    echo $DBC->deleteScientificPaperPartaker($id_partakings);
} // if