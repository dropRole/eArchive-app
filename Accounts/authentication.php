<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../autoload.php';

// commence a new session
session_start();

$index = $_POST['index'];
$pass = $_POST['pass'];

// if credentials are succesfully passed 
if (isset($index, $pass)) {
    // retrieve a PDO instance carrying database server connection
    $DBC = new DBC();
    echo $DBC->checkAccountCredentials($index, $pass);
} // if

