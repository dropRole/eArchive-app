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
            // select students by index filter
            foreach ($DBC->selectStudentsByIndex($index) as $student) {
            ?>
                <tr>
                    <td><?php echo $student->fullname; ?></td>
                    <td><span class="bg-warning"><?php echo $index; ?></span><?php echo substr($student->index, strlen($index)); ?></td>
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
                            <button class="btn btn-warning acc-ins-btn" type="button" value="<?php echo $student->id_attendances; ?>" data-toggle="modal" data-target="#acctAssignMdl" data-index="<?php echo $student->index; ?>">Ustvari</button>
                        <?php
                        } // else
                        ?>
                    </td>
                    <td>
                        <a class="stu-upd-a" href="#studentInsrMdl" data-toggle="modal" data-id-students="<?php echo $student->id_students; ?>">Uredi</a>
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
<?php
} // if 
