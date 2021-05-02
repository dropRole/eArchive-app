<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once '../Documents/Documents.php';

// proceed with the session
session_start();

$id_documents = $_GET['id_documents'];

// if id of a document was passed
if (isset($id_documents)) {
    // establish a new database connection
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // attempt a document deletion
    echo $DBC->deleteDocument($id_documents);
} // if
