<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once './ScientificPapers.php';

// proceed with the session
session_start();

$id_attendances = $_POST['id_attendances'];
$topic = $_POST['topic'];
$type = $_POST['type'];
$written = $_POST['written'];
$documents = $_POST['documents'];

// if data for scientific paper and documents have been prosperously passed
if (isset($id_attendances, $topic, $type, $written, $documents)) {
    // establish a new database connection
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // attempt an insert
    $report = $DBC->insertScientificPapers($id_attendances, $topic, $type, (new DateTime($written)));
    echo $report['message'];
    // if an attempt was successful 
    if ($report['id_scientific_papers']) {
        foreach ($documents as $document) {
            echo $DBC->insertDocument($report['id_scientific_papers'], $document['version'], $document['name']);
        } // foreach
    } // if
} // if 