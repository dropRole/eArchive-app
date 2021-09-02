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
                $topic = $_GET['topic'];
                // if any scientific paper of the given topic
                if (count($DBC->selectScientificPapersByTopic($topic)))
                    // filter scientific papers by their topics
                    foreach ($DBC->selectScientificPapersByTopic($topic) as $scientificPaper) {
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
                                foreach ($DBC->selectPartakers($scientificPaper->getIdScientificPapers()) as $partaker) {
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
                                foreach ($DBC->selectMentors($scientificPaper->getIdScientificPapers()) as $mentor) {
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
                                        <a class="doc-del-a" data-source="<?php echo $document->getSource(); ?>">Izbriši</a>
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
                else {
                ?>
                    <tr>
                        <td colspan="9">
                            <p class="font-italic text-muted">Ni znanstvenih del z dano temo.</p>
                        </td>
                    </tr>
                <?php
                } // else
            } // if
            // if author searched for was passed
            else if (isset($_GET['author'])) {
                $author = $_GET['author'];
                // if any scientific paper written by the author
                if (count($DBC->selectScientificPapersByAuthor($author)))
                    // select scientific achievements of the given author
                    foreach ($DBC->selectScientificPapersByAuthor($author) as $scientificPaper) {
                ?>
                    <tr>
                        <td><?php echo $scientificPaper->getTopic(); ?></td>
                        <td>
                            <a class="stu-sel-a text-decoration-none" href="#stuSelMdl" data-toggle="modal" data-id-attendances="<?php echo $scientificPaper->getIdAttendances(); ?>">
                                <span class="bg-warning"><?php echo substr($scientificPaper->author, 0, strlen($author)); ?></span><?php echo substr($scientificPaper->author, strlen($author)); ?>
                            </a>
                            <?php
                            // if author had partakers in writting 
                            if (count($DBC->selectPartakers($scientificPaper->getIdScientificPapers()))) {
                            ?>
                                <sup><a class="par-sel-a text-decoration-none" href="#partSelMdl" data-toggle="modal" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>">Soavtorji</a></sup>
                            <?php
                            }
                            ?>
                        </td>
                        <td><?php echo $scientificPaper->getType(); ?></td>
                        <td><?php echo (new DateTime($scientificPaper->getWritten()))->format('d-m-Y'); ?></td>
                        <td>
                            <a class="doc-sel-a" href="#docSelMdl" data-toggle="modal">
                                <img src="/eArchive/custom/img/previewSciPapers.png" alt="Pregled" class="doc-sel-img" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-toggle="tooltip" title="Pregled">
                            </a>
                        </td>
                        <td>
                            <?php
                            // if graduated on the scientific paper
                            if ($scientificPaper->id_certificates != NULL) {
                            ?>
                                <a class="cert-sel-a" href="#certSelMdl" data-toggle="modal">
                                    <img src="/eArchive/custom/img/previewCertificate.png" alt="Pregled" class="cert-sel-img" data-id-attendances="<?php echo $scientificPaper->getIdAttendances(); ?>" data-toggle="tooltip" title="Pregled">
                                </a>
                            <?php
                            } // if
                            ?>
                        </td>
                    </tr>
                <?php
                    } // foreach
                else {
                ?>
                    <tr>
                        <td colspan="6">
                            <p class="font-italic text-muted">Ni znanstvenih del danega avtorja.</p>
                        </td>
                    </tr>
                <?php
                } // if
            } // else if
            // if mentor searched for is succesfully passed
            else if (isset($_GET['mentor'])) {
                $mentor = $_GET['mentor'];
                // if any scientific paper mentored by 
                if (count($DBC->selectScientificPapersByMentor($mentor)))
                    // select scientific achievements mentored by the given mentor
                    foreach ($DBC->selectScientificPapersByMentor($mentor) as $scientificPaper) {
                ?>
                    <tr>
                        <td><?php echo $scientificPaper->getTopic(); ?></td>
                        <td>
                            <a class="stu-sel-a text-decoration-nonw" href="#stuSelMdl" data-toggle="modal" data-id-attendances="<?php echo $scientificPaper->getIdAttendances(); ?>"><?php echo $scientificPaper->author; ?></a>
                            <?php
                            // if author had partakers in writting 
                            if (count($DBC->selectPartakers($scientificPaper->getIdScientificPapers()))) {
                            ?>
                                <sup><a class="par-sel-a text-decoration-none" href="#partSelMdl" data-toggle="modal" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>">Soavtorji</a></sup>
                            <?php
                            }
                            ?>
                        </td>
                        <td><?php echo $scientificPaper->getType(); ?></td>
                        <td><?php echo (new DateTime($scientificPaper->getWritten()))->format('d-m-Y'); ?></td>
                        <td>
                            <a class="text-decoration-none" href="#docSelMdl" data-toggle="modal">
                                <img src="/eArchive/custom/img/previewSciPapers.png" alt="Pregled" class="doc-sel-img" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-toggle="tooltip" title="Pregled">
                            </a>
                            <sup>
                                <a class="men-sel-a text-decoration-none" href="#mentSelMdl" data-toggle="modal" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-mentor="<?php echo $scientificPaper->mentor; ?>">Mentorji</a>
                            </sup>
                        </td>
                        <td>
                            <?php
                            // if graduated on the scientific paper
                            if ($scientificPaper->id_certificates != NULL) {
                            ?>
                                <a class="cert-sel-a" href="#certSelMdl" data-toggle="modal">
                                    <img src="/eArchive/custom/img/previewCertificate.png" alt="Pregled" class="cert-sel-img" data-id-attendances="<?php echo $scientificPaper->getIdAttendances(); ?>" data-toggle="tooltip" title="Pregled">
                                </a>
                            <?php
                            } // if
                            ?>
                        </td>
                    </tr>
                <?php
                    } // foreach
                else {
                ?>
                    <tr>
                        <td colspan="6">
                            <p class="font-italic text-muted">Ni znanstvenih del mentoriranih s strani danega mentorja.</p>
                        </td>
                    </tr>
                <?php
                }
            } // else if
            // if date of writing searched for is prosperously passed
            else if (isset($_GET['written'])) {
                $written = $_GET['written'];
                // if any scientific paper written at the given year
                if (count($DBC->selectScientificPapersByYear($written)))
                    // select scientific by the year of writing
                    foreach ($DBC->selectScientificPapersByYear($written) as $scientificPaper) {
                ?>
                    <tr>
                        <td><?php echo $scientificPaper->getTopic(); ?></td>
                        <td>
                            <a class="stu-sel-a text-decoration-none" href="#stuSelMdl" data-toggle="modal" data-id-attendances="<?php echo $scientificPaper->getIdAttendances(); ?>"><?php echo $scientificPaper->author; ?></a>
                            <?php
                            // if author had partakers in writting 
                            if (count($DBC->selectPartakers($scientificPaper->getIdScientificPapers()))) {
                            ?>
                                <sup><a class="par-sel-a text-decoration-none" href="#partSelMdl" data-toggle="modal" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>">Soavtorji</a></sup>
                            <?php
                            }
                            ?>
                        </td>
                        <td><?php echo $scientificPaper->getType(); ?></td>
                        <td><?php echo (new DateTime($scientificPaper->getWritten()))->format('d-m-'); ?>
                            <span class="bg-warning"><?php echo (new DateTime($scientificPaper->getWritten()))->format('Y') ?></span>
                        </td>
                        <td>
                            <a class="doc-sel-a" href="#docSelMdl" data-toggle="modal">
                                <img src="/eArchive/custom/img/previewSciPapers.png" alt="Pregled" class="doc-sel-img" data-id-scientific-papers="<?php echo $scientificPaper->getIdScientificPapers(); ?>" data-toggle="tooltip" title="Pregled">
                            </a>
                        </td>
                        <td>
                            <?php
                            // if graduated on the scientific paper
                            if ($scientificPaper->id_certificates != NULL) {
                            ?>
                                <a class="cert-sel-a" href="#certSelMdl" data-toggle="modal">
                                    <img src="/eArchive/custom/img/previewCertificate.png" alt="Pregled" class="cert-sel-img" data-id-attendances="<?php echo $scientificPaper->getIdAttendances(); ?>" data-toggle="tooltip" title="Pregled">
                                </a>
                            <?php
                            } // if
                            ?>
                        </td>
                    </tr>
                <?php
                    } // foreach
                else {
                ?>
                    <tr>
                        <td colspan="6">
                            <p class="font-italic text-muted">Ni znanstvenih del napisanih v danem letu.</p>
                        </td>
                    </tr>
            <?php
                }
            } // else if
            ?>
        </tbody>
    </table>
<?php
} // if