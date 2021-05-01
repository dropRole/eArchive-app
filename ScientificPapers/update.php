<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once './ScientificPapers.php';

// proceed with the session
session_start();

$id_scientific_papers = $_POST['id_scientific_papers'];
$topic = $_POST['topic'];
$type = $_POST['type'];
$written = $_POST['written'];

// if scientific paper data was successfully submitted
if (isset($id_scientific_papers, $topic, $type, $written)) {
    // establish a new database connection
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // update scientific paper
    echo $DBC->updateScientificPapers($id_scientific_papers, $topic, $type, (new DateTime($written)));
} // if