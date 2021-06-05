<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once '../Documents/Documents.php';

// proceed with the session
session_start();

$id_scientific_papers = $_POST['id_scientific_papers'];
$documents = $_POST['documents'];

// if scientific paper id and documents data are successfully passed
if (isset($id_scientific_papers, $documents)) {
    // establish a new database connection
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // insert and upload each document
    foreach ($documents as $document)
        echo $DBC->insertDocument($id_scientific_papers, $document['version'], $document['name']);
} // if
