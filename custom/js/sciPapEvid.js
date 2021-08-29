// IIFE
(() => {
    var fragment = new DocumentFragment(), // minimal document object structure
        sciPapInsFrm = document.getElementById('sciPapInsFrm'), // form for scientific paper data manipulation
        avtrUplFrm = document.getElementById('avtrUplFrm'), // form for uploading account avatar
        sciPapInsBtn = document.getElementById('sciPapInsBtn'), // button for toggling scientific paper insertion modal
        fltInpEl = document.getElementById('fltInpEl') // input for filtering scientific papers by their subject

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

    // load scientific paper evidence table upon latterly data amendment 
    let loadScientificPaperEvidenceTable = () => {
            request(
                    '/eArchive/Accounts/student/sciPapEvid.php',
                    'GET',
                    'document'
                )
                .then(response => {
                    // compose node tree structure
                    fragment = response
                        // reflect fragments body  
                    document.body.querySelector('div.table-responsive').replaceWith(frag.body.querySelector('div.table-responsive'))
                        // enabling tooltips 
                    $('[data-toggle="tooltip"]').tooltip()
                })
                .then(() => listenScientificPaperEvidenceTable())
                .catch(error => alert(error)) // catch
        } // loadScientificPaperEvidenceTable

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
                .then(() => $('div#sciPapInsMdl').modal('hide'))
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
                .then(() => $('div#sciPapInsMdl').modal('hide'))
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
                .then(() => $('div#sciPapInsMdl').modal('hide'))
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
                .then(() => $('div#sciPapInsMdl').modal('hide'))
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
                .then(() => $('div#sciPapInsMdl').modal('hide'))
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
                .then(() => $('div#sciPapInsMdl').modal('hide'))
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
                .then(() => $('div#sicPapMdl').modal('hide'))
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
     *  upload account avatar document
     *  @param Event e
     */
    let uploadAccountAvatar = e => {
            // prevent default action of submitting account avater through a form
            e.preventDefault()
            request(
                    '/eArchive/Accounts/student/uploadAvatar.php',
                    'POST',
                    'text',
                    (new FormData(avtrUplFrm))
                )
                .then(response => reportOnAction(response))
                .then(() => $('#avtrUplMdl').modal('hide'))
                .then(() => request(
                    '/eArchive/Accounts/student/sciPapEvid.php',
                    'GET',
                    'document'
                ))
                .then(response => {
                    // compose node tree structure of passive documents body
                    fragment = response
                        // replace nodes of the passive and active tree structures
                    document.body.querySelector('nav').replaceWith(frag.body.querySelector('nav'))
                    document.body.querySelector('#avatar').replaceWith(frag.body.querySelector('#avatar'))
                })

            .then(() => listenAvatarRemovalSign())
                .catch(error => alert(error)) // catch
        } // uploadAccountAvatar

    /*
     *  physically and logically delete account avatar    
     *  @param Event e  
     */
    let deleteAccountAvatar = e => {
            request(
                    `/eArchive/Accounts/student/deleteAvatar.php?id_attendances=${e.target.dataset.idAttendances}&avatar=${e.target.dataset.avatar}`,
                    'GET',
                    'text'
                )
                .then(response => reportOnAction(response))
                .then(() => $('#avtrUplMdl').modal('hide'))
                .then(() => request(
                    '/eArchive/Accounts/student/sciPapEvid.php',
                    'GET',
                    'document'
                ))
                .then(response => {
                    // compose node tree structure of passive documents body
                    fragment = response
                        // replace nodes of the passive and active tree structures
                    document.body.querySelector('nav').replaceWith(frag.body.querySelector('nav'))
                    document.body.querySelector('#avatar').replaceWith(frag.body.querySelector('#avatar'))
                })
                .catch(error => alert(error)) // catch
        } // deleteAccountAvatar

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
        } // propagateSelectElement

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
                        index = document.querySelectorAll('div#partakers > div.row').length // the following index for an array of data on a partaker  
                        // set observation criterion
                    observer.observe(document.getElementById('partakers'), {
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
                    partakerInput.setAttribute('list', 'studentDatalist')
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
                    document.getElementById('partakers').appendChild(container)
                }) // Promise
        } // addPartakerSection

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
                        index = document.querySelectorAll('div#mentors > div.row').length // the following index for an array of data on documents of scientific paper  
                        // set observation criterion
                    observer.observe(document.getElementById('mentors'), {
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
                        .then(() => document.getElementById('mentors').appendChild(container))
                        .catch(error => alert(error))
                }) // Promise
        } // addMentorSection

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
                    observer.observe(document.getElementById('documents'), {
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
                    document.getElementById('documents').appendChild(container)
                }) // Promise
        } // addDocumentUploadSection

    /*
     *   rearrange form when interpolating data regarding scientific paper and uploading its documents    
     *   @param Event e
     */
    let toScientificPaperInsertForm = e => {
            document.querySelector('div#sciPapInsMdl div.modal-header > h4.modal-title').textContent = 'Vstavljanje znanstvenega dela'
                // clone from the existing form node
            let cloneForm = sciPapInsFrm.cloneNode(true)
            cloneForm.querySelector('input[name=id_attendances]').value = e.target.dataset.idAttendances
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
            cloneForm.querySelectorAll('div.row:nth-child(4), div#documents').forEach(node => {
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
            idSciPapInpt.value = e.target.dataset.idScientificPapers
                // replace form element node with its clone
            document.getElementById('sciPapInsFrm').replaceWith(cloneForm)
            cloneForm.prepend(idSciPapInpt)
                // widen form group across the whole grid
            cloneForm.querySelector('#partakers').classList = 'col-12'
            cloneForm.querySelector('input[type=submit]').value = 'Dodeli'
            listenScientificPaperInsertForm()
            addPartakerSection()
                .then(() => {
                    // remove nodes except those matching given selector expression 
                    cloneForm.querySelectorAll('div.row:nth-child(3), div#documents, p, button').forEach(node => {
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
            idPartInpt.value = e.target.dataset.idPartakings
                // replace form node with its clone
            document.getElementById('sciPapInsFrm').replaceWith(cloneForm)
            cloneForm.prepend(idPartInpt)
                // widen form group across the whole grid
            cloneForm.querySelector('#partakers').classList = 'col-12'
            listenScientificPaperInsertForm()
            addPartakerSection()
                .then(() => {
                    // remove nodes except those matching given selector expression 
                    cloneForm.querySelectorAll('div#particulars, div#mentors, div#documents, p, div.d-flex, button').forEach(node => {
                            node.parentElement.removeChild(node)
                        }) // forEach
                        // populate form fields concerning data of the partaker
                    cloneForm.querySelector('input[name="partakers[0][index]"]').value = e.target.dataset.index
                    cloneForm.querySelector('input[name="partakers[0][part]"]').value = e.target.dataset.part
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
            idSciPapInpt.value = e.target.dataset.idScientificPapers
                // replace form element node with its clone
            document.getElementById('sciPapInsFrm').replaceWith(cloneForm)
            cloneForm.prepend(idSciPapInpt)
                // widen form group across the whole grid
            cloneForm.querySelector('#mentors').classList = 'col-12'
            cloneForm.querySelector('input[type=submit]').value = 'Določi'
            listenScientificPaperInsertForm()
            addMentorSection()
                .then(() => {
                    // remove nodes except those matching given selector expression 
                    cloneForm.querySelectorAll('div#particulars, div#partakers, div#documents, p, button').forEach(node => {
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
            idMentInpt.value = e.target.dataset.idMentorings
                // replace form element node with its clone
            document.getElementById('sciPapInsFrm').replaceWith(cloneForm)
            cloneForm.prepend(idMentInpt)
            cloneForm.querySelector('input[type=submit]').value = 'Uredi'
            listenScientificPaperInsertForm()
            addMentorSection()
                .then(() => {
                    // remove DIV nodes except matching given selector expression 
                    cloneForm.querySelectorAll('div#particulars, div#partakers, div#documents, p, button').forEach(node => {
                            node.remove()
                        }) // forEach
                        // widen form group across the whole grid
                    cloneForm.querySelector('#mentors').classList = 'col-12'
                }).then(() => request(
                    `/eArchive/Mentorings/select.php?id_mentorings=${e.target.dataset.idMentorings}`,
                    'GET',
                    'json'
                )).then(response => {
                    // populate form fields with selected mentor data
                    cloneForm.querySelector('input[name=id_mentorings]').value = e.target.dataset.idMentorings
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
        } // toMentorUpdateForm

    /*
     *   rearrange form for uploading document of the subject scientific paper
     *   @param Event e
     */
    let toDocumentUploadForm = e => {
            document.querySelector('div#sciPapInsMdl div.modal-header > h4.modal-title').textContent = 'Nalaganje dokumentov znanstvenega dela'
                // clone from the existing form node
            let cloneForm = sciPapInsFrm.cloneNode(true),
                idSciPapInpt = document.createElement('input')
            idSciPapInpt.type = 'hidden'
            idSciPapInpt.name = 'id_scientific_papers'
            idSciPapInpt.value = e.target.dataset.idScientificPapers
                // replace form node with its clone
            document.getElementById('sciPapInsFrm').replaceWith(cloneForm)
            cloneForm.prepend(idSciPapInpt)
                // widen form group across the whole grid
            cloneForm.querySelector('#documents').classList = 'col-12 mb-3'
            cloneForm.querySelector('input[type=submit]').value = 'Naloži'
            listenScientificPaperInsertForm()
                // remove nodes except those matching given selector expression 
            cloneForm.querySelectorAll('div#particulars, div#partakers, div#mentors').forEach(node => {
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
        } // toDocumentUploadForm

    // attach event listeners to corresponding input element 
    let listenScientificPaperInsertForm = () => {
            // get the form 
            let form = document.getElementById('sciPapInsFrm')
                // if button for subsequent partaker section additon exists
            if (form.querySelector('#addPartaker'))
                form.querySelector('#addPartaker').addEventListener(
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
            if (form.querySelector('#addMentor'))
                form.querySelector('#addMentor').addEventListener(
                    'click',
                    addMentorSection
                )
                // if button for subsequent document section additon exists
            if (form.querySelector('#addDocument'))
            // append controls for additional scientific paper document upload
                form.querySelector('#addDocument').addEventListener(
                'click',
                addDocumentUploadSection
            )
        } // listenScientificPaperInsertForm

    // attach event listeners to a scientific paper cards when rendered
    let listenScientificPaperEvidenceTable = () => {
            // if anchor nodes for partaker insertion exist
            if (document.querySelectorAll('.par-ins-img'))
                document.querySelectorAll('.par-ins-img').forEach(image => {
                    // form will contain only control for partaker insertion
                    image.addEventListener('click', toPartakerInsertForm)
                }) // forEach
                // if spans for scientific paper partaker deletion exist
            if (document.querySelectorAll('.par-del-a'))
                document.querySelectorAll('.par-del-a').forEach(anchor => {
                    // attempt deletion of a partaker
                    anchor.addEventListener(
                            'click',
                            () => {
                                deletePartaker(anchor.getAttribute('data-id-partakings'))
                            }
                        ) // addEventListener
                }) // forEach
                // if anchors for scientific paper partaker data update exist
            if (document.querySelectorAll('.par-upd-a'))
                document.querySelectorAll('.par-upd-a').forEach(anchor => {
                    // attempt deletion of a partaker
                    anchor.addEventListener('click', toPartakerUpdateForm) // addEventListener
                }) // forEach
                // if anchors for scientific paper partaker data update exist
            if (document.querySelectorAll('.par-upd-a'))
                document.querySelectorAll('.par-upd-a').forEach(anchor => {
                    // attempt deletion of a partaker
                    anchor.addEventListener('click', toPartakerUpdateForm) // addEventListener
                }) // forEach
                // if anchors for mentor insertion are rendered
            if (document.querySelectorAll('.men-ins-img'))
                document.querySelectorAll('.men-ins-img').forEach(image => {
                    // restructure form for document upload
                    image.addEventListener('click', toMentorInsertForm)
                }) // forEach
                // if anchor elements for mentor data update exist
            if (document.querySelectorAll('.men-upd-a'))
                document.querySelectorAll('.men-upd-a').forEach(anchor => {
                    // restructure form for document upload
                    anchor.addEventListener('click', toMentorUpdateForm)
                }) // forEachF
                // if span elements for mentor deletion are rendered
            if (document.querySelectorAll('.men-del-a'))
                document.querySelectorAll('.men-del-a').forEach(anchor => {
                    // restructure form for document upload
                    anchor.addEventListener(
                            'click',
                            () => {
                                deleteMentor(anchor.getAttribute('data-id-mentorings'))
                            }
                        ) // addEventListener
                }) // forEach
                // if anchors for scientific paper update are rendered
            if (document.querySelectorAll('.sp-upd-img'))
                document.querySelectorAll('.sp-upd-img').forEach(image => {
                    // fill form fields and modify the form
                    image.addEventListener('click', e => {
                            request(
                                    `/eArchive/ScientificPapers/select.php?id_scientific_papers=${image.getAttribute('data-id-scientific-papers')}`,
                                    'GET',
                                    'json'
                                )
                                .then(response => toScientificPaperUpdateForm(response))
                                .catch(error => alert(error)) // catch
                        }) // addEventListener
                }) // forEach
                // if anchors for scientific paper deletion are rendered
            if (document.querySelectorAll('.sp-del-img'))
                document.querySelectorAll('.sp-del-img').forEach(image => {
                    image.addEventListener(
                            'click',
                            () => {
                                deleteScientificPaper(image.getAttribute('data-id-scientific-papers'))
                            }
                        ) // addEventListener
                }) // forEach
                // if anchors for scientific paper document upload exist
            if (document.querySelectorAll('.doc-upl-img'))
                document.querySelectorAll('.doc-upl-img').forEach(image => {
                    // delete particular document
                    image.addEventListener('click', toDocumentUploadForm)
                }) // forEach
                // if anchors for scientific paper documentation deletion are rendered
            if (document.querySelectorAll('.doc-del-a'))
                document.querySelectorAll('.doc-del-a').forEach(span => {
                    // delete particular document
                    span.addEventListener(
                            'click',
                            () => {
                                deleteDocument(span.getAttribute('data-source'))
                            }
                        ) // addEventListener
                }) // forEach
        } // listenScientificPaperEvidenceTable

    listenScientificPaperEvidenceTable()

    /*  
     *   report to the user on the performed action
     *   @param String message
     */
    let reportOnAction = messae => {
            $('div#reportModal').modal('show')
            $('div#reportModal > div.modal-dialog > div.modal-content > div.modal-body').text(messae)
        } // rprtOnAction

    avtrUplFrm.addEventListener('submit', uploadAccountAvatar)

    fltInpEl.addEventListener(
            'input',
            e => {
                request(
                        `/eArchive/ScientificPapers/filter.php?topic=${e.target.value}`,
                        'GET',
                        'document'
                    )
                    .then(response => {
                        // compose documents node tree structure
                        fragment = response
                            // replace passive with the active node structures
                        document.querySelector('table>tbody').replaceWith(frag.body.querySelector('table>tbody'))
                            // enable tooltips on table images
                        $('[data-toggle="tooltip"]').tooltip()
                    })
                    .then(() => listenScientificPaperEvidenceTable())
                    .catch(error => alert(error))
            }
        ) // addEventListener

    let listenAvatarRemovalSign = () => {
            // if icon for account avatar removal exists
            if (document.getElementById('removeAvatar'))
                document.getElementById('removeAvatar').addEventListener('click', deleteAccountAvatar)
        } // listenAcctRmvIcon

    listenAvatarRemovalSign()

    sciPapInsBtn.addEventListener('click', toScientificPaperInsertForm)

    // enabling tooltips 
    $('[data-toggle="tooltip"]').tooltip()
})()