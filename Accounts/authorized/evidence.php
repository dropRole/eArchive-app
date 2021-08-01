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
<script defer src="/eArchive/custom/js/evidence.js"></script>

<section class="container p-3">
    <p class="h2 my-3">Evidenca študentov</p>
    <div class="d-lg-flex justify-content-lg-between">
        <div>
            <input id="fltrInputEl" class="form-control" type="text" placeholder="Indeks">
        </div>
        <div>
            <button id="studtInsrBtn" class="btn btn-primary" type="button" data-toggle="modal" data-target="
        #studtInsrMdl">Vstavi študenta</button>
        </div>
        <button id="rprtMdlBtn" class="d-none" type="button" data-toggle="modal" data-target="#rprtMdl"></button>
    </div>
    <div class="table-responsive mt-3">
        <table class="table">
            <thead>
                <tr>
                    <th>Ime in priimek</th>
                    <th>Indeks</th>
                    <th>Program</th>
                    <th>Stopnja programa</th>
                    <th>Fakulteta</th>
                    <th>Znanstvena dela</th>
                    <th>Certifikat</th>
                    <th>Račun</th>
                    <th colspan="2"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // for each student in the record
                foreach ($DBC->selectStudents() as $student) {
                ?>
                    <tr>
                        <td><?php echo $student->fullname; ?></td>
                        <td><?php echo $student->index; ?></td>
                        <td><?php echo $student->program; ?></td>
                        <td><?php echo $student->degree; ?></td>
                        <td><?php echo $student->faculty; ?></td>
                        <td>
                            <a class="sp-vw-a text-decoration-none mr-3" href="#sciPapViewMdl" data-toggle="modal" data-id-attendances="<?php echo $student->id_attendances; ?>">
                                <img src="/eArchive/custom/img/previewSciPapers.png" alt="Pregled" data-toggle="tooltip" title="Pregled">
                            </a>
                            <a class="sp-ins-a" href="#sciPapInsrMdl" data-toggle="modal" data-id-attendances="<?php echo $student->id_attendances; ?>">
                                <img src="/eArchive/custom/img/insert.png" alt="Vstavljanje" data-toggle="tooltip" title="Vstavljanje">
                            </a>
                        </td>
                        <td>
                            <?php
                            // if student possesses a certificate
                            if ($DBC->selectCertificate($student->id_attendances) != NULL) {
                            ?>
                                <a class="cert-vw-a" href="#gradCertViewMdl" data-toggle="modal" data-id-attendances="<?php echo $student->id_attendances; ?>">
                                    <img src="/eArchive/custom/img/previewCertificate.png" alt="Pregled" data-toggle="tooltip" title="Pregled">
                                </a>
                            <?php
                            } // if
                            // if student doesn't  possess a certificate
                            if ($DBC->selectCertificate($student->id_attendances) == NULL) {
                            ?>
                                <a class="cert-ins-a" href="#gradCertUpldMdl" data-toggle="modal" data-id-attendances="<?php echo $student->id_attendances; ?>">
                                    <img src="/eArchive/custom/img/insert.png" alt="Vstavljanje" data-toggle="tooltip" title="Vstavljanje">
                                </a>
                            <?php
                            } // if
                            ?>
                        </td>
                        <td>
                            <?php
                            // if student is assigned an account to  
                            if ($DBC->checkAcctAssignment($student->id_attendances)) {
                            ?>
                                <img class="acc-del-btn" src="/eArchive/custom/img/unassignAccount.png" data-id-attendances="<?php echo $student->id_attendances; ?>" data-index="<?php echo $student->index; ?>" data-toggle="tooltip" data-html="true" title="<p>Odvzemi<br><?php echo "(Dodeljen: {$DBC->selectAcctGrantDate($student->id_attendances)})"; ?></p>">
                            <?php
                            } // if
                            else {
                            ?>
                                <a class="acc-ins-btn" href="#acctAssignMdl" data-id-attendances="<?php echo $student->id_attendances; ?>" data-index="<?php echo $student->index; ?>" data-toggle="modal">
                                    <img src="/eArchive/custom/img/assignAccount.png" alt="Dodeli" data-toggle="tooltip" title="Dodeli">
                                </a>
                            <?php
                            } // else
                            ?>
                        </td>
                        <td>
                            <a class="stu-upd-a" href="#studtInsrMdl" data-toggle="modal" data-id-students="<?php echo $student->id_students; ?>">
                                <img src="/eArchive/custom/img/updateRecord.png" alt="Uredi" data-toggle="tooltip" title="Uredi">
                            </a>
                        </td>
                        <td>
                            <a class="stu-del-a" href="#" data-id-students="<?php echo $student->id_students; ?>" data-id-attendances="<?php echo $student->id_attendances; ?>" data-index="<?php echo $student->index; ?>">
                                <img src="/eArchive/custom/img/deleteRecord.png" alt="Izbriši" data-toggle="tooltip" title="Izbriši">
                            </a>
                        </td>
                    </tr>
                <?php
                } // foreach
                ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Link to the cutsom CSS -->
<link rel="stylesheet" href="/eArchive/custom/css/evidence.css">

<?php

// script import declaration

include_once 'modals.php';

include_once '../../footer.php';

?>