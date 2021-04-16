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
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".
        bd-example-modal-lg">Vstavi študenta</button>
        <button id="rBtn" class="d-none" type="button" data-toggle="modal" data-target="#rMdl"></button>
    </div>
    <div class="table-responsive">
        <table class="table table-info">
            <thead>
                <tr>
                    <th>Ime in priimek</th>
                    <th>Indeks</th>
                    <th>Program</th>
                    <th>Stopnja programa</th>
                    <th>Fakulteta</th>
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
                    </tr>
                <?php
                } // foreach
                ?>
            </tbody>
        </table>
    </div>
</section>
<!-- Large modal -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="rMdl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Poročilo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>
<!-- Custom core JavaScript -->
<script src="/eArchive/custom/js/studentRecord.js"></script>
<?php

// script import declaration

include_once '../../footer.php';

?>