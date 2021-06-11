<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once '../Documents/Documents.php';
require_once '../Partakings/Partakings.php';
require_once '../Mentorings/Mentorings.php';
require_once './ScientificPapers.php';

// proceed with the session
session_start();

$id_scientific_papers = $_GET['id_scientific_papers'];

// if id of scientific paper was prosperously passed
if (isset($id_scientific_papers)) {
    // establish a new database connection
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // try a scientific papers deletion with belonging documentation
    echo $DBC->deleteScientificPaper($id_scientific_papers);
} // if