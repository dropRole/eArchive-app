<!-- Modal for inserting data concerning scientific paper and uploading its documents -->
<div class="modal fade" id="sciPapInsMdl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLongTitle">Vstavljanje znanstvenega dela</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form id="sciPapInsFrm">
                    <input type="hidden" name="id_attendances" value="<?php echo $DBC->selectStudentsByIndex($_SESSION['index'])[0]->id_attendances; ?>">
                    <div id="particulars" class="row">
                        <div class="form-group col-12">
                            <label class="w-100">Predmet
                                <input class="form-control" type="text" name="topic" required>
                            </label>
                        </div>
                        <div class="form-group col-6">
                            <label class="w-100">Vrsta
                                <select class="form-control" name="type">
                                    <option value="DOKTORSKO DELO">Doktorsko delo</option>
                                    <option value="MAGISTRSKO DELO">Magistrsko delo</option>
                                    <option value="DIPLOMSKO DELO">Diplomsko delo</option>
                                    <option value="RAZISKOVALNO DELO">Raziskovalno delo</option>
                                    <option value="SEMINARSKO DELO">Seminarsko delo</option>
                                </select>
                            </label>
                        </div>
                        <div class="form-group col-6">
                            <label class="w-100">Napisano
                                <input class="form-control" type="date" name="written" required>
                            </label>
                        </div>
                    </div>
                    <div class="row">   
                        <div id="partakers" class="col-lg-6 col-12 mb-3">
                            <p class="h6"><strong>Soavtorji</strong></p>
                            <div class="d-flex justify-content-center col-12">
                                <button id="addPartaker" type="button" data-toggle="tooltip" title="Dodeli"></button>
                            </div>
                            <datalist id="studentDatalist">
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
                        <div id="mentors" class="col-lg-6 col-12 mb-3">
                            <p class="h6"><strong>Mentorji</strong></p>
                            <div class="d-flex justify-content-center col-12">
                                <button id="addMentor" type="button" data-toggle="tooltip" title="Dodeli"></button>
                            </div>
                        </div>
                    </div>
                    <div id="documents" class="mb-3">
                        <p class="h6"><strong>Dokumentacija</strong></p>
                        <div class="row">
                            <div class="form-group col-6">
                                <label class="w-100">Verzija
                                    <input class="form-control" type="text" name="documents[0][version]" required>
                                </label>
                            </div>
                            <div class="form-group col-6">
                                <input type="hidden" name="documents[0][name]" value="">
                                <label class="w-100 file-label">Dokument
                                    <input type="file" name="document[]" accept=".pdf" required>
                                </label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center col-12">
                            <button id="addDocument" type="button" data-toggle="tooltip" title="Dodaj"></button>
                        </div>
                    </div>
                    <input class="btn btn-warning offset-lg-5 offset-3 col-lg-2 col-6" type="submit" value="Dodaj">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal for uploading account avatar -->
<div class="modal fade" id="acctInsMdl" tabindex="-1" role="dialog" aria-labelledby="reportMdl" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div id="avatar">
                <img src="<?php echo $DBC->hasAcctAvatar($_SESSION['index']) ? "/eArchive/{$DBC->hasAcctAvatar($_SESSION['index'])}"  : '/eArchive/custom/img/defaultAvatar.png'; ?>">
            </div>
            <form id="avtrUplFrm">
                <div class="form-group d-flex flex-column align-items-center">
                    <input type="hidden" name="id_attendances" value="<?php echo $DBC->selectStudentsByIndex($_SESSION['index'])[0]->id_attendances; ?>">
                    <label class="file-label w-50">Avatar
                        <input type="file" name="avatar" accept=".jpg" required>
                    </label>
                    <input class="btn btn-warning w-50" type="submit" value="Naloži">
                </div>
            </form>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<!-- Modal for reporting on performed operations -->
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportMdl" aria-hidden="true">
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