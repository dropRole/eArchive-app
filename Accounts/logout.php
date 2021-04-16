<?php

// start currently running session
session_start();
// destroy currently running sesssion
session_destroy();
header('Location:../login.php');
