<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once '../Certificates/Certificates.php';
require_once './Students.php';

// proceed with the current session
session_start();

$index = $_GET['index'];

// if index was passed by URL query string
if (isset($index)) {
    // create a new PDO interface object instance 
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // select students by index filter
    $students = $DBC->selectStudentsByIndex($index);
?>
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
            foreach ($students as $student) {
            ?>
                <tr>
                    <td><?php echo $student->fullname; ?></td>
                    <td><span class="bg-warning"><?php echo $index; ?></span><?php echo substr($student->index, strlen($index)); ?></td>
                    <td><?php echo $student->program; ?></td>
                    <td><?php echo $student->degree; ?></td>
                    <td><?php echo $student->faculty; ?></td>
                    <td>
                        <a class="sp-vw-a" href="#sPVMdl" data-toggle="modal" data-id="<?php echo $student->id_attendances; ?>">Pregled</a>
                        <a class="sp-ins-a" href="#sPMdl" data-toggle="modal" data-id="<?php echo $student->id_attendances; ?>">Vstavljanje</a>
                    </td>
                    <td>
                        <?php
                        // if student possesses a certificate
                        if ($DBC->selectCertificate($student->id_attendances) != NULL) {
                        ?>
                            <a class="cert-vw-a" href="#certificateMdl" data-toggle="modal" data-id="<?php echo $student->id_attendances; ?>">Pregled</a>
                        <?php
                        } // if
                        // if student doesn't  possess a certificate
                        if ($DBC->selectCertificate($student->id_attendances) == NULL) {
                        ?>
                            <a class="cert-ins-a" href="#certificateMdl" data-toggle="modal" data-id="<?php echo $student->id_attendances; ?>">Vstavljanje</a>
                        <?php
                        } // if
                        ?>
                    </td>
                    <td>
                        <?php
                        // if assigned an account 
                        if ($DBC->checkStudentAccount($student->id_attendances)) {
                        ?>
                            Dodeljen: <span class="text-warning"><?php echo $DBC->getAccountParticulars($student->id_attendances); ?></span>
                            <span class="acc-del-btn" data-id="<?php echo $student->id_attendances; ?>">&#10007;</span>
                        <?php
                        } // if
                        else {
                        ?>
                            <button class="btn btn-warning acc-ins-btn" type="button" value="<?php echo $student->id_attendances; ?>" data-toggle="modal" data-target="#accountMdl">Ustvari</button>
                        <?php
                        } // else
                        ?>
                    </td>
                    <td>
                        <a class="stu-upd-a" href="#studentMdl" data-toggle="modal" data-id="<?php echo $student->id_students; ?>">Uredi</a>
                    </td>
                    <td>
                        <a class="stu-del-a" href="#" data-id-students="<?php echo $student->id_students; ?>" data-id-attendances="<?php echo $student->id_attendances; ?>">Izbriši</a>
                    </td>
                </tr>
            <?php
            } // foreach
            ?>
        </tbody>
    </table>
<?php
} // if 
