<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../autoload.php';

// proceed with the session
session_start();

// retrieve an instance of PDO holding database server connection
$DBC = new DBC();

// if superglobal $_GET is composed of URLs query string criterion variables 
if (!empty($_GET)) {
?>
    <table class="table">
        <tbody>
            <?php
            // if topic searched for was passed
            if (isset($_GET['topic'])) {
                // filter scientific papers by their topics
                $topic = $_GET['topic'];
                foreach ($DBC->selectSciPapsByTopic($topic) as $scientificPaper) {
            ?>
                    <tr>
                        <td><span class="bg-warning"><?php echo substr($scientificPaper->getTopic(), 0, strlen($topic)); ?></span><?php echo substr($scientificPaper->getTopic(), strlen($topic)); ?></td>
                        <td><?php echo $scientificPaper->getType(); ?></td>
                        <td><?php echo (new DateTime($scientificPaper->getWritten()))->format('d-m-Y'); ?></td>
                        <td>
                            <ul class="list-inline">
                                <li class="list-group-item text-center">
                                    <a href="#sciPapInsMdl" data-toggle="modal">
                                        <img class="par-ins-img" src="/eArchive/custom/img/assignPartaker.png" alt="Dodeli" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-toggle="tooltip" title="Dodeli">
                                    </a>
                                </li>
                                <?php
                                foreach ($DBC->selectPartakings($scientificPaper->getIdScientificPapers()) as $partaker) {
                                ?>
                                    <li class="list-group-item">
                                        <p class="d-flex justify-content-between">
                                            <span class="w-100 text-center"><?php echo $partaker->fullname; ?></span>
                                            <span class="w-100 text-center"><?php echo $partaker->getPart(); ?></span>
                                        </p>
                                        <p class="d-flex justify-content-around">
                                            <a class="par-upd-a" href="#sciPapInsMdl" data-toggle="modal" data-id-partakings="<?php echo $partaker->getIdPartakings(); ?>" data-index="<?php echo $partaker->index; ?>" data-part="<?php echo $partaker->getPart(); ?>">Uredi</a>
                                            <a class="par-del-a" data-id-partakings="<?php echo $partaker->getIdPartakings(); ?>">Izbriši</a>
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
                                    <a href="#sciPapInsMdl" data-toggle="modal">
                                        <img src="/eArchive/custom/img/assignMentor.png" alt="Dodeli" class="men-ins-img" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-toggle="tooltip" title="Dodeli">
                                    </a>
                                </li>
                                <?php
                                foreach ($DBC->selectSciPapMentors($scientificPaper->getIdScientificPapers()) as $mentor) {
                                ?>
                                    <li class="list-group-item">
                                        <p class="d-flex justify-content-between">
                                            <span class="w-100 text-center"><?php echo $mentor->getMentor(); ?></span>
                                            <span class="w-100 text-center"><?php echo $mentor->faculty; ?></span>
                                        </p>
                                        <p class="d-flex justify-content-around">
                                            <a class="men-upd-a" href="#sciPapInsMdl" data-toggle="modal" data-id-mentorings="<?php echo $mentor->getIdMentorings(); ?>">Uredi</a>
                                            <a class="men-del-a" data-id-mentorings="<?php echo $mentor->getIdMentorings(); ?>">Izbriši</a>
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
                                    <a href="#sciPapInsMdl" data-toggle="modal">
                                        <img src="/eArchive/custom/img/upload.png" alt="Naloži" class="doc-upl-img" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-toggle="tooltip" title="Naloži">
                                    </a>
                                </li>
                                <?php
                                foreach ($DBC->selectDocuments($scientificPaper->getIdScientificPapers()) as $document) {
                                ?>
                                    <li class="list-group-item d-flex justify-content-around">
                                        <a href="<?php echo "/eArchive/{$document->getSource()}"; ?>" target="_blank"><?php echo $document->getVersion(); ?></a>
                                        <a class="doc-del-a" href="#sciPapInsMdl" data-source="<?php echo $document->getSource(); ?>">Izbriši</a>
                                    </li>
                                <?php
                                } // forach
                                ?>
                            </ul>
                        </td>
                        <td>
                            <a href="#sciPapInsMdl" data-toggle="modal">
                                <img src="/eArchive/custom/img/updateRecord.png" alt="Uredi" class="sp-upd-img" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-toggle="tooltip" title="Uredi">
                            </a>
                        </td>
                        <td>
                            <img src="/eArchive/custom/img/deleteDocument.png" alt="Izbriši" class="sp-del-img" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-toggle="tooltip" title="Izbriši">
                        </td>
                    </tr>
                <?php
                }  // foreach
            } // if
            // if author searched for was passed
            else if (isset($_GET['author'])) {
                $author = $_GET['author'];
                // select scientific achievements of the given author
                foreach ($DBC->selectSciPapsByAuthor($author) as $scientificPaper) {
                ?>
                    <tr>
                        <td><?php echo $scientificPaper->getTopic(); ?></td>
                        <td>
                            <a class="stu-vw-a text-decoration-none" href="#studtViewMdl" data-toggle="modal" data-id-attendances="<?php echo $scientificPaper->getIdAttendances(); ?>">
                                <span class="bg-warning"><?php echo substr($scientificPaper->author, 0, strlen($author)); ?></span><?php echo substr($scientificPaper->author, strlen($author)); ?>
                            </a>
                            <?php
                            // if author had partakers in writting 
                            if (count($DBC->selectPartakings($scientificPaper->getIdScientificPapers()))) {
                            ?>
                                <sup><a class="par-vw-a text-decoration-none" href="#sciPapPrtViewMdl" data-toggle="modal" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>">Soavtorji</a></sup>
                            <?php
                            }
                            ?>
                        </td>
                        <td><?php echo $scientificPaper->getType(); ?></td>
                        <td><?php echo (new DateTime($scientificPaper->getWritten()))->format('d-m-Y'); ?></td>
                        <td>
                            <a class="doc-vw-a" href="#sciPapDocsViewMdl" data-toggle="modal">
                                <img src="/eArchive/custom/img/previewSciPapers.png" alt="Pregled" class="doc-vw-img" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-toggle="tooltip" title="Pregled">
                            </a>
                        </td>
                        <td>
                            <?php
                            // if graduated on the scientific paper
                            if ($scientificPaper->id_certificates != NULL) {
                            ?>
                                <a class="cert-vw-a" href="#gradCertViewMdl" data-toggle="modal">
                                    <img src="/eArchive/custom/img/previewCertificate.png" alt="Pregled" class="cert-vw-img" data-id-attendances="<?php echo $scientificPaper->getIdAttendances(); ?>" data-toggle="tooltip" title="Pregled">
                                </a>
                            <?php
                            } // if
                            ?>
                        </td>
                    </tr>
                <?php
                } // foreach
            } // else if
            // if mentor searched for is succesfully passed
            else if (isset($_GET['mentor'])) {
                $mentor = $_GET['mentor'];
                // select scientific achievements mentored by the given mentor
                foreach ($DBC->selectSciPapsByMentor($mentor) as $scientificPaper) {
                ?>
                    <tr>
                        <td><?php echo $scientificPaper->getTopic(); ?></td>
                        <td>
                            <a class="stu-vw-a text-decoration-nonw" href="#studtViewMdl" data-toggle="modal" data-id-attendances="<?php echo $scientificPaper->getIdAttendances(); ?>"><?php echo $scientificPaper->author; ?></a>
                            <?php
                            // if author had partakers in writting 
                            if (count($DBC->selectPartakings($scientificPaper->getIdScientificPapers()))) {
                            ?>
                                <sup><a class="par-vw-a text-decoration-none" href="#sciPapPrtViewMdl" data-toggle="modal" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>">Soavtorji</a></sup>
                            <?php
                            }
                            ?>
                        </td>
                        <td><?php echo $scientificPaper->getType(); ?></td>
                        <td><?php echo (new DateTime($scientificPaper->getWritten()))->format('d-m-Y'); ?></td>
                        <td>
                            <a class="text-decoration-none" href="#sciPapDocsViewMdl" data-toggle="modal">
                                <img src="/eArchive/custom/img/previewSciPapers.png" alt="Pregled" class="doc-vw-img" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-toggle="tooltip" title="Pregled">
                            </a>
                            <sup>
                                <a class="men-vw-a text-decoration-none" href="#sciPapMenViewMdl" data-toggle="modal" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-mentor="<?php echo $scientificPaper->mentor; ?>">Mentorji</a>
                            </sup>
                        </td>
                        <td>
                            <?php
                            // if graduated on the scientific paper
                            if ($scientificPaper->id_certificates != NULL) {
                            ?>
                                <a class="cert-vw-a" href="#gradCertViewMdl" data-toggle="modal">
                                    <img src="/eArchive/custom/img/previewCertificate.png" alt="Pregled" class="cert-vw-img" data-id-attendances="<?php echo $scientificPaper->getIdAttendances(); ?>" data-toggle="tooltip" title="Pregled">
                                </a>
                            <?php
                            } // if
                            ?>
                        </td>
                    </tr>
                <?php
                } // foreach
            } // else if
            // if date of writing searched for is prosperously passed
            else if (isset($_GET['written'])) {
                $written = $_GET['written'];
                // select scientific by the year of writing
                foreach ($DBC->selectSciPapsByYear($written) as $scientificPaper) {
                ?>
                    <tr>
                        <td><?php echo $scientificPaper->getTopic(); ?></td>
                        <td>
                            <a class="stu-vw-a text-decoration-none" href="#studtViewMdl" data-toggle="modal" data-id-attendances="<?php echo $scientificPaper->getIdAttendances(); ?>"><?php echo $scientificPaper->author; ?></a>
                            <?php
                            // if author had partakers in writting 
                            if (count($DBC->selectPartakings($scientificPaper->getIdScientificPapers()))) {
                            ?>
                                <sup><a class="par-vw-a text-decoration-none" href="#sciPapPrtViewMdl" data-toggle="modal" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>">Soavtorji</a></sup>
                            <?php
                            }
                            ?>
                        </td>
                        <td><?php echo $scientificPaper->getType(); ?></td>
                        <td><?php echo (new DateTime($scientificPaper->getWritten()))->format('d-m-'); ?>
                            <span class="bg-warning"><?php echo (new DateTime($scientificPaper->getWritten()))->format('Y') ?></span>
                        </td>
                        <td>
                            <a class="doc-vw-a" href="#sciPapDocsViewMdl" data-toggle="modal">
                                <img src="/eArchive/custom/img/previewSciPapers.png" alt="Pregled" class="doc-vw-img" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-toggle="tooltip" title="Pregled">
                            </a>
                        </td>
                        <td>
                            <?php
                            // if graduated on the scientific paper
                            if ($scientificPaper->id_certificates != NULL) {
                            ?>
                                <a class="cert-vw-a" href="#gradCertViewMdl" data-toggle="modal">
                                    <img src="/eArchive/custom/img/previewCertificate.png" alt="Pregled" class="cert-vw-img" data-id-attendances="<?php echo $scientificPaper->getIdAttendances(); ?>" data-toggle="tooltip" title="Pregled">
                                </a>
                            <?php
                            } // if
                            ?>
                        </td>
                    </tr>
            <?php
                } // foreach
            } // else if
            ?>
        </tbody>
    </table>
<?php
} // if