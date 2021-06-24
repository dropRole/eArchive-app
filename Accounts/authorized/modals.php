<!-- Modal for inserting data about the student and its attendance of faculty programs  -->
<div id="studentInsertionMdl" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="studentInsertionFrm">
                <p class="h4 pt-2 px-3">Osnovni podatki</p>
                <div class="row px-3">
                    <div class="form-group col-6">
                        <label>Ime
                            <input id="nameInputElement" class="form-control" type="text" name="name" required>
                        </label>
                    </div>
                    <div class="form-group col-6">
                        <label>Priimek
                            <input id="surnameInputElement" class="form-control" type="text" name="surname" required>
                        </label>
                    </div>
                    <div class="form-group col-6">
                        <label>E-naslov
                            <input id="emailInputElement" class="form-control" type="email" name="email">
                        </label>
                    </div>
                    <div class="form-group col-6">
                        <label>Telefon
                            <input id="telInputElement" class="form-control" type="text" name="telephone">
                        </label>
                    </div>
                    <p class="h6 col-12">Rojen</p>
                    <div class="form-group col-6">
                        <label>Država
                            <select id="birthCtrySelElement" class="form-control country-select" data-target="birthPostalCodeSelElement">
                                <?php
                                foreach ($DBC->selectCountries() as $country) {
                                ?>
                                    <option value="<?php echo $country->getIdCountries(); ?>"><?php echo "{$country->getName()}({$country->getISO3Code()})"; ?></option>
                                <?php
                                } // foreach
                                ?>
                            </select>
                        </label>
                    </div>
                    <div class="form-group col-6">
                        <label>Kraj
                            <select id="birthPostalCodeSelElement" class="form-control" name="id_postal_codes" required>
                                <?php
                                foreach ($DBC->selectCountries()[0]->getIdCountries() as $postalCode) {
                                ?>
                                    <option value="<?php echo $postalCode->getIdPostalCodes(); ?>"><?php echo "{$postalCode->getMunicipality()}({$postalCode->getCode()})"; ?></option>
                                <?php
                                } // foreach
                                ?>
                            </select>
                        </label>
                    </div>
                    <div class="col-12">
                        <p class="h5">Podatki o prebivališču</p>
                    </div>
                </div>
                <div id="residences" class="px-3">
                    <div id="permanentResidence" class="row">
                        <p class="col-12 h6">Stalno prebivališče</p>
                        <input type="hidden" name="residences[0][status]" value="STALNO">
                        <div class="form-group col-4">
                            <label>Država
                                <select id="permResCtrySelElement" class="form-control country-select" data-target="permResPostalCodeSelElement">
                                    <?php
                                    foreach ($DBC->selectCountries() as $id_countries) {
                                    ?>
                                        <option value="<?php echo $id_countries->getIdCountries(); ?>"><?php echo "{$id_countries->getName()}({$id_countries->getISO3Code()})"; ?></option>
                                    <?php
                                    } // foreach
                                    ?>
                                </select>
                            </label>
                        </div>
                        <div class="form-group col-4">
                            <label>Kraj
                                <select id="permResPostalCodeSelElement" class="form-control" name="residences[0][id_postal_codes]" required>
                                    <?php
                                    foreach ($DBC->selectPostalCodes($DBC->selectCountries()[0]->getIdCountries()) as $postalCode) {
                                    ?>
                                        <option value="<?php echo $postalCode->getIdPostalCodes(); ?>"><?php echo "{$postalCode->getMunicipality()}({$postalCode->getCode()})"; ?></option>
                                    <?php
                                    } // foreach
                                    ?>
                                </select>
                            </label>
                        </div>
                        <div class="form-group col-4">
                            <label>Naslov
                                <input id="permResAddressInputElement" class="form-control" type="text" name="residences[0][address]" required>
                            </label>
                        </div>
                    </div>
                    <p class="h6">Začasna bivališča</p>
                    <div class="d-flex justify-content-center col-12">
                        <button id="addTempResBtn" class="btn btn-secondary" type="button">&plus;</button>
                    </div>
                </div>
                <div id="attendances" class="px-3 pb-3">
                    <p class="h4 pt-2">Podatki o študiranju</p>
                    <div class="row">
                        <p class="col-12 h6">Študijski programi</p>
                        <div class="form-group col-6">
                            <label>
                                Fakulteta
                                <select id="facSelElement" class="form-control" name="attendances[0][id_faculties]" required>
                                    <?php
                                    foreach ($faculties = $DBC->selectFaculties() as $faculty) {
                                    ?>
                                        <option value="<?php echo $faculty->getIdFaculties(); ?>"><?php echo $faculty->getName(); ?></option>
                                    <?php
                                    } // foreach
                                    ?>
                                </select>
                            </label>
                        </div>
                        <div class="form-group col-6">
                            <label>Program(polje, stopnja, trajanje)
                                <select id="progSelElement" class="form-control" name="attendances[0][id_programs]" required>
                                    <?php
                                    foreach ($DBC->selectPrograms($DBC->selectFaculties()[0]->getIdFaculties()) as $program) {
                                    ?>
                                        <option value="<?php echo $program->getIdPrograms(); ?>"><?php echo "{$program->getName()}({$program->getField()}, {$program->getDegree()}, {$program->getDuration()})"; ?></option>
                                    <?php
                                    } // foreach
                                    ?>
                                </select>
                            </label>
                        </div>
                        <div class="form-group col-4">
                            <label>Vpisan
                                <input id="enrlInputElement" class="form-control" type="date" name="attendances[0][enrolled]" required>
                            </label>
                        </div>
                        <div class="form-group col-4">
                            <label>Indeks
                                <input id="indexInputElement" class="form-control" type="text" name="attendances[0][index]" required>
                            </label>
                        </div>
                        <div class="d-flex align-items-center justify-content-center form-group col-4">
                            <label class="mt-2">
                                <input id="gradCheckBox" class="mr-2" type="checkbox" data-indx="0" data-lbl-num="0">
                                Diplomiral
                            </label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center col-12">
                        <button id="addAttendanceBtn" class="btn btn-secondary" type="button">&plus;</button>
                    </div>
                </div>
                <input class="btn btn-warning offset-5 col-2 my-2" type="submit" value="Vstavi">
            </form>
        </div>
    </div>
