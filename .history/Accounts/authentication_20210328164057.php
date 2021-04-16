<?php

// namespace and class import declaration

use DBC\DBC;

// script imprort declaration

require_once '../DBC/DBC.php';

session_start();

$index = $_POST['index'];
$pass = $_POST['pass'];

// if credentials are succesfully passed 
if (isset($index, $pass)) {
    // create a new instance
    $DBC = new DBC();
    echo $DBC->checkAccountCredentials($index, $pass);
} // if

