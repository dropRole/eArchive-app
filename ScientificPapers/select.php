<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once './ScientificPapers.php';

// proceed with the session
session_start();

$id_attendances = $_GET['id_attendances'];

// if id of attendance is prosperously passed
if (isset($id_attendances)) {
    // establish a new database connection
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // fetch scientific papers
    $scientific_papers = $DBC->selectScientificPapers($id_attendances);
    // if there're no papers at all
    if (count($scientific_papers) == 0)
        echo 'Opomba: v evidenci ni znanstvenih del.';
    // if papares exist in evidence
    if (count($scientific_papers) >= 1) {
?>

        <?php
    } // if
} // if