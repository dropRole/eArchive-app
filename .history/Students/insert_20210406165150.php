 <?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once '../Universities/Universities.php';

$name = $_POST['name'];
$surname = $_POST['surname'];
$email = $_POST['email'];
$telephone = $_POST['telephone'];
$addresses = $_POST['addresses'];
$attendances = $_POST['attendances'];

// create a new instance
$DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
$universities = $DBC->insertStudent();
?>