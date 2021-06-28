// IIFE
(() => {
    // global scope variable declaration
    var frag = new DocumentFragment(), // minimal document object structure
        studtInsrFrm = document.getElementById('studtInsrFrm'), // form for inserting and updating data regarding the student
        sciPapInsrFrm = document.getElementById('sciPapInsrFrm'), // form for inserting, updating and deleting data regarding the scientific paper 
        acctAssignFrm = document.getElementById('acctAssignFrm'), // form for assigning student account and its credentials
        gradCertUplFrm = document.getElementById('gradCertUplFrm'), // form for uploading graduation certificates
        rprtMdl = document.getElementById('rprtMdl'), // modal for reporting about performed operations 
        reportMdlBtn = document.getElementById('rprtMdlBtn'), // report modal toggler
        fltrInputEl = document.getElementById('fltrInputEl') // input for filtering students by their index numbers

    gradCertUplFrm.querySelector('input[type=file]').addEventListener(
            'change',
            () => {
                // assign the name of the uploaded certificate to hidden input type
                gradCertUplFrm.querySelector('input[name=certificate]').value = gradCertUplFrm.querySelector('input[type=file]').files[0].name
            }
        ) // addEventListener

    // attach event listeners to corresponding input element 
    let listenSciPapInsrFrm = () => {
            // get the form 
            let frm = document.getElementById('sciPapInsrMdl')
                // if button for subsequent partaker section additon exists
            if (frm.querySelector('#addPartakerBtn'))
                addPartakerBtn.addEventListener(
                    'click',
                    addPartakerSect
                )
                // if file input is rendered 
            if (frm.querySelector('input[name="document[]"]'))
                frm.querySelector('input[name="document[]"]').addEventListener(
                    'change',
                    e => {
                        // assign the filename of the uploaded document to the hidden input type
                        frm.querySelector('input[name="documents[0][name]"]').value = e.target.files[0].name
                    }
                ) // addEventListener
                // if button for subsequent mentor section additon exists 
            if (frm.querySelector('#addMentorBtn'))
                frm.querySelector('#addMentorBtn').addEventListener(
                    'click',
                    addMentoringsSect
                )
                // if button for subsequent document section additon exists
            if (frm.querySelector('#addDocumentBtn'))
            // append controls for additional scientific paper document upload
                frm.querySelector('#addDocumentBtn').addEventListener(
                'click',
                addDocUploadSect
            )
        } // attachSciPapFrmListeners

    // attach event listeners to corresponding input and selecet elements
    let listenStudtInsrFrm = () => {
            let addTempResBtn = document.getElementById('addTempResBtn'), // button for appending addiational temporal residence section 
                addAttendanceBtn = document.getElementById('addAttendanceBtn'), // button for apppending additional program attendance section
                birthCtrySelEl = document.getElementById('birthCtrySelEl'),
                permResCtrySelEl = document.getElementById('permResCtrySelEl'),
                facSelEl = document.getElementById('facSelEl'), // faculty select element
                gradCheckBox = document.getElementById('gradCheckBox') // checkbox for denoting graduation
            addTempResBtn.addEventListener(
                    'click',
                    () => {
                        addTempResSect()
                    }
                ) // addEventListener
            addAttendanceBtn.addEventListener(
                'click',
                addProgAttendanceSect
            )
            birthCtrySelEl.addEventListener(
                    // propagate postal codes by selected country 
                    'change',
                    () => propagateSelEl(
                        document.getElementById('birthPostCodeSelEl'),
                        `/eArchive/PostalCodes/select.php?id_countries=${birthCtrySelEl.selectedOptions[0].value}`
                    )
                ) // addEventListener
            permResCtrySelEl.addEventListener(
                    // propagate postal codes by selected country 
                    'change',
                    () => propagateSelEl(
                        document.getElementById('permResPostCodeSelEl'),
                        `/eArchive/PostalCodes/select.php?id_countries=${permResCtrySelEl.selectedOptions[0].value}`
                    )
                ) // addEventListener
            facSelEl.addEventListener(
                    'input',
                    () => {
                        // propagate programs by faculty selection
                        propagateSelEl(
                            document.getElementById('progSelElement'),
                            `/eArchive/Programs/select.php?id_faculties=${facSelEl.selectedOptions[0].value}`
                        )
                    }) // addEventListener
            gradCheckBox.addEventListener(
                    'change',
                    e => {
                        // if it's checked
                        if (gradCheckBox.checked)
                        // append graduation section if graduated
                            addProgGradSect(e)
                        else {
                            // remove selected graduation section
                            gradCheckBox.closest('.row').removeChild(gradCheckBox.closest('.row').lastElementChild)
                            gradCheckBox.closest('.row').removeChild(gradCheckBox.closest('.row').lastElementChild)
                            gradCheckBox.closest('.row').removeChild(gradCheckBox.closest('.row').lastElementChild)
                        } // else
                    }
                ) // addEventListener
        } // listenStudtInsrFrm

    // rearrange form when inserting a student record  
    let toStudtInsrFrm = () => {
            // clone from the existing form node
            let cloneFrm = studtInsrFrm.cloneNode(true)
                // replace form element node with its clone
            document.getElementById('studtInsrFrm').replaceWith(cloneFrm)
            listenStudtInsrFrm()
            cloneFrm.querySelector('input[type=submit]').value = 'Vstavi'
                // exchange callbacks
            studtInsrFrm.addEventListener('submit', e => insertStudt(e, cloneFrm))
        } // toStudtInsrFrm

    /*
     *   rearrange form when interpolating data regarding scientific paper and uploading its documents    
     *   @param Event e
     */
    let toSciPapInsrFrm = e => {
            document.querySelector('#sciPapInsrMdl .modal-header .modal-title').textContent = 'Vstavljanje znanstvenega dela'
                // clone from the existing form node
            let cloneFrm = sciPapInsrFrm.cloneNode(true)
            cloneFrm.querySelector('input[name=id_attendances]').value = e.target.getAttribute('data-id-attendances')
                // replace form element node with its clone
            document.getElementById('sciPapInsrMdl').replaceWith(cloneFrm)
            cloneFrm.querySelector('input[type=submit]').value = 'Vstavi'
            listenSciPapInsrFrm()
            cloneFrm.addEventListener(
                    'submit',
                    insertSciPap
                ) // addEventListner
        } // toSciPapInsrFrm

    // attach listeners to student evidence table appropriate anchors and buttons   
    let listenStudtEvidTbl = () => {
            let studtInsrBtn = document.getElementById('studtInsrBtn'), // button for exposing form for student scientific achievements insertion
                sciPapViewLst = document.querySelectorAll('.sp-vw-a'), // array of anchors for exposing scientific papers of the student
                sciPapInsrLst = document.querySelectorAll('.sp-ins-a'), // array of anchors for exposing form for insertion of the scientific papers and belonging documents
                gradCertInsrLst = document.querySelectorAll('.cert-ins-a'), // array of anchors for exposing form for uploading students graduation certificate
                gradCertViewLst = document.querySelectorAll('.cert-vw-a'), // array of anchors for exposing graduation certificate of the student
                acctInsrLst = document.querySelectorAll('.acc-ins-btn'), // array of buttons for exposing form for assigning an account to student
                acctDelLst = document.querySelectorAll('.acc-del-btn'), // array of buttons for deletion of a particular student account 
                studtUpdLst = document.querySelectorAll('.stu-upd-a'), // array of anchors for exposing form for updating fundamental data of the student
                studtDelLst = document.querySelectorAll('.stu-del-a') // array of anchors for exposing form for deletion of fundamental data of the student
            studtInsrBtn.addEventListener(
                    'click',
                    toStudtInsrFrm
                ) // addEventListener
            sciPapViewLst.forEach(anchor => {
                    // preview scientific papers   
                    anchor.addEventListener(
                            'click',
                            () => {
                                selectSciPaps(anchor.getAttribute('data-id-attendances'))
                                sciPapInsrFrm.querySelector('input[name=id_attendances]').value = anchor.getAttribute('data-id-attendances')
                            }
                        ) //addEventListener
                }) // forEach
            sciPapInsrLst.forEach(anchor => {
                    // modify form for scientific paper insertion
                    anchor.addEventListener('click', toSciPapInsrFrm)
                }) // forEach
            gradCertInsrLst.forEach(anchor => {
                    // assign an attendance id value to an upload forms hidden input type 
                    anchor.addEventListener(
                            'click',
                            () => {
                                gradCertUplFrm.querySelector('input[type=hidden]').value = anchor.getAttribute('data-id-attendances')
                            }
                        ) //addEventListener
                }) // forEach
            gradCertViewLst.forEach(anchor => {
                    // view certificate particulars in a form of a card in the modal
                    anchor.addEventListener(
                            'click',
                            () => {
                                selectGradCert(anchor.getAttribute('data-id-attendances'))
                                    // set value of id to the hidden input of the form
                                certCloneFrm.querySelector('input[name=id_attendances]').value = anchor.getAttribute('data-id-attendances')
                            }
                        ) // addEventListener
                }) // forEach
            studtUpdLst.forEach(anchor => {
                    // propagate update form with student particulars
                    anchor.addEventListener(
                            'click',
                            e => {
                                selectStudt(e, anchor.getAttribute('data-id-students'))
                            }
                        ) // addEventListener
                }) // forEach
            studtDelLst.forEach(anchor => {
                    // delete student from the student evidence table
                    anchor.addEventListener(
                            'click',
                            () => {
                                // if record deletion was confirmed
                                if (confirm('S sprejemanjem boste izbrisali vse podatke o študentu ter podatke o znanstvenih dosežkih!'))
                                    deleteStudt(anchor.getAttribute('data-id-students'), anchor.getAttribute('data-id-attendances'))
                            }
                        ) // addEventListener
                }) // forEach
            acctDelLst.forEach(btn => {
                    // delete particular account 
                    btn.addEventListener(
                            'click',
                            () => {
                                deleteAcctOfStudt(btn.getAttribute('data-id-attendances'))
                            }
                        ) //addEventListener
                }) // forEach
            acctInsrLst.forEach(btn => {
                    // pass an id of an attendance through forms hidden input type 
                    btn.addEventListener(
                            'click',
                            () => {
                                acctAssignFrm.querySelector('input[name=id_attendances]').value = btn.value
                            }
                        ) // addEventListener
                }) // forEach
        } // attachStudentTableListeners
    listenStudtEvidTbl()

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
                xmlhttp.addEventListener(
                        'load',
                        () => {
                            // resolve the promise if transaction was successful
                            resolve(xmlhttp.response)
                        }
                    ) // addEventListener
                xmlhttp.addEventListener(
                        'error',
                        () => {
                            // reject the promise if transaction encountered an error
                            reject('Prišlo je do napake na strežniku!')
                        }
                    ) // addEventListener
                xmlhttp.open(method, script, true)
                xmlhttp.responseType = resType
                xmlhttp.send(frmData)
            })
        } // request

    // load student evidence table upon latterly data amendment 
    let loadStudtEvidTbl = () => {
            request
                (
                    '/eArchive/Students/selectAll.php',
                    'GET',
                    'document'
                )
                .then(response => {
                    // compose node tree structure
                    frag = response
                        // reflect fragments body  
                    document.querySelector('div.table-responsive').innerHTML = frag.body.innerHTML
                })
                .then(() => listenStudtEvidTbl())
                .catch(error => alert(error)) // catch
        } // loadStudtEvidTbl

    /*
     *   propagate passed select element with options from the requested resource 
     *   @param HTMLSelectElement select
     *   @param String script
     *   @param Number id
     */
    let propagateSelEl = async(select, script, id = 0) => {
            try {
                const response = await request(
                    script,
                    'GET',
                    'document'
                )
                frag = response
                    // remove previously disposable options
                while (select.options.length)
                    select.remove(0)
                    // traverse through nodes 
                frag.body.querySelectorAll('option').forEach(option => {
                        select.add(option)
                            // if id matches options value
                        if (option.value == id)
                        // set option as selected
                            option.selected = true
                    }) // forEach
            } catch (error) {
                alert(error)
            }
        } // propagateSelEl

    /*
     *  !recursive 
     *  create and subsequently append form controls for new temporal residence section 
     *  @param Array residences
     */
    let addTempResSect = (residences = null) => {
            return new Promise((resolve) => {
                    // instantiate a MutationObserver object
                    let observer = new MutationObserver(() => {
                            // if updating recorded temporal residence data 
                            if (residences) {
                                residences.shift()
                                    // if there's more records
                                if (residences.length)
                                    resolve(addTempResSect(residences))
                            } // if
                            else
                                resolve()
                        }), // MutationObserver
                        // form controls
                        ctr = document.createElement('div'),
                        headline = document.createElement('p'),
                        cross = document.createElement('span'),
                        ctryFrmGrp = document.createElement('div'),
                        postCodeFrmGrp = document.createElement('div'),
                        addressFrmGrp = document.createElement('div'),
                        ctryLbl = document.createElement('label'),
                        postCodeLbl = document.createElement('label'),
                        addressLbl = document.createElement('label'),
                        statusInptEl = document.createElement('input'),
                        ctrySelEl = document.createElement('select'),
                        postCodeSelEl = document.createElement('select'),
                        addressInptEl = document.createElement('input'),
                        index = document.querySelectorAll('div#residences > div.row').length // the following index for an array of data on student temporal residences 
                        // set the target and options of observation
                    observer.observe(
                            document.getElementById('residences'), {
                                attributes: false,
                                childList: true,
                                subtree: false
                            }
                        ) // observe
                    ctr.className = 'row temporal-residence'
                    ctr.style.position = 'relative'
                    headline.classList = 'col-12 h6'
                    cross.style.float = 'right'
                    cross.style.transform = 'scale(1.2)'
                    cross.style.cursor = 'pointer'
                    cross.innerHTML = '&#10007;'
                    cross.setAttribute('data-id-residences', !residences ? '' : residences[0].id_residences)
                    cross.addEventListener(
                            'click',
                            () => {
                                // if data attributes value isn't empty 
                                if (cross.getAttribute('data-id-residences') !== '')
                                    deleteTempResOfStudt(cross.getAttribute('data-id-residences'))
                                document.getElementById('residences').removeChild(ctr)
                            }
                        ) // addEventListener
                    ctryFrmGrp.className = 'form-group col-4'
                    postCodeFrmGrp.className = 'form-group col-4'
                    addressFrmGrp.className = 'form-group col-4'
                    ctryLbl.textContent = 'Država'
                    ctryLbl.classList = 'w-100'
                    postCodeLbl.textContent = 'Kraj'
                    postCodeLbl.classList = 'w-100'
                    addressLbl.textContent = 'Naslov'
                    addressLbl.classList = 'w-100'
                    statusInptEl.type = 'hidden'
                    statusInptEl.name = `residences[${index}][status]`
                    statusInptEl.value = 'ZAČASNO'
                    ctrySelEl.classList = 'form-control country-select'
                    ctrySelEl.addEventListener(
                            'input',
                            () => {
                                propagateSelEl(
                                    postCodeSelEl,
                                    `/eArchive/postalCodes/select.php?id_countries=${ctrySelEl.selectedOptions[0].value}`
                                )
                            }
                        ) // addEventListener
                    postCodeSelEl.classList = 'form-control'
                    postCodeSelEl.name = `residences[${index}][id_postal_codes]`
                    postCodeSelEl.required = true
                    addressInptEl.classList = 'form-control'
                    addressInptEl.type = 'text'
                    addressInptEl.name = `residences[${index}][address]`
                    addressInptEl.required = true
                    headline.appendChild(cross)
                    ctryLbl.appendChild(ctrySelEl)
                    ctryFrmGrp.appendChild(ctryLbl)
                    postCodeLbl.appendChild(postCodeSelEl)
                    postCodeFrmGrp.appendChild(postCodeLbl)
                    addressLbl.appendChild(addressInptEl)
                    addressFrmGrp.appendChild(addressLbl)
                    ctr.appendChild(headline)
                    ctr.appendChild(statusInptEl)
                    ctr.appendChild(ctryFrmGrp)
                    ctr.appendChild(postCodeFrmGrp)
                    ctr.appendChild(addressFrmGrp)
                    propagateSelEl(
                            ctrySelEl,
                            '/eArchive/Countries/select.php', !residences ? null : residences[0].id_countries
                        )
                        .then(() => {
                            propagateSelEl(
                                postCodeSelEl,
                                `/eArchive/PostalCodes/select.php?id_countries=${ctrySelEl.selectedOptions[0].value}`, !residences ? null : residences[0].id_postal_codes
                            )
                            return
                        }).then(() => {
                            addressInptEl.value = !residences ? '' : residences[0].address
                        }).then(() => document.getElementById('residences').appendChild(ctr))
                        .catch((error) => {
                            alert(error)
                        }) // catch
                }) // Promise
        } // addTempResSect

    /*
     *  create and subsequently append graduation section for a student attending particular program 
     *  @param Event e
     */
    let addProgGradSect = e => {
            let lblNum = e.target.getAttribute('data-lbl-nm'), // get ordinal number for label numeration   
                indx = e.target.getAttribute('data-indx'), // get next index position for attendances array 
                // create form controls 
                gradCertFrmGrp = document.createElement('div'),
                defendedFrmGrp = document.createElement('div'),
                issuedFrmGrp = document.createElement('div'),
                gradCertLbl = document.createElement('label'),
                defendedLbl = document.createElement('label'),
                issuedLbl = document.createElement('label'),
                certNameInptEl = document.createElement('input'),
                certInptEl = document.createElement('input'),
                defendedInptEl = document.createElement('input'),
                issuedInptEl = document.createElement('input')
            gradCertFrmGrp.className = 'form-group col-4'
            defendedFrmGrp.className = 'form-group col-4'
            issuedFrmGrp.className = 'form-group col-4'
            gradCertLbl.textContent = 'Certifikat'
            gradCertLbl.setAttribute('for', `certificateInpt${lblNum}`)
            defendedLbl.textContent = 'Zagovorjen'
            defendedLbl.setAttribute('for', `defendedInpt${lblNum}`)
            issuedLbl.textContent = 'Izdan'
            issuedLbl.setAttribute('for', `issuedInpt${lblNum}`)
            issuedInptEl.textContent = 'Izdan'
            issuedInptEl.setAttribute('for', `iInpt${lblNum}`)
            certInptEl.id = `certificateInpt${lblNum}`
            certInptEl.type = 'file'
            certInptEl.setAttribute('name', 'certificate[]')
            certInptEl.accept = '.pdf'
            certInptEl.required = true
                // determine hidden input type value if graduated
            certInptEl.addEventListener(
                    'change',
                    e => {
                        certNameInptEl.value = e.target.files[0].name
                    }
                ) // addEventListener
            certNameInptEl.type = 'hidden'
            certNameInptEl.name = `attendances[${indx}][certificate]`
            defendedInptEl.id = `defendedInpt${lblNum}`
            defendedInptEl.className = 'form-control'
            defendedInptEl.type = 'date'
            defendedInptEl.required = true
            defendedInptEl.name = `attendances[${indx}][defended]`
            issuedInptEl.id = `issuedInpt${lblNum}`
            issuedInptEl.className = 'form-control'
            issuedInptEl.type = 'date'
            issuedInptEl.name = `attendances[${indx}][issued]`
            issuedInptEl.required = true
                // append graduation form controls to a particular attendance section
            gradCertFrmGrp.appendChild(gradCertLbl)
            gradCertFrmGrp.appendChild(certInptEl)
            defendedFrmGrp.appendChild(defendedLbl)
            defendedFrmGrp.appendChild(defendedInptEl)
            issuedFrmGrp.appendChild(issuedLbl)
            issuedFrmGrp.appendChild(issuedInptEl)
            e.target.closest('.row').appendChild(certNameInptEl)
            e.target.closest('.row').appendChild(gradCertFrmGrp)
            e.target.closest('.row').appendChild(defendedFrmGrp)
            e.target.closest('.row').appendChild(issuedFrmGrp)
        } // addProgGradSect

    // subsequently create and append attendance section of the student insertion form 
    let addProgAttendanceSect = () => {
            // create form controls
            let ctr = document.createElement('div'),
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
                facSelEl = document.createElement('select'),
                progSelEl = document.createElement('select'),
                enrlInputEl = document.createElement('input'),
                indexInputEl = document.createElement('input'),
                gradCheckBox = document.createElement('input'),
                gradTxt = document.createTextNode('Diplomiral')
            index = document.querySelectorAll('div#attendances > div.row').length - 1 // the following index for an array od data on program attendance       
            gradCheckBox.addEventListener(
                    'change',
                    e => {
                        // append or remove graduation section depending on the condition
                        // if it's checked
                        if (gradCheckBox.checked)
                            addProgGradSect(e)
                        else {
                            ctr.removeChild(ctr.lastChild)
                            ctr.removeChild(ctr.lastChild)
                            ctr.removeChild(ctr.lastChild)
                        } // else
                    }
                ) // addEventListener
            headline.className = 'col-12 h6'
            cross.style.float = 'right'
            cross.style.transform = 'scale(1.2)'
            cross.style.cursor = 'pointer'
            cross.innerHTML = '&#10007'
                // remove selected attendance section
            cross.addEventListener(
                    'click',
                    () => {
                        document.getElementById('attendances').removeChild(ctr)
                    }
                ) // addEventListener
            ctr.className = 'row'
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
            facSelEl.className = 'form-control'
            facSelEl.name = `attendances[${index}][id_faculties]`
            facSelEl.required = true
            facSelEl.addEventListener(
                    'change',
                    e => {
                        // propagate programs by faculty selection
                        propagateSelEl(
                            progSelEl,
                            `/eArchive/Programs/select.php?id_faculties=${e.target.selectedOptions[0].value}`
                        )
                    }
                ) // addEventListener
            progSelEl.className = 'form-control'
            progSelEl.name = `attendances[${index}][id_programs]`
            progSelEl.required = true
            enrlInputEl.className = 'form-control'
            enrlInputEl.type = 'date'
            enrlInputEl.name = `attendances[${index}][enrolled]`
            enrlInputEl.required = true
            indexInputEl.className = 'form-control'
            indexInputEl.type = 'text'
            indexInputEl.name = `attendances[${index}][index]`
            indexInputEl.required = true
            gradCheckBox.type = 'checkbox'
            gradCheckBox.classList = 'mr-2'
            gradCheckBox.setAttribute('data-index', index)
                // append controls to a form attendances section
            facLbl.appendChild(facSelEl)
            facFrmGrp.appendChild(facLbl)
            progLbl.appendChild(progSelEl)
            progFrmGrp.appendChild(progLbl)
            enrlLbl.appendChild(enrlInputEl)
            enrlFrmGrp.appendChild(enrlLbl)
            indexLbl.appendChild(indexInputEl)
            indexFrmGrp.appendChild(indexLbl)
            gradLbl.appendChild(gradCheckBox)
            gradLbl.appendChild(gradTxt)
            gradFrmGrp.appendChild(gradLbl)
            headline.appendChild(cross)
            ctr.appendChild(headline)
            ctr.appendChild(facFrmGrp)
            ctr.appendChild(progFrmGrp)
            ctr.appendChild(enrlFrmGrp)
            ctr.appendChild(indexFrmGrp)
            ctr.appendChild(gradFrmGrp)
                // initial propagation
            propagateSelEl(
                    facSelEl,
                    '/eArchive/Faculties/select.php'
                )
                .then(() => propagateSelEl(
                    progSelEl,
                    `/eArchive/Programs/select.php?id_faculties=${facSelEl.selectedOptions[0].value}`))
                .then(() => document.getElementById('attendances').appendChild(ctr))
                .catch(error => alert(error)) // catch
        } // addProgAttendanceSect 

    // create and subsequently append partaker section of the scientific paper insertion form 
    let addPartakerSect = () => {
            // create form controls 
            let ctr = document.createElement('div'),
                headline = document.createElement('p'),
                cross = document.createElement('span'),
                partakerFrmGrp = document.createElement('div'),
                partFrmGrp = document.createElement('div'),
                partakerLbl = document.createElement('label'),
                partLbl = document.createElement('label'),
                partakerInptEl = document.createElement('input'),
                partInptEl = document.createElement('input'),
                index = document.querySelectorAll('div#sciPapPartakerSect > div.row').length // the following index for an array of data on a partaker  
            ctr.classList = 'row'
            headline.classList = 'h6 col-12'
            cross.style.float = 'right'
            cross.style.transform = 'scale(1.2)'
            cross.style.cursor = 'pointer'
            cross.innerHTML = '&#10007'
                // remove selected attendance section
            cross.addEventListener(
                    'click',
                    () => {
                        document.getElementById('sciPapPartakerSect').removeChild(ctr)
                    }
                ) // addEventListener
            partakerFrmGrp.classList = 'form-group col-6'
            partFrmGrp.classList = 'form-group col-6'
            partakerLbl.textContent = 'Sodelovalec'
            partLbl.textContent = 'Vloga'
            partakerInptEl.classList = 'form-control'
            partakerInptEl.setAttribute('list', 'students')
            partakerInptEl.required = true
            partInptEl.classList = 'form-control'
            partInptEl.type = 'text'
            partInptEl.name = `partakers[${index}][part]`
            partInptEl.required = true
                // compose a node hierarchy by appending them to active tree structure 
            headline.appendChild(cross)
            partakerLbl.appendChild(partakerInptEl)
            partakerFrmGrp.appendChild(partakerLbl)
            partLbl.appendChild(partInptEl)
            partFrmGrp.appendChild(partLbl)
            ctr.appendChild(headline)
            ctr.appendChild(partakerFrmGrp)
            ctr.appendChild(partFrmGrp)
            document.getElementById('sciPapPartakerSect').appendChild(ctr)
        } // addPartakerSection

    //  create and append additional form controls for uploading document of the scientific paper
    let addDocUploadSect = () => {
            // form controls 
            let ctr = document.createElement('div'), // row
                cross = document.createElement('span'), // removal sign
                versionFrmGrp = document.createElement('div'), // form group
                docFrmGrp = document.createElement('div'), // form group
                versionLbl = document.createElement('label'), // version label
                docLbl = document.createElement('label'), // document label
                versionInptEl = document.createElement('input'), // version input
                docInptEl = document.createElement('input'), // document input 
                docNameInptEl = document.createElement('input'), // document hidden input 
                index = document.querySelectorAll('div#documents > div.row').length // the following index for an array of data on documents of scientific paper  
            docInptEl.addEventListener(
                    'change',
                    e => {
                        // assign chosen document name as a value to the docNameInputElement
                        docNameInptEl.value = e.target.files[0].name
                    }
                ) // addEventListener
            cross.addEventListener(
                    'click',
                    () => {
                        // remove appended controls
                        document.getElementById('sciPapDocs').removeChild(ctr)
                    }
                ) // addEventListener
            ctr.classList = 'row mt-2'
            ctr.style.position = 'relative'
            versionFrmGrp.classList = 'form-group col-6'
            docFrmGrp.classList = 'form-group col-6'
            versionLbl.textContent = 'Verzija'
            versionLbl.classList = 'w-100'
            docLbl.textContent = 'Dokument'
            docLbl.classList = 'w-100'
            versionInptEl.classList = 'form-control'
            versionInptEl.type = 'text'
            versionInptEl.name = `documents[${index}][version]`
            docInptEl.type = 'file'
            docInptEl.accept = '.pdf'
            docInptEl.name = 'document[]'
            docInptEl.required = true
            docNameInptEl.type = 'hidden'
            docNameInptEl.name = `documents[${index}][name]`
            cross.style.position = 'absolute'
            cross.style.top = 0
            cross.style.right = '10px'
            cross.style.zIndex = 1
            cross.style.cursor = 'pointer'
            cross.innerHTML = '&#10007;'
            versionLbl.appendChild(versionInptEl)
            versionFrmGrp.appendChild(versionLbl)
            docLbl.appendChild(docInptEl)
            docFrmGrp.appendChild(docNameInptEl)
            docFrmGrp.appendChild(docLbl)
            ctr.appendChild(cross)
            ctr.appendChild(versionFrmGrp)
            ctr.appendChild(docFrmGrp)
                // append controls to scientific paper insert form
            document.getElementById('sciPapDocs').appendChild(ctr)
        } // addDocUploadSect

    //  create and append additional form controls for providing data on mentors 
    let addMentoringsSect = () => {
            // create form controls 
            let ctr = document.createElement('div'), // row
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
                facSelEl = document.createElement('select'), // faculty input
                mentorSelEl = document.createElement('input'), // mentor input
                taughtInptEl = document.createElement('input'), // subject input
                emailInptEl = document.createElement('input'), // email input
                telInptEl = document.createElement('input'), // telephone input
                index = document.querySelectorAll('div#sciPapMentorSect > div.row').length // the following index for an array of data on documents of scientific paper  
            ctr.classList = 'row'
            headline.classList = 'col-12 h6'
            cross.style.float = 'right'
            cross.style.transform = 'scale(1.2)'
            cross.style.cursor = 'pointer'
            cross.innerHTML = '&#10007'
                // remove selected attendance section
            cross.addEventListener(
                    'click',
                    () => {
                        document.getElementById('sciPapMentorSect').removeChild(ctr)
                    }
                ) // addEventListener
            mentorFrmGrp.classList = 'form-group col-12'
            facFrmGrp.classList = 'form-group col-6'
            taughtFrmGrp.classList = 'form-group col-6'
            emailFrmGrp.classList = 'form-group col-6'
            telFrmGrp.classList = 'form-group col-6'
            facLbl.textContent = 'Fakulteta'
            facLbl.classList = 'w-100'
            mentorLbl.textContent = 'Mentor'
            mentorLbl.classList = 'w-100'
            taughtLbl.textContent = 'Poučeval'
            taughtLbl.classList = 'w-100'
            emailLbl.textContent = 'E-naslov'
            emailLbl.classList = 'w-100'
            telLbl.textContent = 'Telefon'
            telLbl.classList = 'w-100'
            facSelEl.classList = 'form-control'
            facSelEl.name = `mentors[${index}][id_faculties]`
            facSelEl.required = true
            mentorSelEl.classList = 'form-control'
            mentorSelEl.type = 'text'
            mentorSelEl.name = `mentors[${index}][mentor]`
            mentorSelEl.required = true
            taughtInptEl.classList = 'form-control'
            taughtInptEl.type = 'text'
            taughtInptEl.name = `mentors[${index}][taught]`
            taughtInptEl.required = true
            emailInptEl.classList = 'form-control'
            emailInptEl.type = 'email'
            emailInptEl.name = `mentors[${index}][email]`
            emailInptEl.required = true
            telInptEl.classList = 'form-control'
            telInptEl.type = 'telephone'
            telInptEl.name = `mentors[${index}][telephone]`
            telInptEl.required = true
            headline.appendChild(cross)
            mentorLbl.appendChild(mentorSelEl)
            mentorFrmGrp.appendChild(mentorLbl)
            facLbl.appendChild(facSelEl)
            facFrmGrp.appendChild(facLbl)
            taughtLbl.appendChild(taughtInptEl)
            taughtFrmGrp.appendChild(taughtLbl)
            emailLbl.appendChild(emailInptEl)
            emailFrmGrp.appendChild(emailLbl)
            telLbl.appendChild(telInptEl)
            telFrmGrp.appendChild(telLbl)
            ctr.appendChild(headline)
            ctr.appendChild(mentorFrmGrp)
            ctr.appendChild(facFrmGrp)
            ctr.appendChild(taughtFrmGrp)
            ctr.appendChild(emailFrmGrp)
            ctr.appendChild(telFrmGrp)
                // populate HTMLSelectElement with the data regarding faculties 
            propagateSelEl(
                    facSelEl,
                    '/eArchive/Faculties/select.php'
                )
                .then(() => document.getElementById('sciPapMentorSect').appendChild(ctr))
                .catch(error => alert(error))
        } // addMentoringsSect

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
     *   @param String fullname
     *   @param Number index
     */
    let interpolateStudtDatalst = (fullname, index) => {
            let option = document.createElement('option')
            option.value = index
            option.textContent = fullname
            document.getElementById('sciPapInsrMdl').querySelector('datalist').appendChild(option)
        } // interpolateStudtDatalst

    /*
     *   asynchronous script execution for selection of student particulars and scientific achievements    
     *   @param Event e
     *   @param Number idStudents
     */
    let selectStudt = (e, idStudents) => {
            request(
                    `/eArchive/Students/select.php?id_students=${idStudents}`,
                    'GET',
                    'json'
                )
                .then(response => toStudentUpdateFrm(e, response))
                .catch(error => alert(error)) // catch
        } // selectStudt

    /*
     *   asynchronous script execution for filtered student selection by index      
     *   @param Number index
     */
    let selStudtsByIndx = index => {
            request(
                    `/eArchive/Students/filterByIndex.php?index=${index}`,
                    'GET',
                    'document'
                )
                .then(response => {
                    // compose node passive tree structure
                    frag = response
                        // reflect fragments body  
                    document.querySelector('div.table-responsive').innerHTML = frag.body.innerHTML
                })
                .then(() => listenStudtEvidTbl())
                .catch(error => alert(error)) // catch
        } // selStudtsByIndx

    fltrInputEl.addEventListener('input', () => {
            // filter students by their index numbers 
            selStudtsByIndx(fltrInputEl.value)
        }) // addEventListener

    /*
     *  asynchronous script execution for insretion of student particulars and scientific achievements
     *  @param Event e
     *  @param HTMLFormElement form
     */
    let insertStudt = (e, frm) => {
            // prevent default action of submitting student data through a form
            e.preventDefault()
            request(
                    '/eArchive/Students/insert.php',
                    'POST',
                    'text',
                    (new FormData(frm))
                )
                .then(response => rprtOnAction(response))
                .then(() => loadStudtEvidTbl())
                .then(() => emptyFrmInptFields(studtInsrFrm))
                .then(() => document.getElementById('studentInsBtn').click())
                .then(() => interpolateStudtDatalst())
                .catch(error => alert(error)) // catch
        } // insertStudt

    /*
     *  fill out form fields with student birthplace particulars
     *  @param Number idpostalCode
     *  @param Number idCountries
     */
    let setBirthplaceOfStudt = (idPostalCodes, idCountries) => {
            // propagate target select element with postal codes of the chosen country
            propagateSelEl(
                    document.querySelector('#birthCtrySelEl'),
                    '/eArchive/Countries/select.php',
                    idCountries
                )
                .then(() => propagateSelEl(
                    document.querySelector('#birthPostCodeSelEl'),
                    `eArchive/PostalCodes/select.php?id_countries=${idCountries}`,
                    idPostalCodes
                ))
        } // determineStudenBirthplace

    /*
     *  fill out form fields with student permanent residence particulars
     *  @param Array residence
     */
    let setPermResOfStudt = (residence) => {
            // create hidden input type that stores record if of the residence  
            let idResidencesInputElement = document.createElement('input')
            idResidencesInputElement.type = 'hidden'
            idResidencesInputElement.name = 'residences[0][id_residences]'
            idResidencesInputElement.value = residence.id_residences
            document.querySelector('#studentInsertionFrm #perResCtrySelElement').parentElement.prepend(idResidencesInputElement)
                // propagate target select element with postal codes of the chosen country
            propagateSelEl(
                    document.querySelector('#permResCtrySelElement'),
                    '/eArchive/Countries/select.php',
                    residence.id_countries
                )
                .then(() => propagateSelEl(
                    document.querySelector('#permResPostalCodeSelElement'),
                    `/eArchive/PostalCodes/select.php?id_countries=${residence.id_countries}`,
                    residence.id_postal_codes
                )).then(() => {
                    document.querySelector('#permResAddressInputElement').value = residence.address
                })
        } // setPermResOfStudt

    /*
     *  fill out form fields with student temporal residence particulars
     *  @param Array residences
     */
    let setTempResOfStudt = residences =>
        addTempResSect(residences)

    /*
     *   asynchronous script run for deletion of temporal residence record
     *   @param idResidences
     */
    let deleteTempResOfStudt = idResidences => {
            request(
                    `/eArchive/Residences/delete.php?id_residences=${idResidences}`,
                    'GET',
                    'text'
                )
                .then(response => rprtOnAction(response))
                .catch(error => alert(error)) // catch
        } // deleteTempResOfStudt

    /*
     *  asynchronous script execution for updating of student particulars
     *  Event e
     */
    let updateStudt = (e, frm) => {
            // prevent default action of submitting updated student data through a form
            e.preventDefault()
            request(
                    '/eArchive/Students/update.php',
                    'POST',
                    'text',
                    (new FormData(frm))
                )
                .then(response => rprtOnAction(response))
                .then(() => emptyFrmInptFields(studtInsrFrm))
                .then(() => loadStudtEvidTbl())
                .catch(error => alert(error)) // catch
        } // updateStudt

    /*
     *   asynchronous script execution for deletion of all records regarding the student   
     *   @param Number idStudents
     *   @param Number idAttendances
     */
    let deleteStudt = (idStudents, idAttendances) => {
            request(
                    `/eArchive/Students/delete.php?id_students=${idStudents}&id_attendances=${idAttendances}`,
                    'GET',
                    'text'
                )
                .then(response => rprtOnAction(response))
                .then(() => loadStudtEvidTbl())
                .catch(error => alert(error)) // catch
        } // deleteStudt

    /*
     *   asynchronous script run for assigning an account credentials to the student 
     *   @param Event e
     */
    let assignAcctCred = e => {
            // prevent default action of submutting the form containing account credentials
            e.preventDefault()
            request(
                    '/eArchive/Accounts/authorized/insert.php',
                    'POST',
                    'text',
                    (new FormData(acctAssignFrm))
                )
                .then(response => rprtOnAction(response))
                .then(() => $('#acctAssigningMdl').modal('hide'))
                .then(() => loadStudtEvidTbl())
                .catch(error => alert(error)) // catch
        } // assignAcctCred

    acctAssignFrm.addEventListener(
            'submit',
            e => {
                // prevent form from submitting account details  
                e.preventDefault()
                assignAcctCred(e)
            }
        ) // addEventListener

    /*
     *   asynchronous script execution for deletion of the given account 
     *   @param idAttendances
     */
    let deleteAcctOfStudt = idAttendances => {
            request(
                    `/eArchive/Accounts/authorized/delete.php?id_attendances=${idAttendances}`,
                    'GET',
                    'text'
                )
                .then(response => rprtOnAction(response))
                .then(() => loadStudtEvidTbl())
                .catch(error => alert(error)) // catch
        } // deleteAcctOfStudt

    /*
     *   rearrange form and fill out form fields when updating student data
     *   @param Object sciPap
     */
    let toSciPapUpdateFrm = sciPap => {
            document.querySelector('#sciPapInsrMdl .modal-header > .modal-title').textContent = 'Urejanje podatkov znanstvenega dela'
                // clone from the existing form node
            let cloneFrm = sciPapInsrFrm.cloneNode(true),
                idScienitificPapersInputElement = document.createElement('input')
            idScienitificPapersInputElement.type = 'hidden'
            idScienitificPapersInputElement.name = 'id_scientific_papers'
            idScienitificPapersInputElement.value = sciPap.id_scientific_papers
                // replace form element node with its clone
            document.getElementById('sciPapInsrFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idScienitificPapersInputElement)
            listenSciPapInsrFrm()
            cloneFrm.querySelector('input[name="topic"]').value = sciPap.topic
            cloneFrm.querySelector('select[name="type"]').value = sciPap.type
            cloneFrm.querySelector('input[name="written"]').value = sciPap.written
            cloneFrm.querySelector('input[type=submit]').value = 'Uredi'
                // remove determined element nodes 
            cloneFrm.querySelectorAll('div.row:nth-child(4), #sPDocs').forEach(node => {
                    node.parentElement.removeChild(node)
                }) // forEach
            cloneFrm.addEventListener(
                    'submit',
                    e => {
                        // prevent default action of submitting scientific paper data    
                        e.preventDefault()
                        updateSciPap(cloneFrm)
                    }
                ) // addEventListener
        } // toSciPapUpdateFrm

    /*
     *   rearrange form for uploading document of the subject scientific paper
     *   @param Event e
     */
    let toSciPapDocUploadFrm = e => {
            document.querySelector('#sciPapInsrMdl .modal-header > .modal-title').textContent = 'Nalaganje dokumentov znanstvenega dela'
                // clone from the existing form node
            let cloneFrm = sciPapInsrFrm.cloneNode(true),
                idScientificPaperInputElement = document.createElement('input')
            idScientificPaperInputElement.type = 'hidden'
            idScientificPaperInputElement.name = 'id_scientific_papers'
            idScientificPaperInputElement.value = e.target.getAttribute('data-id-scientific-papers')
                // replace form node with its clone
            document.getElementById('sciPapInsrFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idScientificPaperInputElement)
                // widen form group across the whole grid
            cloneFrm.querySelector('#sciPapDocs').classList = 'col-12'
            cloneFrm.querySelector('input[type=submit]').value = 'Naloži'
            listenSciPapInsrFrm()
                // remove nodes except those matching given selector expression 
            cloneFrm.querySelectorAll('div#particulars, div.row:nth-child(4)').forEach(node => {
                    node.parentElement.removeChild(node)
                }) // forEach
            cloneFrm.addEventListener(
                    'submit',
                    e => {
                        // prevent upload of scientific paper documents
                        e.preventDefault()
                        uploadDocsOfSciPap(cloneFrm)
                    }
                ) // addEventListener
        } // toSciPapDocUploadFrm

    /*
     *  rearrange form when inserting data of the scientific paper partaker   
     *  @param Event e
     */
    let toPartakerInsertFrm = e => {
            document.querySelector('#sciPapInsrMdl .modal-header > .modal-title').textContent = 'Dodeljevanje soavtorja znanstvenega dela'
                // clone from the existing form node
            let cloneFrm = sciPapInsrFrm.cloneNode(true),
                idScientificPapersInputElement = document.createElement('input')
            idScientificPapersInputElement.type = 'hidden'
            idScientificPapersInputElement.name = 'id_scientific_papers'
            idScientificPapersInputElement.value = e.target.getAttribute('data-id-scientific-papers')
                // replace form element node with its clone
            document.getElementById('sciPapInsrFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idScientificPapersInputElement)
                // widen form group across the whole grid
            cloneFrm.querySelector('#sciPapPartakerSect').classList = 'col-12'
            cloneFrm.querySelector('input[type=submit]').value = 'Dodeli'
            listenSciPapInsrFrm()
                // dispatch a synthetic click event
            cloneFrm.querySelector('#addPartakerBtn').dispatchEvent((new Event('click')))
                // remove nodes except those matching given selector expression 
            cloneFrm.querySelectorAll('div#particulars, #sPMentors, #sPDocs, p, button').forEach(node => {
                    node.parentElement.removeChild(node)
                }) // forEach
            cloneFrm.addEventListener(
                    'submit',
                    e => {
                        // cancel submitting partaker data by default
                        e.preventDefault()
                        insertPartakerOfSciPap(cloneFrm)
                    }
                ) // addEventListener
        } // toPartakerInsertFrm

    /*
     *  rearrange form when updating data with regard to partaker of the scientific paper 
     *  @param Event e
     */
    let toPartakerUpdateFrm = e => {
            document.querySelector('#sciPapInsrMdl .modal-header .modal-title').textContent = 'Urejanje vloge soavtorja znanstvenega dela'
                // clone from the existing form node
            let cloneFrm = sciPapInsrFrm.cloneNode(true),
                idPartakingsHiddInpt = document.createElement('input')
            idPartakingsHiddInpt.type = 'hidden'
            idPartakingsHiddInpt.name = 'id_partakings'
            idPartakingsHiddInpt.value = e.target.getAttribute('data-id-partakings')
                // replace form node with its clone
            document.getElementById('sciPapInsrFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idPartakingsHiddInpt)
                // widen form group across the whole grid
            cloneFrm.querySelector('#sciPapPartakerSect').classList = 'col-12'
            listenSciPapInsrFrm()
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
            cloneFrm.addEventListener(
                    'submit',
                    e => {
                        // cancel submitting partaker data by default
                        e.preventDefault()
                        updatePartakerOfSciPap(cloneFrm)
                    }
                ) // addEventListener
        } // toPartakerUpdateFrm

    /*
     *  rearrange form when inserting data regarding mentor of the scientific paper 
     *  @param Event e
     */
    let toMentorInsertFrm = e => {
            document.querySelector('#sciPapInsrMdl .modal-header > .modal-title').textContent = 'Določanje mentorja znanstvenega dela'
                // clone from the existing form node
            let cloneFrm = sciPapInsrFrm.cloneNode(true),
                idScientificPapersInputElement = document.createElement('input')
            idScientificPapersInputElement.type = 'hidden'
            idScientificPapersInputElement.name = 'id_scientific_papers'
            idScientificPapersInputElement.value = e.target.getAttribute('data-id-scientific-papers')
                // replace form element node with its clone
            document.getElementById('sciPapInsrFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idScientificPapersInputElement)
                // widen form group across the whole grid
            cloneFrm.querySelector('#sciPapMentorSect').classList = 'col-12'
            cloneFrm.querySelector('input[type=submit]').value = 'Določi'
            listenSciPapInsrFrm()
                // dispatch a synthetic click event
            cloneFrm.querySelector('#addMentorBtn').dispatchEvent((new Event('click')))
                // remove nodes except those matching given selector expression 
            cloneFrm.querySelectorAll('div#particulars, #sPPartkers, #sPDocs, p, button').forEach(node => {
                    node.parentElement.removeChild(node)
                }) // forEach
            cloneFrm.addEventListener(
                    'submit',
                    e => {
                        // cancel submitting mentor data by default
                        e.preventDefault()
                        insertMentorOfSciPap(cloneFrm)
                    }
                ) // addEventListener
        } // toMentorInsertFrm

    /*
     *  rearrange form when updating data with regard to mentor of the scientific paper  
     *  @param Event e
     */
    let toMentorUpdateFrm = e => {
            document.querySelector('#sciPapInsrMdl .modal-header > .modal-title').textContent = 'Urejanje podatkov mentorja znanstvenega dela'
            let cloneFrm = sciPapInsrFrm.cloneNode(true),
                idMentoringsInputElement = document.createElement('input')
            idMentoringsInputElement.type = 'hidden'
            idMentoringsInputElement.name = 'id_mentorings'
            idMentoringsInputElement.value = e.target.getAttribute('data-id-mentorings')
                // replace form element node with its clone
            document.getElementById('sciPapInsrFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idMentoringsInputElement)
            cloneFrm.querySelector('input[type=submit]').value = 'Uredi'
            listenSciPapInsrFrm()
                // dispatch a synthetic click event to button for subsequent addition of form mentor section
            cloneFrm.querySelector('#addMentorBtn').dispatchEvent((new Event('click')))
                // remove DIV nodes except matching given selector expression 
            cloneFrm.querySelectorAll('#particulars, #sciPapPartakerSect, #sPDocs, p, button').forEach(node => {
                    node.parentElement.removeChild(node)
                }) // forEach
                // widen form group across the whole grid
            cloneFrm.querySelector('#sPMentors').classList = 'col-12'
            request(
                    `/eArchive/Mentorings/select.php?id_mentorings=${e.target.getAttribute('data-id-mentorings')}`,
                    'GET',
                    'json'
                )
                .then(response => {
                    // populate form fields with selected mentor data
                    cloneFrm.querySelector('input[name=id_mentorings]').value = e.target.getAttribute('data-id-mentorings')
                    cloneFrm.querySelector('input[name="mentors[0][mentor]"]').value = response.mentor
                    cloneFrm.querySelector('select[name="mentors[0][id_faculties]"]').value = response.id_faculties
                    cloneFrm.querySelector('input[name="mentors[0][taught]"]').value = response.taught
                    cloneFrm.querySelector('input[name="mentors[0][email]"]').value = response.email
                    cloneFrm.querySelector('input[name="mentors[0][telephone]"]').value = response.telephone
                }).catch(error => alert(error)) // catch
            cloneFrm.addEventListener(
                    'submit',
                    e => {
                        // prevent form from submitting updated mentor data 
                        e.preventDefault();
                        updateMentorOfSciPap(cloneFrm)
                    }
                ) // addEventListener
        } // toMentorUpdateFrm

    /*
     *  rearrange form when updating data regarding students graduation certificate  
     *  @param Event e
     */
    let toGradCertUpdateFrm = e => {
            document.querySelector('#gradCertUploadMdl .modal-header > .modal-title').textContent = 'Urejanje podatkov certifikata'
                // clone from the existing form node
            let cloneFrm = certCloneFrm.cloneNode(true),
                idCertificatesInputElement = document.createElement('input')
            idCertificatesInputElement.type = 'hidden'
            idCertificatesInputElement.name = 'id_certificates'
            idCertificatesInputElement.value = e.target.getAttribute('data-id-certificates')
                // replace form element node with its clone
            document.getElementById('certUploadFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idCertificatesInputElement)
            listenGradCertCard()
                // remove certificate file input 
            cloneFrm.querySelector('div.row').removeChild(cloneFrm.querySelector('div.row').querySelector('.form-group'))
                // fill out form fileds with carried data
            cloneFrm.querySelector('input[name=defended]').value = e.target.getAttribute('data-defended')
            cloneFrm.querySelector('input[name=issued]').value = e.target.getAttribute('data-issued')
                // change submit buttons value
            cloneFrm.querySelector('input[type=submit]').value = 'Uredi'
            cloneFrm.addEventListener(
                    'submit',
                    e => {
                        // cancel submitting updated certificate data by default
                        e.preventDefault()
                        updateGradCert(cloneFrm)
                    }
                ) // addEventListener
        } // toGradCertUpdateFrm

    /*
     *   rearrange form when updating data related to the student
     *   @param Event e
     *   @param Object student
     */
    let toStudentUpdateFrm = (e, student) => {
            let // clone from the existing form node
                cloneFrm = studtInsrFrm.cloneNode(true),
                idStudentsInputElement = document.createElement('input')
            idStudentsInputElement.type = 'hidden'
            idStudentsInputElement.name = 'id_students'
            idStudentsInputElement.value = e.target.getAttribute('data-id-students')
                // replace node with its clone
            document.getElementById('studentInsertionFrm').replaceWith(cloneFrm)
            listenStudtInsrFrm()
            cloneFrm.prepend(idStudentsInputElement)
                // fill out input fields with student particulars
            cloneFrm.querySelector('input[name=name]').value = student.particulars.name
            cloneFrm.querySelector('input[name=surname]').value = student.particulars.surname
            cloneFrm.querySelector('input[name=email]').value = student.particulars.email
            cloneFrm.querySelector('input[name=telephone]').value = student.particulars.telephone
            setBirthplaceOfStudt(cloneFrm, student.particulars.id_postal_codes, student.particulars.id_countries)
            setPermResOfStudt(cloneFrm, student.permResidence)
            setTempResOfStudt(student.tempResidence)
            cloneFrm.removeChild(cloneFrm.querySelector('#attendances'))
            cloneFrm.querySelector('input[type=submit]').value = 'Posodobi'
                // exchange callbacks
            cloneFrm.removeEventListener('submit', insertStudt)
            cloneFrm.addEventListener('submit', updateStudt)
        } // toStudentUpdateFrm

    // attach event listeners to a scientific paper cards when rendered
    let listenSciPapCards = () => {
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
                    span.addEventListener(
                            'click',
                            () => {
                                deletePartakerOfSciPap(span.getAttribute('data-id-partakings'))
                            }
                        ) // addEventListener
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
                    anchor.addEventListener(
                            'click',
                            () => {
                                deleteMentorOfSciPap(anchor.getAttribute('data-id-mentorings'))
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
                                .then(response => toSciPapUpdateFrm(response))
                                .catch(error => alert(error)) // catch
                        }) // addEventListener
                }) // forEach
                // if anchors for scientific paper deletion are rendered
            if (document.querySelectorAll('.sp-del-a'))
                document.querySelectorAll('.sp-del-a').forEach(anchor => {
                    anchor.addEventListener(
                            'click',
                            () => {
                                deleteSciPap(anchor.getAttribute('data-id-scientific-papers'))
                            }
                        ) // addEventListener
                }) // forEach
                // if anchors for scientific paper document upload exist
            if (document.querySelectorAll('.doc-upl-a'))
                document.querySelectorAll('.doc-upl-a').forEach(span => {
                    // delete particular document
                    span.addEventListener('click', toSciPapDocUploadFrm)
                }) // forEach
                // if anchors for scientific paper documentation deletion are rendered
            if (document.querySelectorAll('.doc-del-spn'))
                document.querySelectorAll('.doc-del-spn').forEach(span => {
                    // delete particular document
                    span.addEventListener(
                            'click',
                            () => {
                                deleteDocsOfSciPap(span.getAttribute('data-source'))
                            }
                        ) // addEventListener
                }) // forEach
        } // attachSPCardListeners

    // attach listeners to certificate card when selected
    let listenGradCertCard = () => {
            // get modal for graduation certificate review
            let mdl = document.getElementById('certViewingMdl')
                // if anchor element for update of certificate connected data exist
            if (mdl.querySelector('.modal-content .cert-upd-a'))
                mdl.querySelector('.modal-content .cert-upd-a').addEventListener('click', toGradCertUpdateFrm)
                // if anchor element for certificate deletion is contained
            if (mdl.querySelector('.modal-content .cert-del-a'))
                mdl.querySelector('.modal-content .cert-del-a').addEventListener(
                    'click',
                    e => {
                        deleteGradCert(e.target.getAttribute('data-id-attendances'), e.target.getAttribute('data-source'))
                    }
                ) // addEventListner
        } // attachCertificateCardListeners

    // asynchronous script execution for insertion of a scientific paper partaker    
    let insertPartakerOfSciPap = frm => {
            request(
                    '/eArchive/Partakings/insert.php',
                    'POST',
                    'text',
                    (new FormData(frm))
                )
                .then(response => rprtOnAction(response))
                .then(() => $('#sciPapMdl').modal('hide'))
                .then(() => selectSciPaps(frm.querySelector('input[name=id_attendances]').value))
                .catch(error => alert(error)) // catch
        } // insertPartakerOfSciPap

    /*
     *  asynchronous script execution for updating data of the scientific paper partaker    
     *  @param HTMLFormElement frm
     */
    let updatePartakerOfSciPap = frm => {
            request(
                    '/eArchive/Partakings/update.php',
                    'POST',
                    'text',
                    (new FormData(frm))
                )
                .then(response => rprtOnAction(response))
                .then(() => selectSciPaps(frm.querySelector('input[name=id_attendances]').value))
                .catch(error => alert(error)) // catch
        } // updatePartakerOfSciPap

    /*
     *  asynchronous script execution for deletion of the scientific paper partaker    
     *  @param Number idPartakings
     */
    let deletePartakerOfSciPap = idPartakings => {
            request(
                    `/eArchive/Partakings/delete.php?id_partakings=${idPartakings}`,
                    'GET',
                    'text'
                )
                .then(response => rprtOnAction(response))
                .then(() => selectSciPaps(document.getElementById('sciPapInsrFrm').querySelector('input[name=id_attendances]').value))
                .catch(error => alert(error)) // catch
        } // deletePartakerOfSciPap

    /*
     *  asynchronous script execution for inserting submitted mentor data     
     *  @param HTMLFormElement frm
     */
    let insertMentorOfSciPap = frm => {
            request(
                    '/eArchive/Mentorings/insert.php',
                    'POST',
                    'text',
                    (new FormData(frm))
                )
                .then(response => rprtOnAction(response))
                .then(() => $('#sciPapInsrMdl').modal('hide'))
                .then(() => selectSciPaps(frm.querySelector('input[name=id_attendances]').value))
                .catch(error => alert(error)) // catch
        } // insertMentorOfSciPap

    // asynchronously script run for updating data regarding mentor of the scientific paper       
    let updateMentorOfSciPap = frm => {
            request(
                    '/eArchive/Mentorings/update.php',
                    'POST',
                    'text',
                    (new FormData(frm))
                )
                .then(response => rprtOnAction(response))
                .then(() => selectSciPaps(frm.querySelector('input[name=id_attendances]').value))
                .catch(error => alert(error)) // catch
        } // updateMentorOfSciPap

    /*
     *  asynchronously script run for deletion of data concerning scientific paper mentor       
     *  @param Number idMentorings
     */
    let deleteMentorOfSciPap = idMentorings => {
            request(
                    `/eArchive/Mentorings/delete.php?id_mentorings=${idMentorings}`,
                    'GET',
                    'text'
                )
                .then(response => rprtOnAction(response))
                .then(() => selectSciPaps(document.getElementById('sciPapFrm').querySelector('input[name=id_attendances]').value))
                .catch(error => alert(error)) // catch
        } // deleteMentorOfSciPap

    /*  
     *   asynchronous script execution for scientific paper documents upload    
     *   @param HTMLFormElement frm
     */
    let uploadDocsOfSciPap = frm => {
            request(
                    '/eArchive/Documents/insert.php',
                    'POST',
                    'text',
                    (new FormData(frm))
                )
                .then(response => rprtOnAction(response))
                .then(() => $('#sicPapMdl').modal('hide'))
                .then(() => selectSciPaps(frm.querySelector('input[name=id_attendances').value))
                .catch(error => alert(error)) // catch
        } // uploadDocsOfSciPap

    /*
     *  asynchronous script execution for scientific paper documents deletion    
     *  @param String source  
     */
    let deleteDocsOfSciPap = source => {
            request(
                    `/eArchive/Documents/delete.php?source=${source}`,
                    'GET',
                    'text'
                )
                .then(response => rprtOnAction(response))
                .then(() => selectSciPaps(document.getElementById('sciPapInsrFrm').querySelector('input[name=id_attendances]').value))
                .catch(error => alert(error)) // catch
        } // deleteDocsOfSciPap

    /*
     *   asynchronous script execution for selection of scientific papers per student program attendance 
     *   @param Number idAttendances
     */
    let selectSciPaps = idAttendances => {
            // fetch resources
            request(
                    `/eArchive/ScientificPapers/select.php?id_attendances=${idAttendances}`,
                    'GET',
                    'document'
                )
                .then(response => {
                    // compose node tree structure
                    frag = response
                        // reflect fragments body     
                    document.querySelector('#sciPapViewingMdl .modal-content').innerHTML = frag.body.innerHTML
                })
                .then(() => listenSciPapCards())
                .catch(error => alert(error)) // catch
        } // selectSciPaps

    /*
     *   asynchronous script execution for insertion data regarding scientific paper and its documents upload 
     *   @param Event e
     *   @param HTMLFormElement frm
     */
    let insertSciPap = (e, frm) => {
            // prevent default action of submitting scientific paper data    
            e.preventDefault()
            request(
                    '/eArchive/ScientificPapers/insert.php',
                    'POST',
                    'text',
                    (new FormData(frm))
                )
                .then(response => rprtOnAction(response))
                .then(() => $('#sciPapInsrMdl').modal('hide'))
                .catch(error => alert(error)) // catch
        } // insertSciPap

    /*
     *   asynchronous script execution for scientific paper data alteration 
     *   @param HTMLFormElement frm
     */
    let updateSciPap = frm => {
            request(
                    '/eArchive/ScientificPapers/update.php',
                    'POST',
                    'text',
                    (new FormData(frm))
                )
                .then(response => rprtOnAction(response))
                .then(() => $('#sciPapInsrMdl').modal('hide'))
                .then(() => selectSciPaps(frm.querySelector('input[name=id_attendances]').value))
                .catch(error => alert(error)) // catch
        } // updateSciPap

    /*
     *  asynchronous script execution for scientific paper deletion with its belonging documents     
     *  @param Number idScientificPapers
     */
    let deleteSciPap = idScientificPapers => {
            request(
                    `/eArchive/ScientificPapers/delete.php?id_scientific_papers=${idScientificPapers}`,
                    'GET',
                    'text'
                )
                .then(response => rprtOnAction(response))
                .then(() => selectSciPaps(document.getElementById('sciPapInsrFrm').querySelector('input[name=id_attendances]').value))
                .catch(error => alert(error)) // catch
        } // deleteScientificPaper

    /*
     *   asynchronous script execution for graduation certificate upload     
     *   @param Event e
     */
    let uploadGradCert = e => {
            // prevent default action of submitting certificate upload form
            e.preventDefault()
            request(
                    '/eArchive/Certificates/insert.php',
                    'POST',
                    'text',
                    (new FormData(gradCertUplFrm))
                )
                .then(response => rprtOnAction(response))
                .then(() => loadStudtEvidTbl())
                .then(() => $('#gradCertUploadMdl').modal('hide'))
                .catch(error => alert(error)) // catch
        } // uploadGradCert

    gradCertUplFrm.addEventListener('submit', uploadGradCert)

    /*
     *  asynchronous script execution for graduation certificate selection    
     *  @param Number idAttendances
     */
    let selectGradCert = idAttendances => {
            request(
                    `/eArchive/Certificates/select.php?id_attendances=${idAttendances}`,
                    'GET',
                    'document'
                )
                .then(response => {
                    // compose node tree structure
                    frag = response
                        // reflect fragments body     
                    document.querySelector('div#gradCertViewingMdl > div.modal-dialog > .modal-content').innerHTML = frag.body.innerHTML
                })
                .then(() => listenGradCertCard())
                .catch(error => alert(error)) // catch
        } // selectGradCert

    /*
     *  asynchronously script run for graduation certificate data update     
     *  @param HTMLFormElement frm
     */
    let updateGradCert = frm => {
            request(
                    '/eArchive/Certificates/update.php',
                    'POST',
                    'text',
                    (new FormData(frm))
                )
                .then(response => rprtOnAction(response))
                .then(() => selectGradCert(frm.querySelector('input[name=id_attendances]').value))
                .catch(error => alert(error)) // catch
        } // updateGradCert

    /*
     *  asynchronous script execution for graduation certificate deletion    
     *  @param Number idAttendance
     *  @param String source
     */
    let deleteGradCert = (idAttendances, source) => {
            request(
                    `/eArchive/Graduations/delete.php?id_attendances=${idAttendances}&source=${source}`,
                    'GET',
                    'text'
                )
                .then(response => rprtOnAction(response))
                .then(() => loadStudtEvidTbl())
                .then(() => $('#gradCertViewingMdl').modal('hide'))
                .catch(error => alert(error)) // catch
        } // deleteGradCert

    /*  
     *   report to the user on the performed action
     *   @param String mssg
     */
    let rprtOnAction = mssg => {
            rprtMdl.querySelector('.modal-body').textContent = mssg
            reportMdlBtn.click()
        } // rprtOnAction
})()