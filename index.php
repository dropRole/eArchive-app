<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once './autoload.php';
include_once './header.php';

$DBC = new DBC();

include_once './nav.php';

?>

<!-- Deferred load of JS script -->
<script defer src="./custom/js/index.js"></script>

<div class="container my-5">
    <div class="h2 mb-4 p-4 text-center">
        Digitalna arhiva<br>znanstvenih dosežkov
        <div class="heading-border-top-left"></div>
        <div class="heading-border-bottom-right"></div>
    </div>
    <div class="input-group mb-2">
        <input id="searchInptEl" type="text" class="form-control" data-criterion="author" placeholder="Ime in priimek avtorja dela" aria-label="Text input with segmented dropdown button">
        <div class="input-group-append">
            <button type="button" class="btn btn-dark">Pregled</button>
            <button type="button" class="btn btn-dark dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#div.dropdown-menu" data-criterion="author" data-placeholder="Ime in priimek avtorja dela">Po avtrojih</a>
                <a class="dropdown-item" href="#div.dropdown-menu" data-criterion="mentor" data-placeholder="Ime in priimek mentorja dela">Po mentorjih</a>
                <a class="dropdown-item" href="#div.dropdown-menu" data-criterion="written" data-placeholder="Leto pisanja dela">Po letu pisanja</a>
            </div>
        </div>
    </div>
    <div id="sciPapSrchRslt" class="table-responsive">
        <table class="table table-hover">
            <caption>EVIDENCA ZNANSTVENIH DOSEŽKOV</caption>
            <thead class="thead-dark">
                <tr>
                    <th>Predmet</th>
                    <th>Avtor</th>
                    <th>Vrsta</th>
                    <th>Napisano</th>
                    <th>Dokumenti</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($DBC->selectScientificPapers() as $sciPap) {
                ?>
                    <tr>
                        <td><?php echo $sciPap->getTopic(); ?></td>
                        <td>
                            <a class="stu-vw-a text-decoration-none" href="#studtViewMdl" data-toggle="modal" data-id-attendances="<?php echo $sciPap->getIdAttendances(); ?>"><?php echo $sciPap->author; ?></a>
                            <?php
                            // if author had partakers in writting 
                            if (count($DBC->selectPartakers($sciPap->getIdScientificPapers()))) {
                            ?>
                                <sup><a class="par-vw-a text-decoration-none" href="#sciPapPrtViewMdl" data-toggle="modal" data-id-scientific-papers="<?php echo $sciPap->getIdScientificPapers(); ?>">Soavtorji</a></sup>
                            <?php
                            }
                            ?>
                        </td>
                        <td><?php echo $sciPap->getType(); ?></td>
                        <td><?php echo (new DateTime($sciPap->getWritten()))->format('d-m-Y'); ?></td>
                        <td>
                            <a href="#sciPapDocsViewMdl" data-toggle="modal">
                            <img src="/eArchive/custom/img/previewSciPapers.png" alt="Pregled"  class="doc-vw-img" data-id-scientific-papers="<?php echo $sciPap->getIdScientificPapers(); ?>" data-toggle="tooltip" title="Pregled">
                        </a>
                        </td>
                        <td>
                            <?php
                            // if graduated on the scientific paper
                            if ($sciPap->id_certificates != NULL) {
                            ?>
                                <a class="cert-vw-a" href="#gradCertViewMdl" data-toggle="modal">
                            <img src="/eArchive/custom/img/previewCertificate.png" alt="Pregled" class="cert-vw-img" data-id-attendances="<?php echo $sciPap->getIdAttendances(); ?>" data-toggle="tooltip" title="Pregled">
                            </a>
                            <?php
                            } // if
                            ?>
                        </td>
                    </tr>
                <?php
                } // foreach
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add custom stylesheet --> 
<link rel="stylesheet" href="./custom/css/index.css"> 

<?php

// script import declaration

include_once './modals.php';
include_once './footer.php';

?>