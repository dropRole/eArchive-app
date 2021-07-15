<?php

// namespace and class import declaration
use DBC\DBC;

// script import declaration
require_once '../DBC/DBC.php';
require_once '../Mentorings/Mentorings.php';

// proceed with the session
session_start();

// if id of mentoring record was prosperously forwarded via URL query string
if (isset($_GET['id_mentorings'])) {
    $id_mentorings = $_GET['id_mentorings'];
    // return a new PDO object instance that carries connection with the database server 
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // select data of the specified mentor
    echo $DBC->selectMentorOfScientificPaper($id_mentorings);
} // if
// if id of scientific paper record is successfully passed by URL query string
else if (isset($_GET['id_scientific_papers'])) {
    $id_scientific_papers = $_GET['id_scientific_papers'];
    // return a new PDO object instance that carries connection with the database server 
    $DBC = new DBC();
?>
    <div class="table-responsive">
        <table class="table table-borderless">
            <thead class="thead-dark">
                <tr>
                    <th>Mentor</th>
                    <th>Učil</th>
                    <th>Fakulteta</th>
                    <th>E-naslov</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // select data regarding mentors of the scientific paper 
                foreach ($DBC->selectSciPapMentors($id_scientific_papers) as $mentor) {
                ?>
                    <tr>
                        <td><?php echo $mentor->getMentor(); ?></td>
                        <td><?php echo $mentor->getTaught(); ?></td>
                        <td><?php echo $mentor->faculty; ?></td>
                        <td><?php echo $mentor->getEmail(); ?></td>
                    </tr>
                <?php
                } // foreach
                ?>
            </tbody>
        </table>
    </div>
<?php
} // else if 
