<?php

// script import declaration

require_once __DIR__ . './session.php';

?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no' />
    <meta name='description' content='eArchive for student sctientific achivements' />
    <title>eArchive</title>
    <!-- Favicon-->
    <link rel='icon' type='image/x-icon' href='#' />
    <!-- Core template CSS (includes Bootstrap) -->
    <link href='/eArchive/vendor/bootstrap/css/bootstrap.css' rel='stylesheet' />
    <!-- Custom CSS design -->
    <!-- <link href='./custom/css/styles.css' rel='stylesheet' /> -->
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
        <div class="container">
            <a class="navbar-brand" href="#">eArhiv</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Domov
                            <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <?php
                    // if authorized is logged in
                    if (isset($_SESSION['authorized'])) {
                    ?>
                        <li class="nav-item active">
                            <a class="nav-link" href="studentRecord.php">Študenti
                                <span class="sr-only">Študenti</span>
                            </a>
                        </li>
                    <?php
                    } // if
                    ?>
                    <?php
                    // if student or authorized is logged in
                    if (isset($_SESSION['index']) || isset($_SESSION['authorized'])) {
                    ?>
                        <div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Račun
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="../logout.php">Odjava</a>
                            </div>
                        </div>
                    <?php
                    } // if
                    ?>
                </ul>
            </div>
        </div>
    </nav>