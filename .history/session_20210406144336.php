<?php

// proceed or start a new session
session_start();

// if superglobal doesn't contain vars and server isn't currently executing one of the given scripts
if (!isset($_SESSION['pass']) && (!isset($_SESSION['authorized']) || !isset($_SESSION['user'])) && basename($_SERVER['REQUEST_URI']) != 'login.php') {
    header('Location:login.php');
    die();
} // if

