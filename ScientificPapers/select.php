<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once '../Documents/Documents.php';
require_once '../Partakings/Partakings.php';
require_once '../Mentorings/Mentorings.php';
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
    if (count($scientificPapers) > 0) {
        foreach ($scientificPapers as $scientificPaper) {
        ?>
            <div class="card m-3 col-6">
                <div class="card-body">
                    <p class="card-title">
                        <span class="h5"><?php echo $scientificPaper->getTopic(); ?></span>
                        <span class="float-right font-italic text-muted small">Napisano: <?php echo (new DateTime($scientificPaper->getWritten()))->format('d-m-y'); ?></span>
                    </p>
                    <p class="card-subtitle mb-2 text-muted h6"><?php echo $scientificPaper->getType(); ?></p>
                    <ul class="list-group">
                        <div class="row">
                            <div class="col-6">
                                <p class="h6">Soavtorji</p>
                            </div>
                            <div class="col-6">
                                <a href="#sciPapInsrMdl" class="card-link float-right par-ins-a" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-toggle="modal">Dodeli</a>
                            </div>
                        </div>
                        <?php
                        $partakers = $DBC->selectPartakersOfScientificPaper($scientificPaper->getIdScientificPapers());
                        // if paper had partakers
                        if (count($partakers))
                            foreach ($partakers as $partaker) {
                        ?>
                            <li class="list-group-item">
                                <span><?php echo "{$partaker->fullname}({$partaker->getPart()})"; ?></span>
                                <a class="par-upd-a" href="#sciPapInsrMdl" data-id-partakings="<?php echo $partaker->getIdPartakings(); ?>" data-index="<?php echo $partaker->index; ?>" data-part="<?php echo $partaker->getPart(); ?>" data-toggle="modal">Uredi</a>
                                <span class="par-del-spn ml-3" data-id-partakings="<?php echo $partaker->getIdPartakings(); ?>">&#10007;</span>
                            </li>
                        <?php
                            } // foreach
                        else
                            echo 'Delo nima soavtorjev.';
                        ?>
                    </ul>
                    <ul class="list-group">
                        <div class="row">
                            <div class="col-6">
                                <p class="h6">Mentorji</p>
                            </div>
                            <div class="col-6">
                                <a href="#sciPapInsrMdl" class="card-link float-right men-ins-a" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-toggle="modal">Določi</a>
                            </div>
                        </div>
                        <?php
                        $mentors = $DBC->selectMentorsOfScientificPaper($scientificPaper->getIdScientificPapers());
                        // if paper was mentored
                        if (count($mentors))
                            foreach ($mentors as $mentor) {
                        ?>
                            <li class="list-group-item">
                                <span><?php echo $mentor->getMentor(); ?> (</span><span><?php echo $mentor->name; ?>)</span>
                                <a class="men-upd-a" href="#sciPapInsrMdl" data-toggle="modal" data-id-mentorings="<?php echo $mentor->getIdMentorings(); ?>">Uredi</a>
                                <span class="men-del-spn ml-3" data-id-mentorings="<?php echo $mentor->getIdMentorings(); ?>">&#10007;</span>
                            </li>
                        <?php
                            } // foreach
                        else
                            echo 'Delo ni mentorirano.';
                        ?>
                    </ul>
                    <ul class="list-group">
                        <div class="row">
                            <div class="col-6">
                                <p class="h6">Dokumentacija</p>
                            </div>
                            <div class="col-6">
                                <a href="#sciPapInsrMdl" class="card-link float-right doc-upl-a" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-toggle="modal" >Naloži</a>
                            </div>
                        </div>
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
