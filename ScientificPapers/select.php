<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once '../Documents/Documents.php';
require_once './ScientificPapers.php';

// proceed with the session
session_start();

// if id of attendance is prosperously passed
if (isset($_GET['id_attendances'])) {
    $id_attendances = $_GET['id_attendances'];
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
            <div class="card m-3 col-6">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $scientificPaper->topic; ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $scientificPaper->type; ?></h6>
                    <ul class="list-group">
                        <?php
                        $documents = $DBC->selectDocuments($scientificPaper->id_scientific_papers);
                        // if there's evidence of the documentation
                        if (count($documents))
                            foreach ($documents as $document) {
                        ?>
                            <li class="list-group-item"><a href="#"><?php echo basename($document->getSource()); ?></a><span class="doc-del-spn ml-3" data-id="<?php echo $document->getIdDocuments(); ?>">&#10007;</span></li>
                        <?php
                            } // foreach
                        // if there's no evidence of the documentation
                        else
                            echo 'Ni predane dokumentacije.';
                        ?>
                    </ul>
                    <a href="#" class="card-link sp-upd-а" data-id="<?php echo $scientificPaper->id_scientific_papers; ?>" data-toggle="modal" data-target="#sPIUMdl">Uredi</a>
                    <a href="#" class="card-link sp-del-a" data-id="<?php echo $scientificPaper->id_scientific_papers; ?>">Izbriši</a>
                </div>
            </div>
<?php
        } // foreach
    } // if
} // if

// if id of a scientific paper is successfully passed
if (isset($_GET['id_scientific_papers'])) {
    $id_scientific_papers = $_GET['id_scientific_papers'];
    // establish a new database connection
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // fetch scientific papers
    $scientificPaper = $DBC->selectScientificPaper($id_scientific_papers);
    // if paper is returned
    if (isset($scientificPaper))
        echo json_encode($scientificPaper);
} // if
