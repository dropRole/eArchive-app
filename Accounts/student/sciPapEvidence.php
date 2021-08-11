<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../../autoload.php';
include_once '../../header.php';

$DBC = new DBC($_SESSION['user'], $_SESSION['pass']);

include_once '../../nav.php';

?>

<!-- Custom core JavaScript -->
<script defer src="../../custom/js/sciPapEvidence.js"></script>

<section class="container p-3">
    <p class="h2">Evidenca znanstvenih del</p>
    <div class="d-lg-flex justify-content-between">
        <div>
            <input id="fltrInputEl" class="form-control" type="text" placeholder="Predmet">
        </div>
        <div>
            <button id="sciPapInsrBtn" class="btn btn-primary" data-toggle="modal" data-target="
            #sciPapInsrMdl">Vstavi delo</button>
        </div>
    </div>
    <button id="rprtMdlBtn" class="d-none" type="button" data-toggle="modal" data-target="#rprtMdl"></button>
    <div class="table-responsive mt-3">
        <table class="table">
            <thead>
                <tr>
                    <th>Predmet</th>
                    <th>Vrsta</th>
                    <th>Napisano</th>
                    <th>Soavtorji</th>
                    <th>Mentorji</th>
                    <th>Dokumenti</th>
                    <th colspan="2"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($DBC->selectStudtSciPapers($_SESSION['index']) as $sciPap) {
                ?>
                    <tr>
                        <td><?php echo $sciPap->getTopic(); ?></td>
                        <td><?php echo $sciPap->getType(); ?></td>
                        <td><?php echo (new DateTime($sciPap->getWritten()))->format('d-m-Y'); ?></td>
                        <td>
                            <ul class="list-inline">
                                <li class="list-group-item text-center">
                                    <a href="#sciPapInsrMdl" data-toggle="modal" data-id-scientific-papers="<?php echo $sciPap->getIdScientificPapers(); ?>">
                                        <img class="par-ins-img" src="/eArchive/custom/img/assignPartaker.png" alt="Dodeli" data-toggle="tooltip" title="Dodeli">
                                    </a>
                                </li>
                                <?php
                                foreach ($DBC->selectPartakings($sciPap->getIdScientificPapers()) as $partaker) {
                                ?>
                                    <li class="list-group-item">
                                        <p class="d-flex justify-content-between">
                                            <span class="w-100 text-center"><?php echo $partaker->fullname; ?></span>
                                            <span class="w-100 text-center"><?php echo $partaker->getPart(); ?></span>
                                        </p>
                                        <p class="d-flex justify-content-around">
                                            <a class="par-upd-a" href="#sciPapInsrMdl" data-toggle="modal" data-id-partakings="<?php echo $partaker->getIdPartakings(); ?>" data-index="<?php echo $partaker->index; ?>" data-part="<?php echo $partaker->getPart(); ?>">Uredi</a>
                                            <a class="par-del-a" href="#sciPapInsrMdl" data-id-partakings="<?php echo $partaker->getIdPartakings(); ?>">Izbriši</a>
                                        </p>
                                    </li>
                                <?php
                                } // forach
                                ?>
                            </ul>
                        </td>
                        <td>
                            <ul class="list-inline">
                                <li class="list-group-item text-center">
                                    <a href="#sciPapInsrMdl" data-toggle="modal" data-id-scientific-papers="<?php echo $sciPap->getIdScientificPapers(); ?>">
                                        <img src="/eArchive/custom/img/assignMentor.png" alt="Dodeli" class="men-ins-img" data-toggle="tooltip" title="Dodeli">
                                    </a>
                                </li>
                                <?php
                                foreach ($DBC->selectSciPapMentors($sciPap->getIdScientificPapers()) as $mentor) {
                                ?>
                                    <li class="list-group-item">
                                        <p class="d-flex justify-content-between">
                                            <span class="w-100 text-center"><?php echo $mentor->getMentor(); ?></span>
                                            <span class="w-100 text-center"><?php echo $mentor->faculty; ?></span>
                                        </p>
                                        <p class="d-flex justify-content-around">
                                            <a class="men-upd-a" href="#sciPapInsrMdl" data-toggle="modal" data-id-mentorings="<?php echo $mentor->getIdMentorings(); ?>">Uredi</a>
                                            <a class="men-del-a" href="#sciPapInsrMdl" data-id-mentorings="<?php echo $mentor->getIdMentorings(); ?>">Izbriši</a>
                                        </p>
                                    </li>

                                <?php
                                } // forach
                                ?>
                            </ul>
                        </td>
                        <td>
                            <ul class="list-inline">
                                <li class="list-group-item text-center">
                                    <a href="#sciPapInsrMdl" data-toggle="modal" data-id-scientific-papers="<?php echo $sciPap->getIdScientificPapers(); ?>">
                                        <img src="/eArchive/custom/img/upload.png" alt="Naloži" class="doc-ins-img" data-toggle="tooltip" title="Naloži">
                                    </a>
                                </li>
                                <?php
                                foreach ($DBC->selectDocuments($sciPap->getIdScientificPapers()) as $doc) {
                                ?>
                                    <li class="list-group-item d-flex justify-content-around">
                                        <a href="<?php echo "/eArchive/{$doc->getSource()}"; ?>" target="_blank"><?php echo $doc->getVersion(); ?></a>
                                        <a class="doc-del-a" href="#sciPapInsrMdl" data-source="<?php echo $doc->getSource(); ?>">Izbriši</a>
                                    </li>
                                <?php
                                } // forach
                                ?>
                            </ul>
                        </td>
                        <td>
                            <a href="#sciPapInsrMdl" data-toggle="modal" data-id-scientific-papers="<?php echo $sciPap->getIdScientificPapers(); ?>">
                                <img src="/eArchive/custom/img/updateRecord.png" alt="Uredi" class="sp-upd-img" data-toggle="tooltip" title="Uredi">
                            </a>
                        </td>
                        <td>
                            <img src="/eArchive/custom/img/deleteRecord.png" alt="Izbriši" class="sp-del-img" data-id-scientific-papers="<?php echo $sciPap->getIdScientificPapers(); ?>" data-toggle="tooltip" title="Izbriši">
                        </td>
                    </tr>
                <?php
                }  // foreach
                ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Link with the custom CSS -->
<link rel="stylesheet" href="/eArchive/custom/css/sciPapEvidence.css">

<?php

// script import declaration

include_once 'modals.php';

include_once '../../footer.php';

?>