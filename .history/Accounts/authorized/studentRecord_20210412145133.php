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
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Vstavi študenta</button>
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
                    <div class="row col-12">
                    <p class="h6 col-12"></p>
                        <div class="form-group col-6">
                            <select id="mSlct" class="form-control" name="sojourns[municipality][0]" required>
                                <?php
                                $postalCodes = $DBC->selectPostalCodes($DBC->selectCountries()[0]->getIdCountries());
                                foreach ($postalCodes as $postalCode) {
                                ?>
                                    <option value="<?php echo $postalCode->getIdPostalCodes(); ?>"><?php echo "{$postalCode->getMunicipality()}({$postalCode->getCode()})"; ?></option>
                                <?php
                                } // foreach
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <select id="cSlct" class="form-control" name="sojourns[country][0]" required>
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
                    <div class="col-12">
                        <p class="h5">Bivališča</p>
                    </div>
                </div>
                <div id="sojourns" class="px-3">
                    <div class="row">
                        <div class="form-group col-4">
                            <label for="aInpt">1. Naslov</label>
                            <input id="aInpt" class="form-control" type="text" name="sojourns[address][0]" required>
                        </div>
                        <div class="form-group col-4">
                            <label for="mSlct">Kraj</label>
                            <select id="mSlct" class="form-control" name="sojourns[municipality][0]" required>
                                <?php
                                $postalCodes = $DBC->selectPostalCodes($DBC->selectCountries()[0]->getIdCountries());
                                foreach ($postalCodes as $postalCode) {
                                ?>
                                    <option value="<?php echo $postalCode->getIdPostalCodes(); ?>"><?php echo "{$postalCode->getMunicipality()}({$postalCode->getCode()})"; ?></option>
                                <?php
                                } // foreach
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-4">
                            <label for="cSlct">Država</label>
                            <select id="cSlct" class="form-control" name="sojourns[country][0]" required>
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
                    <div id="rDiv" class="row">
                        <p class="col-12 h6">1. študijski program</p>
                        <div class="form-group col-4">
                            <label for="fSlct">Fakulteta</label>
                            <select id="fSlct" class="form-control" name="attendances[facluties][0]" required>
                                <?php
                                $faculties = $DBC->selectFaculties();
                                foreach ($faculties as $faculty) {
                                ?>
                                    <option value="<?php echo $faculty->getIdFaculties(); ?>"><?php echo $faculty->getName(); ?></option>
                                <?php
                                } // foreach
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-4">
                            <label for="pSlct">Program(polje, stopnja, trajanje)</label>
                            <select id="pSlct" class="form-control" name="attendances[programs][0]" required>
                                <?php
                                $programs = $DBC->selectPrograms($DBC->selectFaculties()[0]->getIdFaculties());
                                foreach ($programs as $program) {
                                ?>
                                    <option value="<?php echo $program->getIdPrograms(); ?>"><?php echo "{$program->getName()}({$program->getField()}, {$program->getDegree()}, {$program->getDuration()})"; ?></option>
                                <?php
                                } // foreach
                                ?>
                            </select>
                        </div>
                        <div class="d-flex align-items-center justify-content-center form-group col-4">
                            <input id="gCb" class="mr-2" type="checkbox">
                            <label class="mt-2" for="gCb">Diplomiral</label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center col-12">
                        <button id="addAttendance" class="btn btn-secondary" type="button">&plus;</button>
                    </div>
                </div>
                <input class="btn btn-warning offset-5 col-2 my-2" type="submit" value="Vstavi">
            </form>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="rMdl" tabindex="-1" role="dialog" aria-labelledby="rMdlLbl" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rMdlLbl">Modal title</h5>
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