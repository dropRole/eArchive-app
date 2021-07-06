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

$topic = $_GET['topic'];

// if topic searched for was passed by URL query string
if (isset($topic)) {
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
                        foreach ($DBC->selectPartakersOfScientificPaper($sciPap->getIdScientificPapers()) as $partaker) {
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
                        foreach ($DBC->selectMentorsOfScientificPaper($sciPap->getIdScientificPapers()) as $mentor) {
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