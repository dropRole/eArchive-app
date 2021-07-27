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
    <p class="h2">Evidenca študentov</p>
    <div class="d-flex justify-content-starts w-25">
        <input id="fltrInputEl" class="form-control" type="text" placeholder="Indeks">
    </div>
    <div class="d-flex justify-content-end">
        <button id="studtInsrBtn" type="button" class="btn btn-primary" data-toggle="modal" data-target="
        #studtInsrMdl">Vstavi študenta</button>
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
                    <th colspan="2">Račun</th>
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
                            <a class="sp-vw-a" href="#sciPapViewMdl" data-toggle="modal" data-id-attendances="<?php echo $student->id_attendances; ?>">Pregled</a>
                            <a class="sp-ins-a" href="#sciPapInsrMdl" data-toggle="modal" data-id-attendances="<?php echo $student->id_attendances; ?>">Vstavljanje</a>
                        </td>
                        <td>
                            <?php
                            // if student possesses a certificate
                            if ($DBC->selectCertificate($student->id_attendances) != NULL) {
                            ?>
                                <a class="cert-vw-a" href="#gradCertViewMdl" data-toggle="modal" data-id-attendances="<?php echo $student->id_attendances; ?>">Pregled</a>
                            <?php
                            } // if
                            // if student doesn't  possess a certificate
                            if ($DBC->selectCertificate($student->id_attendances) == NULL) {
                            ?>
                                <a class="cert-ins-a" href="#gradCertUpldMdl" data-toggle="modal" data-id-attendances="<?php echo $student->id_attendances; ?>">Vstavljanje</a>
                            <?php
                            } // if
                            ?>
                        </td>
                        <td>
                            <?php
                            // if student is assigned an account to  
                            if ($DBC->checkAcctAssignment($student->id_attendances)) {
                            ?>
                                Dodeljen: <span class="text-warning"><?php echo $DBC->selectAcctGrantDate($student->id_attendances); ?></span>
                                <span class="acc-del-btn" data-id-attendances="<?php echo $student->id_attendances; ?>" data-index="<?php echo $student->index; ?>">&#10007;</span>
                            <?php
                            } // if
                            else {
                            ?>
                                <button class="btn btn-warning acc-ins-btn" type="button" value="<?php echo $student->id_attendances; ?>" data-index="<?php echo $student->index; ?>" data-toggle="modal" data-target="#acctAssignMdl">Ustvari</button>
                            <?php
                            } // else
                            ?>
                        </td>
                        <td>
                            <a class="stu-upd-a" href="#studtInsrMdl" data-toggle="modal" data-id-students="<?php echo $student->id_students; ?>">Uredi</a>
                        </td>
                        <td>
                            <a class="stu-del-a" href="#" data-id-students="<?php echo $student->id_students; ?>" data-id-attendances="<?php echo $student->id_attendances; ?>" data-index="<?php echo $student->index; ?>">Izbriši</a>
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

?>
<!-- Custom core JavaScript -->
<script src="/eArchive/custom/js/studentRecord.js"></script>
<?php

// script import declaration

include_once '../../footer.php';

?>