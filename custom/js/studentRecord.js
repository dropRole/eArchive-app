// IIFE
(() => {
    // global scope variable declaration
    var fragment = new DocumentFragment(), // minimal document object structure
        studentFrm = document.getElementById('studentFrm'), // form for student data insertion and update
        studentCloneFrm = studentFrm.cloneNode(true), // studentFrm clone node
        sPCloneFrm = document.getElementById('sPFrm'), // cloned form element node for scientific paper insretion
        acctFrm = document.getElementById('acctFrm'), // form for creating student account and its credentials
        certFrm = document.getElementById('certFrm'), // certificate upload/insertion form
        certCloneFrm = certFrm.cloneNode(true), // cloned form element node for graduation certificate upload
        reportMdl = document.getElementById('reportMdl'), // report modal 
        reportMdlBtn = document.getElementById('reportMdlBtn'), // button for report modal toggle
        filterInpt = document.getElementById('filterByIndx') // input for filtering students by their index numbers

    certFrm.querySelector('input[type=file').addEventListener('change', () => {
            certFrm.querySelector('input[name=certificate]').value = certFrm.querySelector('input[type=file]').files[0].name
        }) // addEventListener

    // attach event listeners to corresponding input element of the scientific paper form
    let attachSPFrmListeners = () => {
            // get form for scientific paper insertion 
            let form = document.getElementById('sPFrm')
                // if button for subsequent partaker senction additon exist
            if (document.getElementById('addPartakerBtn'))
            // add partaker form section on button click  
                addPartakerBtn.addEventListener('click', addPartakerFrmSect)
                // if document file input is rendered 
            if (form.querySelector('input[name="document[]"]'))
            // assign to hidden input the filename of uploaded document
                form.querySelector('input[name="document[]"]').addEventListener('change', e => {
                    form.querySelector('input[name="documents[0][name]"]').value = e.target.files[0].name
                }) // addEventListener
                // if button for subsequent mentor section additon exist 
            if (form.querySelector('#addMentorBtn'))
            // add controls for mentor of a scientific papers
                form.querySelector('#addMentorBtn').addEventListener('click', addMentoringsFrmSect)
                // if button for subsequent document section additon exist
            if (form.querySelector('#addDocumentBtn'))
            // add controls for scientific paper document upload
                form.querySelector('#addDocumentBtn').addEventListener('click', addDocumentFrmSect)
                // add another partaker section 
        } // attachSPFrmListeners

    // attach event listeners to corresponding input and selecet elements of the student form
    let attachStudentFrmListeners = () => {
            let addTRBtn = document.getElementById('addTRBtn'), // button for residence addition 
                addAttendanceBtn = document.getElementById('addAttendanceBtn'), // button for attendance addition
                countryLst = document.querySelectorAll('.country-select'), // select elements for birth, permanent and temporal residence country 
                facultySlct = document.getElementById('facultySlct'), // faculty select input
                graduationCB = document.getElementById('graduationCB') // graduation checkbox
            addTRBtn.addEventListener('click', () => {
                    addTempResFrmSect()
                }) // addEventListener
            addAttendanceBtn.addEventListener('click', addProgramAttendanceSect)
            countryLst.forEach(select => {
                    // propagate target select element with postal codes of the chosen country
                    select.addEventListener('input', () => {
                            propagateSelectElement(document.querySelector(`#${select.getAttribute('data-target')}`), `/eArchive/PostalCodes/select.php?id_countries=${select.selectedOptions[0].value}`)
                        }) // addEventListener
                }) // forEach
                // propagate programs by faculty selection
            facultySlct.addEventListener('input', () => {
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

    // restitute form for insertion of the student data  
    let toStudentInsertFrm = () => {
            studentFrm.innerHTML = studentCloneFrm.innerHTML
            attachStudentFrmListeners()
                // clear up all input field values 
            emptyFrmInptFields(studentFrm)
            studentFrm.querySelector('input[type=submit]').value = 'Vstavi'
                // exchange callbacks
            studentFrm.removeEventListener('submit', updateStudent)
            studentFrm.addEventListener('submit', insertStudent)
        } // toStudentInsertFrm

    /*
     *   rearrange form when inserting student data   
     *   @param Event e
     */
    let toSPInsertFrm = e => {
            document.querySelector('#sPMdl .modal-header .modal-title').textContent = 'Vstavljanje znanstvenega dela'
                // clone from the existing cloned form node
            let cloneFrm = sPCloneFrm.cloneNode(true)
            cloneFrm.querySelector('input[name=id_attendances]').value = e.target.getAttribute('data-id-attendances')
                // replace form element node with its clone
            document.getElementById('sPFrm').replaceWith(cloneFrm)
            cloneFrm.querySelector('input[type=submit]').value = 'Vstavi'
            attachSPFrmListeners()
            cloneFrm.addEventListener('submit', insertScientificPaper)
        } // toSPInsertFrm

    // attach listeners to student evidence table appropriate anchors and buttons   
    let attachStudentTableListeners = () => {
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
                            selectScientificPapers(anchor.getAttribute('data-id-attendances'))
                            sPCloneFrm.querySelector('input[name=id_attendances]').value = anchor.getAttribute('data-id-attendances')
                        }) //addEventListener
                }) // forEach
            sPIALst.forEach(anchor => {
                    // modify form for scientific paper insertion
                    anchor.addEventListener('click', toSPInsertFrm)
                }) // forEach
            certIALst.forEach(anchor => {
                    // assign an attendance id value to an upload forms hidden input type 
                    anchor.addEventListener('click', e => {
                            certFrm.querySelector('input[type=hidden]').value = anchor.getAttribute('data-id-attendances')
                        }) //addEventListener
                }) // forEach
            certVALst.forEach(anchor => {
                    // view certificate particulars in a form of a card in the modal
                    anchor.addEventListener('click', () => {
                            selectGraduationCertificate(anchor.getAttribute('data-id-attendances'))
                                // set value of id to the hidden input of the form
                            certCloneFrm.querySelector('input[name=id_attendances]').value = anchor.getAttribute('data-id-attendances')
                        }) // addEventListener
                }) // forEach
            stuUALst.forEach(anchor => {
                    // propagate update form with student particulars
                    anchor.addEventListener('click', e => {
                            selectStudent(e, anchor.getAttribute('data-id-students'))
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
                            deleteStudentAccount(btn.getAttribute('data-id-attendances'))
                        }) //addEventListener
                }) // forEach
            accIBtnLst.forEach(btn => {
                    // pass an id of an attendance through forms hidden input type 
                    btn.addEventListener('click', () => {
                            acctFrm.querySelector('input[name=id_attendances]').value = btn.value
                        }) // addEventListener
                }) // forEach
        } // attachStudentTableListeners
    attachStudentTableListeners()

    /*
     *   instantiate an object of integrated XHR interface and make an asynchronous operation on a script   
     *   @param String script
     *   @param String method
     *   @param String responseType 
     */
    let request = (script, method, resType = '', frmData = null) => {
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
    let refreshStudentsTable = () => {
            request('/eArchive/Students/selectAll.php', 'GET', 'document').then(response => {
                    let tblCtr = document.querySelector('.table-responsive')
                        // compose node tree structure
                    fragment = response
                        // reflect fragments body  
                    tblCtr.innerHTML = fragment.body.innerHTML
                    attachStudentTableListeners()
                }).catch(error => {
                    alert(error)
                }) // catch
        } // refreshStudentsTable

    /*
     *   propagate passed select element with options from the requested resource 
     *   @param HTMLSelectElement select
     *   @param String script
     *   @param Number id
     */
    let propagateSelectElement = async(select, script, id = 0) => {
            try {
                const response = await request(script, 'GET', 'document')
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
            }
        } // propagateSelectElement

    /*
     *  !recursive 
     *  create and subsequently append form controls for new temporal residence section 
     *  @param Array residences
     */
    let addTempResFrmSect = (residences = null) => {
            return new Promise((resolve) => {
                    // instantiate a MutationObserver object
                    let observer = new MutationObserver(() => {
                            // if updating recorded temporal residence data 
                            if (residences) {
                                residences.shift()
                                    // if there's more records
                                if (residences.length)
                                    resolve(addTempResFrmSect(residences))
                                resolve()
                            } // if
                        }), // MutationObserver
                        // create form controls 
                        container = document.createElement('div'),
                        headline = document.createElement('p'),
                        cross = document.createElement('span'),
                        countryFG = document.createElement('div'),
                        postalCodeFG = document.createElement('div'),
                        addressFG = document.createElement('div'),
                        countryLbl = document.createElement('label'),
                        postalCodeLbl = document.createElement('label'),
                        addressLbl = document.createElement('label'),
                        statusHiddInpt = document.createElement('input'),
                        countrySlct = document.createElement('select'),
                        postalCodeSlct = document.createElement('select'),
                        addressInpt = document.createElement('input'),
                        lblNum = document.querySelectorAll('#residences .row').length, // number of added temporal residences 
                        indx = lblNum // the following index for an array of data on student residences 
                        // set the target and options of observation
                    observer.observe(document.getElementById('residences'), {
                            attributes: false,
                            childList: true,
                            subtree: false
                        }) // observe
                    container.className = 'row temporal-residence'
                    container.style.position = 'relative'
                    headline.classList = 'col-12 h6'
                    headline.textContent = `${lblNum}. začasno bivališče`
                    cross.style.float = 'right'
                    cross.style.transform = 'scale(1.2)'
                    cross.style.cursor = 'pointer'
                    cross.innerHTML = '&#10007;'
                    cross.setAttribute('data-id-residences', !residences ? '' : residences[0].id_residences)
                    cross.addEventListener('click', () => {
                            // if data attributes value isn't empty 
                            if (cross.getAttribute('data-id-residences') !== '')
                                deleteTempResOfStudent(cross.getAttribute('data-id-residences'))
                            document.getElementById('residences').removeChild(container)
                        }) // addEventListener
                    countryFG.className = 'form-group col-4'
                    postalCodeFG.className = 'form-group col-4'
                    addressFG.className = 'form-group col-4'
                    countryLbl.setAttribute('for', `TRCountrySlct${lblNum}`)
                    countryLbl.textContent = 'Država'
                    postalCodeLbl.setAttribute('for', `TRPCSlct${lblNum}`)
                    postalCodeLbl.textContent = 'Kraj'
                    addressLbl.setAttribute('for', `TRAddressInpt${lblNum}`)
                    addressLbl.textContent = 'Naslov'
                    statusHiddInpt.type = 'hidden'
                    statusHiddInpt.name = `residences[${indx}][status]`
                    statusHiddInpt.value = 'ZAČASNO'
                    countrySlct.id = `TRCountrySlct${lblNum}`
                    countrySlct.classList = 'form-control country-select'
                    countrySlct.addEventListener('input', () => {
                            propagateSelectElement(
                                postalCodeSlct,
                                `/eArchive/postalCodes/select.php?id_countries=${countrySlct.selectedOptions[0].value}`
                            )
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
                    headline.appendChild(cross)
                    countryFG.appendChild(countryLbl)
                    countryFG.appendChild(countrySlct)
                    postalCodeFG.appendChild(postalCodeLbl)
                    postalCodeFG.appendChild(postalCodeSlct)
                    addressFG.appendChild(addressLbl)
                    addressFG.appendChild(addressInpt)
                    container.appendChild(headline)
                    container.appendChild(statusHiddInpt)
                    container.appendChild(countryFG)
                    container.appendChild(postalCodeFG)
                    container.appendChild(addressFG)
                    propagateSelectElement(
                            countrySlct,
                            '/eArchive/Countries/select.php', !residences ? null : residences[0].id_countries
                        ).then(() => {
                            propagateSelectElement(
                                postalCodeSlct,
                                `/eArchive/PostalCodes/select.php?id_countries=${countrySlct.selectedOptions[0].value}`, !residences ? null : residences[0].id_postal_codes
                            )
                            return
                        }).then(() => {
                            addressInpt.value = !residences ? '' : residences[0].address
                        }).then(() => {
                            document.getElementById('residences').appendChild(container)
                        }).catch((error) => {
                            alert(error)
                        }) // catch
                }) // Promise
        } // addTempResFrmSect

    /*
     *  subsequently create and append graduation section of the student insertion form 
     *  @param Event e
     */
    let addProgramGraduationFrmSect = e => {
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
                    certificateHiddInpt.value = e.target.files[0].name
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
    let addProgramAttendanceSect = () => {
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
    let addPartakerFrmSect = () => {
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
            partakerLbl.htmlFor = `partakerInpt${lblNum}`
            partakerLbl.textContent = 'Sodelovalec'
            partLbl.htmlFor = `partInpt${lblNum}`
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
    let addDocumentFrmSect = () => {
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
    let addMentoringsFrmSect = () => {
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
    let emptyFrmInptFields = frm => {
            frm.querySelectorAll('input:not(input[type=hidden]').forEach(input => {
                    input.value = ''
                }) // forEach
        } // emptyFrmInptFields

    /*  
     *   interpolate datalist with name, surname and index number of the inserted student
     *   @param DOMString fullname
     *   @param Number index
     */
    let interpolateStudentDatalist = (fullname, index) => {
            let option = document.createElement('option')
            option.value = index
            option.textContent = fullname
            sPCloneFrm.querySelector('datalist').appendChild(option)
        } // interpolateStudentDatalist

    /*
     *   asynchronous script execution for selection of student particulars and scientific achievements    
     *   @param Event e
     *   @param Number idStudents
     */
    let selectStudent = (e, idStudents) => {
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
    let selectStudentsByIndex = index => {
            request(`/eArchive/Students/filterByIndex.php?index=${index}`, 'GET', 'document').then(response => {
                    let tblCtr = document.querySelector('.table-responsive')
                        // compose node tree structure
                    fragment = response
                        // reflect fragments body  
                    tblCtr.innerHTML = fragment.body.innerHTML
                    attachStudentTableListeners()
                }).catch(error => {
                    alert(error)
                }) // catch
        } // selectStudentsByIndex

    // filter students by their index numbers 
    filterInpt.addEventListener('input', () => {
            selectStudentsByIndex(filterInpt.value)
        }) // addEventListener

    /*
     *   asynchronous script execution for insretion of student particulars and scientific achievements
     *   @param Event e
     */
    let insertStudent = e => {
            // prevent default action of submitting student data through a form
            e.preventDefault()
            request('/eArchive/Students/insert.php', 'POST', 'text', (new FormData(studentFrm))).then(response => {
                    reportMdl.querySelector('.modal-body').textContent = response
                    reportMdlBtn.click()
                    emptyFrmInptFields(studentFrm)
                        // close the modal after insertion 
                    document.getElementById('insertStudentBtn').click()
                    return
                }).then(() => {
                    refreshStudentsTable()
                    return
                })
                .then(() => {
                    interpolateStudentDatalist()
                }).catch(error => {
                    alert(error)
                }) // catch
        } // insertStudent

    /*
     *  fill out form fields with student birthplace particulars
     *  @param HTMLFormElement frm
     *  @param Number idpostalCode
     *  @param Number idCountries
     */
    let determineStudentBirthplace = (frm, idPostalCodes, idCountries) => {
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
    let determineStudentPermanentResidence = (frm, residence) => {
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
     *  @param Array residences
     */
    let determineTempResOfStudent = residences => {
            addTempResFrmSect(residences)
        } // determineTempResOfStudent

    /*
     *   delete student temporal residence by clicking cross sign of a section 
     *   @param idStudents
     *   @param idResidences
     */
    let deleteTempResOfStudent = idResidences => {
            request(`/eArchive/Residences/delete.php?id_residences=${idResidences}`, 'GET', 'text').then(response => {
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
    let updateStudent = e => {
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
    let deleteStudent = (idStudents, idAttendances) => {
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
     */
    let assignStudentAccount = e => {
            // prevent default action by submitting data insert form
            e.preventDefault()
            request(
                    '/eArchive/Accounts/authorized/insert.php',
                    'POST',
                    'text',
                    (new FormData(acctFrm))
                ).then(response => {
                    // report on account assignment 
                    reportMdl.querySelector('.modal-body').textContent = response
                    $('#reportMdl').modal('show')
                        // close the modal after account assignment
                    $('#acctMdl').modal('hide')
                    return
                }).then(() => {
                    // repaint student evidence table
                    refreshStudentsTable()
                }).catch(error => {
                    alert(error)
                }) // catch
        } // assignStudentAccount

    acctFrm.addEventListener('submit', e => {
            // prevent form from submitting account details  
            e.preventDefault()
            assignStudentAccount(e)
        }) // addEventListener

    /*
     *   asynchronous script execution for deletion of the given account 
     *   @param idAttendances
     */
    let deleteStudentAccount = idAttendances => {
            request(`/eArchive/Accounts/authorized/delete.php?id_attendances=${idAttendances}`, 'GET', 'text').then(response => {
                    // report on account deletion
                    reportMdl.querySelector('.modal-body').textContent = response
                    reportMdlBtn.click()
                    refreshStudentsTable()
                }).catch(error => {

                }) // catch
        } // deleteStudentAccount

    /*
     *   rearrange form and fill out form fields when updating student data
     *   @param Object sPpr
     */
    let toSPUpdateFrm = sPpr => {
            document.querySelector('#sPMdl .modal-header .modal-title').textContent = 'Urejanje podatkov znanstvenega dela'
                // clone from the existing cloned form node
            let cloneFrm = sPCloneFrm.cloneNode(true),
                idSPHiddInpt = document.createElement('input')
            idSPHiddInpt.type = 'hidden'
            idSPHiddInpt.name = 'id_scientific_papers'
            idSPHiddInpt.value = sPpr.id_scientific_papers
                // replace form element node with its clone
            document.getElementById('sPFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idSPHiddInpt)
            attachSPFrmListeners()
            cloneFrm.querySelector('input[name="topic"]').value = sPpr.topic
            cloneFrm.querySelector('select[name="type"]').value = sPpr.type
            cloneFrm.querySelector('input[name="written"]').value = sPpr.written
            cloneFrm.querySelector('input[type=submit]').value = 'Uredi'
                // remove determined element nodes 
            cloneFrm.querySelectorAll('div.row:nth-child(4), #sPDocs').forEach(node => {
                    node.parentElement.removeChild(node)
                }) // forEach
            cloneFrm.addEventListener('submit', e => {
                    // prevent default action of submitting scientific paper data    
                    e.preventDefault()
                    updateScientificPaper(cloneFrm)
                }) // addEventListener
        } // toSPUpdateFrm

    /*
     *   transform to form for upload of scientific paper documents
     *   @param Event e
     */
    let toSPDocumentUploadFrm = e => {
            document.querySelector('#sPMdl .modal-header .modal-title').textContent = 'Nalaganje dokumentov znanstvenega dela'
                // clone from the existing cloned form node
            let cloneFrm = sPCloneFrm.cloneNode(true),
                idSPHiddInpt = document.createElement('input')
            idSPHiddInpt.type = 'hidden'
            idSPHiddInpt.name = 'id_scientific_papers'
            idSPHiddInpt.value = e.target.getAttribute('data-id-scientific-papers')
                // replace form node with its clone
            sPFrm.replaceWith(cloneFrm)
            cloneFrm.prepend(idSPHiddInpt)
                // widen form group across the whole grid
            cloneFrm.querySelector('#sPDocs').classList = 'col-12'
            cloneFrm.querySelector('input[type=submit]').value = 'Naloži'
            attachSPFrmListeners()
                // remove nodes except those matching given selector expression 
            cloneFrm.querySelectorAll('div#particulars, div.row:nth-child(4)').forEach(node => {
                    node.parentElement.removeChild(node)
                }) // forEach
            cloneFrm.addEventListener('submit', e => {
                    // prevent upload of scientific paper documents
                    e.preventDefault()
                    insertDocumentsOfScientificPaper(cloneFrm)
                }) // addEventListener
        } // toSPDocumentUploadFrm

    /*
     *  rearrange form when inserting data of the scientific paper partaker   
     *  @param Event e
     */
    let toPartakerInsertFrm = e => {
            document.querySelector('#sPMdl .modal-header .modal-title').textContent = 'Dodeljevanje soavtorja znanstvenega dela'
                // clone from the existing cloned form node
            let cloneFrm = sPCloneFrm.cloneNode(true),
                idSPHiddInpt = document.createElement('input')
            idSPHiddInpt.type = 'hidden'
            idSPHiddInpt.name = 'id_scientific_papers'
            idSPHiddInpt.value = e.target.getAttribute('data-id-scientific-papers')
                // replace form element node with its clone
            document.getElementById('sPFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idSPHiddInpt)
                // widen form group across the whole grid
            cloneFrm.querySelector('#sPPartakers').classList = 'col-12'
            cloneFrm.querySelector('input[type=submit]').value = 'Dodeli'
            attachSPFrmListeners()
                // dispatch a synthetic click event
            cloneFrm.querySelector('#addPartakerBtn').dispatchEvent((new Event('click')))
                // remove nodes except those matching given selector expression 
            cloneFrm.querySelectorAll('div#particulars, #sPMentors, #sPDocs, p, button').forEach(node => {
                    node.parentElement.removeChild(node)
                }) // forEach
            cloneFrm.addEventListener('submit', e => {
                    // cancel submitting partaker data by default
                    e.preventDefault()
                    insertPartakerOfScientificPaper(cloneFrm)
                }) // addEventListener
        } // toPartakerInsertFrm

    /*
     *  rearrange form when updating data with regard to partaker of the scientific paper 
     *  @param Event e
     */
    let toPartakerUpdateFrm = e => {
            document.querySelector('#sPMdl .modal-header .modal-title').textContent = 'Urejanje vloge soavtorja znanstvenega dela'
                // clone from the existing cloned form node
            let cloneFrm = sPCloneFrm.cloneNode(true),
                idPartakingsHiddInpt = document.createElement('input')
            idPartakingsHiddInpt.type = 'hidden'
            idPartakingsHiddInpt.name = 'id_partakings'
            idPartakingsHiddInpt.value = e.target.getAttribute('data-id-partakings')
                // replace form node with its clone
            document.getElementById('sPFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idPartakingsHiddInpt)
                // widen form group across the whole grid
            cloneFrm.querySelector('#sPPartakers').classList = 'col-12'
            attachSPFrmListeners()
                // dispatch a synthetic click event
            cloneFrm.querySelector('#addPartakerBtn').dispatchEvent((new Event('click')))
                // remove nodes except those matching given selector expression 
            cloneFrm.querySelectorAll('div#particulars, #sPMentors, #sPDocs, p, .d-flex, button').forEach(node => {
                    node.parentElement.removeChild(node)
                }) // forEach
                // populate form fields concerning data of the partaker
            cloneFrm.querySelector('input[name="partakers[1][index]"]').value = e.target.getAttribute('data-index')
            cloneFrm.querySelector('input[name="partakers[1][part]"]').value = e.target.getAttribute('data-part')
            cloneFrm.querySelector('input[type=submit]').value = 'Uredi'
            cloneFrm.addEventListener('submit', e => {
                    // cancel submitting partaker data by default
                    e.preventDefault()
                    updatePartakerOfScientificPaper(cloneFrm)
                }) // addEventListener
        } // toPartakerUpdateFrm

    /*
     *  rearrange form when inserting data regarding mentor of the scientific paper 
     *  @param Event e
     */
    let toMentorInsertFrm = e => {
            document.querySelector('#sPMdl .modal-header .modal-title').textContent = 'Določanje mentorja znanstvenega dela'
                // clone from the existing cloned form node
            let cloneFrm = sPCloneFrm.cloneNode(true),
                idSPHiddInpt = document.createElement('input')
            idSPHiddInpt.type = 'hidden'
            idSPHiddInpt.name = 'id_scientific_papers'
            idSPHiddInpt.value = e.target.getAttribute('data-id-scientific-papers')
                // replace form element node with its clone
            document.getElementById('sPFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idSPHiddInpt)
                // widen form group across the whole grid
            cloneFrm.querySelector('#sPMentors').classList = 'col-12'
            cloneFrm.querySelector('input[type=submit]').value = 'Določi'
            attachSPFrmListeners()
                // dispatch a synthetic click event
            cloneFrm.querySelector('#addMentorBtn').dispatchEvent((new Event('click')))
                // remove nodes except those matching given selector expression 
            cloneFrm.querySelectorAll('div#particulars, #sPPartkers, #sPDocs, p, button').forEach(node => {
                    node.parentElement.removeChild(node)
                }) // forEach
            cloneFrm.addEventListener('submit', e => {
                    // cancel submitting mentor data by default
                    e.preventDefault()
                    insertMentorOfScientificPaper(cloneFrm)
                }) // addEventListener
        } // toMentorInsertFrm

    /*
     *  rearrange form when updating data with regard to mentor of the scientific paper  
     *  @param Event e
     */
    let toMentorUpdateFrm = e => {
            document.querySelector('#sPMdl .modal-header .modal-title').textContent = 'Urejanje podatkov mentorja znanstvenega dela'
            let cloneFrm = sPCloneFrm.cloneNode(true),
                idMentoringsHiddInpt = document.createElement('input')
            idMentoringsHiddInpt.type = 'hidden'
            idMentoringsHiddInpt.name = 'id_mentorings'
            idMentoringsHiddInpt.value = e.target.getAttribute('data-id-mentorings')
                // replace form element node with its clone
            document.getElementById('sPFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idMentoringsHiddInpt)
            cloneFrm.querySelector('input[type=submit]').value = 'Uredi'
            attachSPFrmListeners()
                // dispatch a synthetic click event to button for subsequent addition of form mentor section
            cloneFrm.querySelector('#addMentorBtn').dispatchEvent((new Event('click')))
                // remove DIV nodes except matching given selector expression 
            cloneFrm.querySelectorAll('#particulars, #sPPartakers, #sPDocs, p, button').forEach(node => {
                    node.parentElement.removeChild(node)
                }) // forEach
                // widen form group across the whole grid
            cloneFrm.querySelector('#sPMentors').classList = 'col-12'
                // remove the headline
            request(`/eArchive/Mentorings/select.php?id_mentorings=${e.target.getAttribute('data-id-mentorings')}`, 'GET', 'json').then(response => {
                    // populate form fields with selected mentor data
                    cloneFrm.querySelector('input[name=id_mentorings]').value = e.target.getAttribute('data-id-mentorings')
                    cloneFrm.querySelector('input[name="mentors[0][mentor]"]').value = response.mentor
                    cloneFrm.querySelector('select[name="mentors[0][id_faculties]"]').value = response.id_faculties
                    cloneFrm.querySelector('input[name="mentors[0][taught]"]').value = response.taught
                    cloneFrm.querySelector('input[name="mentors[0][email]"]').value = response.email
                    cloneFrm.querySelector('input[name="mentors[0][telephone]"]').value = response.telephone
                }).catch(error => {
                    alert(error)
                }) // catch
            cloneFrm.addEventListener('submit', e => {
                    // prevent form from submitting updated mentor data 
                    e.preventDefault();
                    updateMentorOfScientificPaper(cloneFrm)
                }) // addEventListener
        } // toMentorUpdateFrm

    /*
     *  rearrange form when updating data regarding students graduation certificate  
     *  @param Event e
     */
    let toGradCertUpdateFrm = e => {
            document.querySelector('#certUploadMdl .modal-header .modal-title').textContent = 'Urejanje podatkov certifikata'
                // clone from the existing cloned form node
            let cloneFrm = certCloneFrm.cloneNode(true),
                idCertificatesHiddInpt = document.createElement('input')
            idCertificatesHiddInpt.type = 'hidden'
            idCertificatesHiddInpt.name = 'id_certificates'
            idCertificatesHiddInpt.value = e.target.getAttribute('data-id-certificates')
                // replace form element node with its clone
            document.getElementById('certFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idCertificatesHiddInpt)
            attachCertificateCardListeners()
                // remove certificate file input 
            cloneFrm.querySelector('div.row').removeChild(cloneFrm.querySelector('div.row').querySelector('.form-group'))
                // fill out form fileds with carried data
            cloneFrm.querySelector('input[name=defended]').value = e.target.getAttribute('data-defended')
            cloneFrm.querySelector('input[name=issued]').value = e.target.getAttribute('data-issued')
                // change submit buttons value
            cloneFrm.querySelector('input[type=submit]').value = 'Uredi'
            cloneFrm.addEventListener('submit', e => {
                    // cancel submitting updated certificate data by default
                    e.preventDefault()
                    updateGraduationCertificate(cloneFrm)
                }) // addEventListener
        } // toGradCertUpdateFrm

    /*
     *   transform to form for student particulars update
     *   @param Event e
     *   @param Object student
     */
    let toStudentUpdateFrm = (e, student) => {
            let idStudents = document.createElement('input')
            idStudents.type = 'hidden'
            idStudents.name = 'id_students'
            idStudents.value = e.target.getAttribute('data-id-students')
            studentFrm.innerHTML = studentCloneFrm.innerHTML
            attachStudentFrmListeners()
            studentFrm.prepend(idStudents)
                // fill out input fields with student particulars
            studentFrm.querySelector('input[name=name]').value = student.particulars.name
            studentFrm.querySelector('input[name=surname]').value = student.particulars.surname
            studentFrm.querySelector('input[name=email]').value = student.particulars.email
            studentFrm.querySelector('input[name=telephone]').value = student.particulars.telephone
            determineStudentBirthplace(studentFrm, student.particulars.id_postal_codes, student.particulars.id_countries)
            determineStudentPermanentResidence(studentFrm, student.permResidence)
            determineTempResOfStudent(student.tempResidence)
            studentFrm.removeChild(studentFrm.querySelector('#attendances'))
            studentFrm.querySelector('input[type=submit]').value = 'Posodobi'
                // exchange callbacks
            studentFrm.removeEventListener('submit', insertStudent)
            studentFrm.addEventListener('submit', updateStudent)
        } // toStudentUpdateFrm

    // attach event listeners to a scientific paper cards when rendered
    let attachSPCardListeners = () => {
            // if anchor nodes for partaker insertion exist
            if (document.querySelectorAll('.par-ins-a'))
                document.querySelectorAll('.par-ins-a').forEach(anchor => {
                    // form will contain only control for partaker insertion
                    anchor.addEventListener('click', toPartakerInsertFrm)
                }) // forEach
                // if spans for scientific paper partaker deletion exist
            if (document.querySelectorAll('.par-del-spn'))
                document.querySelectorAll('.par-del-spn').forEach(span => {
                    // attempt deletion of a partaker
                    span.addEventListener('click', () => {
                            deletePartakerOfScientificPaper(span.getAttribute('data-id-partakings'))
                        }) // addEventListener
                }) // forEach
                // if anchors for scientific paper partaker data update exist
            if (document.querySelectorAll('.par-upd-a'))
                document.querySelectorAll('.par-upd-a').forEach(anchor => {
                    // attempt deletion of a partaker
                    anchor.addEventListener('click', toPartakerUpdateFrm) // addEventListener
                }) // forEach
                // if anchors for scientific paper partaker data update exist
            if (document.querySelectorAll('.par-upd-a'))
                document.querySelectorAll('.par-upd-a').forEach(anchor => {
                    // attempt deletion of a partaker
                    anchor.addEventListener('click', toPartakerUpdateFrm) // addEventListener
                }) // forEach
                // if anchors for mentor insertion are rendered
            if (document.querySelectorAll('.men-ins-a'))
                document.querySelectorAll('.men-ins-a').forEach(anchor => {
                    // restructure form for document upload
                    anchor.addEventListener('click', toMentorInsertFrm)
                }) // forEach
                // if anchor elements for mentor data update exist
            if (document.querySelectorAll('.men-upd-a'))
                document.querySelectorAll('.men-upd-a').forEach(anchor => {
                    // restructure form for document upload
                    anchor.addEventListener('click', toMentorUpdateFrm)
                }) // forEachF
                // if span elements for mentor deletion are rendered
            if (document.querySelectorAll('.men-del-spn'))
                document.querySelectorAll('.men-del-spn').forEach(anchor => {
                    // restructure form for document upload
                    anchor.addEventListener('click', () => {
                            deleteMentorOfScientificPaper(anchor.getAttribute('data-id-mentorings'))
                        }) // addEventListener
                }) // forEach
                // if anchors for scientific paper update are rendered
            if (document.querySelectorAll('.sp-upd-а'))
                document.querySelectorAll('.sp-upd-а').forEach(anchor => {
                    // fill form fields and modify the form
                    anchor.addEventListener('click', e => {
                            request(`/eArchive/ScientificPapers/select.php?id_scientific_papers=${anchor.getAttribute('data-id-scientific-papers')}`, 'GET', 'json').then(response => {
                                    // retrieve JSON of ScientificPapers object 
                                    toSPUpdateFrm(response)
                                }).catch(error => {
                                    alert(error)
                                }) // catch
                        }) // addEventListener
                }) // forEach
                // if anchors for scientific paper deletion are rendered
            if (document.querySelectorAll('.sp-del-a'))
                document.querySelectorAll('.sp-del-a').forEach(anchor => {
                    anchor.addEventListener('click', () => {
                            deleteScientficPaper(anchor.getAttribute('data-id-scientific-papers'))
                        }) // addEventListener
                }) // forEach
                // if anchors for scientific paper document upload exist
            if (document.querySelectorAll('.doc-upl-a'))
                document.querySelectorAll('.doc-upl-a').forEach(span => {
                    // delete particular document
                    span.addEventListener('click', toSPDocumentUploadFrm)
                }) // forEach
                // if anchors for scientific paper documentation deletion are rendered
            if (document.querySelectorAll('.doc-del-spn'))
                document.querySelectorAll('.doc-del-spn').forEach(span => {
                    // delete particular document
                    span.addEventListener('click', () => {
                            deleteDocumentOfScientificPaper(span.getAttribute('data-source'))
                        }) // addEventListener
                }) // forEach
        } // attachSPCardListeners

    // attach listeners to certificate card when selected
    let attachCertificateCardListeners = () => {
            // get modal for graduation certificate review
            let mdl = document.getElementById('certViewMdl')
                // if anchor element for update of certificate connected data exist
            if (mdl.querySelector('.modal-content .cert-upd-a'))
                mdl.querySelector('.modal-content .cert-upd-a').addEventListener('click', toGradCertUpdateFrm)
                // if anchor element for certificate deletion is contained
            if (mdl.querySelector('.modal-content .cert-del-a'))
                mdl.querySelector('.modal-content .cert-del-a').addEventListener('click', e => {
                    deleteGraduationCertificate(e.target.getAttribute('data-id-attendances'), e.target.getAttribute('data-source'))
                }) // addEventListner
        } // attachCertificateCardListeners

    // asynchronous script execution for insertion of a scientific paper partaker    
    let insertPartakerOfScientificPaper = frm => {
            request('/eArchive/Partakings/insert.php', 'POST', 'text', (new FormData(frm))).then(response => {
                    // report on the insertion
                    reportMdl.querySelector('.modal-body').textContent = response
                    reportMdlBtn.click()
                        // close the modal after submission
                    $('#sPMdl').modal('hide')
                    return
                }).then(() => {
                    // repaint cards containing data concerning scientific papers
                    selectScientificPapers(frm.querySelector('input[name=id_attendances]').value)
                }).catch(error => {
                    alert(error)
                }) // catch
        } // insertPartakerOfScientificPaper

    /*
     *  asynchronous script execution for update of a scientific paper partakers data    
     *  @param Number idPartakings
     */
    let updatePartakerOfScientificPaper = frm => {
            request('/eArchive/Partakings/update.php', 'POST', 'text', (new FormData(frm))).then(response => {
                    // report on update
                    reportMdl.querySelector('.modal-body').textContent = response
                    reportMdlBtn.click()
                    return
                }).then(() => {
                    selectScientificPapers(frm.querySelector('input[name=id_attendances]').value)
                }).catch(error => {
                    alert(error)
                }) // catch
        } // updatePartakerOfScientificPaper

    /*
     *  asynchronous script execution for deletion of a scientific paper partaker    
     *  @param Number idPartakings
     */
    let deletePartakerOfScientificPaper = idPartakings => {
            request(`/eArchive/Partakings/delete.php?id_partakings=${idPartakings}`, 'GET', 'text').then(response => {
                    // report on deletion
                    reportMdl.querySelector('.modal-body').textContent = response
                    reportMdlBtn.click()
                }).then(() => {
                    selectScientificPapers(document.getElementById('sPFrm').querySelector('input[name=id_attendances]').value)
                }).catch(error => {
                    alert(error)
                }) // catch
        } // deletePartakerOfScientificPaper

    /*
     *  asynchronous script execution for selecting mentor data of the subject scientific paper    
     *  @param Number idMentorings
     */
    let selectMentorOfScientificPaper = idMentorings => {
            request('/eArchive/Mentorings/insert.php', 'GET', 'text').then(response => {
                    // report on selection
                    reportMdl.querySelector('.modal-body').textContent = response
                    reportMdlBtn.click()
                }).catch(error => {
                    alert(error)
                }) // catch
        } // selectMentorOfScientificPaper

    /*
     *  asynchronous script execution for inserting submitted mentor data     
     *  @param HTMLFormElement | Node frm
     */
    let insertMentorOfScientificPaper = frm => {
            request('/eArchive/Mentorings/insert.php', 'POST', 'text', (new FormData(frm))).then(response => {
                    // report on update
                    reportMdl.querySelector('.modal-body').textContent = response
                    reportMdlBtn.click()
                        // close the modal after submission
                    $('#sPMdl').modal('hide')
                    return
                }).then(() => {
                    // repaint cards containing data concerning scientific papers
                    selectScientificPapers(frm.querySelector('input[name=id_attendances]').value)
                }).catch(error => {
                    alert(error)
                }) // catch
        } // insertMentorOfScientificPaper

    // asynchronously run script for update of data with regard to mentor of the scientific paper       
    let updateMentorOfScientificPaper = frm => {
            request('/eArchive/Mentorings/update.php', 'POST', 'text', (new FormData(frm))).then(response => {
                    // report on update
                    reportMdl.querySelector('.modal-body').textContent = response
                    reportMdlBtn.click()
                    return
                }).then(() => {
                    selectScientificPapers(frm.querySelector('input[name=id_attendances]').value)
                }).catch(error => {
                    alert(error)
                }) // catch
        } // updateMentorOfScientificPaper

    /*
     *  asynchronously run script for deletion of mentor data concerning scientific paper       
     *  @param Number idMentorings
     */
    let deleteMentorOfScientificPaper = idMentorings => {
            request(`/eArchive/Mentorings/delete.php?id_mentorings=${idMentorings}`, 'GET', 'text').then(response => {
                    // report on deletion
                    reportMdl.querySelector('.modal-body').textContent = response
                    reportMdlBtn.click()
                }).then(() => {
                    selectScientificPapers(document.getElementById('sPFrm').querySelector('input[name=id_attendances]').value)
                }).catch(error => {
                    alert(error)
                }) // catch
        } // deleteMentorOfScientificPaper

    /*  
     *   asynchronous script execution for scientific paper documents upload    
     *   @param Event e
     */
    let insertDocumentsOfScientificPaper = frm => {
            request('/eArchive/Documents/insert.php', 'POST', 'text', (new FormData(frm))).then(response => {
                    // report on document deletion
                    reportMdl.querySelector('.modal-body').textContent = response
                    reportMdlBtn.click()
                        // close the modal after upload
                    $('#sPMdl').modal('hide')
                    return
                }).then(() => {
                    // repaint cards containing data concerning scientific papers
                    selectScientificPapers(frm.querySelector('input[name=id_attendances').value)
                }).catch(error => {
                    alert(error)
                }) // catch
        } // insertDocumentsOfScientificPaper

    /*
     *  asynchronous script execution for scientific paper documents deletion    
     *  @param DOMString source  
     */
    let deleteDocumentOfScientificPaper = source => {
            request(`/eArchive/Documents/delete.php?source=${source}`, 'GET', 'text').then(response => {
                    // report on document deletion
                    reportMdl.querySelector('.modal-body').textContent = response
                    reportMdlBtn.click()
                }).then(() => {
                    selectScientificPapers(document.getElementById('sPFrm').querySelector('input[name=id_attendances]').value)
                }).catch(error => {
                    alert(error)
                }) // catch
        } // deleteDocumentOfScientificPaper

    /*
     *   asynchronous script execution for selection of scientific papers per student program attendance 
     *   @param Number idAttendances
     */
    let selectScientificPapers = idAttendances => {
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
    let insertScientificPaper = e => {
            // prevent default action of submitting scientific paper data    
            e.preventDefault()
            request('/eArchive/ScientificPapers/insert.php', 'POST', 'text', (new FormData(sPFrm))).then(response => {
                    // report on scientific papers insertion
                    reportMdl.querySelector('.modal-body').textContent = response
                    reportMdlBtn.click()
                        // close the modal after insertion 
                    $('#studentMdl').modal('hide')
                }).catch(error => {
                    alert(error)
                }) // catch
        } // insertScientificPaper

    /*
     *   asynchronous script execution for scientific paper data alteration 
     *   @param Node | HTMLFormElement  e
     */
    let updateScientificPaper = frm => {
            request('/eArchive/ScientificPapers/update.php', 'POST', 'text', (new FormData(frm))).then(response => {
                    // report on scientific paper update
                    reportMdl.querySelector('.modal-body').textContent = response
                    reportMdlBtn.click()
                        // close the modal after update
                    $('#sPMdl').modal('hide')
                    return
                }).then(() => {
                    // repaint cards containing data concerning scientific papers 
                    selectScientificPapers(frm.querySelector('input[name=id_attendances]').value)
                }).catch(error => {
                    alert(error)
                }) // catch
        } // updateScientificPaper

    /*
     *  asynchronous script execution for scientific paper deletion with its belonging documents     
     *  @param Number idScientificPapers
     */
    let deleteScientficPaper = idScientificPapers => {
            request(`/eArchive/ScientificPapers/delete.php?id_scientific_papers=${idScientificPapers}`, 'GET', 'text').then(response => {
                    // report on the deletion
                    reportMdl.querySelector('.modal-body').textContent = response
                    reportMdlBtn.click()
                    return
                }).then(() => {
                    selectScientificPapers(document.getElementById('sPFrm').querySelector('input[name=id_attendances]').value)
                }).catch(error => {
                    alert(error)
                }) // catch
        } // deleteScientificPaper

    /*
     *   asynchronous script execution for graduation certificate upload/insertion     
     *   @param Event e
     */
    let uploadGraduationCertificate = e => {
            // prevent default action of submitting certificate insertion form
            e.preventDefault()
            request('/eArchive/Certificates/insert.php', 'POST', 'text', (new FormData(certFrm))).then(response => {
                    // report on the deletion
                    reportMdl.querySelector('.modal-body').textContent = response
                    reportMdlBtn.click()
                }).then(() => {
                    refreshStudentsTable()
                        // close certificate upload modal after uploading the certificate
                    $('#certUploadMdl').modal('hide')
                }).catch(error => {
                    alert(error)
                }) // catch
        } // uploadGraduationCertificate

    certFrm.addEventListener('submit', uploadGraduationCertificate)

    /*
     *  asynchronous script execution for graduation certificate selection    
     *  @param Number idAttendances
     */
    let selectGraduationCertificate = idAttendances => {
            request(`/eArchive/Certificates/select.php?id_attendances=${idAttendances}`, 'GET', 'document').then(response => {
                    // get modal for certificate review
                    let mdl = document.getElementById('certViewMdl')
                        // compose node tree structure
                    fragment = response
                        // reflect fragments body     
                    mdl.querySelector('.modal-content').innerHTML = fragment.body.innerHTML
                    attachCertificateCardListeners()
                }).catch(error => {
                    alert(error)
                }) // catch
        } // selectGraduationCertificate

    /*
     *  asynchronously script run for graduation certificate data update     
     *  @param Number idAttendance
     *  @param Number idCertificates
     */
    let updateGraduationCertificate = frm => {
            request('/eArchive/Certificates/update.php', 'POST', 'text', (new FormData(frm))).then(response => {
                    // report on update
                    reportMdl.querySelector('.modal-body').textContent = response
                    reportMdlBtn.click()
                        // close certificate upload modal after update
                    $('#certUploadMdl').modal('hide')
                    return
                }).then(() => {
                    // select update graduation certificate
                    selectGraduationCertificate(frm.querySelector('input[name=id_attendances]').value)
                }).catch(error => {
                    alert(error)
                }) // catch
        } // updateGraduationCertificate

    /*
     *  asynchronous script execution for graduation certificate deletion    
     *  @param Number idAttendance
     *  @param Number idCertificates
     */
    let deleteGraduationCertificate = (idAttendances, source) => {
            request(`/eArchive/Graduations/delete.php?id_attendances=${idAttendances}&source=${source}`, 'GET', 'text').then(response => {
                    // report on the deletion
                    reportMdl.querySelector('.modal-body').textContent = response
                    reportMdlBtn.click()
                    return
                }).then(() => {
                    refreshStudentsTable()
                        // close certificate review modal after deletion
                    $('#certViewMdl').modal('hide')
                }).catch(error => {
                    alert(error)
                }) // catch
        } // deleteGraduationCertificate
})()