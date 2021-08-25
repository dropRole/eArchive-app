<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../../autoload.php';
include_once '../../header.php';
include_once '../../nav.php';

$DBC = new DBC($_SESSION['user'], $_SESSION['pass']);

?>

<!-- Link to the cutsom CSS -->
<link rel="stylesheet" href="/eArchive/custom/css/studentEvidence.css">

<!-- Custom core JavaScript -->
<script defer src="/eArchive/custom/js/studentEvidence.js"></script>

<section class="container my-3 p-3">
    <p class="h2 my-3 text-center">Evidenca študentov</p>
    <div class="d-flex flex-lg-row flex-column justify-content-lg-between">
        <div>
            <input id="fltInpEl" class="form-control" type="text" placeholder="Indeks">
        </div>
        <div>
            <button id="studtInsrBtn" class="btn btn-primary" type="button" data-toggle="modal" data-target="
        #stuInsMdl">Vstavi študenta</button>
        </div>
        <button id="repMdlBtn" class="d-none" type="button" data-toggle="modal" data-target="#repMdl"></button>
    </div>
    <div class="table-responsive mt-3">
        <table class="table">
            <caption>Zapisi študentov na univerzi </caption>
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
                            <a class="text-decoration-none mr-3" href="#sciPapSelMdl" data-toggle="modal">
                                <img src="/eArchive/custom/img/previewSciPapers.png" alt="Pregled" class="sp-sel-img" data-id-attendances="<?php echo $student->id_attendances; ?>" data-toggle="tooltip" title="Pregled">
                            </a>
                            <a href="#sciPapInsMdl" data-toggle="modal">
                                <img src="/eArchive/custom/img/insert.png" alt="Vstavljanje" class="sp-ins-img" data-toggle="tooltip" title="Vstavljanje" data-id-attendances="<?php echo $student->id_attendances; ?>">
                            </a>
                        </td>
                        <td>
                            <?php
                            // if student possesses a certificate
                            if ($DBC->selectCertificate($student->id_attendances) != NULL) {
                            ?>
                                <a href="#gradCertSelMdl" data-toggle="modal">
                                    <img src="/eArchive/custom/img/previewCertificate.png" alt="Pregled" class="cert-sel-img" data-id-attendances="<?php echo $student->id_attendances; ?>" data-toggle="tooltip" title="Pregled">
                                </a>
                            <?php
                            } // if
                            else {
                            ?>
                                <a href="#gradCertUplMdl" data-toggle="modal">
                                    <img src="/eArchive/custom/img/insert.png" alt="Vstavljanje" class="cert-ins-img" data-id-attendances="<?php echo $student->id_attendances; ?>" data-toggle="tooltip" title="Vstavljanje">
                                </a>
                            <?php
                            } // else
                            ?>
                        </td>
                        <td>
                            <?php
                            // if student is assigned an account to  
                            if ($DBC->assignedWithAccount($student->id_attendances)) {
                            ?>
                                <img class="acc-del-img" src="/eArchive/custom/img/unassignAccount.png" data-id-attendances="<?php echo $student->id_attendances; ?>" data-index="<?php echo $student->index; ?>" data-toggle="tooltip" data-html="true" title="<p>Odvzemi<br><?php echo "(Dodeljen: {$DBC->selectAcctGrantDate($student->id_attendances)})"; ?></p>">
                            <?php
                            } // if
                            else {
                            ?>
                                <a href="#acctInsMdl" data-toggle="modal">
                                    <img src="/eArchive/custom/img/assignAccount.png" alt="Dodeli" class="acc-ins-img" data-id-attendances="<?php echo $student->id_attendances; ?>" data-index="<?php echo $student->index; ?>" data-toggle="tooltip" title="Dodeli">
                                </a>
                            <?php
                            } // else
                            ?>
                        </td>
                        <td>
                            <a href="#stuInsMdl" data-toggle="modal">
                                <img src="/eArchive/custom/img/updateRecord.png" alt="Uredi" class="stu-upd-img" data-id-students="<?php echo $student->id_students; ?>" data-toggle="tooltip" title="Uredi">
                            </a>
                        </td>
                        <td>
                            <img src="/eArchive/custom/img/deleteRecord.png" alt="Izbriši" class="stu-del-a" data-id-students="<?php echo $student->id_students; ?>" data-id-attendances="<?php echo $student->id_attendances; ?>" data-index="<?php echo $student->index; ?>" data-toggle="tooltip" title="Izbriši">
                        </td>
                    </tr>
                <?php
                } // foreach
                ?>
            </tbody>
        </table>
    </div>
</section>

<?php

// script import declaration

include_once 'modals.php';

include_once '../../footer.php';

?>