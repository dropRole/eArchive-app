<?php

// proceed or start a new session
session_start();

// if unauthorized or unaccredited user is running scripts, redirect to
if(!(isset($_SESSION['user'])) && basename($_SERVER['REQUEST_URI']) != 'index.php' && basename($_SERVER['REQUEST_URI']) != 'login.php'){
    header('Location: /eArchive/login.php');
    die();
} // if