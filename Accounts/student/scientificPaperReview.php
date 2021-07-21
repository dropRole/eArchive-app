<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../../autoload.php';
include_once '../../header.php';

$DBC = new DBC($_SESSION['user'], $_SESSION['pass']);

include_once '../../nav.php';

?>
<section class="container p-3">
    <p class="h2">Evidenca znanstvenih del</p>
    <div class="d-flex">
        <div class="w-25">
            <input id="fltrInputEl" class="form-control" type="text" placeholder="Predmet">
        </div>
        <div class="w-25">
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
                            <a class="par-ins-a" href="#sciPapInsrMdl" data-toggle="modal" data-id-scientific-papers="<?php echo $sciPap->getIdScientificPapers(); ?>">Dodeli</a>
                            <?php
                            foreach ($DBC->selectPartakings($sciPap->getIdScientificPapers()) as $partaker) {
                            ?>
                                <ul class="list-inline">
                                    <li class="list-group-item">
                                        <?php echo "{$partaker->fullname} -> Indeks {$partaker->index}"; ?>
                                        <a class="par-upd-a" href="#sciPapInsrMdl" data-toggle="modal" data-id-partakings="<?php echo $partaker->getIdPartakings(); ?>" data-index="<?php echo $partaker->index; ?>" data-part="<?php echo $partaker->getPart(); ?>">Uredi</a>
                                        <a class="par-del-a" href="#sciPapInsrMdl" data-id-partakings="<?php echo $partaker->getIdPartakings(); ?>">Izbriši</a>
                                    </li>
                                </ul>
                            <?php
                            } // forach
                            ?>
                        </td>
                        <td>
                            <a class="men-ins-a" href="#sciPapInsrMdl" data-toggle="modal" data-id-scientific-papers="<?php echo $sciPap->getIdScientificPapers(); ?>">Dodeli</a>
                            <?php
                            foreach ($DBC->selectSciPapMentors($sciPap->getIdScientificPapers()) as $mentor) {
                            ?>
                                <ul class="list-inline">
                                    <li class="list-group-item">
                                        <?php echo "{$mentor->getMentor()} -> Fakulteta {$mentor->faculty}"; ?>
                                        <a class="men-upd-a" href="#sciPapInsrMdl" data-toggle="modal" data-id-mentorings="<?php echo $mentor->getIdMentorings(); ?>">Uredi</a>
                                        <a class="men-del-a" href="#sciPapInsrMdl" data-id-mentorings="<?php echo $mentor->getIdMentorings(); ?>">Izbriši</a>
                                    </li>
                                </ul>
                            <?php
                            } // forach
                            ?>
                        </td>
                        <td>
                            <a class="doc-upl-a" href="#sciPapInsrMdl" data-toggle="modal" data-id-scientific-papers="<?php echo $sciPap->getIdScientificPapers(); ?>">Naloži</a>
                            <?php
                            foreach ($DBC->selectDocuments($sciPap->getIdScientificPapers()) as $doc) {
                            ?>
                                <ul class="list-inline">
                                    <li class="list-group-item">
                                        <?php echo "{$doc->getVersion()} -> Objavljen {$doc->getPublished()}"; ?>
                                        <a class="doc-del-a" href="#sciPapInsrMdl" data-source="<?php echo $doc->getSource(); ?>">Izbriši</a>
                                    </li>
                                </ul>
                            <?php
                            } // forach
                            ?>
                        </td>
                        <td>
                            <a class="sp-upd-а" href="#sciPapInsrMdl" data-toggle="modal" data-id-scientific-papers="<?php echo $sciPap->getIdScientificPapers(); ?>">Uredi</a>
                        </td>
                        <td>
                            <a class="sp-del-a" href="#" data-id-scientific-papers="<?php echo $sciPap->getIdScientificPapers(); ?>">Izbriši</a>
                        </td>
                    </tr>
                <?php
                }  // foreach
                ?>
            </tbody>
        </table>
    </div>
</section>
<?php

// script import declaration

include_once 'modals.php';

?>
<!-- Custom core JavaScript -->
<script src="../../custom/js/scientificPaperReview.js"></script>
<?php

// script import declaration

include_once '../../footer.php';

?>