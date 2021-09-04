<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../../autoload.php';
include_once '../../header.php';

$DBC = new DBC($_SESSION['user'], $_SESSION['pass']);

include_once '../../nav.php';

?>

<!-- Link with the custom CSS -->
<link rel="stylesheet" href="/eArchive/custom/css/sciPapEvid.css">

<!-- Custom core JavaScript -->
<script defer src="../../custom/js/sciPapEvid.js"></script>

<section class="container my-3 p-3">
    <p class="h2 my-3 text-center">Evidenca znanstvenih del</p>
    <div class="d-lg-flex justify-content-lg-between">
        <div>
            <input id="fltInpEl" class="form-control" type="text" placeholder="Predmet">
        </div>
        <div>
            <button id="sciPapInsBtn" class="btn btn-primary" data-toggle="modal" data-target="
            #sciPapInsMdl">Vstavi delo</button>
        </div>
    </div>
    <button id="repMdlBtn" class="d-none" type="button" data-toggle="modal" data-target="#reportModal"></button>
    <div class="table-responsive mt-3">
        <table class="table">
            <caption>Zapisi znanstvenih del</caption>
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
                foreach ($DBC->selectScientificPapersByIndex($_SESSION['index']) as $scientificPaper) {
                ?>
                    <tr>
                        <td><?php echo $scientificPaper->getTopic(); ?></td>
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
                ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Modal for inserting data concerning scientific paper and uploading its documents -->
<div class="modal fade" id="sciPapInsMdl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLongTitle">Vstavljanje znanstvenega dela</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form id="sciPapInsFrm">
                    <input type="hidden" name="id_attendances" value="<?php echo $DBC->selectStudentsByIndex($_SESSION['index'])[0]->id_attendances; ?>">
                    <div id="particulars" class="row">
                        <div class="form-group col-12">
                            <label class="w-100">Predmet
                                <input class="form-control" type="text" name="topic" required>
                            </label>
                        </div>
                        <div class="form-group col-6">
                            <label class="w-100">Vrsta
                                <select class="form-control" name="type">
                                    <option value="DOKTORSKO DELO">Doktorsko delo</option>
                                    <option value="MAGISTRSKO DELO">Magistrsko delo</option>
                                    <option value="DIPLOMSKO DELO">Diplomsko delo</option>
                                    <option value="RAZISKOVALNO DELO">Raziskovalno delo</option>
                                    <option value="SEMINARSKO DELO">Seminarsko delo</option>
                                </select>
                            </label>
                        </div>
                        <div class="form-group col-6">
                            <label class="w-100">Napisano
                                <input class="form-control" type="date" name="written" required>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div id="partakers" class="col-lg-6 col-12 mb-3">
                            <p class="h6"><strong>Soavtorji</strong></p>
                            <div class="d-flex justify-content-center col-12">
                                <button id="addPartaker" type="button" data-toggle="tooltip" title="Dodeli"></button>
                            </div>
                            <datalist id="studentDatalist">
                                <?php
                                // denote student as potential partaker on a scientific paper
                                foreach ($DBC->selectStudents() as $student) {
                                    // exclude currently logged in student
                                    if ($student->index == $_SESSION['index']) {
                                ?>
                                        <option value="<?php echo $student->index; ?>"><?php echo $student->fullname; ?></option>
                                <?php
                                    } // if
                                } // foreach
                                ?>
                            </datalist>
                        </div>
                        <div id="mentors" class="col-lg-6 col-12 mb-3">
                            <p class="h6"><strong>Mentorji</strong></p>
                            <div class="d-flex justify-content-center col-12">
                                <button id="addMentor" type="button" data-toggle="tooltip" title="Dodeli"></button>
                            </div>
                        </div>
                    </div>
                    <div id="documents" class="mb-3">
                        <p class="h6"><strong>Dokumentacija</strong></p>
                        <div class="row">
                            <div class="form-group col-6">
                                <label class="w-100">Verzija
                                    <input class="form-control" type="text" name="documents[0][version]" required>
                                </label>
                            </div>
                            <div class="form-group col-6">
                                <input type="hidden" name="documents[0][name]" value="">
                                <label class="w-100 file-label">Dokument
                                    <input type="file" name="document[]" accept=".pdf" required>
                                </label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center col-12">
                            <button id="addDocument" type="button" data-toggle="tooltip" title="Dodaj"></button>
                        </div>
                    </div>
                    <input class="btn btn-warning offset-lg-5 offset-3 col-lg-2 col-6" type="submit" value="Dodaj">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal for uploading account avatar -->
<div class="modal fade" id="avtrUplMdl" tabindex="-1" role="dialog" aria-labelledby="reportMdl" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div id="avatar">
                <img src="<?php echo $DBC->hasAccountAvatar($_SESSION['index']) ? "/eArchive/{$DBC->hasAccountAvatar($_SESSION['index'])}"  : '/eArchive/custom/img/defaultAvatar.png'; ?>">
            </div>
            <form id="avtrUplFrm">
                <div class="form-group d-flex flex-column align-items-center">
                    <input type="hidden" name="id_attendances" value="<?php echo $DBC->selectStudentsByIndex($_SESSION['index'])[0]->id_attendances; ?>">
                    <label class="file-label w-50">Avatar
                        <input type="file" name="avatar" accept=".jpg" required>
                    </label>
                    <input class="btn btn-warning w-50" type="submit" value="Naloži">
                </div>
            </form>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<!-- Modal for reporting on performed operations -->
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportMdl" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Poročilo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<?php

// script import declaration

include_once '../../footer.php';

?>