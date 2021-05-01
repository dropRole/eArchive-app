<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../../DBC/DBC.php';
require_once '../../PostalCodes/PostalCodes.php';
require_once '../../Countries/Countries.php';
require_once '../../Faculties/Faculties.php';
require_once '../../Programs/Programs.php';
include_once '../../header.php';

$DBC = new DBC($_SESSION['user'], $_SESSION['pass']);

?>
<section class="container p-3">
    <p class="h2">Evidenca študentov</p>
    <div class="d-flex justify-content-end mr-3">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="
        #sMdl">Vstavi študenta</button>
        <button id="rMdlBtn" class="d-none" type="button" data-toggle="modal" data-target="#rMdl"></button>
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
                $students = $DBC->selectStudents();
                // for each student in the record
                foreach ($students as $student) {
                ?>
                    <tr>
                        <td><?php echo $student->fullname; ?></td>
                        <td><?php echo $student->index; ?></td>
                        <td><?php echo $student->program; ?></td>
                        <td><?php echo $student->degree; ?></td>
                        <td><?php echo $student->faculty; ?></td>
                        <td>
                            <a class="sp-vw-a" href="#sPVMdl" data-toggle="modal" data-id="<?php echo $student->id_attendances; ?>">Pregled</a>
                            <a class="sp-ins-a" href="#sPIUMdl" data-toggle="modal" data-id="<?php echo $student->id_attendances; ?>">Dodajanje</a>
                        </td>
                        <td><a href="#">Pregled</a></td>
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
                                <button class="btn btn-warning acc-ins-btn" type="button" value="<?php echo $student->id_attendances; ?>" data-toggle="modal" data-target="#aMdl">Ustvari</button>
                            <?php
                            } // else
                            ?>
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