</div>
<!-- Modal for viewing data regarding scientific papers of the student  -->
<div id="sciPapViewingMdl" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"></div>
    </div>
</div>
<!-- Modal for inserting data concerning scientific paper and uploading its documents -->
<div class="modal fade" id="sciPapInsertionMdl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Vstavljanje znanstvenega dela</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="sciPapInsertionMdl">
                    <input type="hidden" name="id_attendances" value="">
                    <div class="row">
                        <div class="form-group col-12">
                            <label>Predmet
                                <input id="topicInputElement" class="form-control" type="text" name="topic" required>
                            </label>
                        </div>
                        <div class="form-group col-6">
                            <label>Vrsta
                                <select id="typeInputElement" class="form-control" name="type">
                                    <option value="DOKTORSKO DELO">Doktorsko delo</option>
                                    <option value="MAGISTRSKO DELO">Magistrsko delo</option>
                                    <option value="DIPLOMSKO DELO">Diplomsko delo</option>
                                    <option value="RAZISKOVALNO DELO">Raziskovalno delo</option>
                                    <option value="SEMINARSKO DELO">Seminarsko delo</option>
                                </select>
                            </label>
                        </div>
                        <div class="form-group col-6">
                            <label>Napisano
                                <input id="writtenInputElement" class="form-control" type="date" name="written" required>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div id="sciPapPartakerSect" class="col-6">
                            <p class="h6">Soavtorji</p>
                            <div class="d-flex justify-content-center col-12">
                                <button id="addPartakerBtn" class="btn btn-secondary" type="button">&plus;</button>
                            </div>
                            <datalist id="students">
                                <?php
                                // denote student as potential partaker on a scientific paper
                                foreach ($DBC->selectStudents() as $student) {
                                ?>
                                    <option value="<?php echo $student->index; ?>"><?php echo $student->fullname; ?></option>
                                <?php
                                } // foreach
                                ?>
                            </datalist>
                        </div>
                        <div id="sciPapMentorSect" class="col-6">
                            <p class="h6">Mentorji</p>
                            <div class="d-flex justify-content-center col-12">
                                <button id="addMentorBtn" class="btn btn-secondary" type="button">&plus;</button>
                            </div>
                        </div>
                    </div>
                    <div id="sciPapDocs">
                        <p class="h6">Dokumentacija</p>
                        <div class="row">
                            <div class="form-group col-6">
                                <label>Verzija
                                    <input id="versionInputEl" class="form-control" type="text" name="documents[0][version]" required>
                                </label>
                            </div>
                            <div class="form-group col-6">
                                <input id="docNameInputElement" type="hidden" name="documents[0][name]" value="">
                                <label>Dokument
                                    <input id="docInputElement" type="file" name="document[]" accept=".pdf" required>
                                </label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center col-12">
                            <button id="addDocumentBtn" class="btn btn-secondary" type="button">&plus;</button>
                        </div>
                    </div>
                    <input class="btn btn-secondary float-right" type="submit" value="Dodaj">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal for viewing graduation certificate obtained attending the program  -->
<div id="gradCertViewingMdl" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>
<!-- Modal for uploading graduation certificate and inserting data regarding its issuance and defence dates  -->
<div class="modal fade" id="gradCertUploadingMdl" tabindex="-1" role="dialog" aria-labelledby="exampleModlaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Vstavljanje certifikata</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="certUploadFrm">
                    <input type="hidden" name="id_attendances" value="">
                    <div class="row">
                        <div class="form-group col-12">
                            <input type="hidden" name="certificate" value="">
                            <label>Certifikat
                                <input id="certInputEl" type="file" name="certificate[]" required>
                            </label>
                        </div>
                        <div class="form-group col-6">
                            <label>Zagovarjan
                                <input id="defendedInputElement" class="form-control" type="date" name="defended" required>
                            </label>
                        </div>
                        <div class="form-group col-6">
                            <label>Izdan
                                <input id="issuedInputElement" class="form-control" type="date" name="issued" required>
                            </label>
                        </div>
                    </div>
                    <input class="btn btn-secondary float-right" type="submit" value="Vstavi">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal for assigning account credentials to a student -->
<div class="modal fade" id="acctAssigningMdl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Ustvarjanje računa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="acctInsertionFrm">
                    <input type="hidden" name="id_attendances">
                    <div class="form-group">
                        <label>Geslo
                            <input id="passInputElement" class="form-control" name="pass" required>
                        </label>
                    </div>
                    <input class="btn btn-warning float-right" type="submit" value="Ustvari">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal for reporting on performed operations -->
<div class="modal fade" id="reportingMdl" tabindex="-1" role="dialog" aria-labelledby="reportMdl" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Poročilo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>