<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once './ScientificPapers.php';
require_once '../Partakings/Partakings.php';
require_once '../Mentorings/Mentorings.php';
require_once '../Documents/Documents.php';

// proceed with the session
session_start();

// if topic searched for was passed by URL query string
if (isset($_GET['topic'])) {
    $topic = $_GET['topic'];
    // retrieve an instance of PDO holding database server connection
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // filter scientific papers by their topics
?>
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
            foreach ($DBC->selectSciPapsByTopic($topic) as $sciPap) {
            ?>
                <tr>
                    <td><span class="bg-warning"><?php echo $topic; ?></span><?php echo substr($sciPap->getTopic(), strlen($topic)); ?></td>
                    <td><?php echo $sciPap->getType(); ?></td>
                    <td><?php echo (new DateTime($sciPap->getWritten()))->format('d-m-Y'); ?></td>
                    <td>
                        <a class="par-ins-a" href="#sciPapInsrMdl" data-toggle="modal" data-id-scientific-papers="<?php echo $sciPap->getIdScientificPapers(); ?>">Dodeli</a>
                        <?php
                        foreach ($DBC->selectSciPapPartakers($sciPap->getIdScientificPapers()) as $partaker) {
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
                                    <?php echo "{$mentor->getMentor()} -> Fakulteta {$mentor->name}"; ?>
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
            } // foreach
            ?>
        </tbody>
    </table>
<?php
} // if
// if author was prosperously passed via URL query string
else if (isset($_GET['author'])) {
    $author = $_GET['author'];
    // retrieve new PDO object instance holding database connection
    $DBC = new DBC();
?>
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
            // select scientific achievements of the given author
            foreach ($DBC->selectSciPapsByAuthor($author) as $sciPap) {
            ?>
                <tr>
                    <td><?php echo $sciPap->getTopic(); ?></td>
                    <td>
                        <a class="stu-vw-a" href="#studtViewMdl" data-toggle="modal" data-id-attendances="<?php echo $sciPap->getIdAttendances(); ?>">
                            <span class="bg-warning"><?php echo substr($sciPap->author, 0, strlen($author)); ?></span><?php echo substr($sciPap->author, strlen($author)); ?>
                        </a>
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
        </tbody>
    </table>
<?php
} // else if
// if mentor was prosperously passed via URL query string
else if (isset($_GET['mentor'])) {
    $mentor = $_GET['mentor'];
    // retrieve new PDO object instance holding database connection
    $DBC = new DBC();
?>
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
            // select scientific achievements mentored by the given mentor
            foreach ($DBC->selectSciPapsByMentor($mentor) as $sciPap) {
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
                        <sup>
                            <a class="men-vw-a" href="#sciPapMenViewMdl" data-toggle="modal" data-id-scientific-papers="<?php echo $sciPap->getIdScientificPapers(); ?>" data-mentor="<?php echo $sciPap->mentor; ?>">Mentorji</a>
                        </sup>
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
        </tbody>
    </table>
<?php
} // else if
// if date of writing was prosperously passed via URL query string
else if (isset($_GET['written'])) {
    $written = $_GET['written'];
    // retrieve new PDO object instance holding database connection
    $DBC = new DBC();
?>
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
            // select scientific by the year of writing
            foreach ($DBC->selectSciPapsByYear($written) as $sciPap) {
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
                    <td><?php echo (new DateTime($sciPap->getWritten()))->format('d-m-'); ?>
                        <span class="bg-warning"><?php echo (new DateTime($sciPap->getWritten()))->format('Y') ?></span>
                    </td>
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
        </tbody>
    </table>
<?php
} // else if