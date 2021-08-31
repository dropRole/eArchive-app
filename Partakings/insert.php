<?php

// namespace and class import declaration
use DBC\DBC;

// script import declaration
require_once '../autoload.php';

// proceed with the session
session_start();

$id_scientific_papers = $_POST['id_scientific_papers'];
$partakers = $_POST['partakers'];

// if id of a scientific paper and partakers were successfully submitted
if (isset($id_scientific_papers, $partakers)) {
    // establish a new database connection
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // insert and upload each document
    foreach ($partakers as $partaker)
        $DBC->insertPartaker($id_scientific_papers, $DBC->selectStudentsByIndex($partaker['index'])[0]->id_attendances, $DBC->selectStudentsByIndex($partaker['index'])[0]->fullname, $partaker['part']);
} // if
