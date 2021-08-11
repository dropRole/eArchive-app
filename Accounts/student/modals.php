<!-- Modal for inserting data concerning scientific paper and uploading its documents -->
<div class="modal fade" id="sciPapInsrMdl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLongTitle">Vstavljanje znanstvenega dela</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form id="sciPapInsrFrm">
                    <input type="hidden" name="id_attendances" value="<?php echo $DBC->selectStudentsByIndex($_SESSION['index'])[0]->id_attendances; ?>">
                    <div id="particulars" class="row">
                        <div class="form-group col-12">
                            <label class="w-100">Predmet
                                <input id="topicInptEl" class="form-control" type="text" name="topic" required>
                            </label>
                        </div>
                        <div class="form-group col-6">
                            <label class="w-100">Vrsta
                                <select id="typeInptEl" class="form-control" name="type">
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
                                <input id="writtenInptEl" class="form-control" type="date" name="written" required>
                            </label>
                        </div>
                    </div>
                    <div class="row">   
                        <div id="sciPapPartakers" class="col-lg-6 col-12 mb-3">
                            <p class="h6"><strong>Soavtorji</strong></p>
                            <div class="d-flex justify-content-center col-12">
                                <button id="addPartakerBtn" type="button" data-toggle="tooltip" title="Dodeli"></button>
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
                        <div id="sciPapMentors" class="col-lg-6 col-12 mb-3">
                            <p class="h6"><strong>Mentorji</strong></p>
                            <div class="d-flex justify-content-center col-12">
                                <button id="addMentorBtn" type="button" data-toggle="tooltip" title="Dodeli"></button>
                            </div>
                        </div>
                    </div>
                    <div id="sciPapDocs" class="mb-3">
                        <p class="h6"><strong>Dokumentacija</strong></p>
                        <div class="row">
                            <div class="form-group col-6">
                                <label class="w-100">Verzija
                                    <input id="versionInptEl" class="form-control" type="text" name="documents[0][version]" required>
                                </label>
                            </div>
                            <div class="form-group col-6">
                                <input id="docNameInptEl" type="hidden" name="documents[0][name]" value="">
                                <label class="w-100 file-label">Dokument
                                    <input id="docInptEl" type="file" name="document[]" accept=".pdf" required>
                                </label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center col-12">
                            <button id="addDocBtn" type="button" data-toggle="tooltip" title="Dodaj"></button>
                        </div>
                    </div>
                    <input class="btn btn-warning offset-lg-5 offset-3 col-lg-2 col-6" type="submit" value="Dodaj">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal for uploading account avatar -->
<div class="modal fade" id="acctAvtrUpldMdl" tabindex="-1" role="dialog" aria-labelledby="reportMdl" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div id="currAvtr">
                <img src="<?php echo $DBC->hasAcctAvatar($_SESSION['index']) ? "/eArchive/{$DBC->hasAcctAvatar($_SESSION['index'])}"  : '/eArchive/custom/img/defaultAvatar.png'; ?>">
            </div>
            <form id="acctAvtrUpldFrm">
                <div class="form-group">
                    <input type="hidden" name="id_attendances" value="<?php echo $DBC->selectStudentsByIndex($_SESSION['index'])[0]->id_attendances; ?>">
                    <label>Avatar
                        <input type="file" name="avatar" accept=".jpg" required>
                    </label>
                    <input class="btn btn-warning" type="submit" value="Naloži">
                </div>
            </form>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<!-- Modal for reporting on performed operations -->
<div class="modal fade" id="rprtMdl" tabindex="-1" role="dialog" aria-labelledby="reportMdl" aria-hidden="true">
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
<!-- Custom stylesheet for modals -->
<link rel="stylesheet" href="/eArchive/custom/css/modals.css">