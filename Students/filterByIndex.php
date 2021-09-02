<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../autoload.php';

// proceed with the current session
session_start();

$index = $_GET['index'];

// if index was passed by URL query string
if (isset($index)) {
    // create a new PDO interface object instance 
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
?>
    <table class="table">
        <caption>Zapisi študentov na univerzi</caption>
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
            // if student records in the evidence
            if (count($DBC->selectStudentsByIndex($index)))
                // for each student in the record
                foreach ($DBC->selectStudentsByIndex($index) as $student) {
            ?>
                <tr>
                    <td><?php echo $student->fullname; ?></td>
                    <td><span class="bg-warning"><?php echo $index; ?></span><?php echo substr($student->index, strlen($index)); ?></td>
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
                            <a href="#certSelMdl" data-toggle="modal">
                                <img src="/eArchive/custom/img/previewCertificate.png" alt="Pregled" class="cert-sel-img" data-id-attendances="<?php echo $student->id_attendances; ?>" data-toggle="tooltip" title="Pregled">
                            </a>
                        <?php
                        } // if
                        else {
                        ?>
                            <a href="#certUplMdl" data-toggle="modal">
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
                            <img class="acc-del-img" src="/eArchive/custom/img/unassignAccount.png" data-id-attendances="<?php echo $student->id_attendances; ?>" data-index="<?php echo $student->index; ?>" data-toggle="tooltip" data-html="true" title="<p>Odvzemi<br><?php echo "(Dodeljen: {$DBC->selectAccountGrantDate($student->id_attendances)})"; ?></p>">
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
                        <img src="/eArchive/custom/img/deleteRecord.png" alt="Izbriši" class="stu-del-img" data-id-students="<?php echo $student->id_students; ?>" data-id-attendances="<?php echo $student->id_attendances; ?>" data-index="<?php echo $student->index; ?>" data-toggle="tooltip" title="Izbriši">
                    </td>
                </tr>
            <?php
                } // foreach
            else {
            ?>
                <tr>
                    <td colspan="3">
                        <p class="font-italic text-muted">Ni študentov z dano indeks številko.</p>
                    </td>
                </tr>
            <?php
            } // else
            ?>
        </tbody>
    </table>
<?php
} // if 
