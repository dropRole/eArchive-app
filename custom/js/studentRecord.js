// IIFE
(() => {
    // global scope variable declaration
    var fragment = new DocumentFragment(), // minimal document object structure
        studentFrm = document.getElementById('studentFrm'), // form for student data insertion and update
        studentFrmClone = studentFrm.cloneNode(true), // studentFrm clone node
        sPFrm = document.getElementById('sPFrm'), // scientific paper insert form
        sPFrmClone = sPFrm.cloneNode(true), // sPFrm clone node
        accountFrm = document.getElementById('accountFrm'), // form for creating student account and its credentials   
        certificateFrm = document.getElementById('certificateFrm'), // certificate upload/insertion form
        reportMdl = document.getElementById('reportMdl'), // report modal 
        reportMdlBtn = document.getElementById('reportMdlBtn'), // button for report modal toggle
        addTRBtn = document.getElementById('addTRBtn'), // button for residence addition 
        addAttendanceBtn = document.getElementById('addAttendanceBtn'), // button for attendance addition
        countryLst = document.querySelectorAll('.country-select'), // select elements for birth, permanent and temporal residence country 
        facultySlct = document.getElementById('facultySlct'), // faculty select input
        graduationCB = document.getElementById('graduationCB'), // graduation checkbox
        documentInpt = document.getElementById('documentInpt') // document input
    studentFrm.addEventListener('submit', insertStudent)
    sPFrm.addEventListener('submit', insertScientificPaper)
    accountFrm.addEventListener('submit', e => {
            // prevent form from submitting account details  
            e.preventDefault()
            insertStudentAccount(e, accountFrm)
        }) // addEventListener
    certificateFrm.addEventListener('submit', insertCertificate)
    certificateFrm.querySelector('input[type=file').addEventListener('change', () => {
            certificateFrm.querySelector('input[name=certificate]').value = certificateFrm.querySelector('input[type=file]').files[0].name
        }) // addEventListener
    addTRBtn.addEventListener('click', addResidence)
    addAttendanceBtn.addEventListener('click', addAttendance)
    countryLst.forEach(select => {
            // propagate target select element with postal codes of the chosen country
            select.addEventListener('input', () => {
                    propagateSelectElement(document.querySelector(`#${select.getAttribute('data-target')}`), `/eArchive/PostalCodes/select.php?id_countries=${select.selectedOptions[0].value}`)
                }) // addEventListener
        }) // forEach
        // propagate programs by faculty selection
    facultySlct.addEventListener('change', e => {
            propagateSelectElement(document.getElementById('programSlct'), `/eArchive/Programs/select.php?id_faculties=${facultySlct.selectedOptions[0].value}`)
        }) // addEventListener
        // append graduation section if graduated
    graduationCB.addEventListener('change', e => {
            // if it's checked
            if (graduationCB.checked)
                addGraduation(e)
            else {
                // remove selected graduation section
                graduationCB.closest('.row').removeChild(graduationCB.closest('.row').lastElementChild)
                graduationCB.closest('.row').removeChild(graduationCB.closest('.row').lastElementChild)
                graduationCB.closest('.row').removeChild(graduationCB.closest('.row').lastElementChild)
            } // else
        }) // addEventListener
        // give hidden input type value of chosens document name
    documentInpt.addEventListener('change', e => {
            document.getElementById('docHInpt').value = e.target.files[0].name
        }) // addEventListener        
        // attach listeners to student evidence table elements  
    function attachTableListeners() {
        let sPVALst = document.querySelectorAll('.sp-vw-a'), // anchor list for scientific papers selection
            sPIALst = document.querySelectorAll('.sp-ins-a'), // anchor list for scientific papers insertion
            certIALst = document.querySelectorAll('.cert-ins-a'), // anchor list for certificate insertion
            certVALst = document.querySelectorAll('.cert-vw-a'), // anchor list for certificate view
            accDBtnLst = document.querySelectorAll('.acc-del-btn'), // button list for account deletion
            stuUALst = document.querySelectorAll('.stu-upd-a'), // anchor list for student data update
            stuDALst = document.querySelectorAll('.stu-del-a') // anchor list for student data deletion
        accDBtnLst.forEach(btn => {
                // delete particular account 
                btn.addEventListener('click', () => {
                        deleteStudentAccount(btn.getAttribute('data-id'))
                    }) //addEventListener
            }) // forEach
        sPVALst.forEach(anchor => {
                // preview scientific papers   
                anchor.addEventListener('click', () => {
                        selectScientificPapers(anchor.getAttribute('data-id'))
                    }) //addEventListener
            }) // forEach
        sPIALst.forEach(anchor => {
                // modify form for scientific paper insertion
                anchor.addEventListener('click', e => {
                        modifysPFrm(e, null, 'insert')
                    }) //addEventListener
            }) // forEach
        certIALst.forEach(anchor => {
                // assign an attendance id value to an upload forms hidden input type 
                anchor.addEventListener('click', e => {
                        certificateFrm.querySelector('input[type=hidden]').value = anchor.getAttribute('data-id')
                    }) //addEventListener
            }) // forEach
        certVALst.forEach(anchor => {
                // view certificate particulars in a form of a card in the modal
                anchor.addEventListener('click', () => {
                        selectCertificate(anchor.getAttribute('data-id'))
                    }) // addEventListener
            }) // forEach
        stuUALst.forEach(anchor => {
                // propagate update form with student particulars
                anchor.addEventListener('click', e => {
                        selectStudent(e, anchor.getAttribute('data-id'))
                    }) // addEventListener
            }) // forEach
        stuDALst.forEach(anchor => {
                // delete student from the student evidence table
                anchor.addEventListener('click', () => {
                        // if record deletion was confirmed
                        if (confirm('S sprejemanjem boste izbrisali vse podatke o študentu ter podatke o znanstvenih dosežkih!'))
                            deleteStudent(anchor.getAttribute('data-id-students'), anchor.getAttribute('data-id-attendances'))
                    }) // addEventListener
            }) // forEach
    } // attachTableListeners
    attachTableListeners()
        // refresh students evidence table upon latterly data amendmantion 
    function refreshStudentsTable() {
        let xmlhttp = new XMLHttpRequest
            // report on data insertion
        xmlhttp.addEventListener('load', () => {
                let tblCtr = document.querySelector('.table-responsive')
                    // compose node tree structure
                fragment = xmlhttp.response
                    // reflect fragments body innerHTML 
                tblCtr.innerHTML = fragment.body.innerHTML
                attachTableListeners()
            }) // addEventListener
        xmlhttp.open('GET', '/eArchive/Students/selectAll.php', true)
        xmlhttp.responseType = 'document'
        xmlhttp.send()
    } // refreshStudentsTable
    // propagate select control with suitable options
    function propagateSelectElement(select, script) {
        let xmlhttp = new XMLHttpRequest()
        xmlhttp.addEventListener('load', () => {
                fragment = xmlhttp.response
                    // remove options while on disposal
                while (select.options.length) {
                    select.remove(0)
                } // while
                // traverse through nodes 
                fragment.body.querySelectorAll('option').forEach(element => {
                        select.add(element)
                    }) // forEach
            }) // addEventListener
        xmlhttp.open('GET', script)
        xmlhttp.responseType = 'document'
        xmlhttp.send()
    } // propagateSelectElement

    // create and append additional form residence section controls 
    function addResidence() {
        // create form controls 
        let xmlhttp = new XMLHttpRequest(),
            container = document.createElement('div'),
            headline = document.createElement('p'),
            cross = document.createElement('span'),
            countryFG = document.createElement('div'),
            postalCodeFG = document.createElement('div'),
            addressFG = document.createElement('div'),
            countryLbl = document.createElement('label'),
            postalCodeLbl = document.createElement('label'),
            addressLbl = document.createElement('label'),
            postalCodeSlct = document.createElement('select'),
            countrySlct = document.createElement('select'),
            addressInpt = document.createElement('input'),
            lblNum = document.querySelectorAll('#residences .row').length, // number of added temporal residences 
            indx = lblNum - 1 // the following index for an array of data on student residences 
        container.className = 'row'
        container.style.position = 'relative'
        headline.classList = 'col-12 h6'
        headline.textContent = `${lblNum}. začasno bivališče`
        cross.style.float = 'right'
        cross.style.transform = 'scale(1.2)'
        cross.style.cursor = 'pointer'
            // remove selected residence section
        cross.addEventListener('click', () => {
                document.getElementById('residences').removeChild(container)
            }) // addEventListener
        cross.innerHTML = '&#10007;'
        countryFG.className = 'form-group col-4'
        postalCodeFG.className = 'form-group col-4'
        addressFG.className = 'form-group col-4'
        countryLbl.setAttribute('for', `TRCountrySlct${lblNum}`)
        countryLbl.textContent = 'Država'
        postalCodeLbl.setAttribute('for', `TRPCSlct${lblNum}`)
        postalCodeLbl.textContent = 'Kraj'
        addressLbl.setAttribute('for', `TRAddressInpt${lblNum}`)
        addressLbl.textContent = 'Naslov'
        countrySlct.id = `TRCountrySlct${lblNum}`
        countrySlct.classList = 'form-control country-select'
            // propagate postal codes by country selection
        countrySlct.addEventListener('input', e => {
                propagateSelectElement(postalCodeSlct, `/eArchive/PostalCodes/select.php?id_countries=${e.target.selectedOptions[0].value}`)
            }) // addEventListener
        postalCodeSlct.id = `TRPCSlct${lblNum}`
        addressInpt.id = `TRAddressInpt${lblNum}`
        postalCodeSlct.classList = 'form-control'
        postalCodeSlct.name = `residences[${indx}][id_postal_codes]`
        postalCodeSlct.required = true
        addressInpt.classList = 'form-control'
        addressInpt.type = 'text'
        addressInpt.name = `residences[${indx}][address]`
        addressInpt.required = true
            // propagate countries by adding new residence
        xmlhttp.addEventListener('load', () => {
                fragment = xmlhttp.response
                    // traverse through nodes
                fragment.body.querySelectorAll('option').forEach(element => {
                        countrySlct.add(element)
                    }) // forEach
                    // append controls to a form residence section
                headline.appendChild(cross)
                countryFG.appendChild(countryLbl)
                countryFG.appendChild(countrySlct)
                postalCodeFG.appendChild(postalCodeLbl)
                postalCodeFG.appendChild(postalCodeSlct)
                addressFG.appendChild(addressLbl)
                addressFG.appendChild(addressInpt)
                container.appendChild(headline)
                container.appendChild(countryFG)
                container.appendChild(postalCodeFG)
                container.appendChild(addressFG)
                document.querySelector('#residences').appendChild(container)
            }) // addEventListener
        xmlhttp.responseType = 'document'
        xmlhttp.open('GET', '/eArchive/Countries/select.php')
        xmlhttp.send()
    } // addResidence

    // create and append subsequent graduation form controls 
    function addGraduation(e) {
        let lblNum = e.target.getAttribute('data-lbl-nm'), // get ordinal number for label numeration   
            indx = e.target.getAttribute('data-indx'), // get next index position for attendances array 
            // create form controls 
            certificateFG = document.createElement('div'),
            defendedFG = document.createElement('div'),
            issuedFG = document.createElement('div'),
            certificateLbl = document.createElement('label'),
            defendedLbl = document.createElement('label'),
            issuedLbl = document.createElement('label'),
            certificateHiddInpt = document.createElement('input'),
            certificateInpt = document.createElement('input'),
            defendedInpt = document.createElement('input'),
            issuedInpt = document.createElement('input')
        certificateFG.className = 'form-group col-4'
        defendedFG.className = 'form-group col-4'
        issuedFG.className = 'form-group col-4'
        certificateLbl.textContent = 'Certifikat'
        certificateLbl.setAttribute('for', `certificateInpt${lblNum}`)
        defendedLbl.textContent = 'Zagovorjen'
        defendedLbl.setAttribute('for', `defendedInpt${lblNum}`)
        issuedLbl.textContent = 'Izdan'
        issuedLbl.setAttribute('for', `issuedInpt${lblNum}`)
        issuedInpt.textContent = 'Izdan'
        issuedInpt.setAttribute('for', `iInpt${lblNum}`)
        certificateInpt.id = `certificateInpt${lblNum}`
        certificateInpt.type = 'file'
        certificateInpt.setAttribute('name', 'certificate[]')
        certificateInpt.accept = '.pdf'
        certificateInpt.required = true
            // determine hidden input type value if graduated
        certificateInpt.addEventListener('change', e => {
                certificateInpt.value = e.target.files[0].name
            }) // addEventListener
        certificateHiddInpt.type = 'hidden'
        certificateHiddInpt.name = `attendances[${indx}][certificate]`
        defendedInpt.id = `defendedInpt${lblNum}`
        defendedInpt.className = 'form-control'
        defendedInpt.type = 'date'
        defendedInpt.required = true
        defendedInpt.name = `attendances[${indx}][defended]`
        issuedInpt.id = `issuedInpt${lblNum}`
        issuedInpt.className = 'form-control'
        issuedInpt.type = 'date'
        issuedInpt.name = `attendances[${indx}][issued]`
        issuedInpt.required = true
            // append graduation form controls to a particular attendance section
        certificateFG.appendChild(certificateLbl)
        certificateFG.appendChild(certificateInpt)
        defendedFG.appendChild(defendedLbl)
        defendedFG.appendChild(defendedInpt)
        issuedFG.appendChild(issuedLbl)
        issuedFG.appendChild(issuedInpt)
        e.target.closest('.row').appendChild(certificateHiddInpt)
        e.target.closest('.row').appendChild(certificateFG)
        e.target.closest('.row').appendChild(defendedFG)
        e.target.closest('.row').appendChild(issuedFG)
    } // addGraduation

    // create and append subsequent attendance form controls 
    function addAttendance() {
        // create form controls
        let container = document.createElement('div'),
            headline = document.createElement('p'),
            cross = document.createElement('span'),
            facultyFG = document.createElement('div'),
            programFG = document.createElement('div'),
            enrolledFG = document.createElement('div'),
            indexFG = document.createElement('div'),
            graduationFG = document.createElement('div'),
            facultyLbl = document.createElement('label'),
            programLbl = document.createElement('label'),
            enrolledLbl = document.createElement('label'),
            graduationLbl = document.createElement('label'),
            indexLbl = document.createElement('label'),
            facultySlct = document.createElement('select'),
            programSlct = document.createElement('select'),
            enrolledInpt = document.createElement('input'),
            indexInpt = document.createElement('input'),
            graduationCB = document.createElement('input'),
            lblNum = document.querySelectorAll('#attendances .row').length, // number of added program attendances  
            indx = lblNum - 1 // the following index for an array od data on program attendance 
            // initial propagation
        propagateSelectElement(facultySlct, '/eArchive/Faculties/select.php')
        setTimeout(() => {
                propagateSelectElement(programSlct, `/eArchive/Programs/select.php?id_faculties=${facultySlct.selectedOptions[0].value}`)
            }, 500) // setTimeout
            // propagate programs by faculty selection
        facultySlct.addEventListener('change', e => {
                propagateSelectElement(programSlct, `/eArchive/Programs/select.php?id_faculties=${e.target.selectedOptions[0].value}`)
            }) // addEventListener
            // append graduation section if graduated        
        graduationCB.addEventListener('change', e => {
                // if it's checked
                if (graduationCB.checked)
                    addGraduation(e)
                else {
                    // remove selected graduation section
                    graduationCB.closest('.row').removeChild(graduationCB.closest('.row').lastElementChild)
                    graduationCB.closest('.row').removeChild(graduationCB.closest('.row').lastElementChild)
                    graduationCB.closest('.row').removeChild(graduationCB.closest('.row').lastElementChild)
                } // else
            }) // addEventListener
        headline.className = 'col-12 h6'
        headline.textContent = `${lblNum + 1}. študijski program`
        cross.style.float = 'right'
        cross.style.transform = 'scale(1.2)'
        cross.style.cursor = 'pointer'
        cross.innerHTML = '&#10007'
            // remove selected attendance section
        cross.addEventListener('click', () => {
                document.getElementById('attendances').removeChild(container)
            }) // addEventListener
        container.className = 'row'
        facultyFG.className = 'form-group col-6'
        programFG.className = 'form-group col-6'
        enrolledFG.className = 'form-group col-4'
        indexFG.className = 'form-group col-4'
        graduationFG.className = 'd-flex align-items-center justify-content-center form-group col-4'
        facultyLbl.setAttribute('for', `facultySlct${lblNum}`)
        facultyLbl.textContent = 'Fakulteta'
        programLbl.textContent = 'Program'
        programLbl.setAttribute('for', `pSlct${lblNum}`)
        enrolledLbl.textContent = 'Vpisan'
        enrolledLbl.setAttribute('for', `enInpt${lblNum}`)
        indexLbl.textContent = 'Indeks'
        indexLbl.setAttribute('for', `iInpt${lblNum}`)
        graduationLbl.textContent = 'Diplomiral'
        graduationLbl.setAttribute('for', `graduationCB${lblNum}`)
        graduationLbl.className = 'mt-2'
        facultySlct.className = 'form-control'
        facultySlct.id = `facultySlct${lblNum}`
        facultySlct.name = `attendances[${indx}][id_faculties]`
        facultySlct.required = true
        programSlct.className = 'form-control'
        programSlct.id = `pSlct${lblNum}`
        programSlct.name = `attendances[${indx}][id_programs]`
        programSlct.required = true
        enrolledInpt.className = 'form-control'
        enrolledInpt.id = `enInpt${lblNum}`
        enrolledInpt.type = 'date'
        enrolledInpt.name = `attendances[${indx}][enrolled]`
        enrolledInpt.required = true
        indexInpt.className = 'form-control'
        indexInpt.id = `iInpt${lblNum}`
        indexInpt.type = 'text'
        indexInpt.name = `attendances[${indx}][index]`
        indexInpt.required = true
        graduationCB.id = `graduationCB${lblNum}`
        graduationCB.type = 'checkbox'
        graduationCB.classList = 'mr-2'
        graduationCB.setAttribute('data-index', indx)
        graduationCB.setAttribute('data-lbl-num', lblNum)
            // append controls to a form attendances section
        facultyFG.appendChild(facultyLbl)
        facultyFG.appendChild(facultySlct)
        programFG.appendChild(programLbl)
        programFG.appendChild(programSlct)
        enrolledFG.appendChild(enrolledLbl)
        enrolledFG.appendChild(enrolledInpt)
        indexFG.appendChild(indexLbl)
        indexFG.appendChild(indexInpt)
        graduationFG.appendChild(graduationCB)
        graduationFG.appendChild(graduationLbl)
        headline.appendChild(cross)
        container.appendChild(headline)
        container.appendChild(facultyFG)
        container.appendChild(programFG)
        container.appendChild(enrolledFG)
        container.appendChild(indexFG)
        container.appendChild(graduationFG)
        document.getElementById('attendances').appendChild(container)
    } // addAttendance 

    /*
     *   select particulars of the given student
     *   @param Event e
     *   @param number idStudents
     */
    function selectStudent(e, idStudents) {
        let xmlhttp = new XMLHttpRequest
            // report on data seletion
        xmlhttp.addEventListener('load', () => {
                // pass JSON response
                alterStudentFrm(e, xmlhttp.response, 'update')
            }) // addEventListener
        xmlhttp.open('GET', `/eArchive/Students/select.php?id_students=${idStudents}`, true)
        xmlhttp.responseType = 'json'
        xmlhttp.send()
    } // selectStudent

    // pass and insert student data
    function insertStudent(e) {
        // prevent default action of submitting student data through a form
        e.preventDefault()
        let xmlhttp = new XMLHttpRequest,
            frmData = new FormData(studentFrm)
            // report on data insertion
        xmlhttp.addEventListener('load', () => {
                reportMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                reportMdlBtn.click()
                refreshStudentsTable()
            }) // addEventListener
        xmlhttp.open('POST', '/eArchive/Students/insert.php', true)
        xmlhttp.responseType = 'text'
        xmlhttp.send(frmData)
    } // insertStudent

    /*
     *  fill out form fields with student birthplace particulars
     *  @param Number idpostalCode
     *  @param Number idCountries
     */
    function determineStudentBirthplace(frm, idPostalCodes, idCountries) {
        // propagate target select element with postal codes of the chosen country
        frm.querySelector('#bCountrySlct').addEventListener('input', () => {
                propagateSelectElement(document.querySelector(`#${frm.querySelector('#bCountrySlct').getAttribute('data-target')}`), `/eArchive/PostalCodes/select.php?id_countries=${frm.querySelector('#bCountrySlct').selectedOptions[0].value}`)
            }) // addEventListener
        Array.from(frm.querySelector('#bCountrySlct').options).forEach(option => {
                // if countries match
                if (option.value == idCountries)
                    option.selected = true
            }) // forEach
            // dispatch synthetically generated event
        frm.querySelector('#bCountrySlct').dispatchEvent((new Event('input')))
        setTimeout(() => {
                // put postal code of a residence as selected option
                Array.from(frm.querySelector('#bPCSlct').options).forEach(option => {
                        // if postal codes match
                        if (option.value == idPostalCodes)
                            option.selected = true
                    }) // forEach
            }, 500) // setTimeout
    } // determineStudenBirthplace

    /*
     *  fill out form fields with student permanent residence particulars
     *  @param Node frm
     *  @param Array residence
     */
    function determineStudentPermanentResidence(frm, residence) {
        // create hidden input type for id of a residence
        let idResidencesInpt = document.createElement('input')
        idResidencesInpt.type = 'hidden'
        idResidencesInpt.name = 'residences[0][id_residences]'
        idResidencesInpt.value = residence.id_residences
        frm.querySelector('#PRCountrySlct').parentElement.prepend(idResidencesInpt)
            // put country of a residence as selected option
            // propagate target select element with postal codes of the chosen country
        frm.querySelector('#PRCountrySlct').addEventListener('input', () => {
                propagateSelectElement(document.querySelector(`#${frm.querySelector('#PRCountrySlct').getAttribute('data-target')}`), `/eArchive/PostalCodes/select.php?id_countries=${frm.querySelector('#PRCountrySlct').selectedOptions[0].value}`)
            }) // addEventListener
        Array.from(frm.querySelector('#PRCountrySlct').options).forEach(option => {
                // if countries match
                if (option.value == residence.id_countries)
                    option.selected = true
            }) // forEach
            // dispatch synthetically generated event
        frm.querySelector('#PRCountrySlct').dispatchEvent((new Event('input')))
        setTimeout(() => {
                // put postal code of a residence as selected option
                Array.from(frm.querySelector('#PRPCSlct').options).forEach(option => {
                        // if postal codes match
                        if (option.value == residence.id_postal_codes)
                            option.selected = true
                    }) // forEach
            }, 500) // setTimeout
        frm.querySelector('input[name="residences[0][address]"').value = residence.address
    } // determineStudentPermanentResidence

    /*
     *  fill out form fields with student temporal residence particulars
     *  @param Node frm
     *  @param Array residences
     */
    function determineStudentTemporalResidence(frm, residences) {
        // if student has any temporal residence
        if (residences.length) {
            // add each temporal residence section
            residences.forEach(residence => {
                    frm.querySelector('#addTRBtn').click()
                        // while section is being generated
                    setTimeout(() => {
                            // create hidden input type for id of a residence
                            let idResidencesInpt = document.createElement('input'),
                                lblNum = document.querySelectorAll('#residences .row') // number of added temporal residences 
                            idResidencesInpt.type = 'hidden'
                            idResidencesInpt.name = `residences[${lblNum}][id_residences]`
                            idResidencesInpt.value = residence.id_residences
                            frm.querySelector(`#TRCountrySlct${lblNum}`).parentElement.prepend(idResidencesInpt)
                            frm.querySelector(`#TRCountrySlct${lblNum}`).parentElement.parentElement.querySelector('span').addEventListener('click', () => {
                                    deleteStudentTemporalResidence(frm.querySelector('input[type=hidden]').value, residence.id_residences)
                                }) // addEventListener
                                // put country of a residence as selected option
                            Array.from(frm.querySelector(`#TRCountrySlct${lblNum}`).options).forEach(option => {
                                    // if postal codes match
                                    if (option.value == residence.id_countries)
                                        option.selected = true
                                }) // forEach
                                // dispatch synthetically generated event
                            frm.querySelector(`#TRCountrySlct${lblNum}`).dispatchEvent((new Event('input')))
                                // put postal code of a residence as selected option 
                            setTimeout(() => {
                                    Array.from(frm.querySelector(`#TRPCSlct${lblNum}`).options).forEach(option => {
                                            // if countries match
                                            if (option.value == residence.id_postal_codes)
                                                option.selected = true
                                        }) // forEach
                                }, 1000) // setTimeout
                            frm.querySelector(`#TRAddressInpt${lblNum}`).value = residence.address
                        }, 500) // setTimeout
                }) // forEach
        } // if
    } // determineStudentTemporalResidence

    /*
     *   delete student temporal residence by clicking cross sign of a section 
     *   @param idStudents
     *   @param idResidences
     */
    function deleteStudentTemporalResidence(idStudents, idResidences) {
        // declare an XHR object instance
        let xmlhttp = new XMLHttpRequest
            // upon successful transaction completion 
        xmlhttp.addEventListener('load', () => {
                // report the result
                reportMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                reportMdlBtn.click()
            }) // addEventListener
        xmlhttp.open('GET', `/eArchive/Residences/delete.php?id_students=${idStudents}&id_residences=${idResidences}`, true)
        xmlhttp.send()
    } // deleteStudentTemoralResidence

    // update overall student data
    function updateStudent(e) {
        // prevent default action of submitting updated student data through a form
        e.preventDefault()
        let xmlhttp = new XMLHttpRequest,
            frmData = new FormData(studentFrm)
            // report on update
        xmlhttp.addEventListener('load', () => {
                reportMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                reportMdlBtn.click()
                refreshStudentsTable()
            }) // addEventListener
        xmlhttp.open('POST', '/eArchive/Students/update.php', true)
        xmlhttp.responseType = 'text'
        xmlhttp.send(frmData)
    } // updateStudent

    /*
     *   delete overall student data
     *   @param Number idStudents
     *   @param Number idAttendances
     */

    function deleteStudent(idStudents, idAttendances) {
        let xmlhttp = new XMLHttpRequest
            // report on deletion
        xmlhttp.addEventListener('load', () => {
                reportMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                reportMdlBtn.click()
                refreshStudentsTable()
            }) // addEventListener
        xmlhttp.open('GET', `/eArchive/Students/delete.php?id_students=${idStudents}&id_attendances=${idAttendances}`, true)
        xmlhttp.responseType = 'text'
        xmlhttp.send()
    } // deleteStudent

    // generate and assign student account
    function insertStudentAccount(e, form) {
        // prevent default action by submitting data insert form
        e.preventDefault()
        let xmlhttp = new XMLHttpRequest,
            frmData = new FormData(form)
            // report on data insertion
        xmlhttp.addEventListener('load', () => {
                reportMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                reportMdlBtn.click()
            }) // addEventListener
        xmlhttp.open('POST', '/eArchive/Accounts/authorized/insert.php', true)
        xmlhttp.send(frmData)
    } // insertStudentAccount

    /*
     *   asynchronous script execution for deletion of the given account 
     *   @param idAttendances
     */
    function deleteStudentAccount(idAttendances) {
        let xmlhttp = new XMLHttpRequest
            // report on account deletion
        xmlhttp.addEventListener('load', () => {
                reportMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                reportMdlBtn.click()
            }) // addEventListener
        xmlhttp.open('GET', `/eArchive/Accounts/authorized/delete.php?id_attendances=${idAttendances}`, true)
        xmlhttp.send()
    } // deleteStudentAccount

    //  create and append additional form controls for scientific papers documentation upload
    function addDocuments() {
        // create form controls 
        let container = document.createElement('div'), // row
            versionFG = document.createElement('div'), // form group
            documentFG = document.createElement('div'), // form group
            versionLbl = document.createElement('label'), // version label
            versionInpt = document.createElement('input'), // version input
            documentLbl = document.createElement('label'), // document label
            documentInpt = document.createElement('input'), // document input 
            docHiddInpt = document.createElement('input'), // document hidden input 
            cross = document.createElement('span'), // removal sign
            lblNum = document.querySelectorAll('#documents .row').length, // number of added documents  
            indx = lblNum - 1 // the following index for an array of data on documents of scientific paper  
            // give hidden input type value of chosens document name
        documentInpt.addEventListener('change', e => {
                docHiddInpt.value = e.target.files[0].name
            }) // addEventListener
            // remove appended controls
        cross.addEventListener('click', () => {
                document.getElementById('sPDocs').removeChild(container)
            }) // addEventListener
        container.classList = 'row mt-2'
        container.style.position = 'relative'
        versionFG.classList = 'form-group col-6'
        documentFG.classList = 'form-group col-6'
        versionLbl.setAttribute('for', `vInpt${lblNum}`)
        versionLbl.textContent = 'Verzija'
        documentLbl.setAttribute('for', `documentInpt${lblNum}`)
        documentLbl.textContent = 'Dokument'
        versionInpt.id = `vInpt${lblNum}`
        versionInpt.classList = 'form-control'
        versionInpt.type = 'text'
        versionInpt.name = `documents[${lblNum}][version]`
        documentInpt.id = `documentInpt${lblNum}`
        documentInpt.type = 'file'
        documentInpt.accept = '.pdf'
        documentInpt.name = 'document[]'
        documentInpt.required = true
        docHiddInpt.type = 'hidden'
        docHiddInpt.name = `documents[${lblNum}][name]`
        cross.style.position = 'absolute'
        cross.style.top = 0
        cross.style.right = '10px'
        cross.style.zIndex = 1
        cross.style.cursor = 'pointer'
        cross.innerHTML = '&#10007;'
        versionFG.appendChild(versionLbl)
        versionFG.appendChild(versionInpt)
        documentFG.appendChild(docHiddInpt)
        documentFG.appendChild(documentLbl)
        documentFG.appendChild(documentInpt)
        container.appendChild(cross)
        container.appendChild(versionFG)
        container.appendChild(documentFG)
            // append controls to scientific paper insert form
        document.getElementById('sPDocs').appendChild(container)
    } // addDocuments

    /*
     *  modify form for scientific paper insertion or data alteration 
     *  @param Event e
     *  @param object sPpr
     *  @param string action
     */
    function modifysPFrm(e, sPpr = null, action) {
        // store modal and form elements
        let mdl = document.getElementById('sPMdl'),
            frm = document.getElementById('sPFrm')
            // lookup for a case  
        switch (action) {
            case 'insert':
                mdl.querySelector('.modal-header .modal-title').textContent = 'Vstavljanje znanstvenega dela'
                sPFrm.innerHTML = sPFrmClone.innerHTML
                sPFrm.querySelector('input[type=hidden]').value = e.target.getAttribute('data-id')
                    // add controls for scientific paper document upload
                sPFrm.querySelector('#aDBtn').addEventListener('click', addDocuments)
                    // exchange listener callbacks 
                frm.removeEventListener('submit', updateScientificPapers)
                frm.removeEventListener('submit', insertDocuments)
                frm.addEventListener('submit', insertScientificPaper)
                break;
            case 'update':
                mdl.querySelector('.modal-header .modal-title').textContent = 'Urejanje znanstvenega dela'
                frm.innerHTML = sPFrmClone.innerHTML
                frm.querySelector('input[type=hidden]').name = 'id_scientific_papers'
                frm.querySelector('input[name="topic"]').value = sPpr.topic
                frm.querySelector('select[name="type"]').value = sPpr.type
                frm.querySelector('input[name="written"]').value = sPpr.written
                frm.querySelector('input[type=submit]').value = 'Uredi'
                    // disable documents section form controls
                frm.removeChild(frm.querySelector('#sPDocs'))
                    // exchange listener callbacks 
                frm.removeEventListener('submit', insertScientificPaper)
                frm.removeEventListener('submit', insertDocuments)
                frm.addEventListener('submit', updateScientificPapers)
                break;
            case 'upload':
                mdl.querySelector('.modal-header .modal-title').textContent = 'Nalaganje dokumentov znanstvenega dela'
                frm.innerHTML = sPFrmClone.innerHTML
                frm.querySelector('input[type=hidden]').name = 'id_scientific_papers'
                frm.querySelector('input[type=hidden]').value = e.target.getAttribute('data-id')
                frm.removeChild(frm.querySelector('.row'))
                    // add controls for scientific paper document upload
                sPFrm.querySelector('#aDBtn').addEventListener('click', addDocuments)
                    // give hidden input type value of chosens document name
                frm.querySelector('#documentInpt').addEventListener('change', e => {
                        frm.querySelector('#docHInpt').value = e.target.files[0].name
                    }) // addEventListener        
                    // exchange listener callbacks 
                frm.removeEventListener('submit', insertScientificPaper)
                frm.removeEventListener('submit', updateScientificPapers)
                frm.addEventListener('submit', insertDocuments)
                break;
        } // switch
    } // modifysPFrm

    /*
     *  modify form for student particulars insertion or update  
     *  @param Event e
     *  @param object student
     *  @param string action
     */
    function alterStudentFrm(e, student = null, action) {
        // store form node   
        frm = document.getElementById('studentFrm')
            // lookup for a case  
        switch (action) {
            case 'insert':
                frm.innerHTML = studentFrmClone.innerHTML
                    // clear up all input field values 
                frm.querySelectorAll('input').forEach(input => {
                        input.value = ''
                    }) // forEach
                frm.querySelector('input[type=submit]').value = 'Vstavi'
                    // exchange callbacks
                frm.removeEventListener('submit', updateStudent)
                frm.addEventListener('submit', insertStudent)
                break;
            case 'update':
                let idefendedInpt = document.createElement('input')
                idefendedInpt.type = 'hidden'
                idefendedInpt.name = 'id_students'
                idefendedInpt.value = e.target.getAttribute('data-id')
                frm.innerHTML = studentFrmClone.innerHTML
                frm.prepend(idefendedInpt)
                    // fill out input fields with student particulars
                frm.querySelector('input[name=name]').value = student.particulars.name
                frm.querySelector('input[name=surname]').value = student.particulars.surname
                frm.querySelector('input[name=email]').value = student.particulars.email
                frm.querySelector('input[name=telephone]').value = student.particulars.telephone
                determineStudentBirthplace(frm, student.particulars.id_postal_codes, student.particulars.id_countries)
                frm.querySelector('#addTRBtn').addEventListener('click', addResidence)
                determineStudentPermanentResidence(frm, student.permResidence)
                determineStudentTemporalResidence(frm, student.tempResidence)
                frm.removeChild(frm.querySelector('#attendances'))
                frm.querySelector('input[type=submit]').value = 'Posodobi'
                    // exchange callbacks
                frm.removeEventListener('submit', insertStudent)
                frm.addEventListener('submit', updateStudent)
                break;
        } // switch
    } // alterStudentFrm

    /*
     *   asynchronous script execution for selection of scientific papers per student program attendance 
     *   @param idAttendances
     */
    function selectScientificPapers(idAttendances) {
        let xmlhttp = new XMLHttpRequest()
            // report on scientific papers selection
        xmlhttp.addEventListener('load', () => {
                // compose node tree structure
                fragment = xmlhttp.response
                    // reflect fragments body innerHTML    
                document.getElementById('sPVMdl').querySelector('.modal-content').innerHTML = fragment.body.innerHTML
                    // if anchors for document insertion are rendered
                if (document.querySelectorAll('.doc-ins-a'))
                    document.querySelectorAll('.doc-ins-a').forEach(anchor => {
                        // render only document section form controls
                        anchor.addEventListener('click', e => {
                                modifysPFrm(e, null, 'upload')
                            }) // addEventListener
                    }) // forEach
                    // if anchors for scientific papers update are rendered
                if (document.querySelectorAll('.sp-upd-а'))
                    document.querySelectorAll('.sp-upd-а').forEach(anchor => {
                        // fill form fields and modify the form
                        anchor.addEventListener('click', e => {
                                // report on scientific paper selection
                                (xmlhttp = new XMLHttpRequest()).addEventListener('load', () => {
                                        // return JSON of ScientificPapers object 
                                        let sPpr = {
                                                idAttendances: xmlhttp.response.id_attendances,
                                                idScientificPapers: xmlhttp.response.id_scientific_papers,
                                                topic: xmlhttp.response.topic,
                                                type: xmlhttp.response.type,
                                                written: xmlhttp.response.written
                                            } // scientific paper object
                                        modifysPFrm(e, sPpr, 'update')
                                    }) // addEventListener
                                xmlhttp.open('GET', `/eArchive/ScientificPapers/select.php?id_scientific_papers=${anchor.getAttribute('data-id')}`, true)
                                xmlhttp.responseType = 'json'
                                xmlhttp.send()
                                    // assign a value to the hidden input type of scientific paper insertion form 
                                document.getElementById('sPFrm').querySelector('input[type=hidden]').value = anchor.getAttribute('data-id')
                            }) // addEventListener
                    }) // forEach
                    // if anchors for scientific paper deletion are rendered
                if (document.querySelectorAll('.sp-del-a'))
                    document.querySelectorAll('.sp-del-a').forEach(anchor => {
                        anchor.addEventListener('click', () => {
                                deleteScientficPaper(anchor.getAttribute('data-id'))
                            }) // addEventListener
                    }) // forEach
                    // if anchors for scientific paper documentation deletion are rendered
                if (document.querySelectorAll('.doc-del-spn'))
                    document.querySelectorAll('.doc-del-spn').forEach(span => {
                        // delete particular document
                        span.addEventListener('click', () => {
                                deleteDocument(span.getAttribute('data-source'))
                            }) // addEventListener
                    }) // forEach
            }) // addEventListener
        xmlhttp.open('GET', `/eArchive/ScientificPapers/select.php?id_attendances=${idAttendances}`, true)
        xmlhttp.responseType = 'document'
        xmlhttp.send()
    } // selectScientificPapers

    // asynchronous script execution for scientific papers and documentation insertion 
    function insertScientificPaper(e) {
        // prevent default action of submitting scientific paper data    
        e.preventDefault()
        let xmlhttp = new XMLHttpRequest,
            frmData = new FormData(this)
            // report on scientific papers selection
        xmlhttp.addEventListener('load', () => {
                // report the result
                reportMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                reportMdlBtn.click()
            }) // addEventListener
        xmlhttp.open('POST', '/eArchive/ScientificPapers/insert.php', true)
        xmlhttp.send(frmData)
    } // insertScientificPaper

    // asynchronous script execution for scientific paper data alteration 
    function updateScientificPapers(e) {
        // prevent default action of submitting scientific paper data    
        e.preventDefault()
        let xmlhttp = new XMLHttpRequest,
            frmData = new FormData(this)
            // report on scientific paper update
        xmlhttp.addEventListener('load', () => {
                console.log(xmlhttp.responseText)
                    // report the result
                reportMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                reportMdlBtn.click()
            }) // addEventListener
        xmlhttp.open('POST', '/eArchive/ScientificPapers/update.php', true)
        xmlhttp.send(frmData)
    } // updateScientificPapers


    /*
     *  asynchronous script execution for scientific paper documentation deletion    
     *  @param source  
     */
    function deleteDocument(source) {
        // instantiate XHR 
        let xmlhttp = new XMLHttpRequest
            // report on docuement deletion
        xmlhttp.addEventListener('load', () => {
                // report the result
                reportMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                reportMdlBtn.click()
            }) // addEventListener
        xmlhttp.open('GET', `/eArchive/Documents/delete.php?source=${source}`, true)
        xmlhttp.send()
    } // deleteDocument

    //  asynchronous script execution for scientific paper documents upload    
    function insertDocuments(e) {
        // prevent default action by submitting upload form
        e.preventDefault()
            // instantiate XHR 
        let xmlhttp = new XMLHttpRequest,
            frmData = new FormData(this)
            // report on docuement deletion
        xmlhttp.addEventListener('load', () => {
                // report the result
                reportMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                reportMdlBtn.click()
            }) // addEventListener
        xmlhttp.open('POST', '/eArchive/Documents/insert.php', true)
        xmlhttp.send(frmData)
    } // insertDocuments

    /*
     *  asynchronous script execution for scientific paper deletion with its belonging documentation     
     *  @param idScientificPapers
     */
    function deleteScientficPaper(idScientificPapers) {
        // instantiate XHR interface object
        let xmlhttp = new XMLHttpRequest
            // report on the deletion
        xmlhttp.addEventListener('load', () => {
                // report the result
                reportMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                reportMdlBtn.click()
            }) // addEventListener
        xmlhttp.open('GET', `/eArchive/ScientificPapers/delete.php?id_scientific_papers=${idScientificPapers}`, true)
        xmlhttp.send()
    } // deleteScientificPaper

    // asynchronous script execution for graduation certificate upload/insertion     
    function insertCertificate(e) {
        // prevent default action of submitting certificate insertion form
        e.preventDefault()
            // instantiate XHR interface object
        let xmlhttp = new XMLHttpRequest,
            frmData = new FormData(this)
            // report on the deletion
        xmlhttp.addEventListener('load', () => {
                // report the result
                reportMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                reportMdlBtn.click()
            }) // addEventListener
        xmlhttp.open('POST', '/eArchive/Graduations/insert.php', true)
        xmlhttp.send(frmData)
    } // insertCertificate

    /*
     *  asynchronous script execution for graduation certificate selection    
     *  @param idAttendance
     */
    function selectCertificate(idAttendances) {
        // instantiate XHR interface object
        let xmlhttp = new XMLHttpRequest
            // report on the seletion
        xmlhttp.addEventListener('load', () => {
                // compose node tree structure
                fragment = xmlhttp.response
                    // reflect fragments body innerHTML    
                document.getElementById('certMdl').querySelector('.modal-content').innerHTML = fragment.body.innerHTML
                    // if anchor element for certificate deletion is contained
                if (document.getElementById('certMdl').querySelector('.modal-content').querySelector('.cert-del-a'))
                    document.getElementById('certMdl').querySelector('.modal-content').querySelector('.cert-del-a').addEventListener('click', e => {
                        deleteCertificate(e.target.getAttribute('data-att-id'), e.target.getAttribute('data-source'))
                    }) // addEventListner
            }) // addEventListener
        xmlhttp.open('GET', `/eArchive/Certificates/select.php?id_attendances=${idAttendances}`, true)
        xmlhttp.responseType = 'document'
        xmlhttp.send()
    } // selectCertificate

    /*
     *  asynchronous script execution for graduation certificate deletion    
     *  @param idAttendance
     * *  @param idCertificates
     */
    function deleteCertificate(idAttendances, source) {
        // instantiate XHR interface object
        let xmlhttp = new XMLHttpRequest
            // report on the seletion
        xmlhttp.addEventListener('load', () => {
                // report the result
                reportMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                reportMdlBtn.click()
            }) // addEventListener
            // reflect fragments body innerHTML    
        document.getElementById('certMdl').querySelector('.modal-content').innerHTML = fragment.body.innerHTML
        xmlhttp.open('GET', `/eArchive/Graduations/delete.php?id_attendances=${idAttendances}&source=${source}`, true)
        xmlhttp.responseType = 'text'
        xmlhttp.send()
    } // deleteCertificate
})()