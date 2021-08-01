<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../autoload.php';

// proceed with the session
session_start();

// if id of attendance is prosperously passed
if (isset($_GET['id_attendances'])) {
    $id_attendances = $_GET['id_attendances'];
    // establish a new database connection
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // fetch scientific papers
    $scientificPapers = $DBC->selectSciPapsByProgAttendance($id_attendances);
    // if there're no papers at all
    if (count($scientificPapers) == 0) {
?>
        <p class="p-2">Opomba: znanstvenih del ni v evidenci.</p>
        <?php
    } // if
    // if papares exist in evidence
    if (count($scientificPapers) > 0) {
        foreach ($scientificPapers as $scientificPaper) {
        ?>
            <div class="card m-3 col-6">
                <div class="card-body">
                    <p class="card-title">
                        <span class="h5"><?php echo $scientificPaper->getTopic(); ?></span>
                        <span class="font-italic text-muted small">Napisano: <?php echo (new DateTime($scientificPaper->getWritten()))->format('d-m-y'); ?></span>
                    </p>
                    <p class="card-subtitle mb-2 text-muted h6"><?php echo $scientificPaper->getType(); ?></p>
                    <div class="row mb-1">
                        <div class="col-6">
                            <p class="h6"><strong>Soavtorji</strong></p>
                        </div>
                        <div class="col-6">
                            <a href="#sciPapInsrMdl" class="card-link par-ins-a" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-toggle="modal">
                                <img src="/eArchive/custom/img/assignPartaker.png" data-toggle="tooltip" title="Dodeli">
                            </a>
                        </div>
                    </div>
                    <ul class="list-group">
                        <?php
                        $partakers = $DBC->selectPartakings($scientificPaper->getIdScientificPapers());
                        // if paper had partakers
                        if (count($partakers))
                            foreach ($partakers as $partaker) {
                        ?>
                            <li class="list-group-item">
                                <span><?php echo "{$partaker->fullname}({$partaker->getPart()})"; ?></span>
                                <a class="par-upd-a text-decoration-none" href="#sciPapInsrMdl" data-id-partakings="<?php echo $partaker->getIdPartakings(); ?>" data-index="<?php echo $partaker->index; ?>" data-part="<?php echo $partaker->getPart(); ?>" data-toggle="modal">
                                    <img src="/eArchive/custom/img/updateRecord.png" alt="Uredi" data-toggle="tooltip" title="Uredi">
                                </a>
                                <img class="par-del-spn ml-3" src="/eArchive/custom/img/deleteRecord.png" data-id-partakings="<?php echo $partaker->getIdPartakings(); ?>" alt="Izbriši soavtorja" data-toggle="tooltip" title="Izbriši soavtorja">
                            </li>
                        <?php
                            } // foreach
                        else
                            echo 'Delo nima soavtorjev.';
                        ?>
                    </ul>
                    <div class="row mt-3 mb-1">
                        <div class="col-6">
                            <p class="h6"><strong>Mentorji</strong></p>
                        </div>
                        <div class="col-6">
                            <a href="#sciPapInsrMdl" class="card-link float-right men-ins-a" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-toggle="modal">
                                <img src="/eArchive/custom/img/assignMentor.png" alt="Dodeli" data-toggle="tooltip" title="Dodeli">
                            </a>
                        </div>
                    </div>
                    <ul class="list-group">
                        <?php
                        $mentors = $DBC->selectSciPapMentors($scientificPaper->getIdScientificPapers());
                        // if paper was mentored
                        if (count($mentors))
                            foreach ($mentors as $mentor) {
                        ?>
                            <li class="list-group-item d-flex">
                                <?php echo $mentor->getMentor(); ?>
                                <a class="men-upd-a text-decoration-none" href="#sciPapInsrMdl" data-toggle="modal" data-id-mentorings="<?php echo $mentor->getIdMentorings(); ?>">
                                    <img src="/eArchive/custom/img/updateRecord.png" alt="Uredi" data-toggle="tooltip" title="Uredi">
                                </a>
                                <img class="men-del-spn" src="/eArchive/custom/img/deleteRecord.png" data-id-mentorings="<?php echo $mentor->getIdMentorings(); ?>" alt="Izbriši" data-toggle="tooltip" title="Izbriši">
                            </li>
                        <?php
                            } // foreach
                        else
                            echo 'Delo ni mentorirano.';
                        ?>
                    </ul>
                    <div class="row mt-3 mb-1">
                        <div class="col-6">
                            <p class="h6"><strong>Dokumentacija</strong></p>
                        </div>
                        <div class="col-6">
                            <a href="#sciPapInsrMdl" class="card-link doc-upl-a" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-toggle="modal">
                                <img src="/eArchive/custom/img/upload.png" alt="Naloži" data-toggle="tooltip" title="Naloži">
                            </a>
                        </div>
                    </div>
                    <ul class="list-group">
                        <?php
                        $documents = $DBC->selectDocuments($scientificPaper->getIdScientificPapers());
                        // if there's evidence of the documentation
                        if (count($documents))
                            foreach ($documents as $document) {
                        ?>
                            <li class="list-group-item">
                                Dokument
                                <a href="<?php echo "../../{$document->getSource()}"; ?>" target="_blank">
                                    <?php echo $document->getVersion(); ?>
                                </a>
                                <span class="doc-del-spn ml-3" data-source="<?php echo $document->getSource(); ?>">
                                    &#10007;
                                </span>
                            </li>
                        <?php
                            } // foreach
                        // if there's no evidence of the documentation
                        else
                            echo 'Ni predane dokumentacije.';
                        ?>
                    </ul>
                    <a href="#sciPapInsrMdl" class="card-link sp-upd-а" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-toggle="modal">Uredi</a>
                    <a href="#" class="card-link sp-del-a" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>">Izbriši</a>
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

?>