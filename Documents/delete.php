<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once '../Documents/Documents.php';

// proceed with the session
session_start();

$source = $_GET['source'];

// if document source was passed
if (isset($source)) {
    // establish a new database connection
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // attempt a document deletion
    echo $DBC->deleteDocument($source);
} // if
