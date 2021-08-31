<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../autoload.php';

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
    $idScientificPapers = $DBC->insertScientificPaper($id_attendances, $topic, $type, (new DateTime($written)));
    // if an attempt was successful 
    if ($idScientificPapers) {
        // if there were partakers in writting
        if (isset($_POST['partakers']))
            foreach ($_POST['partakers'] as $partaker)
                $DBC->insertPartaker($report['id_scientific_papers'], $DBC->selectStudentsByIndex($partaker['index'])[0]->id_attendances, $DBC->selectStudentsByIndex($partaker['index'])[0]->fullname, $partaker['part']);
        // if scientific paper was mentored
        if (isset($_POST['mentors'])) {
            foreach ($_POST['mentors'] as $mentor)
                $DBC->insertMentor($report['id_scientific_papers'], $mentor['id_faculties'], $mentor['mentor'], $mentor['taught'], $mentor['email'], $mentor['telephone']);
        } // if
        foreach ($documents as $document)
            $DBC->uploadDocument($report['id_scientific_papers'], $document['version'], $document['name']);
    } // if
} // if  