<?php

// namespace and class import declaration
use DBC\DBC;

// script import declaration
require_once '../autoload.php';

// proceed with the session
session_start();

$id_scientific_papers = $_POST['id_scientific_papers'];
$mentors = $_POST['mentors'];

// if id of scientific paper and array of mentors data were prosperously committed
if (isset($id_scientific_papers, $mentors)) {
    // return a new PDO object instance that carries connection with the database server 
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // insert data of each mentor
    foreach ($mentors as $mentor)
        // if data was successfully inserted 
        if ($DBC->insertMentor($id_scientific_papers, $mentor['id_faculties'], $mentor['mentor'], $mentor['taught'], $mentor['email'], $mentor['telephone']))
            echo "Mentor {$mentor['mentor']} je uspešno določen.";
        else
            echo "Mentor {$mentor['mentor']} ni uspešno določen.";
} // if
