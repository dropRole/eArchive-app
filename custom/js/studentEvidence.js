// IIFE
(() => {
    // global scope variable declaration
    var fragment = new DocumentFragment(), // minimal document object structure
        stuInsFrm = document.getElementById('stuInsFrm'), // form for student data manipulation
        sciPapInsFrm = document.getElementById('sciPapInsFrm'), // form for scientific paper data manipulation 
        acctInsFrm = document.getElementById('acctInsFrm'), // form for student account assignment
        certUplFrm = document.getElementById('certUplFrm'), // form for graduation certificate upload
        reportModal = document.getElementById('reportModal'), // modal for data manipulation report exposition 
        repMdlBtn = document.getElementById('repMdlBtn'), // report modal toggler
        fltInpEl = document.getElementById('fltInpEl') // input for filtering student records by index numbers

    /*
     *   instantiate an XHR interface object an asynchronously perform the script   
     *   @param String script
     *   @param String method
     *   @param String responseType 
     *   @param FormData formData 
     */
    let request = (script, method, responseType = '', formData = null) => {
            return new Promise((resolve, reject) => {
                // instanitate an XHR object
                let xmlhttp = new XMLHttpRequest()
                xmlhttp.addEventListener(
                        'load',
                        () => {
                            // if transaction was successful
                            resolve(xmlhttp.response)
                        }
                    ) // addEventListener
                xmlhttp.addEventListener(
                        'error',
                        () => {
                            // if transaction encountered an error
                            reject('Prišlo je do napake na strežniku pri obdelavi zahteve!')
                        }
                    ) // addEventListener
                xmlhttp.open(method, script, true)
                xmlhttp.responseType = responseType
                xmlhttp.send(formData)
            })
        } // request

    /*
     *   select particulars, permanent and temportary residences of the given student     
     *   @param Event e
     *   @param Number idStudents
     */
    let selectStudent = (e, idStudents) => {
            let student = {
                particulars: null,
                permanentResidence: null,
                temporaryResidence: null
            }
            request(
                    `/eArchive/Students/select.php?id_students=${idStudents}`,
                    'GET',
                    'json'
                )
                .then(response => student.particulars = response)
                .then(() => request(
                    `/eArchive/Residences/select.php?id_students=${idStudents}`,
                    'GET',
                    'json'
                ))
                .then(response => {
                    student.permanentResidence = response.permResidence
                    student.temporaryResidence = response.tempResidences
                })
                .then(() => toStudentUpdateForm(e, student))
                .catch(error => alert(error)) // catch
        } // selectStudent

    /*
     *   select all student records filtered by the passed index number      
     *   @param String index
     */
    let selectStudentsByIndex = index => {
            request(
                    `/eArchive/Students/filterByIndex.php?index=${index}`,
                    'GET',
                    'document'
                )
                .then(response => {
                    // compose node passive tree structure
                    fragment = response
                        // reflect fragments body  
                    document.querySelector('div.table-responsive').innerHTML = fragment.body.innerHTML
                        // enabling tooltips
                    $('[data-toggle="tooltip"]').tooltip()
                })
                .then(() => listenStudentEvidenceTable())
                .catch(error => alert(error)) // catch
        } // selectStudentsByIndex

    /*
     *  insert students particulars, program attendance and scientific achievement per program attendance 
     *  @param Event e
     *  @param HTMLFormElement form
     */
    let insertStudent = (e, form) => {
            // prevent default action of submitting student data through a form
            e.preventDefault()
            request(
                    '/eArchive/Students/insert.php',
                    'POST',
                    'text',
                    (new FormData(form))
                )
                .then(response => reportOnAction(response))
                .then(() => loadStudentEvidenceTable())
                .then(() => emptyInputFields(stuInsFrm))
                .then(() => document.getElementById('stuInsBtn').click())
                .then(() => interpolateDatalist())
                .catch(error => alert(error)) // catch
        } // insertStudent

    /*
     *  update particulars of the given student
     *  Event e
     *  HTMLFormElement
     */
    let updateStudent = (e, form) => {
            // prevent default action of submitting updated student data through a form
            e.preventDefault()
            request(
                    '/eArchive/Students/update.php',
                    'POST',
                    'text',
                    (new FormData(form))
                )
                .then(response => reportOnAction(response))
                .then(() => emptyInputFields(stuInsFrm))
                .then(() => loadStudentEvidenceTable())
                .catch(error => alert(error)) // catch
        } // updateStudent

    /*
     *   delete all records in a relationship with the given student record
     *   @param Number idAttendances 
     *   @param Number idStudents
     *   @param String index 
     */
    let deleteStudent = (idAttendances, idStudents, index) => {
            request(
                    `/eArchive/Students/delete.php?id_students=${idStudents}&id_attendances=${idAttendances}&index=${index}`,
                    'GET',
                    'text'
                )
                .then(response => reportOnAction(response))
                .then(() => loadStudentEvidenceTable())
                .catch(error => alert(error)) // catch
        } // deleteStudent

    /* 
     *   insert record of the scientific paper partaker     
     *   @param HTMLFormElement form
     */
    let insertPartaker = form => {
            request(
                    '/eArchive/Partakings/insert.php',
                    'POST',
                    'text',
                    (new FormData(form))
                )
                .then(response => reportOnAction(response))
                .then(() => $('#sciPapInsMdl').modal('hide'))
                .then(() => selectScientificPapers(form.querySelector('input[name=id_attendances]').value))
                .catch(error => alert(error)) // catch
        } // insertPartaker

    /*
     *  update record of the given scientific paper partaker
     *  @param HTMLFormElement form
     */
    let updatePartaker = form => {
            request(
                    '/eArchive/Partakings/update.php',
                    'POST',
                    'text',
                    (new FormData(form))
                )
                .then(response => reportOnAction(response))
                .then(() => $('#sciPapInsMdl').modal('hide'))
                .then(() => selectScientificPapers(form.querySelector('input[name=id_attendances]').value))
                .catch(error => alert(error)) // catch
        } // updatePartaker

    /*
     *  delete record of the given scientific paper partker    
     *  @param Number idPartakings
     */
    let deletePartaker = idPartakings => {
            request(
                    `/eArchive/Partakings/delete.php?id_partakings=${idPartakings}`,
                    'GET',
                    'text'
                )
                .then(response => reportOnAction(response))
                .then(() => selectScientificPapers(document.querySelector('form#sciPapInsFrm input[name=id_attendances]').value))
                .catch(error => alert(error)) // catch
        } // deletePartaker

    /*
     *  insert record of the scientific paper mentor 
     *  @param HTMLFormElement form
     */
    let insertMentor = form => {
            request(
                    '/eArchive/Mentorings/insert.php',
                    'POST',
                    'text',
                    (new FormData(form))
                )
                .then(response => reportOnAction(response))
                .then(() => $('#sciPapInsMdl').modal('hide'))
                .then(() => selectScientificPapers(form.querySelector('input[name=id_attendances]').value))
                .catch(error => alert(error)) // catch
        } // insertMentor

    /* 
     *   update record of scientific paper mentor       
     *   HTMLFormElement form
     */
    let updateMentor = form => {
            request(
                    '/eArchive/Mentorings/update.php',
                    'POST',
                    'text',
                    (new FormData(form))
                )
                .then(response => reportOnAction(response))
                .then(() => selectScientificPapers(form.querySelector('input[name=id_attendances]').value))
                .catch(error => alert(error)) // catch
        } // updateMentor

    /*
     *  delete record of the given scientific paper mentor       
     *  @param Number idMentorings
     */
    let deleteMentor = idMentorings => {
            request(
                    `/eArchive/Mentorings/delete.php?id_mentorings=${idMentorings}`,
                    'GET',
                    'text'
                )
                .then(response => reportOnAction(response))
                .then(() => selectScientificPapers(document.querySelector('form#sciPapInsFrm input[name=id_attendances]').value))
                .catch(error => alert(error)) // catch
        } // deleteMentor

    /*  
     *   upload documents testifying the scientific achievement 
     *   @param HTMLFormElement form
     */
    let uploadDocuments = form => {
            request(
                    '/eArchive/Documents/insert.php',
                    'POST',
                    'text',
                    (new FormData(form))
                )
                .then(response => reportOnAction(response))
                .then(() => $('#sicPapMdl').modal('hide'))
                .then(() => selectScientificPapers(form.querySelector('input[name=id_attendances').value))
                .catch(error => alert(error)) // catch
        } // uploadDocuments

    /*
     *  physically and logically delete documents testifying the scientific achievement 
     *  @param String source  
     */
    let deleteDocument = source => {
            request(
                    `/eArchive/Documents/delete.php?source=${source}`,
                    'GET',
                    'text'
                )
                .then(response => reportOnAction(response))
                .then(() => selectScientificPapers(document.querySelector('form#sciPapInsFrm input[name=id_attendances]').value))
                .catch(error => alert(error)) // catch
        } // deleteDocument

    /*
     *   select all records of scientific papers with regards to the program attendance id 
     *   @param Number idAttendances
     */
    let selectScientificPapers = idAttendances => {
            // fetch resources
            request(
                    `/eArchive/ScientificPapers/select.php?id_attendances=${idAttendances}`,
                    'GET',
                    'document'
                )
                .then(response => {
                    // compose node tree structure
                    fragment = response
                        // reflect fragments body     
                    document.querySelector('#sciPapSelMdl .modal-content').innerHTML = fragment.body.innerHTML
                        // enabling tooltips
                    $('[data-toggle="tooltip"]').tooltip()
                })
                .then(() => listenScientificPaperCards())
                .catch(error => alert(error)) // catch
        } // selectScientificPapers

    /*
     *   insert records of the scientific paper and its uploaded documents
     *   @param Event e
     *   @param HTMLFormElement form
     */
    let insertScientificPaper = (e, form) => {
            // prevent default action of submitting scientific paper data    
            e.preventDefault()
            request(
                    '/eArchive/ScientificPapers/insert.php',
                    'POST',
                    'text',
                    (new FormData(form))
                )
                .then(response => reportOnAction(response))
                .then(() => $('#sciPapInsMdl').modal('hide'))
                .catch(error => alert(error)) // catch
        } // insertScientificPaper

    /*
     *   update record of the scientific paper
     *   @param HTMLFormElement form
     */
    let updateScientificPaper = form => {
            request(
                    '/eArchive/ScientificPapers/update.php',
                    'POST',
                    'text',
                    (new FormData(form))
                )
                .then(response => reportOnAction(response))
                .then(() => $('#sciPapInsMdl').modal('hide'))
                .then(() => selectScientificPapers(form.querySelector('input[name=id_attendances]').value))
                .catch(error => alert(error)) // catch
        } // updateScientificPaper

    /*
     *  delete record of the given scientific paper
     *  @param Number idScientificPapers
     */
    let deleteScientificPaper = idScientificPapers => {
            request(
                    `/eArchive/ScientificPapers/delete.php?id_scientific_papers=${idScientificPapers}`,
                    'GET',
                    'text'
                )
                .then(response => reportOnAction(response))
                .then(() => selectScientificPapers(document.querySelector('form#sciPapInsFrm input[name=id_attendances]').value))
                .catch(error => alert(error)) // catch
        } // deleteScientificPaper

    /*
     *   upload graduation certificate document
     *   @param Event e
     */
    let uploadGraduationCertificate = e => {
            // prevent default action of submitting certificate upload form
            e.preventDefault()
            request(
                    '/eArchive/Certificates/insert.php',
                    'POST',
                    'text',
                    (new FormData(certUplFrm))
                )
                .then(response => reportOnAction(response))
                .then(() => loadStudentEvidenceTable())
                .then(() => $('#certUplMdl').modal('hide'))
                .catch(error => alert(error)) // catch
        } // uploadGraduationCertificate

    certUplFrm.addEventListener('submit', uploadGraduationCertificate)

    /*
     *  select the graduation certificate issued while attending the given program
     *  @param Number idAttendances
     */
    let selectGraduationCertificate = idAttendances => {
            request(
                    `/eArchive/Certificates/select.php?id_attendances=${idAttendances}`,
                    'GET',
                    'document'
                )
                .then(response => {
                    // compose node tree structure
                    fragment = response
                        // reflect fragments body     
                    document.querySelector('div#certSelMdl > div.modal-dialog > .modal-content').innerHTML = fragment.body.innerHTML
                })
                .then(() => listenCertificateCard())
                .catch(error => alert(error)) // catch
        } // selectGraduationCertificate

    /*
     *  update defence and issuance dates of the graduation certificate
     *  @param HTMLFormElement form
     */
    let updateGraduationCertificate = form => {
            request(
                    '/eArchive/Certificates/update.php',
                    'POST',
                    'text',
                    (new FormData(form))
                )
                .then(response => reportOnAction(response))
                .then(() => $('#certUplMdl').modal('hide'))
                .then(() => selectGraduationCertificate(form.querySelector('input[name=id_attendances]').value))
                .catch(error => alert(error)) // catch
        } // updateGraduationCertificate

    /*
     *  physically and logically delete the record on the graduation by the given program attendance id      
     *  @param Number idAttendance
     *  @param String source
     */
    let deleteGraduationCertificate = (idAttendances, source) => {
            request(
                    `/eArchive/Graduations/delete.php?id_attendances=${idAttendances}&source=${source}`,
                    'GET',
                    'text'
                )
                .then(response => reportOnAction(response))
                .then(() => loadStudentEvidenceTable())
                .then(() => $('#certSelMdl').modal('hide'))
                .catch(error => alert(error)) // catch
        } // deleteGraduationCertificate

    // load student evidence table upon latterly record amendment 
    let loadStudentEvidenceTable = () => {
            request
                (
                    '/eArchive/Accounts/authorized/studentEvidece.php',
                    'GET',
                    'document'
                )
                .then(response => {
                    // compose node tree structure
                    fragment = response
                        // reflect fragments body  
                    document.querySelector('div.table-responsive').innerHTML = fragment.body.querySelector('div.table-responsive').innerHTML
                        // enabling tooltips
                    $('[data-toggle="tooltip"]').tooltip()
                })
                .then(() => listenStudentEvidenceTable())
                .catch(error => alert(error)) // catch
        } // loadStuEvidTbl

    /*
     *   propagate passed select element with options from the requested resource 
     *   @param HTMLSelectElement select
     *   @param String script
     *   @param Number id
     */
    let propagateSelectElement = async(select, script, id = 0) => {
            try {
                const response = await request(
                    script,
                    'GET',
                    'document'
                )
                fragment = response
                    // remove previously disposable options
                while (select.options.length)
                    select.remove(0)
                    // traverse through nodes 
                fragment.body.querySelectorAll('option').forEach(option => {
                        select.add(option)
                            // if id matches options value
                        if (option.value == id)
                        // set option as selected
                            option.selected = true
                    }) // forEach
            } catch (error) {
                alert(error)
            } // catch
        } // propagateSelEl

    /*
     *  clear input field values of a form 
     *  @param HTMLFormElement form
     */
    let emptyInputFields = form => {
            form.querySelectorAll('input:not(input[type=hidden]').forEach(input => {
                    input.value = ''
                }) // forEach
        } // emptyInputFields

    /*  
     *   interpolate datalist with name, surname and index number of the momentarily inserted student
     *   @param String fullname
     *   @param String index
     */
    let interpolateDatalist = (fullname, index) => {
            let option = document.createElement('option')
            option.value = index
            option.textContent = fullname
            document.querySelector('form#sciPapInsMdl datalist').appendChild(option)
        } // interpolateDatalist

    /*  
     *   report to the user on the performed action
     *   @param String message
     */
    let reportOnAction = message => {
            reportModal.querySelector('.modal-body').textContent = message
            repMdlBtn.click()
        } // reportOnAction

    /*
     *  !recursive 
     *  create and subsequently append form controls for new temporary residence section 
     *  @param Array residences
     */
    let addTemporaryResidenceSection = (residences = null) => {
            return new Promise((resolve) => {
                    // instantiate a MutationObserver object
                    let observer = new MutationObserver(() => {
                            // if updating recorded temporary residence data 
                            if (residences) {
                                residences.shift()
                                    // if there's more records
                                if (residences.length)
                                    resolve(addTemporaryResidenceSection(residences))
                            } // if
                            else
                                resolve()
                        }), // MutationObserver
                        // form controls
                        container = document.createElement('div'),
                        headline = document.createElement('p'),
                        cross = document.createElement('span'),
                        ctryFrmGrp = document.createElement('div'),
                        postCodeFrmGrp = document.createElement('div'),
                        addrFrmGrp = document.createElement('div'),
                        countryLabel = document.createElement('label'),
                        postCodeLbl = document.createElement('label'),
                        addressLabel = document.createElement('label'),
                        statusInput = document.createElement('input'),
                        countrySelect = document.createElement('select'),
                        postCodeSlct = document.createElement('select'),
                        addressInput = document.createElement('input'),
                        index = document.querySelectorAll('div#residences > div.row').length // the following index for an array of data on student temporary residences 
                        // set the target and options of observation
                    observer.observe(
                            document.getElementById('residences'), {
                                attributes: false,
                                childList: true,
                                subtree: false
                            }
                        ) // observe
                    container.className = 'row temporary-residence'
                    container.style.position = 'relative'
                    headline.classList = 'col-12 h6'
                    cross.style.float = 'right'
                    cross.style.transform = 'scale(1.2)'
                    cross.style.cursor = 'pointer'
                    cross.innerHTML = '&times;'
                    cross.setAttribute('data-id-residences', !residences ? '' : residences[0].id_residences)
                    cross.addEventListener(
                            'click',
                            () => {
                                // if data attributes value isn't empty 
                                if (cross.getAttribute('data-id-residences') !== '')
                                    deleteTemporaryResidence(cross.getAttribute('data-id-residences'))
                                container.remove()
                            }
                        ) // addEventListener
                    ctryFrmGrp.className = 'form-group col-lg-4 col-12'
                    postCodeFrmGrp.className = 'form-group col-lg-4 col-12'
                    addrFrmGrp.className = 'form-group col-lg-4 col-12'
                    countryLabel.textContent = 'Država'
                    countryLabel.classList = 'w-100'
                    postCodeLbl.textContent = 'Kraj'
                    postCodeLbl.classList = 'w-100'
                    addressLabel.textContent = 'Naslov'
                    addressLabel.classList = 'w-100'
                    statusInput.type = 'hidden'
                    statusInput.name = `residences[${index}][status]`
                    statusInput.value = 'ZAČASNO'
                    countrySelect.classList = 'form-control country-select'
                    countrySelect.addEventListener(
                            'input',
                            () => {
                                propagateSelectElement(
                                    postCodeSlct,
                                    `/eArchive/postalCodes/select.php?id_countries=${countrySelect.selectedOptions[0].value}`
                                )
                            }
                        ) // addEventListener
                    postCodeSlct.classList = 'form-control'
                    postCodeSlct.name = `residences[${index}][id_postal_codes]`
                    postCodeSlct.required = true
                    addressInput.classList = 'form-control'
                    addressInput.type = 'text'
                    addressInput.name = `residences[${index}][address]`
                    addressInput.required = true
                    headline.appendChild(cross)
                    countryLabel.appendChild(countrySelect)
                    ctryFrmGrp.appendChild(countryLabel)
                    postCodeLbl.appendChild(postCodeSlct)
                    postCodeFrmGrp.appendChild(postCodeLbl)
                    addressLabel.appendChild(addressInput)
                    addrFrmGrp.appendChild(addressLabel)
                    container.appendChild(headline)
                    container.appendChild(statusInput)
                    container.appendChild(ctryFrmGrp)
                    container.appendChild(postCodeFrmGrp)
                    container.appendChild(addrFrmGrp)
                    propagateSelectElement(
                            countrySelect,
                            '/eArchive/Countries/select.php', !residences ? null : residences[0].id_countries
                        )
                        .then(() => {
                            propagateSelectElement(
                                postCodeSlct,
                                `/eArchive/PostalCodes/select.php?id_countries=${countrySelect.selectedOptions[0].value}`, !residences ? null : residences[0].id_postal_codes
                            )
                        }).then(() => {
                            addressInput.value = !residences ? '' : residences[0].address
                        }).then(() => document.getElementById('residences').appendChild(container))
                        .catch((error) => {
                            alert(error)
                        }) // catch
                }) // Promise
        } // addTemporaryResidenceSection

    /*
     *  create and subsequently append graduation section for a student attending particular program 
     *  @param Event e
     */
    let addProgramGraduationSection = e => {
            return new Promise((resolve) => {
                    let observer = new MutationObserver(() => resolve()),
                        // create form controls 
                        certFrmGrp = document.createElement('div'),
                        defFrmGrp = document.createElement('div'),
                        issFrmGrp = document.createElement('div'),
                        certificateLabel = document.createElement('label'),
                        defendedLabel = document.createElement('label'),
                        issuedLabel = document.createElement('label'),
                        filenameInput = document.createElement('input'),
                        certificateInput = document.createElement('input'),
                        defendedInput = document.createElement('input'),
                        issuedInput = document.createElement('input'),
                        index = e.target.getAttribute('data-index') // get next index position for attendances array 
                    observer.observe(document.getElementById('attendances'), {
                        attributes: false,
                        childList: true,
                        subtree: false
                    })
                    certFrmGrp.className = 'form-group col-lg-4 col-12'
                    defFrmGrp.className = 'form-group col-lg-4 col-12'
                    issFrmGrp.className = 'form-group col-lg-4 col-12'
                    certificateLabel.textContent = 'Certifikat'
                    certificateLabel.className = 'w-100 file-label'
                    defendedLabel.textContent = 'Zagovorjen'
                    defendedLabel.className = 'w-100'
                    issuedLabel.textContent = 'Izdan'
                    issuedLabel.className = 'w-100'
                    issuedInput.textContent = 'Izdan'
                    certificateInput.type = 'file'
                    certificateInput.setAttribute('name', 'certificate[]')
                    certificateInput.accept = '.pdf'
                    certificateInput.required = true
                        // determine hidden input type value if graduated
                    certificateInput.addEventListener(
                            'input',
                            e => {
                                filenameInput.value = e.target.files[0].name
                            }
                        ) // addEventListener
                    filenameInput.type = 'hidden'
                    filenameInput.name = `attendances[${index}][certificate]`
                    defendedInput.className = 'form-control'
                    defendedInput.type = 'date'
                    defendedInput.required = true
                    defendedInput.name = `attendances[${index}][defended]`
                    issuedInput.className = 'form-control'
                    issuedInput.type = 'date'
                    issuedInput.name = `attendances[${index}][issued]`
                    issuedInput.required = true
                        // append graduation form controls to a particular attendance section
                    certificateLabel.appendChild(certificateInput)
                    certFrmGrp.appendChild(certificateLabel)
                    defendedLabel.appendChild(defendedInput)
                    defFrmGrp.appendChild(defendedLabel)
                    issuedLabel.appendChild(issuedInput)
                    issFrmGrp.appendChild(issuedLabel)
                    e.target.closest('.row').appendChild(filenameInput)
                    e.target.closest('.row').appendChild(certFrmGrp)
                    e.target.closest('.row').appendChild(defFrmGrp)
                    e.target.closest('.row').appendChild(issFrmGrp)
                }) // Promise
        } // addProgramGraduationSection

    // subsequently create and append attendance section of the student insertion form 
    let addProgramAttendanceSection = () => {
            // create form controls
            let container = document.createElement('div'),
                headline = document.createElement('p'),
                cross = document.createElement('span'),
                facFrmGrp = document.createElement('div'),
                progFrmGrp = document.createElement('div'),
                enrollFrmGrp = document.createElement('div'),
                indexFrmGrp = document.createElement('div'),
                gradFrmGrp = document.createElement('div'),
                facultyLabel = document.createElement('label'),
                programLabel = document.createElement('label'),
                enrolledLabel = document.createElement('label'),
                graduationLabel = document.createElement('label'),
                indexLabel = document.createElement('label'),
                facultySelect = document.createElement('select'),
                programSelect = document.createElement('select'),
                enrolledInput = document.createElement('input'),
                indexInput = document.createElement('input'),
                gradCB = document.createElement('input'),
                graduationText = document.createTextNode('Diplomiral')
            index = document.querySelectorAll('div#attendances > div.row').length // the following index for an array od data on program attendance       
            gradCB.addEventListener(
                    'input',
                    e => {
                        // append or remove graduation section depending on the condition
                        // if it's checked
                        if (gradCB.checked)
                            addProgramGraduationSection(e)
                        else {
                            container.removeChild(container.lastChild)
                            container.removeChild(container.lastChild)
                            container.removeChild(container.lastChild)
                        } // else
                    }
                ) // addEventListener
            headline.className = 'col-12 h6'
            cross.style.float = 'right'
            cross.style.transform = 'scale(1.2)'
            cross.style.cursor = 'pointer'
            cross.innerHTML = '&times;'
                // remove selected attendance section
            cross.addEventListener(
                    'click',
                    () => {
                        container.remove()
                    }
                ) // addEventListener
            container.className = 'row'
            facFrmGrp.className = 'form-group col-lg-6 col-12'
            progFrmGrp.className = 'form-group col-lg-6 col-12'
            enrollFrmGrp.className = 'form-group col-lg-4 col-6'
            indexFrmGrp.className = 'form-group col-lg-4 col-6'
            gradFrmGrp.className = 'd-flex align-items-center justify-content-center form-group col-lg-4 col-12'
            facultyLabel.textContent = 'Fakulteta'
            facultyLabel.className = 'w-100'
            programLabel.textContent = 'Program'
            programLabel.className = 'w-100'
            enrolledLabel.textContent = 'Vpisan'
            enrolledLabel.className = 'w-100'
            indexLabel.textContent = 'Indeks'
            indexLabel.className = 'w-100'
            graduationLabel.className = 'mt-2'
            facultySelect.className = 'form-control'
            facultySelect.name = `attendances[${index}][id_faculties]`
            facultySelect.required = true
            facultySelect.addEventListener(
                    'input',
                    e => {
                        // propagate programs by faculty selection
                        propagateSelectElement(
                            programSelect,
                            `/eArchive/Programs/select.php?id_faculties=${e.target.selectedOptions[0].value}`
                        )
                    }
                ) // addEventListener
            programSelect.className = 'form-control'
            programSelect.name = `attendances[${index}][id_programs]`
            programSelect.required = true
            enrolledInput.className = 'form-control'
            enrolledInput.type = 'date'
            enrolledInput.name = `attendances[${index}][enrolled]`
            enrolledInput.required = true
            indexInput.className = 'form-control'
            indexInput.type = 'text'
            indexInput.name = `attendances[${index}][index]`
            indexInput.required = true
            gradCB.type = 'checkbox'
            gradCB.classList = 'mr-2'
            gradCB.setAttribute('data-index', index)
                // append controls to a form attendances section
            facultyLabel.appendChild(facultySelect)
            facFrmGrp.appendChild(facultyLabel)
            programLabel.appendChild(programSelect)
            progFrmGrp.appendChild(programLabel)
            enrolledLabel.appendChild(enrolledInput)
            enrollFrmGrp.appendChild(enrolledLabel)
            indexLabel.appendChild(indexInput)
            indexFrmGrp.appendChild(indexLabel)
            graduationLabel.appendChild(gradCB)
            graduationLabel.appendChild(graduationText)
            gradFrmGrp.appendChild(graduationLabel)
            headline.appendChild(cross)
            container.appendChild(headline)
            container.appendChild(facFrmGrp)
            container.appendChild(progFrmGrp)
            container.appendChild(enrollFrmGrp)
            container.appendChild(indexFrmGrp)
            container.appendChild(gradFrmGrp)
                // initial propagation
            propagateSelectElement(
                    facultySelect,
                    '/eArchive/Faculties/select.php'
                )
                .then(() => propagateSelectElement(
                    programSelect,
                    `/eArchive/Programs/select.php?id_faculties=${facultySelect.selectedOptions[0].value}`))
                .then(() => document.getElementById('attendances').appendChild(container))
                .catch(error => alert(error)) // catch
        } // addProgramAttendanceSection 

    // create and subsequently append partaker section of the scientific paper insertion form 
    let addPartakerSection = () => {
            return new Promise((resolve) => {
                    let observer = new MutationObserver(() => resolve()),
                        // create form controls 
                        container = document.createElement('div'),
                        headline = document.createElement('p'),
                        cross = document.createElement('span'),
                        partakerFrmGrp = document.createElement('div'),
                        partFrmGrp = document.createElement('div'),
                        partakerLabel = document.createElement('label'),
                        partLabel = document.createElement('label'),
                        partakerInput = document.createElement('input'),
                        partInput = document.createElement('input'),
                        index = document.querySelectorAll('div#sciPapPartakers > div.row').length // the following index for an array of data on a partaker  
                        // set observation criterion
                    observer.observe(document.getElementById('sciPapPartakers'), {
                        attributes: false,
                        childList: true,
                        subtree: false
                    })
                    container.classList = 'row'
                    headline.classList = 'h6 col-12'
                    cross.style.float = 'right'
                    cross.style.transform = 'scale(1.2)'
                    cross.style.cursor = 'pointer'
                    cross.innerHTML = '&times;'
                        // remove selected attendance section
                    cross.addEventListener(
                            'click',
                            () => {
                                container.remove()
                            }
                        ) // addEventListener
                    partakerFrmGrp.classList = 'form-group col-lg-6 col-12'
                    partFrmGrp.classList = 'form-group col-lg-6 col-12'
                    partakerLabel.textContent = 'Sodelovalec'
                    partakerLabel.classList = 'w-100'
                    partLabel.textContent = 'Vloga'
                    partLabel.classList = 'w-100'
                    partakerInput.classList = 'form-control'
                    partakerInput.setAttribute('list', 'students')
                    partakerInput.name = `partakers[${index}][index]`
                    partakerInput.required = true
                    partInput.classList = 'form-control'
                    partInput.type = 'text'
                    partInput.name = `partakers[${index}][part]`
                    partInput.required = true
                        // compose a node hierarchy by appending them to active tree structure 
                    headline.appendChild(cross)
                    partakerLabel.appendChild(partakerInput)
                    partakerFrmGrp.appendChild(partakerLabel)
                    partLabel.appendChild(partInput)
                    partFrmGrp.appendChild(partLabel)
                    container.appendChild(headline)
                    container.appendChild(partakerFrmGrp)
                    container.appendChild(partFrmGrp)
                    document.getElementById('sciPapPartakers').appendChild(container)
                }) // Promise
        } // addPartakerSection

    //  create and append additional form controls for uploading document of the scientific paper
    let addDocumentUploadSection = () => {
            return new Promise((resolve) => {
                    let observer = new MutationObserver(() => resolve()),
                        // form controls 
                        container = document.createElement('div'), // row
                        cross = document.createElement('span'), // removal sign
                        verFrmGrp = document.createElement('div'), // form group
                        docFrmGrp = document.createElement('div'), // form group
                        versionLabel = document.createElement('label'), // version label
                        documentLabel = document.createElement('label'), // document label
                        versionInput = document.createElement('input'), // version input
                        documentInput = document.createElement('input'), // document input 
                        filenameInput = document.createElement('input'), // document hidden input 
                        index = document.querySelectorAll('div#documents > div.row').length // the following index for an array of data on documents of scientific paper  
                        // set observation criterion
                    observer.observe(document.getElementById('sciPapDocs'), {
                        attributes: false,
                        childList: true,
                        subtree: false
                    })
                    documentInput.addEventListener(
                            'input',
                            e => {
                                // assign chosen document name as a value to the docNameInputElement
                                filenameInput.value = e.target.files[0].name
                            }
                        ) // addEventListener
                    cross.addEventListener(
                            'click',
                            () => {
                                // remove appended controls
                                container.remove()
                            }
                        ) // addEventListener
                    container.classList = 'row mt-2'
                    container.style.position = 'relative'
                    verFrmGrp.classList = 'form-group col-6'
                    docFrmGrp.classList = 'form-group col-6'
                    versionLabel.textContent = 'Verzija'
                    versionLabel.classList = 'w-100'
                    documentLabel.textContent = 'Dokument'
                    documentLabel.classList = 'w-100 file-label'
                    versionInput.classList = 'form-control'
                    versionInput.type = 'text'
                    versionInput.name = `documents[${index}][version]`
                    documentInput.type = 'file'
                    documentInput.accept = '.pdf'
                    documentInput.name = 'document[]'
                    documentInput.required = true
                    filenameInput.type = 'hidden'
                    filenameInput.name = `documents[${index}][name]`
                    cross.style.position = 'absolute'
                    cross.style.top = 0
                    cross.style.right = '10px'
                    cross.style.zIndex = 1
                    cross.style.cursor = 'pointer'
                    cross.innerHTML = '&times;'
                    versionLabel.appendChild(versionInput)
                    verFrmGrp.appendChild(versionLabel)
                    documentLabel.appendChild(documentInput)
                    docFrmGrp.appendChild(filenameInput)
                    docFrmGrp.appendChild(documentLabel)
                    container.appendChild(cross)
                    container.appendChild(verFrmGrp)
                    container.appendChild(docFrmGrp)
                        // append controls to scientific paper insert form
                    document.getElementById('sciPapDocs').appendChild(container)
                }) // Promise
        } // addDocumentUploadSection

    //  create and append additional form controls for providing data on mentors 
    let addMentorSection = () => {
            return new Promise((resolve) => {
                    let container = document.createElement('div'), // row
                        observer = new MutationObserver(() => resolve()),
                        // create form controls 
                        headline = document.createElement('p'),
                        cross = document.createElement('span'), // removal sign 
                        menFrmGrp = document.createElement('div'), // form group
                        facFrmGrp = document.createElement('div'), // form group
                        tghtFrmGrp = document.createElement('div'), // form group
                        emailFrmGrp = document.createElement('div'), // form group
                        telFrmGrp = document.createElement('div'), // form group
                        mentorLabel = document.createElement('label'), // mentor label
                        facultyLabel = document.createElement('label'), // faculty label
                        taughtLabel = document.createElement('label'), // subject label
                        emailLabel = document.createElement('label'), // email label
                        telephoneLabel = document.createElement('label'), // telephone label
                        facultySelect = document.createElement('select'), // faculty input
                        mentorSelect = document.createElement('input'), // mentor input
                        taughtInput = document.createElement('input'), // subject input
                        emailInput = document.createElement('input'), // email input
                        telephoneInput = document.createElement('input'), // telephone input
                        index = document.querySelectorAll('div#sciPapMentors > div.row').length // the following index for an array of data on documents of scientific paper  
                        // set observation criterion
                    observer.observe(document.getElementById('sciPapMentors'), {
                        attributes: false,
                        childList: true,
                        subtree: false
                    })
                    container.classList = 'row'
                    headline.classList = 'col-12 h6'
                    cross.style.float = 'right'
                    cross.style.transform = 'scale(1.2)'
                    cross.style.cursor = 'pointer'
                    cross.innerHTML = '&times;'
                        // remove selected attendance section
                    cross.addEventListener(
                            'click',
                            () => {
                                container.remove()
                            }
                        ) // addEventListener
                    menFrmGrp.classList = 'form-group col-12'
                    facFrmGrp.classList = 'form-group col-lg-6 col-12'
                    tghtFrmGrp.classList = 'form-group col-lg-6 col-12'
                    emailFrmGrp.classList = 'form-group col-6'
                    telFrmGrp.classList = 'form-group col-6'
                    facultyLabel.textContent = 'Fakulteta'
                    facultyLabel.classList = 'w-100'
                    mentorLabel.textContent = 'Mentor'
                    mentorLabel.classList = 'w-100'
                    taughtLabel.textContent = 'Poučeval'
                    taughtLabel.classList = 'w-100'
                    emailLabel.textContent = 'E-naslov'
                    emailLabel.classList = 'w-100'
                    telephoneLabel.textContent = 'Telefon'
                    telephoneLabel.classList = 'w-100'
                    facultySelect.classList = 'form-control'
                    facultySelect.name = `mentors[${index}][id_faculties]`
                    facultySelect.required = true
                    mentorSelect.classList = 'form-control'
                    mentorSelect.type = 'text'
                    mentorSelect.name = `mentors[${index}][mentor]`
                    mentorSelect.required = true
                    taughtInput.classList = 'form-control'
                    taughtInput.type = 'text'
                    taughtInput.name = `mentors[${index}][taught]`
                    taughtInput.required = true
                    emailInput.classList = 'form-control'
                    emailInput.type = 'email'
                    emailInput.name = `mentors[${index}][email]`
                    emailInput.required = true
                    telephoneInput.classList = 'form-control'
                    telephoneInput.type = 'telephone'
                    telephoneInput.name = `mentors[${index}][telephone]`
                    telephoneInput.required = true
                    headline.appendChild(cross)
                    mentorLabel.appendChild(mentorSelect)
                    menFrmGrp.appendChild(mentorLabel)
                    facultyLabel.appendChild(facultySelect)
                    facFrmGrp.appendChild(facultyLabel)
                    taughtLabel.appendChild(taughtInput)
                    tghtFrmGrp.appendChild(taughtLabel)
                    emailLabel.appendChild(emailInput)
                    emailFrmGrp.appendChild(emailLabel)
                    telephoneLabel.appendChild(telephoneInput)
                    telFrmGrp.appendChild(telephoneLabel)
                    container.appendChild(headline)
                    container.appendChild(menFrmGrp)
                    container.appendChild(facFrmGrp)
                    container.appendChild(tghtFrmGrp)
                    container.appendChild(emailFrmGrp)
                    container.appendChild(telFrmGrp)
                        // populate HTMLSelectElement with the data regarding faculties 
                    propagateSelectElement(
                            facultySelect,
                            '/eArchive/Faculties/select.php'
                        )
                        .then(() => document.getElementById('sciPapMentors').appendChild(container))
                        .catch(error => alert(error))
                }) // Promise
        } // addMentorSection

    /*
     *  fill out form fields with student birthplace particulars
     *  @param Number idPostalCodes
     *  @param Number idCountries
     */
    let setBirthplace = (idCountries, idPostalCodes) => {
            // propagate target select element with postal codes of the chosen country
            propagateSelectElement(
                    document.querySelector('#birthCtrySelEl'),
                    '/eArchive/Countries/select.php',
                    idCountries
                )
                .then(() => propagateSelectElement(
                    document.querySelector('#birthPostCodeSelEl'),
                    `/eArchive/PostalCodes/select.php?id_countries=${idCountries}`,
                    idPostalCodes
                ))
        } // setBirthplace

    /*
     *  fill out form fields with student permanent residence particulars
     *  @param Array residence
     */
    let setPermanentResidence = (residence) => {
            // create hidden input type that stores record if of the residence  
            let idResInpt = document.createElement('input')
            idResInpt.type = 'hidden'
            idResInpt.name = 'residences[0][id_residences]'
            idResInpt.value = residence.id_residences
            document.querySelector('select#permResCtrySelEl').parentElement.prepend(idResInpt)
                // propagate target select element with postal codes of the chosen country
            propagateSelectElement(
                    document.querySelector('#permResCtrySelEl'),
                    '/eArchive/Countries/select.php',
                    residence.id_countries
                )
                .then(() => propagateSelectElement(
                    document.querySelector('#permResPostCodeSelEl'),
                    `/eArchive/PostalCodes/select.php?id_countries=${residence.id_countries}`,
                    residence.id_postal_codes
                )).then(() => {
                    document.querySelector('#permResAddressInptEl').value = residence.address
                })
        } // setPermanentResidence

    /*
     *  fill out form fields with student temporary residence particulars
     *  @param Array residences
     */
    let setTemporaryResidence = residences =>
        addTemporaryResidenceSection(residences)

    /*
     *   delete student permanent residence record by the given id 
     *   @param Number idResidences
     */
    let deleteTemporaryResidence = idResidences => {
            request(
                    `/eArchive/Residences/delete.php?id_residences=${idResidences}`,
                    'GET',
                    'text'
                )
                .then(response => reportOnAction(response))
                .catch(error => alert(error)) // catch
        } // deleteTemporaryResidence

    /*
     *   insert an account record  
     *   @param Event e
     */
    let insertAccount = e => {
            // prevent default action of submutting the form containing account credentials
            e.preventDefault()
            request(
                    '/eArchive/Accounts/authorized/insert.php',
                    'POST',
                    'text',
                    (new FormData(acctInsFrm))
                )
                .then(response => reportOnAction(response))
                .then(() => $('#acctAssignMdl').modal('hide'))
                .then(() => loadStudentEvidenceTable())
                .catch(error => alert(error)) // catch
        } // insertAccount

    /*
     *   delete the account record with a unique ID and index number indicating program attendance
     *   @param Number idAttendances
     *   @param String index
     */
    let deleteAccount = (idAttendances, index) => {
            request(
                    `/eArchive/Accounts/authorized/delete.php?id_attendances=${idAttendances}&index=${index}`,
                    'GET',
                    'text'
                )
                .then(response => reportOnAction(response))
                .then(() => loadStudentEvidenceTable())
                .catch(error => alert(error)) // catch
        } // deleteAccount

    /*
     *   rearrange form when updating data related to the student
     *   @param Event e
     *   @param Object student
     */
    let toStudentUpdateForm = (e, student) => {
            let // clone from the existing form node
                cloneForm = stuInsFrm.cloneNode(true),
                idStuInpt = document.createElement('input')
            idStuInpt.type = 'hidden'
            idStuInpt.name = 'id_students'
            idStuInpt.value = e.target.getAttribute('data-id-students')
                // replace node with its clone
            document.getElementById('stuInsFrm').replaceWith(cloneForm)
            document.querySelector('div#stuInsMdl div.modal-header > h4.modal-title').textContent = 'Posodabljanje podatkov študenta'
            listenStudentInsertForm()
            cloneForm.prepend(idStuInpt)
                // fill out input fields with student particulars
            cloneForm.querySelector('input[name=name]').value = student.particulars.name
            cloneForm.querySelector('input[name=surname]').value = student.particulars.surname
            cloneForm.querySelector('input[name=email]').value = student.particulars.email
            cloneForm.querySelector('input[name=telephone]').value = student.particulars.telephone
            setBirthplace(student.particulars.id_countries, student.particulars.id_postal_codes)
            setPermanentResidence(student.permResidence)
            student.tempResidences.length ? setTemporaryResidence(student.tempResidences) : null
            cloneForm.removeChild(cloneForm.querySelector('#attendances'))
            cloneForm.querySelector('input[type=submit]').value = 'Posodobi'
            cloneForm.addEventListener(
                    'submit',
                    e => {
                        updateStudent(e, cloneForm)
                    }
                ) // addEventListener
        } // toStudentUpdateForm

    /*
     *  rearrange form when inserting data of the scientific paper partaker   
     *  @param Event e
     */
    let toPartakerInsertForm = e => {
            document.querySelector('div#sciPapInsMdl div.modal-header > h4.modal-title').textContent = 'Dodeljevanje soavtorja znanstvenega dela'
                // clone from the existing form node
            let cloneForm = sciPapInsFrm.cloneNode(true),
                idSciPapInpt = document.createElement('input')
            idSciPapInpt.type = 'hidden'
            idSciPapInpt.name = 'id_scientific_papers'
            idSciPapInpt.value = e.target.getAttribute('data-id-scientific-papers')
                // replace form element node with its clone
            document.getElementById('sciPapInsFrm').replaceWith(cloneForm)
            cloneForm.prepend(idSciPapInpt)
                // widen form group across the whole grid
            cloneForm.querySelector('#sciPapPartakers').classList = 'col-12'
            cloneForm.querySelector('input[type=submit]').value = 'Dodeli'
            listenScientificPaperInsertForm()
            addPartakerSection()
                .then(() => {
                    // remove nodes except those matching given selector expression 
                    cloneForm.querySelectorAll('div.row:nth-child(3), div#sciPapDocs, p, button').forEach(node => {
                            node.remove()
                        }) // forEach
                })
            cloneForm.addEventListener(
                    'submit',
                    e => {
                        // cancel submitting partaker data by default
                        e.preventDefault()
                        insertPartaker(cloneForm)
                    }
                ) // addEventListener
        } // toPartakerInsertForm

    /*
     *  rearrange form when updating data with regard to partaker of the scientific paper 
     *  @param Event e
     */
    let toPartakerUpdateForm = e => {
            document.querySelector('div#sciPapInsMdl div.modal-header > h4.modal-title').textContent = 'Urejanje vloge soavtorja znanstvenega dela'
                // clone from the existing form node
            let cloneForm = sciPapInsFrm.cloneNode(true),
                idPartInpt = document.createElement('input')
            idPartInpt.type = 'hidden'
            idPartInpt.name = 'id_partakings'
            idPartInpt.value = e.target.getAttribute('data-id-partakings')
                // replace form node with its clone
            document.getElementById('sciPapInsFrm').replaceWith(cloneForm)
            cloneForm.prepend(idPartInpt)
                // widen form group across the whole grid
            cloneForm.querySelector('#sciPapPartakers').classList = 'col-12'
            listenScientificPaperInsertForm()
            addPartakerSection()
                .then(() => {
                    // remove nodes except those matching given selector expression 
                    cloneForm.querySelectorAll('div#particulars, div#sciPapMentors, div#sciPapDocs, p, div.d-flex, button').forEach(node => {
                            node.parentElement.removeChild(node)
                        }) // forEach
                        // populate form fields concerning data of the partaker
                    cloneForm.querySelector('input[name="partakers[0][index]"]').value = e.target.getAttribute('data-index')
                    cloneForm.querySelector('input[name="partakers[0][part]"]').value = e.target.getAttribute('data-part')
                    cloneForm.querySelector('input[type=submit]').value = 'Uredi'
                })
            cloneForm.addEventListener(
                    'submit',
                    e => {
                        // cancel submitting partaker data by default
                        e.preventDefault()
                        updatePartaker(cloneForm)
                    }
                ) // addEventListener
        } // toPartakerUpdateForm

    /*
     *  rearrange form when inserting data regarding mentor of the scientific paper 
     *  @param Event e
     */
    let toMentorInsertForm = e => {
            document.querySelector('div#sciPapInsMdl div.modal-header > h4.modal-title').textContent = 'Določanje mentorja znanstvenega dela'
                // clone from the existing form node
            let cloneForm = sciPapInsFrm.cloneNode(true),
                idSciPapInpt = document.createElement('input')
            idSciPapInpt.type = 'hidden'
            idSciPapInpt.name = 'id_scientific_papers'
            idSciPapInpt.value = e.target.getAttribute('data-id-scientific-papers')
                // replace form element node with its clone
            document.getElementById('sciPapInsFrm').replaceWith(cloneForm)
            cloneForm.prepend(idSciPapInpt)
                // widen form group across the whole grid
            cloneForm.querySelector('#sciPapMentors').classList = 'col-12'
            cloneForm.querySelector('input[type=submit]').value = 'Določi'
            listenScientificPaperInsertForm()
            addMentorSection()
                .then(() => {
                    // remove nodes except those matching given selector expression 
                    cloneForm.querySelectorAll('div#particulars, div#sciPapPartakers, div#sciPapDocs, p, button').forEach(node => {
                            node.remove()
                        }) // forEach
                })
            cloneForm.addEventListener(
                    'submit',
                    e => {
                        // cancel submitting mentor data by default
                        e.preventDefault()
                        insertMentor(cloneForm)
                    }
                ) // addEventListener
        } // toMentorInsertForm

    /*
     *  rearrange form when updating data with regard to mentor of the scientific paper  
     *  @param Event e
     */
    let toMentorUpdateFrm = e => {
            document.querySelector('div#sciPapInsMdl div.modal-header > h4.modal-title').textContent = 'Urejanje podatkov mentorja znanstvenega dela'
            let cloneForm = sciPapInsFrm.cloneNode(true),
                idMentInpt = document.createElement('input')
            idMentInpt.type = 'hidden'
            idMentInpt.name = 'id_mentorings'
            idMentInpt.value = e.target.getAttribute('data-id-mentorings')
                // replace form element node with its clone
            document.getElementById('sciPapInsFrm').replaceWith(cloneForm)
            cloneForm.prepend(idMentInpt)
            cloneForm.querySelector('input[type=submit]').value = 'Uredi'
            listenScientificPaperInsertForm()
            addMentorSection()
                .then(() => {
                    // remove DIV nodes except matching given selector expression 
                    cloneForm.querySelectorAll('div#particulars, div#sciPapPartakers, div#sciPapDocs, p, button').forEach(node => {
                            node.remove()
                        }) // forEach
                        // widen form group across the whole grid
                    cloneForm.querySelector('#sciPapMentors').classList = 'col-12'
                }).then(() => request(
                    `/eArchive/Mentorings/select.php?id_mentorings=${e.target.getAttribute('data-id-mentorings')}`,
                    'GET',
                    'json'
                )).then(response => {
                    // populate form fields with selected mentor data
                    cloneForm.querySelector('input[name=id_mentorings]').value = e.target.getAttribute('data-id-mentorings')
                    cloneForm.querySelector('input[name="mentors[0][mentor]"]').value = response.mentor
                    cloneForm.querySelector('select[name="mentors[0][id_faculties]"]').value = response.id_faculties
                    cloneForm.querySelector('input[name="mentors[0][taught]"]').value = response.taught
                    cloneForm.querySelector('input[name="mentors[0][email]"]').value = response.email
                    cloneForm.querySelector('input[name="mentors[0][telephone]"]').value = response.telephone
                }).catch(error => alert(error)) // catch
            cloneForm.addEventListener(
                    'submit',
                    e => {
                        // prevent form from submitting updated mentor data 
                        e.preventDefault();
                        updateMentor(cloneForm)
                    }
                ) // addEventListener
        } // toMentorInsertForm

    /*
     *   rearrange form for uploading document of the subject scientific paper
     *   @param Event e
     */
    let toScientificPaperUploadForm = e => {
            document.querySelector('div#sciPapInsMdl div.modal-header > h4.modal-title').textContent = 'Nalaganje dokumentov znanstvenega dela'
                // clone from the existing form node
            let cloneForm = sciPapInsFrm.cloneNode(true),
                idSciPapInpt = document.createElement('input')
            idSciPapInpt.type = 'hidden'
            idSciPapInpt.name = 'id_scientific_papers'
            idSciPapInpt.value = e.target.getAttribute('data-id-scientific-papers')
                // replace form node with its clone
            document.getElementById('sciPapInsFrm').replaceWith(cloneForm)
            cloneForm.prepend(idSciPapInpt)
                // widen form group across the whole grid
            cloneForm.querySelector('#sciPapDocs').classList = 'col-12 mb-3'
            cloneForm.querySelector('input[type=submit]').value = 'Naloži'
            listenScientificPaperInsertForm()
                // remove nodes except those matching given selector expression 
            cloneForm.querySelectorAll('div#particulars, div#sciPapPartakers, div#sciPapMentors').forEach(node => {
                    node.remove()
                }) // forEach
            cloneForm.addEventListener(
                    'submit',
                    e => {
                        // prevent upload of scientific paper documents
                        e.preventDefault()
                        uploadDocuments(cloneForm)
                    }
                ) // addEventListener
        } // toScientificPaperUploadForm

    /*
     *  rearrange form when updating data regarding students graduation certificate  
     *  @param Event e
     */
    let toCertificateUpdateForm = e => {
            document.querySelector('div#certUplMdl div.modal-header > h5.modal-title').textContent = 'Urejanje podatkov certifikata'
                // clone from the existing form node
            let cloneForm = certUplFrm.cloneNode(true),
                idCertInpt = document.createElement('input')
            idCertInpt.type = 'hidden'
            idCertInpt.name = 'id_certificates'
            idCertInpt.value = e.target.getAttribute('data-id-certificates')
                // replace form element node with its clone
            document.getElementById('certUplFrm').replaceWith(cloneForm)
            cloneForm.prepend(idCertInpt)
            listenCertificateCard()
                // remove certificate file input 
            cloneForm.querySelector('div.row > div.form-group').remove()
                // fill out form fileds with carried data
            cloneForm.querySelector('input[name=defended]').value = e.target.getAttribute('data-defended')
            cloneForm.querySelector('input[name=issued]').value = e.target.getAttribute('data-issued')
                // change submit buttons value
            cloneForm.querySelector('input[type=submit]').value = 'Uredi'
            cloneForm.addEventListener(
                    'submit',
                    e => {
                        // cancel submitting updated certificate data by default
                        e.preventDefault()
                        updateGraduationCertificate(cloneForm)
                    }
                ) // addEventListener
        } // toCertificateUpdateForm

    /*
     *   rearrange form when interpolating data regarding scientific paper and uploading its documents    
     *   @param Event e
     */
    let toScientificPaperInsertForm = e => {
            document.querySelector('div#sciPapInsMdl div.modal-header > h4.modal-title').textContent = 'Vstavljanje znanstvenega dela'
                // clone from the existing form node
            let cloneForm = sciPapInsFrm.cloneNode(true)
            cloneForm.querySelector('input[name=id_attendances]').value = e.target.getAttribute('data-id-attendances')
                // replace form element node with its clone
            document.getElementById('sciPapInsFrm').replaceWith(cloneForm)
            cloneForm.querySelector('input[type=submit]').value = 'Vstavi'
                // enable tooltips
            $('[data-toggle="tooltip"]').tooltip()
            listenScientificPaperInsertForm()
            cloneForm.addEventListener(
                    'submit',
                    e => { insertScientificPaper(e, cloneForm) }
                ) // addEventListner
        } // toScientificPaperForm

    /*
     *   rearrange form and fill out form fields when updating student data
     *   @param Object sciPap
     */
    let toScientificPaperUpdateForm = sciPap => {
            document.querySelector('div#sciPapInsMdl div.modal-header > h4.modal-title').textContent = 'Urejanje podatkov znanstvenega dela'
                // clone from the existing form node
            let cloneForm = sciPapInsFrm.cloneNode(true),
                idSciPapInpt = document.createElement('input')
            idSciPapInpt.type = 'hidden'
            idSciPapInpt.name = 'id_scientific_papers'
            idSciPapInpt.value = sciPap.id_scientific_papers
                // replace form element node with its clone
            document.getElementById('sciPapInsFrm').replaceWith(cloneForm)
            cloneForm.prepend(idSciPapInpt)
            listenScientificPaperInsertForm()
            cloneForm.querySelector('input[name="topic"]').value = sciPap.topic
            cloneForm.querySelector('select[name="type"]').value = sciPap.type
            cloneForm.querySelector('input[name="written"]').value = sciPap.written
            cloneForm.querySelector('input[type=submit]').value = 'Uredi'
                // remove determined element nodes 
            cloneForm.querySelectorAll('div.row:nth-child(4), div#sciPapDocs').forEach(node => {
                    node.remove()
                }) // forEach
            cloneForm.addEventListener(
                    'submit',
                    e => {
                        // prevent default action of submitting scientific paper data    
                        e.preventDefault()
                        updateScientificPaper(cloneForm)
                    }
                ) // addEventListener
        } // toScientificPaperUpdateForm

    // rearrange form when inserting a student record  
    let toStudentInsertForm = () => {
            // clone from the existing form node
            let cloneForm = stuInsFrm.cloneNode(true)
                // replace form element node with its clone
            document.getElementById('stuInsFrm').replaceWith(cloneForm)
            document.querySelector('div#stuInsMdl div.modal-header > h4.modal-title').textContent = 'Vstavljanje študenta'
            cloneForm.querySelector('input[type=submit]').value = 'Vstavi'
                // enabling tooltips
            $('[data-toggle="tooltip"]').tooltip()
            listenStudentInsertForm()
            cloneForm.addEventListener('submit', e => insertStudent(e, cloneForm))
        } // toStudentInsertForm

    // attach event listeners to corresponding input element 
    let listenScientificPaperInsertForm = () => {
            // get the form 
            let form = document.getElementById('sciPapInsFrm')
                // if button for subsequent partaker section additon exists
            if (form.querySelector('#addPartBtn'))
                form.querySelector('#addPartBtn').addEventListener(
                    'click',
                    addPartakerSection
                )
                // if file input is rendered 
            if (form.querySelector('input[name="document[]"]'))
                form.querySelector('input[name="document[]"]').addEventListener(
                    'input',
                    e => {
                        // assign the filename of the uploaded document to the hidden input type
                        form.querySelector('input[name="documents[0][name]"]').value = e.target.files[0].name
                    }
                ) // addEventListener
                // if button for subsequent mentor section additon exists 
            if (form.querySelector('#addMentBtn'))
                form.querySelector('#addMentBtn').addEventListener(
                    'click',
                    addMentorSection
                )
                // if button for subsequent document section additon exists
            if (form.querySelector('#addDocBtn'))
            // append controls for additional scientific paper document upload
                form.querySelector('#addDocBtn').addEventListener(
                'click',
                addDocumentUploadSection
            )
        } // listenScientificPaperInsertForm

    // attach event listeners to corresponding input and selecet elements
    let listenStudentInsertForm = () => {
            let addTempRes = document.getElementById('addTempResBtn'), // button for appending addiational temporary residence section 
                addAttendance = document.getElementById('addAttendBtn'), // button for apppending additional program attendance section
                birthCtrySlct = document.getElementById('birthCtrySlct'),
                permResCtrySlct = document.getElementById('permResCtrySlct'),
                facultySelect = document.getElementById('facSlct'), // faculty select element
                gradCB = document.getElementById('gradCB') // checkbox for denoting graduation
            addTempRes.addEventListener(
                    'click',
                    () => {
                        addTemporaryResidenceSection()
                    }
                ) // addEventListener
            addAttendance.addEventListener(
                'click',
                addProgramAttendanceSection
            )
            birthCtrySlct.addEventListener(
                    // propagate postal codes by selected country 
                    'input',
                    () => propagateSelectElement(
                        document.getElementById('birthPostCodeSlct'),
                        `/eArchive/PostalCodes/select.php?id_countries=${birthCtrySlct.selectedOptions[0].value}`
                    )
                ) // addEventListener
            permResCtrySlct.addEventListener(
                    // propagate postal codes by selected country 
                    'input',
                    () => propagateSelectElement(
                        document.getElementById('permResPostCodeSlct'),
                        `/eArchive/PostalCodes/select.php?id_countries=${permResCtrySlct.selectedOptions[0].value}`
                    )
                ) // addEventListener
            facultySelect.addEventListener(
                    'input',
                    () => {
                        // propagate programs by faculty selection
                        propagateSelectElement(
                            document.getElementById('progSlct'),
                            `/eArchive/Programs/select.php?id_faculties=${facultySelect.selectedOptions[0].value}`
                        )
                    }) // addEventListener
            gradCB.addEventListener(
                    'input',
                    e => {
                        // if it's checked
                        if (gradCB.checked)
                        // append graduation section if graduated
                            addProgramGraduationSection(e)
                        else {
                            // remove selected graduation section
                            gradCB.closest('.row').removeChild(gradCB.closest('.row').lastElementChild)
                            gradCB.closest('.row').removeChild(gradCB.closest('.row').lastElementChild)
                            gradCB.closest('.row').removeChild(gradCB.closest('.row').lastElementChild)
                        } // else
                    }
                ) // addEventListener
        } // listenStudentInsertForm

    // attach event listeners to a scientific paper cards when rendered
    let listenScientificPaperCards = () => {
            // if anchor nodes for partaker insertion exist
            if (document.querySelectorAll('.par-ins-img'))
                document.querySelectorAll('.par-ins-img').forEach(image => {
                    // form will contain only control for partaker insertion
                    image.addEventListener('click', toPartakerInsertForm)
                }) // forEach
                // if spans for scientific paper partaker deletion exist
            if (document.querySelectorAll('.par-del-img'))
                document.querySelectorAll('.par-del-img').forEach(image => {
                    // attempt deletion of a partaker
                    image.addEventListener(
                            'click',
                            () => {
                                deletePartaker(image.getAttribute('data-id-partakings'))
                            }
                        ) // addEventListener
                }) // forEach
                // if anchors for scientific paper partaker data update exist
            if (document.querySelectorAll('.par-upd-img'))
                document.querySelectorAll('.par-upd-img').forEach(image => {
                    // attempt deletion of a partaker
                    image.addEventListener('click', toPartakerUpdateForm) // addEventListener
                }) // forEach
                // if anchors for mentor insertion are rendered
            if (document.querySelectorAll('.men-ins-img'))
                document.querySelectorAll('.men-ins-img').forEach(image => {
                    // restructure form for document upload
                    image.addEventListener('click', toMentorInsertForm)
                }) // forEach
                // if anchor elements for mentor data update exist
            if (document.querySelectorAll('.men-upd-img'))
                document.querySelectorAll('.men-upd-img').forEach(image => {
                    // restructure form for document upload
                    image.addEventListener('click', toMentorUpdateFrm)
                }) // forEachF
                // if span elements for mentor deletion are rendered
            if (document.querySelectorAll('.men-del-img'))
                document.querySelectorAll('.men-del-img').forEach(image => {
                    // restructure form for document upload
                    image.addEventListener(
                            'click',
                            () => {
                                deleteMentor(image.getAttribute('data-id-mentorings'))
                            }
                        ) // addEventListener
                }) // forEach
                // if anchors for scientific paper update are rendered
            if (document.querySelectorAll('.sp-upd-а'))
                document.querySelectorAll('.sp-upd-а').forEach(anchor => {
                    // fill form fields and modify the form
                    anchor.addEventListener('click', e => {
                            request(
                                    `/eArchive/ScientificPapers/select.php?id_scientific_papers=${anchor.getAttribute('data-id-scientific-papers')}`,
                                    'GET',
                                    'json'
                                )
                                .then(response => toScientificPaperUpdateForm(response))
                                .catch(error => alert(error)) // catch
                        }) // addEventListener
                }) // forEach
                // if anchors for scientific paper deletion are rendered
            if (document.querySelectorAll('.sp-del-a'))
                document.querySelectorAll('.sp-del-a').forEach(anchor => {
                    anchor.addEventListener(
                            'click',
                            () => {
                                deleteScientificPaper(anchor.getAttribute('data-id-scientific-papers'))
                            }
                        ) // addEventListener
                }) // forEach
                // if anchors for scientific paper document upload exist
            if (document.querySelectorAll('.doc-upl-img'))
                document.querySelectorAll('.doc-upl-img').forEach(image => {
                    // delete particular document
                    image.addEventListener('click', toScientificPaperUploadForm)
                }) // forEach
                // if anchors for scientific paper documentation deletion are rendered
            if (document.querySelectorAll('.doc-del-img'))
                document.querySelectorAll('.doc-del-img').forEach(image => {
                    // delete particular document
                    image.addEventListener(
                            'click',
                            () => {
                                deleteDocument(image.getAttribute('data-source'))
                            }
                        ) // addEventListener
                }) // forEach
        } // listenScientificPaperCards

    // attach listeners to certificate card when selected
    let listenCertificateCard = () => {
            // get modal for graduation certificate review
            let modal = document.getElementById('certSelMdl')
                // if anchor element for update of certificate connected data exist
            if (modal.querySelector('.modal-content .cert-upd-a'))
                modal.querySelector('.modal-content .cert-upd-a').addEventListener('click', toCertificateUpdateForm)
                // if anchor element for certificate deletion is contained
            if (modal.querySelector('.modal-content .cert-del-a'))
                modal.querySelector('.modal-content .cert-del-a').addEventListener(
                    'click',
                    e => {
                        deleteGraduationCertificate(e.target.getAttribute('data-id-attendances'), e.target.getAttribute('data-source'))
                    }
                ) // addEventListner
        } // listenCertificateCard

    certUplFrm.querySelector('input[type=file]').addEventListener(
            'input',
            () => {
                // assign the name of the uploaded certificate to hidden input type
                certUplFrm.querySelector('input[name=certificate]').value = certUplFrm.querySelector('input[type=file]').files[0].name
            }
        ) // addEventListener

    acctInsFrm.addEventListener(
            'submit',
            e => {
                // prevent form from submitting account details  
                e.preventDefault()
                insertAccount(e)
            }
        ) // addEventListener

    fltInpEl.addEventListener('input', () => {
            // filter students by their index numbers 
            selectStudentsByIndex(fltInpEl.value)
        }) // addEventListener

    // attach listeners to student evidence table appropriate anchors and buttons   
    let listenStudentEvidenceTable = () => {
            let stuInsBtn = document.getElementById('stuInsBtn'), // button for exposing form for student scientific achievements insertion
                sciPapSelLst = document.querySelectorAll('.sp-sel-img'), // array of images for exposing scientific papers of the student
                sciPapInsLst = document.querySelectorAll('.sp-ins-img'), // array of anchors for exposing form for insertion of the scientific papers and belonging documents
                certInsLst = document.querySelectorAll('.cert-ins-a'), // array of anchors for exposing form for uploading students graduation certificate
                certSelLst = document.querySelectorAll('.cert-vw-a'), // array of anchors for exposing graduation certificate of the student
                acctInsLst = document.querySelectorAll('.acc-ins-a'), // array of buttons for exposing form for assigning an account to student
                acctDelLst = document.querySelectorAll('.acc-del-img'), // array of buttons for deletion of a particular student account 
                stuUpdLst = document.querySelectorAll('.stu-upd-img'), // array of anchors for exposing form for updating fundamental data of the student
                stuDelLst = document.querySelectorAll('.stu-del-a') // array of anchors for exposing form for deletion of fundamental data of the student
            stuInsBtn.addEventListener(
                    'click',
                    toStudentInsertForm
                ) // addEventListener
            sciPapSelLst.forEach(image => {
                    // preview scientific papers   
                    image.addEventListener(
                            'click',
                            () => {
                                selectScientificPapers(image.getAttribute('data-id-attendances'))
                                sciPapInsFrm.querySelector('input[name=id_attendances]').value = image.getAttribute('data-id-attendances')
                            }
                        ) //addEventListener
                }) // forEach
            sciPapInsLst.forEach(image => {
                    // modify form for scientific paper insertion
                    image.addEventListener('click', toScientificPaperInsertForm)
                }) // forEach
            certInsLst.forEach(anchor => {
                    // assign an attendance id value to an upload forms hidden input type 
                    anchor.addEventListener(
                            'click',
                            () => {
                                certUplFrm.querySelector('input[type=hidden]').value = anchor.getAttribute('data-id-attendances')
                            }
                        ) //addEventListener
                }) // forEach
            certSelLst.forEach(anchor => {
                    // view certificate particulars in a form of a card in the modal
                    anchor.addEventListener(
                            'click',
                            () => {
                                selectGraduationCertificate(anchor.getAttribute('data-id-attendances'))
                                    // set value of id to the hidden input of the form
                                certUplFrm.querySelector('input[name=id_attendances]').value = anchor.getAttribute('data-id-attendances')
                            }
                        ) // addEventListener
                }) // forEach
            acctInsLst.forEach(a => {
                    // pass an id and index of an attendance through forms hidden input types 
                    a.addEventListener(
                            'click',
                            () => {
                                acctInsFrm.querySelector('input[name=id_attendances]').value = a.dataset.idAttendances
                                acctInsFrm.querySelector('input[name=index]').value = a.getAttribute('data-index')
                            }
                        ) // addEventListener
                }) // forEach
            acctDelLst.forEach(image => {
                    // delete particular account 
                    image.addEventListener(
                            'click',
                            () => {
                                deleteAccount(image.getAttribute('data-id-attendances'), image.getAttribute('data-index'))
                            }
                        ) //addEventListener
                }) // forEach
            stuUpdLst.forEach(image => {
                    // propagate update form with student particulars
                    image.addEventListener(
                            'click',
                            e => {
                                selectStudent(e, image.getAttribute('data-id-students'))
                            }
                        ) // addEventListener
                }) // forEach
            stuDelLst.forEach(anchor => {
                    // delete student from the student evidence table
                    anchor.addEventListener(
                            'click',
                            () => {
                                // if record deletion was confirmed
                                if (confirm('S sprejemanjem boste izbrisali vse podatke o študentu ter podatke o znanstvenih dosežkih!'))
                                    deleteStudent(anchor.getAttribute('data-id-attendances'), anchor.getAttribute('data-id-students'), anchor.getAttribute('data-index'))
                            }
                        ) // addEventListener
                }) // forEach
        } // attachStudentTableListeners

    listenStudentEvidenceTable()

    // enabling tooltips
    $('[data-toggle="tooltip"]').tooltip()
})()