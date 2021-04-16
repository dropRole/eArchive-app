<?php

// proceed or start a new session
session_start();

// if superglobal doesn't contain vars and server isn't currently executing one of the given scripts
if (!isset($_SESSION['user']) || !isset($_SESSION['pass']) && basename($_SERVER['REQUEST_URI']) != 'login.php') {
    header('Location:login.php');
    die();
} // if
else {
    // if unauthorized user is running scripts
    if(!isset($_SESSION['authorized']) && basename($_SERVER['REQUEST_URI']) == 'studentRecord.php'){

    } // if
} // else

