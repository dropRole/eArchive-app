 <?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once '../Universities/Universities.php';



// create a new instance
$DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
$universities = $DBC->insertStudent();
?>