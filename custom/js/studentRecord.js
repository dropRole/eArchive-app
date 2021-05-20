// IIFE
(() => {
    // global scope variable declaration
    var fragment = new DocumentFragment(), // minimal document object structure
        studentIUFrm = document.getElementById('studentIUFrm'), // form for student data insertion and update
        studentIUFrmClone = studentIUFrm.cloneNode(true), // studentIUFrm clone node
        sPFrm = document.getElementById('sPFrm'), // scientific paper insert form
        certFrm = document.getElementById('certFrm'), // certificate upload/insertion form
        sPFrmClone = sPFrm.cloneNode(true), // sPFrm clone node
        aMdl = document.getElementById('aMdl'), // account modal 
        rMdl = document.getElementById('rMdl'), // report modal 
        rMdlBtn = document.getElementById('rMdlBtn'), // button for report modal toggle
        addTRBtn = document.getElementById('addTRBtn'), // button for residence addition 
        addAttendanceBtn = document.getElementById('addAttendanceBtn'), // button for attendance addition
        countrySlctLst = document.querySelectorAll('.country-select'), // select elements for birth, permanent and temporal residence country 
        facultySlct = document.getElementById('facultySlct'), // faculty select input
        graduationCB = document.getElementById('graduationCB'), // graduation checkbox
        docInpt = document.getElementById('docInpt'), // document input
        rLbl = 2, // residence label counter
        rIndx = 1, // residence array index 
        addressLbl = 1, // attendance label counter
        aIndx = 1, // attendance array index 
        dLbl = 1 // documentation label counter
    studentIUFrm.addEventListener('submit', insertStudent)
    sPFrm.addEventListener('submit', insertScientificPaper)
    certFrm.addEventListener('submit', insertCertificate)
    certFrm.querySelector('input[type=file').addEventListener('change', () => {
            certFrm.querySelector('input[name=certificate]').value = certFrm.querySelector('input[type=file]').files[0].name
        }) // addEventListener
    addTRBtn.addEventListener('click', addResidence)
    addAttendanceBtn.addEventListener('click', addAttendance)
    countrySlctLst.forEach(select => {
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
            addGraduation(e, document.querySelector('#attendances .row'))
        }) // addEventListener
        // give hidden input type value of chosens document name
    docInpt.addEventListener('change', e => {
            document.getElementById('docHInpt').value = e.target.files[0].name
        }) // addEventListener        
        // attach listeners to student evidence table elements  
    function attachTableListeners() {
        let SPVALst = document.querySelectorAll('.sp-vw-a'), // anchor list for scientific papers selection
            SPIALst = document.querySelectorAll('.sp-ins-a'), // anchor list for scientific papers insertion
            certIALst = document.querySelectorAll('.cert-ins-a'), // anchor list for certificate insertion
            certVALst = document.querySelectorAll('.cert-vw-a'), // anchor list for certificate view
            accIBtnLst = document.querySelectorAll('.acc-ins-btn'), // button list for account generation
            accDBtnLst = document.querySelectorAll('.acc-del-btn'), // button list for account deletion
            stuUALst = document.querySelectorAll('.stu-upd-a'), // anchor list for student data update
            stuDALst = document.querySelectorAll('.stu-del-a') // anchor list for student data deletion
        accIBtnLst.forEach(btn => {
                // populate modals body with created account insert form 
                btn.addEventListener('click', createAccountForm) //addEventListener
            }) // forEach
        accDBtnLst.forEach(btn => {
                // delete particular account 
                btn.addEventListener('click', () => {
                        deleteAccount(btn.getAttribute('data-id'))
                    }) //addEventListener
            }) // forEach
        SPVALst.forEach(anchor => {
                // preview scientific papers   
                anchor.addEventListener('click', () => {
                        selectScientificPapers(anchor.getAttribute('data-id'))
                    }) //addEventListener
            }) // forEach
        SPIALst.forEach(anchor => {
                // modify form for scientific paper insertion
                anchor.addEventListener('click', e => {
                        modifySPFrm(e, null, 'insert')
                    }) //addEventListener
            }) // forEach
        certIALst.forEach(anchor => {
                // assign an attendance id value to an upload forms hidden input type 
                anchor.addEventListener('click', e => {
                        certFrm.querySelector('input[type=hidden]').value = anchor.getAttribute('data-id')
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
        // create and append additional form residence section controls 
    function addResidence() {
        // create form controls 
        let xmlhttp = new XMLHttpRequest(),
            container = document.createElement('div'),
            headline = document.createElement('p'),
            cross = document.createElement('span'),
            FGCountry = document.createElement('div'),
            FGPostalCode = document.createElement('div'),
            FGAddress = document.createElement('div'),
            countryLbl = document.createElement('label'),
            postalCodeLbl = document.createElement('label'),
            addressLbl = document.createElement('label'),
            postalCodeSlct = document.createElement('select'),
            countrySlct = document.createElement('select'),
            addressInpt = document.createElement('input')
        container.className = 'row'
        container.style.position = 'relative'
        headline.classList = 'col-12 h6'
        headline.textContent = `${rLbl - 1}. začasno bivališče`
        cross.style.float = 'right'
        cross.style.transform = 'scale(1.2)'
        cross.style.cursor = 'pointer'
            // remove selected residence section
        cross.addEventListener('click', () => {
                document.getElementById('residences').removeChild(container)
                rLbl--
            }) // addEventListener
        cross.innerHTML = '&#10007;'
        FGCountry.className = 'form-group col-4'
        FGPostalCode.className = 'form-group col-4'
        FGAddress.className = 'form-group col-4'
        countryLbl.setAttribute('for', `TRCountrySlct${rLbl}`)
        countryLbl.textContent = 'Država'
        postalCodeLbl.setAttribute('for', `TRPCSlct${rLbl}`)
        postalCodeLbl.textContent = 'Kraj'
        addressLbl.setAttribute('for', `TRAddressSlct${rLbl}`)
        addressLbl.textContent = 'Naslov'
        countrySlct.id = `TRCountrySlct${rLbl}`
        countrySlct.classList = 'form-control country-select'
            // propagate postal codes by country selection
        countrySlct.addEventListener('input', e => {
                propagateSelectElement(postalCodeSlct, `/eArchive/PostalCodes/select.php?id_countries=${e.target.selectedOptions[0].value}`)
            }) // addEventListener
        postalCodeSlct.id = `TRPCSlct${rLbl}`
        addressInpt.id = `TRAddressInpt${rLbl}`
        postalCodeSlct.classList = 'form-control'
        postalCodeSlct.name = `residences[${rIndx}][id_postal_codes]`
        postalCodeSlct.required = true
        addressInpt.classList = 'form-control'
        addressInpt.type = 'text'
        addressInpt.name = `residences[${rIndx}][address]`
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
                FGCountry.appendChild(countryLbl)
                FGCountry.appendChild(countrySlct)
                FGPostalCode.appendChild(postalCodeLbl)
                FGPostalCode.appendChild(postalCodeSlct)
                FGAddress.appendChild(addressLbl)
                FGAddress.appendChild(addressInpt)
                container.appendChild(headline)
                container.appendChild(FGCountry)
                container.appendChild(FGPostalCode)
                container.appendChild(FGAddress)
                document.querySelector('#residences').appendChild(container)
            }) // addEventListener
        xmlhttp.responseType = 'document'
        xmlhttp.open('GET', '/eArchive/Countries/select.php')
        xmlhttp.send()
        rIndx++
        rLbl++
    } // addResidence

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

    // create and append graduation form controls
    function addGraduation(e, pNode) {
        // if it's not checked
        if (e.target.checked) {
            // get graduation section label counter 
            let cntr = e.target.getAttribute('data-counter'),
                // get attendances array current row index
                indx = e.target.getAttribute('data-index'),
                // create form controls 
                FGDiv = document.createElement('div'),
                FGDiv1 = document.createElement('div'),
                FGDiv2 = document.createElement('div'),
                fLbl = document.createElement('label'),
                dLbl = document.createElement('label'),
                fInpt = document.createElement('input'),
                dInpt = document.createElement('input'),
                cInpt = document.createElement('input')
            iLbl = document.createElement('label'),
                iInpt = document.createElement('input')
            FGDiv.className = 'form-group col-4'
            FGDiv1.className = 'form-group col-4'
            FGDiv2.className = 'form-group col-4'
            fLbl.textContent = 'Certifikat'
            fLbl.setAttribute('for', `fInpt${cntr}`)
            dLbl.textContent = 'Zagovorjen'
            dLbl.setAttribute('for', `dInpt${cntr}`)
            iLbl.textContent = 'Izdan'
            iLbl.setAttribute('for', `iInpt${cntr}`)
            fInpt.id = `fInpt${cntr}`
            fInpt.type = 'file'
            fInpt.setAttribute('name', 'certificate[]')
            fInpt.accept = '.pdf'
            fInpt.required = true
                // determine hidden input type value if graduated
            fInpt.addEventListener('change', e => {
                    cInpt.value = e.target.files[0].name
                }) // addEventListener
            cInpt.type = 'hidden'
            cInpt.name = `attendances[${indx}][certificate]`
            dInpt.id = `dInpt${cntr}`
            dInpt.className = 'form-control'
            dInpt.type = 'date'
            dInpt.required = true
            dInpt.name = `attendances[${indx}][defended]`
            iInpt.id = `iInpt${cntr}`
            iInpt.className = 'form-control'
            iInpt.type = 'date'
            iInpt.name = `attendances[${indx}][issued]`
            iInpt.required = true
                // append graduation form controls to a particular attendance section
            FGDiv.appendChild(fLbl)
            FGDiv.appendChild(fInpt)
            FGDiv1.appendChild(dLbl)
            FGDiv1.appendChild(dInpt)
            FGDiv2.appendChild(iLbl)
            FGDiv2.appendChild(iInpt)
            pNode.appendChild(cInpt)
            pNode.appendChild(FGDiv)
            pNode.appendChild(FGDiv1)
            pNode.appendChild(FGDiv2)
            return
        } // if
        // remove selected graduation section
        pNode.removeChild(pNode.lastChild)
        pNode.removeChild(pNode.lastChild)
        pNode.removeChild(pNode.lastChild)
        return
    } // addGraduation

    // create and append attendance form controls 
    function addAttendance() {
        // create form controls
        let div = document.getElementById('attendances'),
            rDiv = document.createElement('div'),
            p = document.createElement('p'),
            span = document.createElement('span'),
            FGDiv = document.createElement('div'),
            FGDiv1 = document.createElement('div'),
            FGDiv2 = document.createElement('div'),
            FGDiv3 = document.createElement('div'),
            FGDiv4 = document.createElement('div'),
            fLbl = document.createElement('label'),
            pLbl = document.createElement('label'),
            eLbl = document.createElement('label'),
            iLbl = document.createElement('label'),
            facultySlct = document.createElement('select'),
            pSlct = document.createElement('select'),
            eInpt = document.createElement('input'),
            iInpt = document.createElement('input'),
            gLbl = document.createElement('label'),
            graduationCB = document.createElement('input')
            // initial propagation
        propagateSelectElement(facultySlct, '/eArchive/Faculties/select.php')
        setTimeout(() => {
                propagateSelectElement(pSlct, `/eArchive/Programs/select.php?id_faculties=${facultySlct.selectedOptions[0].value}`)
            }, 500) // setTimeout
            // propagate programs by faculty selection
        facultySlct.addEventListener('change', e => {
                propagateSelectElement(pSlct, `/eArchive/Programs/select.php?id_faculties=${e.target.selectedOptions[0].value}`)
            }) // addEventListener
            // append graduation section if graduated        
        graduationCB.addEventListener('change', e => {
                addGraduation(e, rDiv)
            }) // addEventListener
        p.className = 'col-12 h6'
        p.textContent = `${addressLbl + 1}. študijski program`
        span.style.float = 'right'
        span.style.transform = 'scale(1.2)'
        span.style.cursor = 'pointer'
        span.innerHTML = '&#10007'
            // remove selected attendance section
        span.addEventListener('click', () => {
                div.removeChild(rDiv)
                addressLbl--
            }) // addEventListener
        span.innerHTML = '&#10007;'
        rDiv.className = 'row'
        FGDiv.className = 'form-group col-6'
        FGDiv1.className = 'form-group col-6'
        FGDiv2.className = 'form-group col-4'
        FGDiv3.className = 'form-group col-4'
        FGDiv4.className = 'd-flex align-items-center justify-content-center form-group col-4'
        fLbl.setAttribute('for', `facultySlct${addressLbl}`)
        fLbl.textContent = 'Fakulteta'
        pLbl.textContent = 'Program'
        pLbl.setAttribute('for', `pSlct${addressLbl}`)
        eLbl.textContent = 'Vpisan'
        eLbl.setAttribute('for', `enInpt${addressLbl}`)
        iLbl.textContent = 'Indeks'
        iLbl.setAttribute('for', `iInpt${addressLbl}`)
        gLbl.textContent = 'Diplomiral'
        gLbl.setAttribute('for', `graduationCB${addressLbl}`)
        gLbl.className = 'mt-2'
        facultySlct.className = 'form-control'
        facultySlct.id = `facultySlct${addressLbl}`
        facultySlct.name = `attendances[${aIndx}][id_faculties]`
        facultySlct.required = true
        pSlct.className = 'form-control'
        pSlct.id = `pSlct${addressLbl}`
        pSlct.name = `attendances[${aIndx}][id_programs]`
        pSlct.required = true
        eInpt.className = 'form-control'
        eInpt.id = `enInpt${addressLbl}`
        eInpt.type = 'date'
        eInpt.name = `attendances[${aIndx}][enrolled]`
        eInpt.required = true
        iInpt.className = 'form-control'
        iInpt.id = `iInpt${addressLbl}`
        iInpt.type = 'text'
        iInpt.name = `attendances[${aIndx}][index]`
        iInpt.required = true
        graduationCB.id = `graduationCB${addressLbl}`
        graduationCB.type = 'checkbox'
        graduationCB.classList = 'mr-2'
        graduationCB.setAttribute('data-index', aIndx)
        graduationCB.setAttribute('data-counter', addressLbl)
            // append controls to a form attendances section
        FGDiv.appendChild(fLbl)
        FGDiv.appendChild(facultySlct)
        FGDiv1.appendChild(pLbl)
        FGDiv1.appendChild(pSlct)
        FGDiv2.appendChild(eLbl)
        FGDiv2.appendChild(eInpt)
        FGDiv3.appendChild(iLbl)
        FGDiv3.appendChild(iInpt)
        FGDiv4.appendChild(graduationCB)
        FGDiv4.appendChild(gLbl)
        p.appendChild(span)
        rDiv.appendChild(p)
        rDiv.appendChild(FGDiv)
        rDiv.appendChild(FGDiv1)
        rDiv.appendChild(FGDiv2)
        rDiv.appendChild(FGDiv3)
        rDiv.appendChild(FGDiv4)
        div.appendChild(rDiv)
        aIndx++
        addressLbl++
    } // addAttendance 

    // pass and insert student data
    function insertStudent(e) {
        // prevent default action of submitting student data through a form
        e.preventDefault()
        let xmlhttp = new XMLHttpRequest,
            fData = new FormData(studentIUFrm)
            // report on data insertion
        xmlhttp.addEventListener('load', () => {
                rMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                rMdlBtn.click()
                refreshStudentsTable()
            }) // addEventListener
        xmlhttp.open('POST', '/eArchive/Students/insert.php', true)
        xmlhttp.responseType = 'text'
        xmlhttp.send(fData)
    } // insertStudent

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
                alterIUStudentFrm(e, xmlhttp.response, 'update')
            }) // addEventListener
        xmlhttp.open('GET', `/eArchive/Students/select.php?id_students=${idStudents}`, true)
        xmlhttp.responseType = 'json'
        xmlhttp.send()
    } // selectStudent

    // update overall student data
    function updateStudent(e) {
        // prevent default action of submitting updated student data through a form
        e.preventDefault()
        let xmlhttp = new XMLHttpRequest,
            fData = new FormData(studentIUFrm)
            // report on update
        xmlhttp.addEventListener('load', () => {
                rMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                rMdlBtn.click()
                refreshStudentsTable()
            }) // addEventListener
        xmlhttp.open('POST', '/eArchive/Students/update.php', true)
        xmlhttp.responseType = 'text'
        xmlhttp.send(fData)
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
                rMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                rMdlBtn.click()
                refreshStudentsTable()
            }) // addEventListener
        xmlhttp.open('GET', `/eArchive/Students/delete.php?id_students=${idStudents}&id_attendances=${idAttendances}`, true)
        xmlhttp.responseType = 'text'
        xmlhttp.send()
    } // deleteStudent

    // refresh students evidence table upon latterly data amendmantion 
    function refreshStudentsTable() {
        let xmlhttp = new XMLHttpRequest
            // report on data insertion
        xmlhttp.addEventListener('load', () => {
                let tContainer = document.querySelector('.table-responsive')
                    // compose node tree structure
                fragment = xmlhttp.response
                    // reflect fragments body innerHTML 
                tContainer.innerHTML = fragment.body.innerHTML
                attachTableListeners()
            }) // addEventListener
        xmlhttp.open('GET', '/eArchive/Students/selectAll.php', true)
        xmlhttp.responseType = 'document'
        xmlhttp.send()
    } // refreshStudentsTable

    // create a student account insert form
    function createAccountForm(e) {
        // if modal already contains form 
        if (aMdl.querySelector('.modal-body').firstChild)
            aMdl.querySelector('.modal-body').removeChild(aMdl.querySelector('.modal-body').firstChild)
            // create form and form controls
        let form = document.createElement('form'), // insert form
            hInpt = document.createElement('input'), // hidden input    
            FGDiv = document.createElement('div'), // form group div
            pLbl = document.createElement('label'), // password input label
            pInpt = document.createElement('input'), // password input
            sInpt = document.createElement('input') // submit input
        form.id = 'aIFrm'
            // generate new account on form submission
        form.addEventListener('submit', ev => {
                insertAccount(ev, form)
                    // close modal a second after form submisson
                setTimeout(() => {
                        e.target.click()
                    }, 500) // setTimeout
            }) // addEventListener
        hInpt.setAttribute('name', 'id_attendances')
        hInpt.type = 'hidden'
        hInpt.value = e.target.getAttribute('value')
        FGDiv.classList = 'form-group'
        pLbl.setAttribute('for', 'pInpt')
        pLbl.textContent = 'Geslo'
        pInpt.id = 'pInpt'
        pInpt.classList = 'form-control'
        pInpt.type = 'password'
        pInpt.setAttribute('name', 'pass')
        pInpt.required = true
        sInpt.classList = 'btn btn-warning'
        sInpt.type = 'submit'
        sInpt.value = 'Ustvari račun'
            // append created form controls
        FGDiv.appendChild(pLbl)
        FGDiv.appendChild(pInpt)
        form.appendChild(hInpt)
        form.appendChild(FGDiv)
        form.appendChild(sInpt)
        aMdl.querySelector('.modal-body').appendChild(form)
        return
    } // createAccountForm

    // generate and assign student account
    function insertAccount(e, form) {
        // prevent default action by submitting data insert form
        e.preventDefault()
        let xmlhttp = new XMLHttpRequest,
            fData = new FormData(form)
            // report on data insertion
        xmlhttp.addEventListener('load', () => {
                rMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                rMdlBtn.click()
            }) // addEventListener
        xmlhttp.open('POST', '/eArchive/Accounts/authorized/insert.php', true)
        xmlhttp.send(fData)
    } // insertAccount

    /*
     *   asynchronous script execution for deletion of the given account 
     *   @param idAttendances
     */
    function deleteAccount(idAttendances) {
        let xmlhttp = new XMLHttpRequest
            // report on account deletion
        xmlhttp.addEventListener('load', () => {
                rMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                rMdlBtn.click()
            }) // addEventListener
        xmlhttp.open('GET', `/eArchive/Accounts/authorized/delete.php?id_attendances=${idAttendances}`, true)
        xmlhttp.send()
        return
    } // deleteAccount

    //  create and append additional form controls for scientific papers documentation upload
    function addDocuments() {
        // create form controls 
        let rDiv = document.createElement('div'), // row
            FGDiv = document.createElement('div'), // form group
            FGDiv1 = document.createElement('div'), // form group
            vLbl = document.createElement('label'), // version label
            vInpt = document.createElement('input'), // version input
            docountryLbl = document.createElement('label'), // document label
            docInpt = document.createElement('input'), // document input 
            docHInpt = document.createElement('input'), // document hidden input 
            span = document.createElement('span') // removal sign
            // give hidden input type value of chosens document name
        docInpt.addEventListener('change', e => {
                docHInpt.value = e.target.files[0].name
            }) // addEventListener
            // remove appended controls
        span.addEventListener('click', () => {
                document.getElementById('sPDocs').removeChild(rDiv)
                dLbl--
            }) // addEventListener
        rDiv.classList = 'row mt-2'
        rDiv.style.position = 'relative'
        FGDiv.classList = 'form-group col-6'
        FGDiv1.classList = 'form-group col-6'
        vLbl.setAttribute('for', `vInpt${dLbl}`)
        vLbl.textContent = 'Verzija'
        docountryLbl.setAttribute('for', `docInpt${dLbl}`)
        docountryLbl.textContent = 'Dokument'
        vInpt.id = `vInpt${dLbl}`
        vInpt.classList = 'form-control'
        vInpt.type = 'text'
        vInpt.name = `documents[${dLbl}][version]`
        docInpt.id = `docInpt${dLbl}`
        docInpt.type = 'file'
        docInpt.accept = '.pdf'
        docInpt.name = 'document[]'
        docInpt.required = true
        docHInpt.type = 'hidden'
        docHInpt.name = `documents[${dLbl}][name]`
        span.style.position = 'absolute'
        span.style.top = 0
        span.style.right = '10px'
        span.style.zIndex = 1
        span.style.cursor = 'pointer'
        span.innerHTML = '&#10007;'
        FGDiv.appendChild(vLbl)
        FGDiv.appendChild(vInpt)
        FGDiv1.appendChild(docHInpt)
        FGDiv1.appendChild(docountryLbl)
        FGDiv1.appendChild(docInpt)
        rDiv.appendChild(span)
        rDiv.appendChild(FGDiv)
        rDiv.appendChild(FGDiv1)
            // append controls to scientific paper insert form
        document.getElementById('sPDocs').appendChild(rDiv)
        dLbl++
        return
    } // addDocuments

    /*
     *  modify form for scientific paper insertion or data alteration 
     *  @param Event e
     *  @param object sPpr
     *  @param string action
     */
    function modifySPFrm(e, sPpr = null, action) {
        // store modal and form elements
        let mdl = document.getElementById('sPIUMdl'),
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
                frm.querySelector('#docInpt').addEventListener('change', e => {
                        frm.querySelector('#docHInpt').value = e.target.files[0].name
                    }) // addEventListener        
                    // exchange listener callbacks 
                frm.removeEventListener('submit', insertScientificPaper)
                frm.removeEventListener('submit', updateScientificPapers)
                frm.addEventListener('submit', insertDocuments)
                break;
        } // switch
        return
    } // modifySPFrm

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
                            let idResidencesInpt = document.createElement('input')
                            idResidencesInpt.type = 'hidden'
                            idResidencesInpt.name = `residences[${rLbl - 1}][id_residences]`
                            idResidencesInpt.value = residence.id_residences
                            frm.querySelector(`#TRCountrySlct${rLbl - 1}`).parentElement.prepend(idResidencesInpt)
                            frm.querySelector(`#TRCountrySlct${rLbl - 1}`).parentElement.parentElement.querySelector('span').addEventListener('click', () => {
                                    deleteStudentTemporalResidence(frm.querySelector('input[type=hidden]').value, residence.id_residences)
                                }) // addEventListener
                                // put country of a residence as selected option
                            Array.from(frm.querySelector(`#TRCountrySlct${rLbl - 1}`).options).forEach(option => {
                                    // if postal codes match
                                    if (option.value == residence.id_countries)
                                        option.selected = true
                                }) // forEach
                                // dispatch synthetically generated event
                            frm.querySelector(`#TRCountrySlct${rLbl - 1}`).dispatchEvent((new Event('input')))
                                // put postal code of a residence as selected option 
                            setTimeout(() => {
                                    Array.from(frm.querySelector(`#TRPCSlct${rLbl - 1}`).options).forEach(option => {
                                            // if countries match
                                            if (option.value == residence.id_postal_codes)
                                                option.selected = true
                                        }) // forEach
                                }, 1000) // setTimeout
                            frm.querySelector(`#TRAddressInpt${rLbl - 1}`).value = residence.address
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
                rMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                rMdlBtn.click()
            }) // addEventListener
        xmlhttp.open('GET', `/eArchive/Residences/delete.php?id_students=${idStudents}&id_residences=${idResidences}`, true)
        xmlhttp.send()
        return
    } // deleteStudentTemoralResidence

    /*
     *  modify form for student particulars insertion or update  
     *  @param Event e
     *  @param object student
     *  @param string action
     */
    function alterIUStudentFrm(e, student = null, action) {
        // store form node   
        frm = document.getElementById('studentIUFrm')
            // lookup for a case  
        switch (action) {
            case 'insert':
                frm.innerHTML = studentIUFrmClone.innerHTML
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
                let idInpt = document.createElement('input')
                idInpt.type = 'hidden'
                idInpt.name = 'id_students'
                idInpt.value = e.target.getAttribute('data-id')
                frm.innerHTML = studentIUFrmClone.innerHTML
                frm.prepend(idInpt)
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
        return
    } // alterIUStudentFrm

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
                                modifySPFrm(e, null, 'upload')
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
                                                id_attendances: xmlhttp.response.id_attendances,
                                                id_scientific_papers: xmlhttp.response.id_scientific_papers,
                                                topic: xmlhttp.response.topic,
                                                type: xmlhttp.response.type,
                                                written: xmlhttp.response.written
                                            } // scientific paper object
                                        modifySPFrm(e, sPpr, 'update')
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
        return
    } // selectScientificPapers

    // asynchronous script execution for scientific papers and documentation insertion 
    function insertScientificPaper(e) {
        // prevent default action of submitting scientific paper data    
        e.preventDefault()
        let xmlhttp = new XMLHttpRequest,
            fData = new FormData(this)
            // report on scientific papers selection
        xmlhttp.addEventListener('load', () => {
                // report the result
                rMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                rMdlBtn.click()
            }) // addEventListener
        xmlhttp.open('POST', '/eArchive/ScientificPapers/insert.php', true)
        xmlhttp.send(fData)
        return
    } // insertScientificPaper

    // asynchronous script execution for scientific paper data alteration 
    function updateScientificPapers(e) {
        // prevent default action of submitting scientific paper data    
        e.preventDefault()
        let xmlhttp = new XMLHttpRequest,
            fData = new FormData(this)
            // report on scientific paper update
        xmlhttp.addEventListener('load', () => {
                console.log(xmlhttp.responseText)
                    // report the result
                rMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                rMdlBtn.click()
            }) // addEventListener
        xmlhttp.open('POST', '/eArchive/ScientificPapers/update.php', true)
        xmlhttp.send(fData)
        return
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
                rMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                rMdlBtn.click()
            }) // addEventListener
        xmlhttp.open('GET', `/eArchive/Documents/delete.php?source=${source}`, true)
        xmlhttp.send()
        return
    } // deleteDocument

    //  asynchronous script execution for scientific paper documents upload    
    function insertDocuments(e) {
        // prevent default action by submitting upload form
        e.preventDefault()
            // instantiate XHR 
        let xmlhttp = new XMLHttpRequest,
            fData = new FormData(this)
            // report on docuement deletion
        xmlhttp.addEventListener('load', () => {
                // report the result
                rMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                rMdlBtn.click()
            }) // addEventListener
        xmlhttp.open('POST', '/eArchive/Documents/insert.php', true)
        xmlhttp.send(fData)
        return
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
                rMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                rMdlBtn.click()
            }) // addEventListener
        xmlhttp.open('GET', `/eArchive/ScientificPapers/delete.php?id_scientific_papers=${idScientificPapers}`, true)
        xmlhttp.send()
        return
    } // deleteScientificPaper

    // asynchronous script execution for graduation certificate upload/insertion     
    function insertCertificate(e) {
        // prevent default action of submitting certificate insertion form
        e.preventDefault()
            // instantiate XHR interface object
        let xmlhttp = new XMLHttpRequest,
            fData = new FormData(this)
            // report on the deletion
        xmlhttp.addEventListener('load', () => {
                // report the result
                rMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                rMdlBtn.click()
            }) // addEventListener
        xmlhttp.open('POST', '/eArchive/Graduations/insert.php', true)
        xmlhttp.send(fData)
        return
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
        return
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
                rMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                rMdlBtn.click()
            }) // addEventListener
            // reflect fragments body innerHTML    
        document.getElementById('certMdl').querySelector('.modal-content').innerHTML = fragment.body.innerHTML
        xmlhttp.open('GET', `/eArchive/Graduations/delete.php?id_attendances=${idAttendances}&source=${source}`, true)
        xmlhttp.responseType = 'text'
        xmlhttp.send()
        return
    } // deleteCertificate
})()