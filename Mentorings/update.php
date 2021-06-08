<?php

// namespace and class import declaration
use DBC\DBC;

// script import declaration
require_once '../DBC/DBC.php';
require_once '../Mentorings/Mentorings.php';

// proceed with the session
session_start();

$id_mentorings = $_POST['id_mentorings'];
$mentor = $_POST['mentors'];

// if mentors data was sucessfully submitted  
if (isset($id_mentorings, $mentor)) {
    // return a new PDO object instance that carries connection with the database server 
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // attempt to update 
    echo $DBC->updateMentorOfScientificPaper($id_mentorings, $mentor[0]['id_faculties'], $mentor[0]['mentor'], $mentor[0]['taught'], $mentor[0]['email'], $mentor[0]['telephone']);
} // if
