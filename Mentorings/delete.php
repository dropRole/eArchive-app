<?php

// namespace and class import declaration
use DBC\DBC;

// script import declaration
require_once '../autoload.php';

// proceed with the session
session_start();

$id_mentorings = $_GET['id_mentorings'];

// if record id was passed by URL query string
if (isset($id_mentorings)) {
    // return a new PDO object instance that carries connection with the database server 
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // try deletion 
    echo $DBC->deleteMentorOfScientificPaper($id_mentorings);
} // if
