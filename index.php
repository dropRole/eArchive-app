<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once './autoload.php';
include_once './header.php';

$DBC = new DBC();

include_once './nav.php';

?>

<!-- Add custom stylesheet --> 
<link rel="stylesheet" href="./custom/css/index.css"> 

<!-- Deferred load of JS script -->
<script defer src="./custom/js/index.js"></script>

<div class="container my-5">
    <div class="h2 mb-4 p-4 text-center">
        Digitalni arhiv<br>znanstvenih dosežkov
        <div class="heading-border-top-left"></div>
        <div class="heading-border-bottom-right"></div>
    </div>
    <div class="input-group mb-2">
        <input id="search" type="text" class="form-control" data-criterion="author" placeholder="Ime in priimek avtorja dela" aria-label="Text input with segmented dropdown button">
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
            <caption>Evidenca znanstvenih dosežkov</caption>
            <thead>
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
                foreach ($DBC->selectScientificPapers() as $scientificPaper) {
                ?>
                    <tr>
                        <td><?php echo $scientificPaper->getTopic(); ?></td>
                        <td>
                            <a class="stu-vw-a text-decoration-none" href="#stuSelMdl" data-toggle="modal" data-id-attendances="<?php echo $scientificPaper->getIdAttendances(); ?>"><?php echo $scientificPaper->author; ?></a>
                            <?php
                            // if author had partakers in writting 
                            if (count($DBC->selectPartakers($scientificPaper->getIdScientificPapers()))) {
                            ?>
                                <sup><a class="par-vw-a text-decoration-none" href="#sciPapInsMdl" data-toggle="modal" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>">Soavtorji</a></sup>
                            <?php
                            }
                            ?>
                        </td>
                        <td><?php echo $scientificPaper->getType(); ?></td>
                        <td><?php echo (new DateTime($scientificPaper->getWritten()))->format('d-m-Y'); ?></td>
                        <td>
                            <a href="#sciPapSelMdl" data-toggle="modal">
                            <img src="/eArchive/custom/img/previewSciPapers.png" alt="Pregled"  class="doc-vw-img" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-toggle="tooltip" title="Pregled">
                        </a>
                        </td>
                        <td>
                            <?php
                            // if graduated on the scientific paper
                            if ($scientificPaper->id_certificates != NULL) {
                            ?>
                                <a href="#certSelMdl" data-toggle="modal">
                            <img src="/eArchive/custom/img/previewCertificate.png" alt="Pregled" class="cert-vw-img" data-id-attendances="<?php echo $scientificPaper->getIdAttendances(); ?>" data-toggle="tooltip" title="Pregled">
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

<!-- Modal for scientific paper partakers view -->
<div class="modal fade" id="sciPapInsMdl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Soavtroji dela</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
            </div>
        </div>
    </div>
</div>
<!-- Modal for scientific paper document view -->
<div class="modal fade" id="sciPapSelMdl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Dokumenta dela</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
            </div>
        </div>
    </div>
</div>
<!-- Modal for graduation certificate view -->
<div class="modal fade" id="certSelMdl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
            </div>
        </div>
    </div>
</div>
<!-- Modal for student particulars view -->
<div class="modal fade" id="stuSelMdl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
            
            </div>
        </div>
    </div>
</div>
<!-- Modal for scientific paper mentor view -->
<div class="modal fade" id="mentSelMdl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
            
            </div>
        </div>
    </div>
</div>

<?php

// script import declaration

include_once './footer.php';

?>