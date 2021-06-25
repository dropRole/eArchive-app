// IIFE
(() => {
    // global scope variable declaration
    var fragment = new DocumentFragment(), // minimal document object structure
        studentFrm = document.getElementById('studentInsertionFrm'), // form for inserting and updating data regarding the student
        sciPapFrm = document.getElementById('sciPapInsertionMdl'), // form for inserting, updating and deleting data regarding the scientific paper 
        acctFrm = document.getElementById('acctInsertionFrm'), // form for creating student account and its credentials
        certFrm = document.getElementById('certUploadFrm'), // form for uploading graduation certificates
        reportMdl = document.getElementById('reportingMdl'), // modal for reporting about performed operations 
        reportMdlBtn = document.getElementById('reportMdlBtn'), // report modal toggler
        filterInpt = document.getElementById('filterByIndx') // input for filtering students by their index numbers

    certFrm.querySelector('input[type=file]').addEventListener('change', () => {
            // assign the name of the uploaded certificate to hidden input type
            certFrm.querySelector('input[name=certificate]').value = certFrm.querySelector('input[type=file]').files[0].name
        }) // addEventListener

    // attach event listeners to corresponding input element 
    let attachListenersToSciPapInsFrm = () => {
            // get the form 
            let frm = document.getElementById('sciPapInsertionMdl')
                // if button for subsequent partaker section additon exists
            if (frm.querySelector('#addPartakerBtn'))
                addPartakerBtn.addEventListener('click', addPartakerSection)
                // if file input is rendered 
            if (frm.querySelector('input[name="document[]"]'))
                frm.querySelector('input[name="document[]"]').addEventListener('change', e => {
                    // assign the filename of the uploaded document to the hidden input type
                    frm.querySelector('input[name="documents[0][name]"]').value = e.target.files[0].name
                }) // addEventListener
                // if button for subsequent mentor section additon exists 
            if (frm.querySelector('#addMentorBtn'))
                frm.querySelector('#addMentorBtn').addEventListener('click', addMentoringsSection)
                // if button for subsequent document section additon exists
            if (frm.querySelector('#addDocumentBtn'))
            // append controls for additional scientific paper document upload
                frm.querySelector('#addDocumentBtn').addEventListener('click', addDocUploadSection)
        } // attachSciPapFrmListeners

    // attach event listeners to corresponding input and selecet elements
    let attachStudentFrmListeners = () => {
            let addTempResBtn = document.getElementById('addTempResBtn'), // button for appending addiational temporal residence section 
                addAttendanceBtn = document.getElementById('addAttendanceBtn'), // button for apppending additional program attendance section
                ctrySelElLst = document.querySelectorAll('.country-select'), // elements for selecting birth, temporal and permanent residence country
                facSelElement = document.getElementById('facSelElement'), // faculty select element
                gradCheckBox = document.getElementById('gradCheckBox') // checkbox for denoting graduation
            addTempResBtn.addEventListener('click', () => {
                    addTempResFrmSect()
                }) // addEventListener
            addAttendanceBtn.addEventListener('click', addProgAttendanceSection)
            ctrySelElLst.forEach(element => {
                    // propagate target select element with postal codes of the chosen country
                    element.addEventListener('input', () => {
                            propagateSelectElement(document.querySelector(`#${element.getAttribute('data-target')}`), `/eArchive/PostalCodes/select.php?id_countries=${element.selectedOptions[0].value}`)
                        }) // addEventListener
                }) // forEach
            facSelElement.addEventListener('input', () => {
                    // propagate programs by faculty selection
                    propagateSelectElement(document.getElementById('progSelElement'), `/eArchive/Programs/select.php?id_faculties=${facSelElement.selectedOptions[0].value}`)
                }) // addEventListener
            gradCheckBox.addEventListener('change', e => {
                    // if it's checked
                    if (gradCheckBox.checked)
                    // append graduation section if graduated
                        addProgramGraduationFrmSect(e)
                    else {
                        // remove selected graduation section
                        gradCheckBox.closest('.row').removeChild(gradCheckBox.closest('.row').lastElementChild)
                        gradCheckBox.closest('.row').removeChild(gradCheckBox.closest('.row').lastElementChild)
                        gradCheckBox.closest('.row').removeChild(gradCheckBox.closest('.row').lastElementChild)
                    } // else
                }) // addEventListener
        } // attachStudentFrmListeners

    // rearrange form when inserting a student record  
    let toStudentInsertFrm = () => {
            // clone from the existing form node
            let cloneFrm = studentFrm.cloneNode(true)
                // replace form element node with its clone
            document.getElementById('studentInsertionFrm').replaceWith(cloneFrm)
            attachStudentFrmListeners()
            cloneFrm.querySelector('input[type=submit]').value = 'Vstavi'
                // exchange callbacks
            studentFrm.addEventListener('submit', e => insertStudent(e, cloneFrm))
        } // toStudentInsertFrm

    /*
     *   rearrange form when interpolating data regarding scientific paper and uploading its documents    
     *   @param Event e
     */
    let toSciPapInsertFrm = e => {
            document.querySelector('#sciPapInsertionMdl .modal-header .modal-title').textContent = 'Vstavljanje znanstvenega dela'
                // clone from the existing form node
            let cloneFrm = sciPapFrm.cloneNode(true)
            cloneFrm.querySelector('input[name=id_attendances]').value = e.target.getAttribute('data-id-attendances')
                // replace form element node with its clone
            document.getElementById('sciPapInsertionMdl').replaceWith(cloneFrm)
            cloneFrm.querySelector('input[type=submit]').value = 'Vstavi'
            attachListenersToSciPapInsFrm()
            cloneFrm.addEventListener('submit', insertScientificPaper)
        } // toSciPapInsertFrm

    // attach listeners to student evidence table appropriate anchors and buttons   
    let attachListenersToStudentEvidenceTbl = () => {
            let studentInsBtn = document.getElementById('studentInsBtn'), // button for exposing form for student scientific achievements insertion
                sciPapViewAnchorLst = document.querySelectorAll('.sp-vw-a'), // anchor list for exposing scientific papers of the student
                sciPapInsAnchorLst = document.querySelectorAll('.sp-ins-a'), // anchor list for exposing form for insertion of the scientific papers and belonging documents
                certInsAnchorLst = document.querySelectorAll('.cert-ins-a'), // anchor list for exposing form for uploading students graduation certificate
                certViewAnchorLst = document.querySelectorAll('.cert-vw-a'), // anchor list for exposing graduation certificate of the student
                acctInsBtnLst = document.querySelectorAll('.acc-ins-btn'), // button list for exposing form for assigning an account to student
                acctDelBtnLst = document.querySelectorAll('.acc-del-btn'), // button list for deletion of a particular student account 
                studentUpdAnchorLst = document.querySelectorAll('.stu-upd-a'), // anchor list for exposing form for updating fundamental data of the student
                studentDelAnchorLst = document.querySelectorAll('.stu-del-a') // anchor list for exposing form for deletion of fundamental data of the student
            studentInsBtn.addEventListener('click', toStudentInsertFrm)
            sciPapViewAnchorLst.forEach(anchor => {
                    // preview scientific papers   
                    anchor.addEventListener('click', () => {
                            selectScientificPapers(anchor.getAttribute('data-id-attendances'))
                            sciPapFrm.querySelector('input[name=id_attendances]').value = anchor.getAttribute('data-id-attendances')
                        }) //addEventListener
                }) // forEach
            sciPapInsAnchorLst.forEach(anchor => {
                    // modify form for scientific paper insertion
                    anchor.addEventListener('click', toSciPapInsertFrm)
                }) // forEach
            certInsAnchorLst.forEach(anchor => {
                    // assign an attendance id value to an upload forms hidden input type 
                    anchor.addEventListener('click', e => {
                            certFrm.querySelector('input[type=hidden]').value = anchor.getAttribute('data-id-attendances')
                        }) //addEventListener
                }) // forEach
            certViewAnchorLst.forEach(anchor => {
                    // view certificate particulars in a form of a card in the modal
                    anchor.addEventListener('click', () => {
                            selectGraduationCertificate(anchor.getAttribute('data-id-attendances'))
                                // set value of id to the hidden input of the form
                            certCloneFrm.querySelector('input[name=id_attendances]').value = anchor.getAttribute('data-id-attendances')
                        }) // addEventListener
                }) // forEach
            studentUpdAnchorLst.forEach(anchor => {
                    // propagate update form with student particulars
                    anchor.addEventListener('click', e => {
                            selectStudent(e, anchor.getAttribute('data-id-students'))
                        }) // addEventListener
                }) // forEach
            studentDelAnchorLst.forEach(anchor => {
                    // delete student from the student evidence table
                    anchor.addEventListener('click', () => {
                            // if record deletion was confirmed
                            if (confirm('S sprejemanjem boste izbrisali vse podatke o študentu ter podatke o znanstvenih dosežkih!'))
                                deleteStudent(anchor.getAttribute('data-id-students'), anchor.getAttribute('data-id-attendances'))
                        }) // addEventListener
                }) // forEach
            acctDelBtnLst.forEach(btn => {
                    // delete particular account 
                    btn.addEventListener('click', () => {
                            deleteStudentAccount(btn.getAttribute('data-id-attendances'))
                        }) //addEventListener
                }) // forEach
            acctInsBtnLst.forEach(btn => {
                    // pass an id of an attendance through forms hidden input type 
                    btn.addEventListener('click', () => {
                            acctFrm.querySelector('input[name=id_attendances]').value = btn.value
                        }) // addEventListener
                }) // forEach
        } // attachStudentTableListeners
    attachListenersToStudentEvidenceTbl()

    /*
     *   instantiate an object of integrated XHR interface and make an asynchronous operation on a script   
     *   @param String script
     *   @param String method
     *   @param String responseType 
     *   @param FormData frmData 
     */
    let request = (script, method, resType = '', frmData = null) => {
            return new Promise((resolve, reject) => {
                // instanitate an XHR object
                let xmlhttp = new XMLHttpRequest()
                xmlhttp.addEventListener('load', () => {
                        // resolve the promise if transaction was successful
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

    // refresh student evidence table upon latterly data amendment 
    let refreshStudentEvidenceTbl = () => {
            request('/eArchive/Students/selectAll.php', 'GET', 'document')
                .then(response => {
                    let tblCtr = document.querySelector('.table-responsive')
                        // compose node tree structure
                    fragment = response
                        // reflect fragments body  
                    tblCtr.innerHTML = fragment.body.innerHTML
                    attachListenersToStudentEvidenceTbl()
                }).catch(error => {
                    alert(error)
                }) // catch
        } // refreshStudentEvidenceTbl

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
                            } // if
                            else
                                resolve()
                        }), // MutationObserver
                        // form controls
                        container = document.createElement('div'),
                        headline = document.createElement('p'),
                        cross = document.createElement('span'),
                        ctryFrmGrp = document.createElement('div'),
                        postalCodeFrmGrp = document.createElement('div'),
                        addressFrmGrp = document.createElement('div'),
                        ctryLbl = document.createElement('label'),
                        postalCodeLbl = document.createElement('label'),
                        addressLbl = document.createElement('label'),
                        statInputElement = document.createElement('input'),
                        ctrySelElement = document.createElement('select'),
                        postalCodeSelElement = document.createElement('select'),
                        addressInputElement = document.createElement('input'),
                        index = document.querySelectorAll('div#residences > div.row').length // the following index for an array of data on student temporal residences 
                        // set the target and options of observation
                    observer.observe(document.getElementById('residences'), {
                            attributes: false,
                            childList: true,
                            subtree: false
                        }) // observe
                    container.className = 'row temporal-residence'
                    container.style.position = 'relative'
                    headline.classList = 'col-12 h6'
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
                    ctryFrmGrp.className = 'form-group col-4'
                    postalCodeFrmGrp.className = 'form-group col-4'
                    addressFrmGrp.className = 'form-group col-4'
                    ctryLbl.textContent = 'Država'
                    postalCodeLbl.textContent = 'Kraj'
                    postalCodeLbl.style.width = '100%'
                    addressLbl.textContent = 'Naslov'
                    addressLbl.style.width = '100%'
                    statInputElement.type = 'hidden'
                    statInputElement.name = `residences[${index}][status]`
                    statInputElement.value = 'ZAČASNO'
                    ctrySelElement.classList = 'form-control country-select'
                    ctrySelElement.addEventListener('input', () => {
                            propagateSelectElement(
                                postalCodeSelElement,
                                `/eArchive/postalCodes/select.php?id_countries=${ctrySelElement.selectedOptions[0].value}`
                            )
                        }) // addEventListener
                    postalCodeSelElement.classList = 'form-control'
                    postalCodeSelElement.name = `residences[${index}][id_postal_codes]`
                    postalCodeSelElement.required = true
                    addressInputElement.classList = 'form-control'
                    addressInputElement.type = 'text'
                    addressInputElement.name = `residences[${index}][address]`
                    addressInputElement.required = true
                    headline.appendChild(cross)
                    ctryLbl.appendChild(ctrySelElement)
                    ctryFrmGrp.appendChild(ctryLbl)
                    postalCodeLbl.appendChild(postalCodeSelElement)
                    postalCodeFrmGrp.appendChild(postalCodeLbl)
                    addressLbl.appendChild(addressInputElement)
                    addressFrmGrp.appendChild(addressLbl)
                    container.appendChild(headline)
                    container.appendChild(statInputElement)
                    container.appendChild(ctryFrmGrp)
                    container.appendChild(postalCodeFrmGrp)
                    container.appendChild(addressFrmGrp)
                    propagateSelectElement(
                            ctrySelElement,
                            '/eArchive/Countries/select.php', !residences ? null : residences[0].id_countries
                        ).then(() => {
                            propagateSelectElement(
                                postalCodeSelElement,
                                `/eArchive/PostalCodes/select.php?id_countries=${ctrySelElement.selectedOptions[0].value}`, !residences ? null : residences[0].id_postal_codes
                            )
                            return
                        }).then(() => {
                            addressInputElement.value = !residences ? '' : residences[0].address
                        }).then(() => {
                            document.getElementById('residences').appendChild(container)
                        }).catch((error) => {
                            alert(error)
                        }) // catch
                }) // Promise
        } // addTempResFrmSect

    /*
     *  create and subsequently append graduation section for a student attending particular program 
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
    let addProgAttendanceSection = () => {
            // create form controls
            let container = document.createElement('div'),
                headline = document.createElement('p'),
                cross = document.createElement('span'),
                facFrmGrp = document.createElement('div'),
                progFrmGrp = document.createElement('div'),
                enrlFrmGrp = document.createElement('div'),
                indexFrmGrp = document.createElement('div'),
                gradFrmGrp = document.createElement('div'),
                facLbl = document.createElement('label'),
                progLbl = document.createElement('label'),
                enrlLbl = document.createElement('label'),
                gradLbl = document.createElement('label'),
                indexLbl = document.createElement('label'),
                facSelElement = document.createElement('select'),
                progSelElement = document.createElement('select'),
                enrlInputElement = document.createElement('input'),
                indexInputElement = document.createElement('input'),
                gradCheckBox = document.createElement('input'),
                gradTxt = document.createTextNode('Diplomiral')
            index = document.querySelectorAll('div#attendances > div.row').length - 1 // the following index for an array od data on program attendance       
            gradCheckBox.addEventListener('change', e => {
                    // append or remove graduation section depending on the condition
                    // if it's checked
                    if (gradCheckBox.checked)
                        addProgramGraduationFrmSect(e)
                    else {
                        container.removeChild(container.lastChild)
                        container.removeChild(container.lastChild)
                        container.removeChild(container.lastChild)
                    } // else
                }) // addEventListener
            headline.className = 'col-12 h6'
            cross.style.float = 'right'
            cross.style.transform = 'scale(1.2)'
            cross.style.cursor = 'pointer'
            cross.innerHTML = '&#10007'
                // remove selected attendance section
            cross.addEventListener('click', () => {
                    document.getElementById('attendances').removeChild(container)
                }) // addEventListener
            container.className = 'row'
            facFrmGrp.className = 'form-group col-6'
            progFrmGrp.className = 'form-group col-6'
            enrlFrmGrp.className = 'form-group col-4'
            indexFrmGrp.className = 'form-group col-4'
            gradFrmGrp.className = 'd-flex align-items-center justify-content-center form-group col-4'
            facLbl.textContent = 'Fakulteta'
            progLbl.textContent = 'Program'
            enrlLbl.textContent = 'Vpisan'
            indexLbl.textContent = 'Indeks'
            gradLbl.className = 'mt-2'
            facSelElement.className = 'form-control'
            facSelElement.name = `attendances[${index}][id_faculties]`
            facSelElement.required = true
            facSelElement.addEventListener('change', e => {
                    // propagate programs by faculty selection
                    propagateSelectElement(progSelElement, `/eArchive/Programs/select.php?id_faculties=${e.target.selectedOptions[0].value}`)
                }) // addEventListener
            progSelElement.className = 'form-control'
            progSelElement.name = `attendances[${index}][id_programs]`
            progSelElement.required = true
            enrlInputElement.className = 'form-control'
            enrlInputElement.type = 'date'
            enrlInputElement.name = `attendances[${index}][enrolled]`
            enrlInputElement.required = true
            indexInputElement.className = 'form-control'
            indexInputElement.type = 'text'
            indexInputElement.name = `attendances[${index}][index]`
            indexInputElement.required = true
            gradCheckBox.type = 'checkbox'
            gradCheckBox.classList = 'mr-2'
            gradCheckBox.setAttribute('data-index', index)
                // append controls to a form attendances section
            facLbl.appendChild(facSelElement)
            facFrmGrp.appendChild(facLbl)
            progLbl.appendChild(progSelElement)
            progFrmGrp.appendChild(progLbl)
            enrlLbl.appendChild(enrlInputElement)
            enrlFrmGrp.appendChild(enrlLbl)
            indexLbl.appendChild(indexInputElement)
            indexFrmGrp.appendChild(indexLbl)
            gradLbl.appendChild(gradCheckBox)
            gradLbl.appendChild(gradTxt)
            gradFrmGrp.appendChild(gradLbl)
            headline.appendChild(cross)
            container.appendChild(headline)
            container.appendChild(facFrmGrp)
            container.appendChild(progFrmGrp)
            container.appendChild(enrlFrmGrp)
            container.appendChild(indexFrmGrp)
            container.appendChild(gradFrmGrp)
                // initial propagation
            propagateSelectElement(facSelElement, '/eArchive/Faculties/select.php')
                .then(() => propagateSelectElement(progSelElement, `/eArchive/Programs/select.php?id_faculties=${facSelElement.selectedOptions[0].value}`))
                .then(() => {
                    document.getElementById('attendances').appendChild(container)
                }).catch(error => {
                    alert(error)
                }) // catch
        } // addProgAttendanceSection 

    // create and subsequently append partaker section of the scientific paper insertion form 
    let addPartakerSection = () => {
            // create form controls 
            let container = document.createElement('div'),
                headline = document.createElement('p'),
                cross = document.createElement('span'),
                partakerFrmGrp = document.createElement('div'),
                partFrmGrp = document.createElement('div'),
                partakerLbl = document.createElement('label'),
                partLbl = document.createElement('label'),
                partakerInputElement = document.createElement('input'),
                partInputElement = document.createElement('input'),
                index = document.querySelectorAll('div#sciPapPartakerSect > div.row').length // the following index for an array of data on a partaker  
            container.classList = 'row'
            headline.classList = 'h6 col-12'
            cross.style.float = 'right'
            cross.style.transform = 'scale(1.2)'
            cross.style.cursor = 'pointer'
            cross.innerHTML = '&#10007'
                // remove selected attendance section
            cross.addEventListener('click', () => {
                    document.getElementById('sciPapPartakerSect').removeChild(container)
                }) // addEventListener
            partakerFrmGrp.classList = 'form-group col-6'
            partFrmGrp.classList = 'form-group col-6'
            partakerLbl.textContent = 'Sodelovalec'
            partLbl.textContent = 'Vloga'
            partakerInputElement.classList = 'form-control'
            partakerInputElement.setAttribute('list', 'students')
            partakerInputElement.required = true
            partInputElement.classList = 'form-control'
            partInputElement.type = 'text'
            partInputElement.name = `partakers[${index}][part]`
            partInputElement.required = true
                // compose a node hierarchy by appending them to active tree structure 
            headline.appendChild(cross)
            partakerLbl.appendChild(partakerInputElement)
            partakerFrmGrp.appendChild(partakerLbl)
            partLbl.appendChild(partInputElement)
            partFrmGrp.appendChild(partLbl)
            container.appendChild(headline)
            container.appendChild(partakerFrmGrp)
            container.appendChild(partFrmGrp)
            document.getElementById('sciPapPartakerSect').appendChild(container)
        } // addPartakerSection

    //  create and append additional form controls for uploading document of the scientific paper
    let addDocUploadSection = () => {
            // form controls 
            let container = document.createElement('div'), // row
                cross = document.createElement('span'), // removal sign
                versionFrmGrp = document.createElement('div'), // form group
                docFrmGrp = document.createElement('div'), // form group
                versionLbl = document.createElement('label'), // version label
                docLbl = document.createElement('label'), // document label
                versionInputElement = document.createElement('input'), // version input
                docInputElement = document.createElement('input'), // document input 
                docNameInputElement = document.createElement('input'), // document hidden input 
                index = document.querySelectorAll('div#documents > .row').length // the following index for an array of data on documents of scientific paper  
            docInputElement.addEventListener('change', e => {
                    // assign chosen document name as a value to the docNameInputElement
                    docNameInputElement.value = e.target.files[0].name
                }) // addEventListener
            cross.addEventListener('click', () => {
                    // remove appended controls
                    document.getElementById('sciPapDocs').removeChild(container)
                }) // addEventListener
            container.classList = 'row mt-2'
            container.style.position = 'relative'
            versionFrmGrp.classList = 'form-group col-6'
            docFrmGrp.classList = 'form-group col-6'
            versionLbl.textContent = 'Verzija'
            versionLbl.style.width = '100%'
            docLbl.textContent = 'Dokument'
            docLbl.style.width = '100%'
            versionInputElement.classList = 'form-control'
            versionInputElement.type = 'text'
            versionInputElement.name = `documents[${index}][version]`
            docInputElement.type = 'file'
            docInputElement.accept = '.pdf'
            docInputElement.name = 'document[]'
            docInputElement.required = true
            docNameInputElement.type = 'hidden'
            docNameInputElement.name = `documents[${index}][name]`
            cross.style.position = 'absolute'
            cross.style.top = 0
            cross.style.right = '10px'
            cross.style.zIndex = 1
            cross.style.cursor = 'pointer'
            cross.innerHTML = '&#10007;'
            versionLbl.appendChild(versionInputElement)
            versionFrmGrp.appendChild(versionLbl)
            docLbl.appendChild(docInputElement)
            docFrmGrp.appendChild(docNameInputElement)
            docFrmGrp.appendChild(docLbl)
            container.appendChild(cross)
            container.appendChild(versionFrmGrp)
            container.appendChild(docFrmGrp)
                // append controls to scientific paper insert form
            document.getElementById('sciPapDocs').appendChild(container)
        } // addDocUploadSection

    //  create and append additional form controls for providing data on mentors 
    let addMentoringsSection = () => {
            // create form controls 
            let container = document.createElement('div'), // row
                headline = document.createElement('p'),
                cross = document.createElement('span'), // removal sign 
                mentorFrmGrp = document.createElement('div'), // form group
                facFrmGrp = document.createElement('div'), // form group
                taughtFrmGrp = document.createElement('div'), // form group
                emailFrmGrp = document.createElement('div'), // form group
                telFrmGrp = document.createElement('div'), // form group
                mentorLbl = document.createElement('label'), // mentor label
                facLbl = document.createElement('label'), // faculty label
                taughtLbl = document.createElement('label'), // subject label
                emailLbl = document.createElement('label'), // email label
                telLbl = document.createElement('label'), // telephone label
                facSelElement = document.createElement('select'), // faculty input
                mentorInputElement = document.createElement('input'), // mentor input
                taughtInputElement = document.createElement('input'), // subject input
                emailInputElement = document.createElement('input'), // email input
                telInputElement = document.createElement('input'), // telephone input
                index = document.querySelectorAll('div#sciPapMentorSect > div.row').length // the following index for an array of data on documents of scientific paper  
            container.classList = 'row'
            headline.classList = 'col-12 h6'
            cross.style.float = 'right'
            cross.style.transform = 'scale(1.2)'
            cross.style.cursor = 'pointer'
            cross.innerHTML = '&#10007'
                // remove selected attendance section
            cross.addEventListener('click', () => {
                    document.getElementById('sciPapMentorSect').removeChild(container)
                }) // addEventListener
            mentorFrmGrp.classList = 'form-group col-12'
            facFrmGrp.classList = 'form-group col-6'
            taughtFrmGrp.classList = 'form-group col-6'
            emailFrmGrp.classList = 'form-group col-6'
            telFrmGrp.classList = 'form-group col-6'
            facLbl.textContent = 'Fakulteta'
            facLbl.style.width = '100%'
            mentorLbl.textContent = 'Mentor'
            mentorLbl.style.width = '100%'
            taughtLbl.textContent = 'Poučeval'
            taughtLbl.style.width = '100%'
            emailLbl.textContent = 'E-naslov'
            emailLbl.style.width = '100%'
            telLbl.textContent = 'Telefon'
            telLbl.style.width = '100%'
            facSelElement.classList = 'form-control'
            facSelElement.name = `mentors[${index}][id_faculties]`
            facSelElement.required = true
            mentorInputElement.classList = 'form-control'
            mentorInputElement.type = 'text'
            mentorInputElement.name = `mentors[${index}][mentor]`
            mentorInputElement.required = true
            taughtInputElement.classList = 'form-control'
            taughtInputElement.type = 'text'
            taughtInputElement.name = `mentors[${index}][taught]`
            taughtInputElement.required = true
            emailInputElement.classList = 'form-control'
            emailInputElement.type = 'email'
            emailInputElement.name = `mentors[${index}][email]`
            emailInputElement.required = true
            telInputElement.classList = 'form-control'
            telInputElement.type = 'telephone'
            telInputElement.name = `mentors[${index}][telephone]`
            telInputElement.required = true
            headline.appendChild(cross)
            mentorLbl.appendChild(mentorInputElement)
            mentorFrmGrp.appendChild(mentorLbl)
            facLbl.appendChild(facSelElement)
            facFrmGrp.appendChild(facLbl)
            taughtLbl.appendChild(taughtInputElement)
            taughtFrmGrp.appendChild(taughtLbl)
            emailLbl.appendChild(emailInputElement)
            emailFrmGrp.appendChild(emailLbl)
            telLbl.appendChild(telInputElement)
            telFrmGrp.appendChild(telLbl)
            container.appendChild(headline)
            container.appendChild(mentorFrmGrp)
            container.appendChild(facFrmGrp)
            container.appendChild(taughtFrmGrp)
            container.appendChild(emailFrmGrp)
            container.appendChild(telFrmGrp)
                // populate HTMLSelectElement with the data regarding faculties 
            propagateSelectElement
                (
                    facSelElement,
                    '/eArchive/Faculties/select.php'
                ).then(() => {
                    document.getElementById('sciPapMentorSect').appendChild(container)
                }).catch(error => {
                    alert(error)
                })
        } // addMentoringsSection

    /*
     *  clear input field values of a form 
     *  @param HTMLFormElement form
     */
    let emptyFrmInputFields = form => {
            form.querySelectorAll('input:not(input[type=hidden]').forEach(input => {
                    input.value = ''
                }) // forEach
        } // emptyFrmInputFields

    /*  
     *   interpolate datalist with name, surname and index number of the inserted student
     *   @param String fullname
     *   @param Number index
     */
    let interpolateStudentDatalist = (fullname, index) => {
            let option = document.createElement('option')
            option.value = index
            option.textContent = fullname
            document.getElementById('sciPapInsertionMdl').querySelector('datalist').appendChild(option)
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
                        // compose node passive tree structure
                    fragment = response
                        // reflect fragments body  
                    tblCtr.innerHTML = fragment.body.innerHTML
                    attachListenersToStudentEvidenceTbl()
                }).catch(error => {
                    alert(error)
                }) // catch
        } // selectStudentsByIndex

    filterInpt.addEventListener('input', () => {
            // filter students by their index numbers 
            selectStudentsByIndex(filterInpt.value)
        }) // addEventListener

    /*
     *  asynchronous script execution for insretion of student particulars and scientific achievements
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
                ).then(response => {
                    reportMdl.querySelector('.modal-body').textContent = response
                    reportMdlBtn.click()
                    emptyFrmInputFields(studentFrm)
                        // close the modal after insertion 
                    document.getElementById('studentInsBtn').click()
                    return
                }).then(() => {
                    refreshStudentEvidenceTbl()
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
     *  @param Number idpostalCode
     *  @param Number idCountries
     */
    let determineStudentBirthplace = (idPostalCodes, idCountries) => {
            // propagate target select element with postal codes of the chosen country
            propagateSelectElement(
                document.querySelector('#birthCtrySelElement'),
                '/eArchive/Countries/select.php',
                idCountries
            ).then(() => propagateSelectElement(
                document.querySelector('#birthPostalCodeSelElement'),
                `eArchive/PostalCodes/select.php?id_countries=${idCountries}`,
                idPostalCodes
            ))
        } // determineStudenBirthplace

    /*
     *  fill out form fields with student permanent residence particulars
     *  @param Array residence
     */
    let determinePermResOfStudent = (residence) => {
            // create hidden input type that stores record if of the residence  
            let idResidencesInputElement = document.createElement('input')
            idResidencesInputElement.type = 'hidden'
            idResidencesInputElement.name = 'residences[0][id_residences]'
            idResidencesInputElement.value = residence.id_residences
            document.querySelector('#studentInsertionFrm #perResCtrySelElement').parentElement.prepend(idResidencesInputElement)
                // propagate target select element with postal codes of the chosen country
            propagateSelectElement(
                document.querySelector('#permResCtrySelElement'),
                '/eArchive/Countries/select.php',
                residence.id_countries
            ).then(() => propagateSelectElement(
                document.querySelector('#permResPostalCodeSelElement'),
                `/eArchive/PostalCodes/select.php?id_countries=${residence.id_countries}`,
                residence.id_postal_codes
            )).then(() => {
                document.querySelector('#permResAddressInputElement').value = residence.address
            })
        } // determinePermResOfStudent

    /*
     *  fill out form fields with student temporal residence particulars
     *  @param Array residences
     */
    let determineTempResOfStudent = residences =>
        addTempResFrmSect(residences)

    /*
     *   asynchronous script run for deletion of temporal residence record
     *   @param idResidences
     */
    let deleteTempResOfStudent = idResidences => {
            request(
                    `/eArchive/Residences/delete.php?id_residences=${idResidences}`,
                    'GET',
                    'text'
                ).then(response => {
                    // report the result
                    reportMdl.querySelector('.modal-body').textContent = response
                    reportMdlBtn.click()
                }).catch(error => {
                    alert(error)
                }) // catch
        } // deleteTempResOfStudent

    /*
     *  asynchronous script execution for updating of student particulars
     *  Event e
     */
    let updateStudent = (e, form) => {
            // prevent default action of submitting updated student data through a form
            e.preventDefault()
            request(
                    '/eArchive/Students/update.php',
                    'POST',
                    'text',
                    (new FormData(form))
                ).then(response => {
                    // report on update
                    reportMdl.querySelector('.modal-body').textContent = response
                    reportMdlBtn.click()
                }).then(() => {
                    emptyFrmInputFields(studentFrm)
                    refreshStudentEvidenceTbl()
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
                    refreshStudentEvidenceTbl()
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
                    $('#acctAssigningMdl').modal('hide')
                    return
                }).then(() => {
                    // repaint student evidence table
                    refreshStudentEvidenceTbl()
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
                    refreshStudentEvidenceTbl()
                }).catch(error => {

                }) // catch
        } // deleteStudentAccount

    /*
     *   rearrange form and fill out form fields when updating student data
     *   @param Object sPpr
     */
    let toSPUpdateFrm = sPpr => {
            document.querySelector('#sPMdl .modal-header .modal-title').textContent = 'Urejanje podatkov znanstvenega dela'
                // clone from the existing form node
            let cloneFrm = sciPapFrm.cloneNode(true),
                idSPHiddInpt = document.createElement('input')
            idSPHiddInpt.type = 'hidden'
            idSPHiddInpt.name = 'id_scientific_papers'
            idSPHiddInpt.value = sPpr.id_scientific_papers
                // replace form element node with its clone
            document.getElementById('sPFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idSPHiddInpt)
            attachListenersToSciPapInsFrm()
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
                // clone from the existing form node
            let cloneFrm = sciPapFrm.cloneNode(true),
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
            attachListenersToSciPapInsFrm()
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
                // clone from the existing form node
            let cloneFrm = sciPapFrm.cloneNode(true),
                idSPHiddInpt = document.createElement('input')
            idSPHiddInpt.type = 'hidden'
            idSPHiddInpt.name = 'id_scientific_papers'
            idSPHiddInpt.value = e.target.getAttribute('data-id-scientific-papers')
                // replace form element node with its clone
            document.getElementById('sPFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idSPHiddInpt)
                // widen form group across the whole grid
            cloneFrm.querySelector('#sciPapPartakerSect').classList = 'col-12'
            cloneFrm.querySelector('input[type=submit]').value = 'Dodeli'
            attachListenersToSciPapInsFrm()
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
                // clone from the existing form node
            let cloneFrm = sciPapFrm.cloneNode(true),
                idPartakingsHiddInpt = document.createElement('input')
            idPartakingsHiddInpt.type = 'hidden'
            idPartakingsHiddInpt.name = 'id_partakings'
            idPartakingsHiddInpt.value = e.target.getAttribute('data-id-partakings')
                // replace form node with its clone
            document.getElementById('sPFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idPartakingsHiddInpt)
                // widen form group across the whole grid
            cloneFrm.querySelector('#sciPapPartakerSect').classList = 'col-12'
            attachListenersToSciPapInsFrm()
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
                // clone from the existing form node
            let cloneFrm = sciPapFrm.cloneNode(true),
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
            attachListenersToSciPapInsFrm()
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
            let cloneFrm = sciPapFrm.cloneNode(true),
                idMentoringsHiddInpt = document.createElement('input')
            idMentoringsHiddInpt.type = 'hidden'
            idMentoringsHiddInpt.name = 'id_mentorings'
            idMentoringsHiddInpt.value = e.target.getAttribute('data-id-mentorings')
                // replace form element node with its clone
            document.getElementById('sPFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idMentoringsHiddInpt)
            cloneFrm.querySelector('input[type=submit]').value = 'Uredi'
            attachListenersToSciPapInsFrm()
                // dispatch a synthetic click event to button for subsequent addition of form mentor section
            cloneFrm.querySelector('#addMentorBtn').dispatchEvent((new Event('click')))
                // remove DIV nodes except matching given selector expression 
            cloneFrm.querySelectorAll('#particulars, #sciPapPartakerSect, #sPDocs, p, button').forEach(node => {
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
                // clone from the existing form node
            let cloneFrm = certCloneFrm.cloneNode(true),
                idCertificatesHiddInpt = document.createElement('input')
            idCertificatesHiddInpt.type = 'hidden'
            idCertificatesHiddInpt.name = 'id_certificates'
            idCertificatesHiddInpt.value = e.target.getAttribute('data-id-certificates')
                // replace form element node with its clone
            document.getElementById('certUploadFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idCertificatesHiddInpt)
            attachListenersToGradCertCard()
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
            determinePermResOfStudent(studentFrm, student.permResidence)
            determineTempResOfStudent(student.tempResidence)
            studentFrm.removeChild(studentFrm.querySelector('#attendances'))
            studentFrm.querySelector('input[type=submit]').value = 'Posodobi'
                // exchange callbacks
            studentFrm.removeEventListener('submit', insertStudent)
            studentFrm.addEventListener('submit', updateStudent)
        } // toStudentUpdateFrm

    // attach event listeners to a scientific paper cards when rendered
    let attachListenersToSciPapCards = () => {
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
    let attachListenersToGradCertCard = () => {
            // get modal for graduation certificate review
            let mdl = document.getElementById('certViewingMdl')
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
                    document.querySelector('#sciPapViewingMdl .modal-content').innerHTML = fragment.body.innerHTML
                    return
                }).then(() => {
                    attachListenersToSciPapCards()
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
                    $('#studentInsertionMdl').modal('hide')
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
                    refreshStudentEvidenceTbl()
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
                    let mdl = document.getElementById('certViewingMdl')
                        // compose node tree structure
                    fragment = response
                        // reflect fragments body     
                    mdl.querySelector('.modal-content').innerHTML = fragment.body.innerHTML
                    attachListenersToGradCertCard()
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
                    refreshStudentEvidenceTbl()
                        // close certificate review modal after deletion
                    $('#certViewMdl').modal('hide')
                }).catch(error => {
                    alert(error)
                }) // catch
        } // deleteGraduationCertificate
})()