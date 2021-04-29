<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once './ScientificPapers.php';

// proceed with the session
session_start();

$id_attendances = $_GET['id_attendances'];

// if id of attendance is prosperously passed
if (isset($id_attendances)) {
    // establish a new database connection
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // fetch scientific papers
    $scientificPapers = $DBC->selectScientificPapers($id_attendances);
    // if there're no papers at all
    if (count($scientificPapers) == 0) {
?>
        <p class="p-2">Opomba: znanstvenih del ni v evidenci.</p>
        <?php
    } // if
    // if papares exist in evidence
    if (count($scientificPapers) >= 1) {
        foreach ($scientificPapers as $scientificPaper) {
        ?>
            <div class="card m-3" style="width:15rem;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $scientificPaper->topic; ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $scientificPaper->type; ?></h6>
                    <p class="card-text"><a href="#"><?php echo $scientificPaper->source; ?></a></p>
                    <a href="#" class="card-link">Uredi</a>
                    <a href="#" class="card-link">Izbriši</a>
                </div>
            </div>
<?php
        } // foreach
    } // if
} // if