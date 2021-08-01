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
                    <p class="card-title d-flex justify-content-between">
                        <span class="h5"><?php echo $scientificPaper->getTopic(); ?></span>
                        <span class="font-italic text-muted small">Napisano: <?php echo (new DateTime($scientificPaper->getWritten()))->format('d-m-y'); ?></span>
                    </p>
                    <p class="card-subtitle mb-2 text-muted h6"><?php echo $scientificPaper->getType(); ?></p>
                    <div class="d-flex justify-content-between mt-3 mb-2">
                        <p class="h6"><strong>Soavtorji</strong></p>
                        <a href="#sciPapInsrMdl" data-toggle="modal">
                            <img class="par-ins-img" src="/eArchive/custom/img/assignPartaker.png" data-toggle="tooltip" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" title="Dodeli">
                        </a>
                    </div>
                    <ul class="list-group">
                        <?php
                        $partakers = $DBC->selectPartakings($scientificPaper->getIdScientificPapers());
                        // if paper had partakers
                        if (count($partakers))
                            foreach ($partakers as $partaker) {
                        ?>
                            <li class="list-group-item d-flex">
                                <p class="w-75 m-0">
                                    <?php echo "{$partaker->fullname}({$partaker->getPart()})"; ?>
                                </p>
                                <div class="w-25">
                                    <a class="mr-3 text-decoration-none" href="#sciPapInsrMdl" data-toggle="modal">
                                        <img class="par-upd-img" src="/eArchive/custom/img/updateRecord.png" alt="Uredi" data-id-partakings="<?php echo $partaker->getIdPartakings(); ?>" data-index="<?php echo $partaker->index; ?>" data-part="<?php echo $partaker->getPart(); ?>" data-toggle="tooltip" title="Uredi">
                                    </a>
                                    <img class="par-del-img" src="/eArchive/custom/img/deleteRecord.png" data-id-partakings="<?php echo $partaker->getIdPartakings(); ?>" alt="Izbriši" data-toggle="tooltip" title="Izbriši">
                                </div>

                            </li>
                        <?php
                            } // foreach
                        else
                            echo 'Delo nima soavtorjev.';
                        ?>
                    </ul>
                    <div class="d-flex justify-content-between mt-3 mb-2">
                        <p class="h6"><strong>Mentorji</strong></p>
                        <a href="#sciPapInsrMdl" data-toggle="modal">
                            <img class="men-ins-img" src="/eArchive/custom/img/assignMentor.png" alt="Dodeli" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-toggle="tooltip" title="Dodeli">
                        </a>
                    </div>
                    <ul class="list-group">
                        <?php
                        $mentors = $DBC->selectSciPapMentors($scientificPaper->getIdScientificPapers());
                        // if paper was mentored
                        if (count($mentors))
                            foreach ($mentors as $mentor) {
                        ?>
                            <li class="list-group-item d-flex">
                                <p class="w-75 m-0">
                                    <?php echo $mentor->getMentor(); ?>
                                </p>
                                <div class="w-25">
                                    <a class="mr-3 text-decoration-none" href="#sciPapInsrMdl" data-toggle="modal">
                                        <img class="men-upd-img" src="/eArchive/custom/img/updateRecord.png" alt="Uredi" data-id-mentorings="<?php echo $mentor->getIdMentorings(); ?>" data-toggle="tooltip" title="Uredi">
                                    </a>
                                    <img class="men-del-img" src="/eArchive/custom/img/deleteRecord.png" data-id-mentorings="<?php echo $mentor->getIdMentorings(); ?>" alt="Izbriši" data-toggle="tooltip" title="Izbriši">
                                </div>
                            </li>
                        <?php
                            } // foreach
                        else
                            echo 'Delo ni mentorirano.';
                        ?>
                    </ul>
                    <div class="d-flex justify-content-between mt-3 mb-1">
                        <p class="h6"><strong>Dokumentacija</strong></p>
                        <a href="#sciPapInsrMdl" data-toggle="modal">
                            <img class="doc-upl-img" src="/eArchive/custom/img/upload.png" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-toggle="tooltip" title="Naloži" alt="Naloži">
                        </a>
                    </div>
                    <ul class="list-group">
                        <?php
                        $documents = $DBC->selectDocuments($scientificPaper->getIdScientificPapers());
                        // if there's evidence of the documentation
                        if (count($documents))
                            foreach ($documents as $document) {
                        ?>
                            <li class="list-group-item d-flex">
                                <a class="w-75 text-info text-decoration-none" href="<?php echo "/eArchive/{$document->getSource()}"; ?>" target="_blank">
                                    Dokument&nbsp;<?php echo $document->getVersion(); ?>
                                </a>
                                <div class="w-25 d-flex justify-content-end">
                                    <img class="doc-del-img" src="/eArchive/custom/img/deleteDocument.png" alt="Izbriši" data-source="<?php echo $document->getSource(); ?>" data-toggle="tooltip" title="Izbriši">
                                </div>
                            </li>
                        <?php
                            } // foreach
                        // if there's no evidence of the documentation
                        else
                            echo 'Ni predane dokumentacije.';
                        ?>
                    </ul>
                    <div class="d-flex justify-content-around mt-3 mt-2">
                        <a href="#sciPapInsrMdl" class="card-link sp-upd-а" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-toggle="modal">Uredi</a>
                        <a href="#" class="card-link sp-del-a" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>">Izbriši</a>
                    </div>
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