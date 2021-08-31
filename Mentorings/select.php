<?php

// namespace and class import declaration
use DBC\DBC;

// script import declaration
require_once '../autoload.php';

// proceed with the session
session_start();

// if id of mentoring record was prosperously forwarded via URL query string
if (isset($_GET['id_mentorings'])) {
    $id_mentorings = $_GET['id_mentorings'];
    // return a new PDO object instance that carries connection with the database server 
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // select data of the specified mentor
    echo $DBC->selectMentor($id_mentorings);
} // if
// if id of scientific paper record is successfully passed by URL query string
else if (isset($_GET['id_scientific_papers'])) {
    $id_scientific_papers = $_GET['id_scientific_papers'];
    // return a new PDO object instance that carries connection with the database server 
    $DBC = new DBC();
?>
    <div class="row p-3">
        <?php
        // select data regarding mentors of the scientific paper 
        foreach ($DBC->selectMentors($id_scientific_papers) as $mentor) {
        ?>
            <div class="card p-0 col-12">
                <div class="card-header d-flex justify-content-between">
                    <span><strong><?php echo $mentor->getMentor(); ?></strong></span>
                    <span><?php echo $mentor->getEmail(); ?></span>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Predmet:</strong>&nbsp;<?php echo $mentor->getTaught(); ?></li>
                    <li class="list-group-item"><strong>Fakulteta:</strong>&nbsp;<?php echo $mentor->faculty; ?></li>
                </ul>
            </div>
        <?php
        } // foreach
        ?>
    </div>
<?php
} // else if 
