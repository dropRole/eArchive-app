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
        filterInpt = document.getElementById('filterByIndx') // input for filtering students by their index numbers
    studentFrm.addEventListener('submit', insertStudent)
    sPFrm.addEventListener('submit', insertScientificPaper)
    accountFrm.addEventListener('submit', e => {
            // prevent form from submitting account details  
            e.preventDefault()
            insertStudentAccount(e, accountFrm)
            refreshStudentsTable()
        }) // addEventListener
    certificateFrm.addEventListener('submit', insertCertificate)
    certificateFrm.querySelector('input[type=file').addEventListener('change', () => {
            certificateFrm.querySelector('input[name=certificate]').value = certificateFrm.querySelector('input[type=file]').files[0].name
        }) // addEventListener

    // filter students by their index numbers 
    filterInpt.addEventListener('input', () => {
            selectStudentsByIndex(filterInpt.value)
        }) // addEventListener

    // attach event listeners to corresponding input element of the scientific paper form
    function attachSPFrmListeners() {
        let addPartakerBtn = document.getElementById('addPartakerBtn') // button for adding section for partaking on a scientific paper
            // assign to hidden input the filename of uploaded document
        sPFrm.querySelector('input[name="document[]"]').addEventListener('change', e => {
                sPFrm.querySelector('input[name="documents[0][name]"]').value = e.target.files[0].name
            }) // addEventListener
            // add controls for mentor of a scientific papers
        sPFrm.querySelector('#addMentorBtn').addEventListener('click', addMentoringsFrmSect)
            // add controls for scientific paper document upload
        sPFrm.querySelector('#addDocumentBtn').addEventListener('click', addDocumentFrmSect)
            // add another partaker section 
        addPartakerBtn.addEventListener('click', addPartakerFrmSect)
    } // attachSPFrmListeners

    // attach event listeners to corresponding input and selecet elements of the student form
    function attachStudentFrmListeners() {
        let addTRBtn = document.getElementById('addTRBtn'), // button for residence addition 
            addAttendanceBtn = document.getElementById('addAttendanceBtn'), // button for attendance addition
            countryLst = document.querySelectorAll('.country-select'), // select elements for birth, permanent and temporal residence country 
            facultySlct = document.getElementById('facultySlct'), // faculty select input
            graduationCB = document.getElementById('graduationCB') // graduation checkbox
        addTRBtn.addEventListener('click', addTemporalResidenceFrmSect)
        addAttendanceBtn.addEventListener('click', addProgramAttendanceSect)
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
                    addProgramGraduationFrmSect(e)
                else {
                    // remove selected graduation section
                    graduationCB.closest('.row').removeChild(graduationCB.closest('.row').lastElementChild)
                    graduationCB.closest('.row').removeChild(graduationCB.closest('.row').lastElementChild)
                    graduationCB.closest('.row').removeChild(graduationCB.closest('.row').lastElementChild)
                } // else
            }) // addEventListener
    } // attachStudentFrmListeners

    // attach listeners to student evidence table appropriate anchors and buttons   
    function attachTableListeners() {
        let insertStudentBtn = document.getElementById('insertStudentBtn'), // button for insertion of student particulars and scientific achievements
            sPVALst = document.querySelectorAll('.sp-vw-a'), // anchor list for scientific papers selection
            sPIALst = document.querySelectorAll('.sp-ins-a'), // anchor list for scientific papers insertion
            certIALst = document.querySelectorAll('.cert-ins-a'), // anchor list for certificate insertion
            certVALst = document.querySelectorAll('.cert-vw-a'), // anchor list for certificate view
            accIBtnLst = document.querySelectorAll('.acc-ins-btn'), // button list for account insertion
            accDBtnLst = document.querySelectorAll('.acc-del-btn'), // button list for account deletion
            stuUALst = document.querySelectorAll('.stu-upd-a'), // anchor list for student data update
            stuDALst = document.querySelectorAll('.stu-del-a') // anchor list for student data deletion
        insertStudentBtn.addEventListener('click', toStudentInsertFrm)
        sPVALst.forEach(anchor => {
                // preview scientific papers   
                anchor.addEventListener('click', () => {
                        selectScientificPapers(anchor.getAttribute('data-id'))
                    }) //addEventListener
            }) // forEach
        sPIALst.forEach(anchor => {
                // modify form for scientific paper insertion
                anchor.addEventListener('click', e => {
                        toSPInsertFrm(e)
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
        accDBtnLst.forEach(btn => {
                // delete particular account 
                btn.addEventListener('click', () => {
                        deleteStudentAccount(btn.getAttribute('data-id'))
                    }) //addEventListener
            }) // forEach
        accIBtnLst.forEach(btn => {
                // pass an id of attendance through forms hidden input type 
                accountFrm.querySelector('input[name=id_attendances]').value = btn.value
            }) // forEach
    } // attachTableListeners
    attachTableListeners()

    /*
     *   instantiate an object of integrated XHR interface and make an asynchronous operation on a script   
     *   @param String script
     *   @param String method
     *   @param String responseType 
     */
    function request(script, method, resType = '', frmData = null) {
        return new Promise((resolve, reject) => {
            let xmlhttp = new XMLHttpRequest()
                // resolve the promise if transaction was successful
            xmlhttp.addEventListener('load', () => {
                    resolve(xmlhttp.response)
                }) // addEventListener
            xmlhttp.addEventListener('error', () => {
                    // reject the promise if transaction encountered an error
                    reject('Prišlo je do napake na strežniku!')
                }) // addEventListener
            xmlhttp.open(method, script, true)
            xmlhttp.responseType = resType
            xmlhttp.send(frmData)
        })
    } // request

    // refresh students evidence table upon latterly data amendmantion 
    function refreshStudentsTable() {
        request('/eArchive/Students/selectAll.php', 'GET', 'document').then(response => {
                let tblCtr = document.querySelector('.table-responsive')
                    // compose node tree structure
                fragment = response
                    // reflect fragments body  
                tblCtr.innerHTML = fragment.body.innerHTML
                attachTableListeners()
            }).catch(error => {
                alert(error)
            }) // catch
    } // refreshStudentsTable

    /*
     *   propagate passed select element with options from the requested resource 
     *   @param HTMLSelectElement select
     *   @param DOMString script
     */
    async function propagateSelectElement(select, script) {
        try {
            const response = await request(script, 'GET', 'document')
            fragment = response
                // remove options while on disposal
            while (select.options.length) {
                select.remove(0)
            } // while
            // traverse through nodes 
            fragment.body.querySelectorAll('option').forEach(element => {
                    select.add(element)
                }) // forEach
        } catch (error) {
            alert(error)
        }
    } // propagateSelectElement

    // create and append additional form residence section controls 
    function addTemporalResidenceFrmSect() {
        return new Promise((resolve) => {
                // create form controls 
                let container = document.createElement('div'),
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
                    // propagate elements for country selection by adding new residence section
                request('/eArchive/Countries/select.php', 'GET', 'document').then((response) => {
                        // instantiate a MutationObserver object
                        let observer = new MutationObserver(() => {
                                resolve()
                            }) // MutationObserver
                            // set the target and options of observation
                        observer.observe(document.getElementById('residences'), {
                                attributes: false,
                                childList: true,
                                subtree: false
                            }) // observe
                        fragment = response
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
                    }).catch(error => {
                        alert(error)
                    }) // catch
            }) // Promise
    } // addTemporalResidenceFrmSect

    /*
     *  subsequently create and append graduation section of the student insertion form 
     *  @param Event e
     */
    function addProgramGraduationFrmSect(e) {
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
    } // addProgramGraduationFrmSect

    // subsequently create and append attendance section of the student insertion form 
    function addProgramAttendanceSect() {
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
                    addProgramGraduationFrmSect(e)
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
    } // addProgramAttendanceSect 

    //  subsequently create and append partaker section of the scientific paper insretion form 
    function addPartakerFrmSect() {
        // create element nodes 
        let ctr = document.createElement('div'),
            headline = document.createElement('p'),
            cross = document.createElement('span'),
            partakerFG = document.createElement('div'),
            partFG = document.createElement('div'),
            partakerLbl = document.createElement('label'),
            partLbl = document.createElement('label'),
            partakerInpt = document.createElement('input'),
            partInpt = document.createElement('input'),
            lblNum = document.querySelectorAll('#sPPartakers .row').length + 1, // number of added partakers on scientific paper
            indx = lblNum // the following index for an array of data on a partaker  
        ctr.classList = 'row'
        headline.classList = 'h6 col-12'
        headline.textContent = `${lblNum}. soavtor`
        cross.style.float = 'right'
        cross.style.transform = 'scale(1.2)'
        cross.style.cursor = 'pointer'
        cross.innerHTML = '&#10007'
            // remove selected attendance section
        cross.addEventListener('click', () => {
                document.getElementById('sPPartakers').removeChild(ctr)
            }) // addEventListener
        partakerFG.classList = 'form-group col-6'
        partFG.classList = 'form-group col-6'
        partakerLbl.htmlFor = 'partakerInpt'
        partakerLbl.textContent = 'Sodelovalec'
        partLbl.htmlFor = 'partInpt'
        partLbl.textContent = 'Vloga'
        partakerInpt.id = `partakerInpt${lblNum}`
        partakerInpt.classList = 'form-control'
        partakerInpt.name = `partakers[${indx}][index]`
        partakerInpt.setAttribute('list', 'students')
        partakerInpt.required = true
        partInpt.id = `partInpt${lblNum}`
        partInpt.classList = 'form-control'
        partInpt.type = 'text'
        partInpt.name = `partakers[${indx}][part]`
        partInpt.required = true
            // compose a node hierarchy by appending them to active tree structure 
        headline.appendChild(cross)
        partakerFG.appendChild(partakerLbl)
        partakerFG.appendChild(partakerInpt)
        partFG.appendChild(partLbl)
        partFG.appendChild(partInpt)
        ctr.appendChild(headline)
        ctr.appendChild(partakerFG)
        ctr.appendChild(partFG)
        document.getElementById('sPPartakers').appendChild(ctr)
    } // addPartakerFrmSect

    //  create and append additional form controls for scientific papers documentation upload
    function addDocumentFrmSect() {
        // create form controls 
        let container = document.createElement('div'), // row
            cross = document.createElement('span'), // removal sign
            versionFG = document.createElement('div'), // form group
            documentFG = document.createElement('div'), // form group
            versionLbl = document.createElement('label'), // version label
            documentLbl = document.createElement('label'), // document label
            versionInpt = document.createElement('input'), // version input
            documentInpt = document.createElement('input'), // document input 
            docHiddInpt = document.createElement('input'), // document hidden input 
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
        versionInpt.name = `documents[${indx}][version]`
        documentInpt.id = `documentInpt${lblNum}`
        documentInpt.type = 'file'
        documentInpt.accept = '.pdf'
        documentInpt.name = 'document[]'
        documentInpt.required = true
        docHiddInpt.type = 'hidden'
        docHiddInpt.name = `documents[${indx}][name]`
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
    } // addDocumentFrmSect

    //  create and append additional form controls for providing data on mentors 
    function addMentoringsFrmSect() {
        // create form controls 
        let ctr = document.createElement('div'), // row
            headline = document.createElement('p'),
            cross = document.createElement('span'), // removal sign 
            mentorFG = document.createElement('div'), // form group
            facultyFG = document.createElement('div'), // form group
            taughtFG = document.createElement('div'), // form group
            emailFG = document.createElement('div'), // form group
            telephoneFG = document.createElement('div'), // form group
            mentorLbl = document.createElement('label'), // mentor label
            facultyLbl = document.createElement('label'), // faculty label
            taughtLbl = document.createElement('label'), // subject label
            emailLbl = document.createElement('label'), // email label
            telephoneLbl = document.createElement('label'), // telephone label
            facultySlct = document.createElement('select'), // faculty input
            mentorInpt = document.createElement('input'), // mentor input
            taughtInpt = document.createElement('input'), // subject input
            emailInpt = document.createElement('input'), // email input
            telephoneInpt = document.createElement('input'), // telephone input
            lblNum = document.querySelectorAll('#sPMentors .row').length + 1, // number of added documents  
            indx = lblNum - 1 // the following index for an array of data on documents of scientific paper  
        ctr.classList = 'row'
        headline.classList = 'col-12 h6'
        headline.textContent = `${lblNum}. mentor`
        cross.style.float = 'right'
        cross.style.transform = 'scale(1.2)'
        cross.style.cursor = 'pointer'
        cross.innerHTML = '&#10007'
            // remove selected attendance section
        cross.addEventListener('click', () => {
                document.getElementById('sPMentors').removeChild(ctr)
            }) // addEventListener
        mentorFG.classList = 'form-group col-12'
        facultyFG.classList = 'form-group col-6'
        taughtFG.classList = 'form-group col-6'
        emailFG.classList = 'form-group col-6'
        telephoneFG.classList = 'form-group col-6'
        facultyLbl.htmlFor = `facultySlct${lblNum}`
        facultyLbl.textContent = 'Fakulteta'
        mentorLbl.htmlFor = `mentorInpt${lblNum}`
        mentorLbl.textContent = 'Mentor'
        taughtLbl.htmlFor = `taughtInpt${lblNum}`
        taughtLbl.textContent = 'Poučeval'
        emailLbl.htmlFor = `emailInpt${lblNum}`
        emailLbl.textContent = 'E-naslov'
        telephoneLbl.htmlFor = `telephoneInpt${lblNum}`
        telephoneLbl.textContent = 'Telefon'
        facultySlct.id = `facultySlct${lblNum}`
        facultySlct.classList = 'form-control'
        facultySlct.name = `mentors[${indx}][id_faculties]`
        propagateSelectElement(facultySlct, '/eArchive/Faculties/select.php')
        facultySlct.required = true
        mentorInpt.id = `mentorInpt${lblNum}`
        mentorInpt.classList = 'form-control'
        mentorInpt.type = 'text'
        mentorInpt.name = `mentors[${indx}][mentor]`
        mentorInpt.required = true
        taughtInpt.id = `taughtInpt${lblNum}`
        taughtInpt.classList = 'form-control'
        taughtInpt.type = 'text'
        taughtInpt.name = `mentors[${indx}][taught]`
        taughtInpt.required = true
        emailInpt.id = `emailInpt${lblNum}`
        emailInpt.classList = 'form-control'
        emailInpt.type = 'email'
        emailInpt.name = `mentors[${indx}][email]`
        emailInpt.required = true
        telephoneInpt.id = `telephoneInpt${lblNum}`
        telephoneInpt.classList = 'form-control'
        telephoneInpt.type = 'telephone'
        telephoneInpt.name = `mentors[${indx}][telephone]`
        telephoneInpt.required = true
        headline.appendChild(cross)
        mentorFG.appendChild(mentorLbl)
        mentorFG.appendChild(mentorInpt)
        facultyFG.appendChild(facultyLbl)
        facultyFG.appendChild(facultySlct)
        taughtFG.appendChild(taughtLbl)
        taughtFG.appendChild(taughtInpt)
        emailFG.appendChild(emailLbl)
        emailFG.appendChild(emailInpt)
        telephoneFG.appendChild(telephoneLbl)
        telephoneFG.appendChild(telephoneInpt)
        ctr.appendChild(headline)
        ctr.appendChild(mentorFG)
        ctr.appendChild(facultyFG)
        ctr.appendChild(taughtFG)
        ctr.appendChild(emailFG)
        ctr.appendChild(telephoneFG)
        document.getElementById('sPMentors').appendChild(ctr)
    } // addMentoringsFrmSect

    /*
     *  clear input field values of a form 
     *  @param HTMLFormElement frm
     */
    function emptyFrmInptFields(frm) {
        frm.querySelectorAll('input').forEach(input => {
                input.value = ''
            }) // forEach
    } // emptyFrmInptFields

    /*  
     *   interpolate datalist with name, surname and index number of the inserted student
     *   @param DOMString fullname
     *   @param Number index
     */
    function interpolateStudentDatalist(fullname, index) {
        let option = document.createElement('option')
        option.value = index
        option.textContent = fullname
        sPFrm.querySelector('datalist').appendChild(option)
    } // interpolateStudentDatalist

    /*
     *   asynchronous script execution for selection of student particulars and scientific achievements    
     *   @param Event e
     *   @param Number idStudents
     */
    function selectStudent(e, idStudents) {
        request(`/eArchive/Students/select.php?id_students=${idStudents}`, 'GET', 'json').then(response => {
                // pass JSON response
                toStudentUpdateFrm(e, response)
            }).catch(error => {
                alert(error)
            }) // catch
    } // selectStudent

    /*
     *   asynchronous script execution for filtered student selection by index      
     *   @param Number index
     */
    function selectStudentsByIndex(index) {
        request(`/eArchive/Students/filterByIndex.php?index=${index}`, 'GET', 'document').then(response => {
                let tblCtr = document.querySelector('.table-responsive')
                    // compose node tree structure
                fragment = response
                    // reflect fragments body  
                tblCtr.innerHTML = fragment.body.innerHTML
                attachTableListeners()
            }).catch(error => {
                alert(error)
            }) // catch
    } // selectStudentsByIndex

    /*
     *   asynchronous script execution for insretion of student particulars and scientific achievements
     *   @param Event e
     */
    function insertStudent(e) {
        // prevent default action of submitting student data through a form
        e.preventDefault()
        request('/eArchive/Students/insert.php', 'POST', 'text', (new FormData(studentFrm))).then(response => {
                reportMdl.querySelector('.modal-body').textContent = response
                reportMdlBtn.click()
                emptyFrmInptFields(studentFrm)
                    // close the modal after insertion 
                document.getElementById('insertStudentBtn').click()
            }).then(refreshStudentsTable())
            .then(interpolateStudentDatalist())
            .catch(error => {
                alert(error)
            }) // catch
    } // insertStudent

    /*
     *  fill out form fields with student birthplace particulars
     *  @param HTMLFormElement frm
     *  @param Number idpostalCode
     *  @param Number idCountries
     */
    function determineStudentBirthplace(frm, idPostalCodes, idCountries) {
        // propagate target select element with postal codes of the chosen country
        frm.querySelector('#bCountrySlct').addEventListener('input', () => {
                propagateSelectElement(document.querySelector(`#${frm.querySelector('#bCountrySlct').getAttribute('data-target')}`), `/eArchive/PostalCodes/select.php?id_countries=${frm.querySelector('#bCountrySlct').selectedOptions[0].value}`).then(() => {
                        // put postal code of a residence as selected option
                        Array.from(frm.querySelector('#bPCSlct').options).forEach(option => {
                                // if postal codes match
                                if (option.value == idPostalCodes)
                                    option.selected = true
                            }) // forEach
                    }) // then
            }) // addEventListener
        Array.from(frm.querySelector('#bCountrySlct').options).forEach(option => {
                // if countries match
                if (option.value == idCountries)
                    option.selected = true
            }) // forEach
            // dispatch synthetically generated event
        frm.querySelector('#bCountrySlct').dispatchEvent((new Event('input')))
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
                propagateSelectElement(document.querySelector(`#${frm.querySelector('#PRCountrySlct').getAttribute('data-target')}`), `/eArchive/PostalCodes/select.php?id_countries=${frm.querySelector('#PRCountrySlct').selectedOptions[0].value}`).then(() => {
                        // put postal code of a residence as selected option
                        Array.from(frm.querySelector('#PRPCSlct').options).forEach(option => {
                                // if postal codes match
                                if (option.value == residence.id_postal_codes)
                                    option.selected = true
                            }) // forEach
                    }) // then
            }) // addEventListener
        Array.from(frm.querySelector('#PRCountrySlct').options).forEach(option => {
                // if countries match
                if (option.value == residence.id_countries)
                    option.selected = true
            }) // forEach
            // dispatch synthetically generated event
        frm.querySelector('#PRCountrySlct').dispatchEvent((new Event('input')))
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
                    frm.querySelector('#addTRBtn').addEventListener('click', addTemporalResidenceFrmSect().then(() => {
                                let idResidencesInpt = document.createElement('input'),
                                    lblNum = document.querySelectorAll('#residences .row').length - 1 // number of added temporal residences 
                                    // create hidden input type for id of a residence
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
                                Array.from(frm.querySelector(`#TRPCSlct${lblNum}`).options).forEach(option => {
                                        // if countries match
                                        if (option.value == residence.id_postal_codes)
                                            option.selected = true
                                    }) // forEach
                                frm.querySelector(`#TRAddressInpt${lblNum}`).value = residence.address
                            }) // then
                        ) // addEventListener
                    frm.querySelector('#addTRBtn').click()
                }) // forEach
        } // if
    } // determineStudentTemporalResidence

    /*
     *   delete student temporal residence by clicking cross sign of a section 
     *   @param idStudents
     *   @param idResidences
     */
    function deleteStudentTemporalResidence(idStudents, idResidences) {
        request(`/eArchive/Residences/delete.php?id_students=${idStudents}&id_residences=${idResidences}`, 'GET', '').then(response => {
                // report the result
                reportMdl.querySelector('.modal-body').textContent = response
                reportMdlBtn.click()
            }).catch(error => {
                alert(error)
            }) // catch
    } // deleteStudentTemoralResidence

    /*
     *  update student particulars and data on scientific achievements
     *  Event e
     */
    function updateStudent(e) {
        // prevent default action of submitting updated student data through a form
        e.preventDefault()
        request('/eArchive/Students/update.php', 'POST', 'text', (new FormData(studentFrm))).then(response => {
                // report on update
                reportMdl.querySelector('.modal-body').textContent = response
                reportMdlBtn.click()
                emptyFrmInptFields(studentFrm)
                document.getElementById('insertStudentBtn').click()
                refreshStudentsTable()
            }).catch(error => {
                alert(error)
            }) // catch
    } // updateStudent

    /*
     *   delete all student related data
     *   @param Number idStudents
     *   @param Number idAttendances
     */
    function deleteStudent(idStudents, idAttendances) {
        request(`/eArchive/Students/delete.php?id_students=${idStudents}&id_attendances=${idAttendances}`, 'GET', 'text').then(response => {
                // report on deletion
                reportMdl.querySelector('.modal-body').textContent = response
                reportMdlBtn.click()
                refreshStudentsTable()
            }).catch(error => {
                alert(error)
            }) // catch
    } // deleteStudent

    /*
     *   generate and assign an account to a student
     *   @param Event e
     *   @param HTMLFormElement form
     */
    function insertStudentAccount(e, form) {
        // prevent default action by submitting data insert form
        e.preventDefault()
        request('/eArchive/Accounts/authorized/insert.php', 'POST', 'text', (new FormData(accountFrm))).then(response => {
            // report on data insertion
            reportMdl.querySelector('.modal-body').textContent = response
            reportMdlBtn.click()
        })
    } // insertStudentAccount

    /*
     *   asynchronous script execution for deletion of the given account 
     *   @param idAttendances
     */
    function deleteStudentAccount(idAttendances) {
        request(`/eArchive/Accounts/authorized/delete.php?id_attendances=${idAttendances}`, 'GET', 'text').then(response => {
                // report on account deletion
                reportMdl.querySelector('.modal-body').textContent = response
                reportMdlBtn.click()
                refreshStudentsTable()
            }).catch(error => {

            }) // catch
    } // deleteStudentAccount

    /*
     *   transform to form for insretion of scientific paper data and document upload  
     *   @param Event e
     */
    function toSPInsertFrm(e) {
        document.querySelector('#sPMdl .modal-header .modal-title').textContent = 'Vstavljanje znanstvenega dela'
        sPFrm.innerHTML = sPFrmClone.innerHTML
        attachSPFrmListeners()
        sPFrm.querySelector('input[type=hidden]').value = e.target.getAttribute('data-id')
            // exchange listener callbacks 
        sPFrm.removeEventListener('submit', updateScientificPapers)
        sPFrm.removeEventListener('submit', insertDocuments)
        sPFrm.addEventListener('submit', insertScientificPaper)
    } // toSPInsertFrm

    /*
     *   transform to form for update of scientific paper data  
     *   @param Object sPpr
     */
    function toSPUpdateFrm(sPpr) {
        document.querySelector('#sPMdl .modal-header .modal-title').textContent = 'Urejanje znanstvenega dela'
        sPFrm.innerHTML = sPFrmClone.innerHTML
        attachSPFrmListeners()
        sPFrm.querySelector('input[type=hidden]').name = 'id_scientific_papers'
        sPFrm.querySelector('input[name="topic"]').value = sPpr.topic
        sPFrm.querySelector('select[name="type"]').value = sPpr.type
        sPFrm.querySelector('input[name="written"]').value = sPpr.written
        sPFrm.querySelector('input[type=submit]').value = 'Uredi'
            // disable documents section form controls
        sPFrm.removeChild(sPFrm.querySelector('#sPDocs'))
            // exchange listener callbacks 
        sPFrm.removeEventListener('submit', insertScientificPaper)
        sPFrm.removeEventListener('submit', insertDocuments)
        sPFrm.addEventListener('submit', updateScientificPapers)
    } // toSPUpdateFrm

    /*
     *   transform to form for upload of scientific paper documents
     *   @param Event e
     */
    function toSPUploadFrm(e) {
        document.querySelector('#sPMdl .modal-header .modal-title').textContent = 'Nalaganje dokumentov znanstvenega dela'
        sPFrm.innerHTML = sPFrmClone.innerHTML
        sPFrm.querySelector('input[type=hidden]').name = 'id_scientific_papers'
        sPFrm.querySelector('input[type=hidden]').value = e.target.getAttribute('data-id')
        sPFrm.removeChild(sPFrm.querySelector('.row'))
            // give hidden input type value of chosens document name
        sPFrm.querySelector('#documentInpt').addEventListener('change', e => {
                sPFrm.querySelector('#docHiddInpt').value = e.target.files[0].name
            }) // addEventListener        
            // exchange listener callbacks 
        sPFrm.removeEventListener('submit', insertScientificPaper)
        sPFrm.removeEventListener('submit', updateScientificPapers)
        sPFrm.addEventListener('submit', insertDocuments)
    } // toSPUploadFrm

    /*
     *  reform to insert a new partaker in a scientific paper
     *  @param Event e
     */
    function toPartakerInsertFrm(e) {
        document.querySelector('#sPMdl .modal-header .modal-title').textContent = 'Dodeljevanje soavtorja znanstvenega dela'
            // derive and modify the section for mentor insertion
        let sect = sPFrmClone.querySelector('#sPPartakers').cloneNode(true),
            idSPHiddInpt = document.createElement('input'),
            btn = sPFrmClone.querySelector('input[type=submit]').cloneNode(true)
        sect.classList = 'col-12'
        idSPHiddInpt.type = 'hidden'
        idSPHiddInpt.name = 'id_scientific_papers'
        idSPHiddInpt.value = e.target.getAttribute('data-id')
        sPFrm.innerHTML = ''
        sPFrm.prepend(idSPHiddInpt)
        sPFrm.appendChild(sect)
        sPFrm.appendChild(btn)
            // add controls for mentor of a scientific papers
        sPFrm.querySelector('#addPartakerBtn').addEventListener('click', addPartakerFrmSect)
            // dispatch a synthetic click event 
        sPFrm.querySelector('#addPartakerBtn').dispatchEvent((new Event('click')))
            // exchange event listeners
        sPFrm.removeEventListener('submit', insertStudent)
        sPFrm.removeEventListener('submit', insertStudent)
        sPFrm.addEventListener('submit', insertScientificPaperPartaker)
    } // toPartakerInsertFrm

    //  transform to form for student insertion of student particulars and submitted scientific achievements
    function toStudentInsertFrm() {
        studentFrm.innerHTML = studentFrmClone.innerHTML
        attachStudentFrmListeners()
            // clear up all input field values 
        emptyFrmInptFields(studentFrm)
        studentFrm.querySelector('input[type=submit]').value = 'Vstavi'
            // exchange callbacks
        studentFrm.removeEventListener('submit', updateStudent)
        studentFrm.addEventListener('submit', insertStudent)
    } // toStudentInsertFrm

    /*
     *   transform to form for student particulars update
     *   @param Event e
     *   @param Object student
     */
    function toStudentUpdateFrm(e, student) {
        let idefendedInpt = document.createElement('input')
        idefendedInpt.type = 'hidden'
        idefendedInpt.name = 'id_students'
        idefendedInpt.value = e.target.getAttribute('data-id')
        studentFrm.innerHTML = studentFrmClone.innerHTML
        attachStudentFrmListeners()
        studentFrm.prepend(idefendedInpt)
            // fill out input fields with student particulars
        studentFrm.querySelector('input[name=name]').value = student.particulars.name
        studentFrm.querySelector('input[name=surname]').value = student.particulars.surname
        studentFrm.querySelector('input[name=email]').value = student.particulars.email
        studentFrm.querySelector('input[name=telephone]').value = student.particulars.telephone
        determineStudentBirthplace(studentFrm, student.particulars.id_postal_codes, student.particulars.id_countries)
        determineStudentPermanentResidence(studentFrm, student.permResidence)
        determineStudentTemporalResidence(studentFrm, student.tempResidence)
        studentFrm.removeChild(studentFrm.querySelector('#attendances'))
        studentFrm.querySelector('input[type=submit]').value = 'Posodobi'
            // exchange callbacks
        studentFrm.removeEventListener('submit', insertStudent)
        studentFrm.addEventListener('submit', updateStudent)
    } // toStudentUpdateFrm

    // attach event listeners to a scientific paper cards when rendered
    function attachSPCardListeners() {
        // if anchor nodes for partaker insertion exist
        if (document.querySelectorAll('.men-par-a'))
            document.querySelectorAll('.men-par-a').forEach(anchor => {
                // form will contain only control for partaker insertion
                anchor.addEventListener('click', toPartakerInsertFrm)
            }) // forEach
            // if spans for scientific paper partaker deletion exist
        if (document.querySelectorAll('.par-del-spn'))
            document.querySelectorAll('.par-del-spn').forEach(span => {
                // attempt deletion of a partaker
                span.addEventListener('click', () => {
                        deleteScientificPaperPartaker(span.getAttribute('data-id'))
                    }) // addEventListener
            }) // forEach
            // if anchors for document insertion are rendered
        if (document.querySelectorAll('.doc-ins-a'))
            document.querySelectorAll('.doc-ins-a').forEach(anchor => {
                // restructure form for document upload
                anchor.addEventListener('click', e => {
                        toSPUploadFrm(e)
                    }) // addEventListener
            }) // forEach
            // if anchors for scientific papers update are rendered
        if (document.querySelectorAll('.sp-upd-а'))
            document.querySelectorAll('.sp-upd-а').forEach(anchor => {
                // fill form fields and modify the form
                anchor.addEventListener('click', e => {
                        request(`/eArchive/ScientificPapers/select.php?id_scientific_papers=${anchor.getAttribute('data-id')}`, 'GET', 'json').then(response => {
                                // retrieve JSON of ScientificPapers object 
                                toSPUpdateFrm(response)
                            }).catch(error => {
                                alert(error)
                            }) // catch
                    }) // addEventListener
                    // assign a value to the hidden input type of scientific paper insertion form 
                document.getElementById('sPFrm').querySelector('input[type=hidden]').value = anchor.getAttribute('data-id')
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
    } // attachSPMdlListeners

    /*
     *   asynchronous script execution for selection of scientific papers per student program attendance 
     *   @param Number idAttendances
     */
    function selectScientificPapers(idAttendances) {
        // fetch resources
        request(`/eArchive/ScientificPapers/select.php?id_attendances=${idAttendances}`, 'GET', 'document').then(response => {
                // compose node tree structure
                fragment = response
                    // reflect fragments body     
                document.querySelector('#sPVMdl .modal-content').innerHTML = fragment.body.innerHTML
                return
            }).then(() => {
                attachSPCardListeners()
            }).catch(error => {
                alert(error)
            }) // catch
    } // selectScientificPapers

    /*
     *   asynchronous script execution for scientific papers and documentation insertion 
     *   @param Event e
     */
    function insertScientificPaper(e) {
        // prevent default action of submitting scientific paper data    
        e.preventDefault()
        request('/eArchive/ScientificPapers/insert.php', 'POST', 'text', (new FormData(sPFrm))).then(response => {
                // report on scientific papers selection
                reportMdl.querySelector('.modal-body').textContent = response
                reportMdlBtn.click()
            }).catch(error => {
                alert(error)
            }) // catch
    } // insertScientificPaper

    /*
     *   asynchronous script execution for scientific paper data alteration 
     *   @param Event e
     */
    function updateScientificPapers(e) {
        // prevent default action of submitting scientific paper data    
        e.preventDefault()
        request('/eArchive/ScientificPapers/update.php', 'POST', 'text', (new FormData(sPFrm))).then(response => {
                // report on scientific paper update
                reportMdl.querySelector('.modal-body').textContent = response
                reportMdlBtn.click()
            }).catch(error => {
                alert(error)
            }) // catch
    } // updateScientificPapers

    /*
     *  asynchronous script execution for scientific paper documents deletion    
     *  @param DOMString source  
     */
    function deleteDocument(source) {
        request(`/eArchive/Documents/delete.php?source=${source}`, 'GET', 'text').then(response => {
                // report on document deletion
                reportMdl.querySelector('.modal-body').textContent = response
                reportMdlBtn.click()
            }).catch(error => {
                alert(error)
            }) // catch
    } // deleteDocument

    /*  
     *   asynchronous script execution for scientific paper documents upload    
     *   @param Event e
     */
    function insertDocuments(e) {
        // prevent default action by submitting upload form
        e.preventDefault()
        request('/eArchive/Documents/insert.php', 'POST', 'text', (new FormData(sPFrm))).then(response => {
                // report on document deletion
                reportMdl.querySelector('.modal-body').textContent = response
                reportMdlBtn.click()
            }).catch(error => {
                alert(error)
            }) // catch
    } // insertDocuments

    /*
     *  asynchronous script execution for scientific paper deletion with its belonging documents     
     *  @param Number idScientificPapers
     */
    function deleteScientficPaper(idScientificPapers) {
        request(`/eArchive/ScientificPapers/delete.php?id_scientific_papers=${idScientificPapers}`, 'GET', 'text').then(response => {
                // report on the deletion
                reportMdl.querySelector('.modal-body').textContent = response
                reportMdlBtn.click()
            }).catch(error => {
                alert(error)
            }) // catch
    } // deleteScientificPaper

    /*
     *   asynchronous script execution for graduation certificate upload/insertion     
     *   @param Event e
     */
    function insertCertificate(e) {
        // prevent default action of submitting certificate insertion form
        e.preventDefault()
        request('/eArchive/Certificates/insert.php', 'POST', 'text', (new FormData(certificateFrm))).then(response => {
                // report on the deletion
                reportMdl.querySelector('.modal-body').textContent = response
                reportMdlBtn.click()
            }).catch(error => {
                alert(error)
            }) // catch
    } // insertCertificate

    /*
     *  asynchronous script execution for graduation certificate selection    
     *  @param Number idAttendances
     */
    function selectCertificate(idAttendances) {
        request(`/eArchive/Certificates/select.php?id_attendances=${idAttendances}`, 'GET', 'document').then(response => {
                // compose node tree structure
                fragment = response
                    // reflect fragments body     
                document.getElementById('certMdl').querySelector('.modal-content').innerHTML = fragment.body.innerHTML
                    // if anchor element for certificate deletion is contained
                if (document.getElementById('certMdl').querySelector('.modal-content').querySelector('.cert-del-a'))
                    document.getElementById('certMdl').querySelector('.modal-content').querySelector('.cert-del-a').addEventListener('click', e => {
                        deleteCertificate(e.target.getAttribute('data-att-id'), e.target.getAttribute('data-source'))
                    }) // addEventListner
            }).catch(error => {
                alert(error)
            }) // catch
    } // selectCertificate

    /*
     *  asynchronous script execution for graduation certificate deletion    
     *  @param Number idAttendance
     *  @param Number idCertificates
     */
    function deleteCertificate(idAttendances, source) {
        request(`/eArchive/Graduations/delete.php?id_attendances=${idAttendances}&source=${source}`, 'GET', 'text').then(response => {
                // report on the deletion
                reportMdl.querySelector('.modal-body').textContent = response
                reportMdlBtn.click()
            }).catch(error => {
                alert(error)
            }) // catch
    } // deleteCertificate

    /*
     *  asynchronous script execution for graduation certificate deletion    
     *  @param Number idAttendance
     *  @param Number idCertificates
     */
    function deleteCertificate(idAttendances, source) {
        request(`/eArchive/Graduations/delete.php?id_attendances=${idAttendances}&source=${source}`, 'GET', 'text').then(response => {
                // report on the deletion
                reportMdl.querySelector('.modal-body').textContent = response
                reportMdlBtn.click()
            }).catch(error => {
                alert(error)
            }) // catch
    } // deleteCertificate

    // asynchronous script execution for insertion of a scientific paper partaker    
    function insertScientificPaperPartaker() {
        request('/eArchive/Partakings/insert.php', 'POST', 'text', (new FormData(sPFrm))).then(response => {
                // report on the insertion
                reportMdl.querySelector('.modal-body').textContent = response
                reportMdlBtn.click()
            }).catch(error => {
                alert(error)
            }) // catch
    } // deleteCertificate

    /*
     *  asynchronous script execution for deletion of a scientific paper partaker    
     *  @param Number idPartakings
     */
    function deleteScientificPaperPartaker(idPartakings) {
        request(`/eArchive/Partakings/delete.php?id_partakings=${idPartakings}`, 'GET', 'text').then(response => {
                // report on the deletion
                reportMdl.querySelector('.modal-body').textContent = response
                reportMdlBtn.click()
            }).catch(error => {
                alert(error)
            }) // catch
    } // deleteScientificPaperPartaker
})()