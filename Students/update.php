<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../autoload.php';

session_start();

$id_students = $_POST['id_students'];
$id_postal_codes = $_POST['id_postal_codes'];
$name = $_POST['name'];
$surname = $_POST['surname'];
$email = $_POST['email'];
$telephone = $_POST['telephone'];
$residences = $_POST['residences'];
// if student particulars were prosperously passed
if (isset($id_students, $id_postal_codes, $name, $surname, $residences)) {
    // establish a new connection with the database server
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // report on update
    $DBC->updateStudent($id_students, $id_postal_codes, $name, $surname, $email, $telephone, $residences);
} // if 

?>