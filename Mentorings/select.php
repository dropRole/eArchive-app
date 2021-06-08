<?php

// namespace and class import declaration
use DBC\DBC;

// script import declaration
require_once '../DBC/DBC.php';
require_once '../Mentorings/Mentorings.php';

// proceed with the session
session_start();

$id_mentorings = $_GET['id_mentorings'];

// if id of mentoring is prosperously passed
if (isset($id_mentorings)) {
    // return a new PDO object instance that carries connection with the database server 
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // select data of the specified mentor
    echo $DBC->selectMentorOfScientificPaper($id_mentorings);
} // if
