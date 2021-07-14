<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once './DBC/DBC.php';
include_once './header.php';
include_once './ScientificPapers/ScientificPapers.php';
include_once './Partakings/Partakings.php';

$DBC = new DBC();

include_once './nav.php';

?>
<h2 class="text-center">Digitalna arhiva znanstvenih dosežkov</h2>

<div class="container">
    <div class="input-group">
        <input id="searchInptEl" type="text" class="form-control" data-criterion="author" placeholder="Ime in priimek avtorja dela" aria-label="Text input with segmented dropdown button">
        <div class="input-group-append">
            <button type="button" class="btn btn-dark">Pregled</button>
            <button type="button" class="btn btn-dark dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#" data-criterion="author" data-placeholder="Ime in priimek avtorja dela">Po avtrojih</a>
                <a class="dropdown-item" href="#" data-criterion="mentor" data-placeholder="Ime in priimek mentorja dela">Po mentorjih</a>
                <a class="dropdown-item" href="#" data-criterion="pubslihed" data-placeholder="Leto objave dela">Po letih objave</a>
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

            <body>
                <?php
                foreach ($DBC->selectSciPaps() as $sciPap) {
                ?>
                    <tr>
                        <td><?php echo $sciPap->getTopic(); ?></td>
                        <td>
                            <a class="stu-vw-a" href="#studtViewMdl" data-toggle="modal" data-id-attendances="<?php echo $sciPap->getIdAttendances(); ?>"><?php echo $sciPap->author; ?></a>
                            <?php
                            // if author had partakers in writting 
                            if (count($DBC->selectSciPapPartakers($sciPap->getIdScientificPapers()))) {
                            ?>
                                <sup><a class="par-vw-a" href="#sciPapPrtViewMdl" data-toggle="modal" data-id-scientific-papers="<?php echo $sciPap->getIdScientificPapers(); ?>">Soavtorji</a></sup>
                            <?php
                            }
                            ?>
                        </td>
                        <td><?php echo $sciPap->getType(); ?></td>
                        <td><?php echo (new DateTime($sciPap->getWritten()))->format('d-m-Y'); ?></td>
                        <td>
                            <a class="doc-vw-a" href="#sciPapDocsViewMdl" data-toggle="modal" data-id-scientific-papers="<?php echo $sciPap->getIdScientificPapers(); ?>">Pregled</a>
                        </td>
                        <td>
                            <?php
                            // if graduated on the scientific paper
                            if ($sciPap->id_certificates != NULL) {
                            ?>
                                <a class="cert-vw-a" href="#gradCertViewMdl" data-toggle="modal" data-id-attendances="<?php echo $sciPap->getIdAttendances(); ?>">Pregled</a>
                            <?php
                            } // if
                            ?>
                        </td>
                    </tr>
                <?php
                } // foreach
                ?>
            </body>
        </table>
    </div>
</div>
<script src="./custom/js/index.js"></script>
<link rel="stylesheet" href="./custom/css/index.css"> 
<?php

// script import declaration

include_once './modals.php';
include_once './footer.php';

?>