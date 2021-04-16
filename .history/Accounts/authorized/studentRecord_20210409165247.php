<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../../DBC/DBC.php';
require_once '../../PostalCodes/PostalCodes.php';
require_once '../../Countries/Countries.php';
include_once '../../header.php';

$DBC = new DBC($_SESSION['user'], $_SESSION['pass']);

?>
<section class="container p-3">
    <p class="h2">Evidenca študentov</p>
    <div class="d-flex justify-content-end mr-3">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Vstavi študenta</button>
    </div>
    <div class="table-responsive">
        <table class="table table-info">
            <thead>
                <tr>
                    <th>Ime in priimek</th>
                    <th>Indeks</th>
                    <th>Program</th>
                    <th>Stopnja programa</th>
                    <th>Univerziteta</th>
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
                        <td><?php echo $student->university; ?></td>
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
            <form id="iFrm">
                <p class="h4 pt-2 px-3">Osnovni podatki</p>
                <div id="fundamentals" class="row px-3">
                    <div class="form-group col-6">
                        <label for="nInpt">Ime</label>
                        <input id="nInpt" class="form-control" type="text" name="name" required>
                    </div>
                    <div class="form-group col-6">
                        <label for="sInpt">Priimek</label>
                        <input id="sInpt" class="form-control" type="text" name="surname" required>
                    </div>
                    <div class="form-group col-6">
                        <label for="eInpt">E-naslov</label>
                        <input id="eInpt" class="form-control" type="email" name="email">
                    </div>
                    <div class="form-group col-6">
                        <label for="tInpt">Telefon</label>
                        <input id="tInpt" class="form-control" type="text" name="telephone">
                    </div>
                    <div class="col-12">
                        <p class="h5">Bivališča</p>
                    </div>
                </div>
                <div id="sojourns" class="px-3">
                    <div class="row">
                        <div class="form-group col-4">
                            <label for="aInpt">1. Naslov</label>
                            <input id="aInpt" class="form-control" type="text" name="addresses[]" required>
                        </div>
                        <div class="form-group col-4">
                            <label for="mSlct">Kraj</label>
                            <select id="mSlct" class="form-control" name="municipalities[]" required></select>
                        </div>
                        <div class="form-group col-4">
                            <label for="cSlct">Država</label>
                            <select id="cSlct" class="form-control" name="countries[]" required>
                                <?php
                                $countries = $DBC->selectCountries();
                                foreach ($countries as $country) {
                                ?>
                                    <option value="<?php echo $country->getIdCountries(); ?>"><?php echo "{$country->getName()}({$country->getISO3Code()})"; ?></option>
                                <?php
                                } // foreach
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center col-12">
                        <button id="addSojourn" class="btn btn-secondary" type="button">&plus;</button>
                    </div>
                </div>
                <div id="attendances" class="px-3 pb-3">
                    <p class="h4 pt-2">Podatki o študiranju</p>
                    <div class="row">
                        <p class="col-12 h6">1. študijski program</p>
                        <div class="d-flex align-items-center form-group col-4">
                            <label for="fSlct"></label>
                            <select id="fSlct" class="form-control" name="faculties[]" required> 

                            </select>
                        </div>
                        <div class="d-flex align-items-center form-group col-4">
                            <label for="pSlct"></label>
                            <select id="pSlct" class="form-control" name="programs[]" required>

                            </select>
                        </div>
                        <div class="d-flex align-items-center form-group col-4">
                            <input id="gCb" class="mr-2" type="checkbox">
                            <label class="mt-2" for="gCb">Dimplomiral</label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center col-12">
                        <button id="addAttendance" class="btn btn-secondary" type="button">&plus;</button>
                    </div>
                </div>
                <input class="btn btn-warning m-2 float-right" type="submit" value="Vstavi">
            </form>
        </div>
    </div>
</div>
<!-- Custom core JavaScript -->
<script src="/eArchive/custom/js/studentRecord.js"></script>
<?php

// script import declaration

include_once '../../footer.php';

?>