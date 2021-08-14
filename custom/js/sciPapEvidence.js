// IIFE
(() => {
    var sciPapInsrFrm = document.getElementById('sciPapInsrFrm'), // form for inserting, updating and deleting data regarding the scientific paper
        acctAvtrUpldFrm = document.getElementById('acctAvtrUpldFrm'), // form for uploading account avatar
        sciPapInsrBtn = document.getElementById('sciPapInsrBtn'), // button for toggling scientific paper insertion modal
        fltrInptEl = document.getElementById('fltrInputEl') // input for filtering scientific papers by their subject

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
    let loadSciPapEvidTbl = () => {
            request(
                    '/eArchive/Accounts/student/sciPapEvidence.php',
                    'GET',
                    'document'
                )
                .then(response => {
                    // compose node tree structure
                    frag = response
                        // reflect fragments body  
                    document.body.querySelector('div.table-responsive').replaceWith(frag.body.querySelector('div.table-responsive'))
                        // enabling tooltips 
                    $('[data-toggle="tooltip"]').tooltip()
                })
                .then(() => listenSciPapEvidTbl())
                .catch(error => alert(error)) // catch
        } // loadSciPapEvidTbl

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
                .then(() => loadSciPapEvidTbl())
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
                .then(() => loadSciPapEvidTbl())
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
                .then(() => loadSciPapEvidTbl())
                .catch(error => alert(error)) // catch
        } // deleteScientificPaper

    // asynchronous script execution for insertion of a scientific paper partaker    
    let insertPartakerOfSciPap = frm => {
            request(
                    '/eArchive/Partakings/insert.php',
                    'POST',
                    'text',
                    (new FormData(frm))
                )
                .then(response => rprtOnAction(response))
                .then(() => $('#sciPapInsrMdl').modal('hide'))
                .then(() => loadSciPapEvidTbl())
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
                .then(() => $('#sciPapInsrMdl').modal('hide'))
                .then(() => loadSciPapEvidTbl())
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
                .then(response => $('#sciPapInsrMdl').modal('hide'))
                .then(() => loadSciPapEvidTbl())
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
                .then(() => loadSciPapEvidTbl())
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
                .then(() => $('#sciPapInsrMdl').modal('hide'))
                .then(() => loadSciPapEvidTbl())
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
                .then(() => $('#sciPapInsrMdl').modal('hide'))
                .then(() => loadSciPapEvidTbl())
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
                .then(() => $('#sciPapMdl').modal('hide'))
                .then(() => loadSciPapEvidTbl())
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
                .then(() => loadSciPapEvidTbl())
                .catch(error => alert(error)) // catch
        } // deleteDocsOfSciPap

    /*
     *  asynchronous script run for account avatar upload    
     *  @param HTMLFormElement frm  
     */
    let uploadAcctAvatar = e => {
            // prevent default action of submitting account avater through a form
            e.preventDefault()
            request(
                    '/eArchive/Accounts/student/uploadAvatar.php',
                    'POST',
                    'text',
                    (new FormData(acctAvtrUpldFrm))
                )
                .then(response => rprtOnAction(response))
                .then(() => $('#acctAvtrUpldMdl').modal('hide'))
                .then(() => request(
                    '/eArchive/Accounts/student/sciPapEvidence.php',
                    'GET',
                    'document'
                ))
                .then(response => {
                    // compose node tree structure of passive documents body
                    frag = response
                        // replace nodes of the passive and active tree structures
                    document.body.querySelector('nav').replaceWith(frag.body.querySelector('nav'))
                    document.body.querySelector('#currAvtr').replaceWith(frag.body.querySelector('#currAvtr'))
                })

            .then(() => listenAcctRmvIcon())
                .catch(error => alert(error)) // catch
        } // uploadAcctAvatar

    /*
     *  asynchronous script run for account avatar deletion    
     *  @param HTMLFormElement frm  
     */
    let deleteAcctAvatar = e => {
            request(
                    `/eArchive/Accounts/student/deleteAvatar.php?id_attendances=${e.target.dataset.idAttendances}&avatar=${e.target.dataset.avatar}`,
                    'GET',
                    'text'
                )
                .then(response => rprtOnAction(response))
                .then(() => $('#acctAvtrUpldMdl').modal('hide'))
                .then(() => request(
                    '/eArchive/Accounts/student/ScientificPaperReview.php',
                    'GET',
                    'document'
                ))
                .then(response => {
                    // compose node tree structure of passive documents body
                    frag = response
                        // replace nodes of the passive and active tree structures
                    document.body.querySelector('nav').replaceWith(frag.body.querySelector('nav'))
                    document.body.querySelector('#currAvtr').replaceWith(frag.body.querySelector('#currAvtr'))
                })
                .catch(error => alert(error)) // catch
        } // uploadAcctAvatar

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

    // create and subsequently append partaker section of the scientific paper insertion form 
    let addPartakerSect = () => {
            return new Promise((resolve) => {
                    let observer = new MutationObserver(() => resolve()),
                        // create form controls 
                        ctr = document.createElement('div'),
                        headline = document.createElement('p'),
                        cross = document.createElement('span'),
                        partakerFrmGrp = document.createElement('div'),
                        partFrmGrp = document.createElement('div'),
                        partakerLbl = document.createElement('label'),
                        partLbl = document.createElement('label'),
                        partakerInptEl = document.createElement('input'),
                        partInptEl = document.createElement('input'),
                        index = document.querySelectorAll('div#sciPapPartakers > div.row').length // the following index for an array of data on a partaker  
                        // set observation criterion
                    observer.observe(document.getElementById('sciPapPartakers'), {
                        attributes: false,
                        childList: true,
                        subtree: false
                    })
                    ctr.classList = 'row'
                    headline.classList = 'h6 col-12'
                    cross.style.float = 'right'
                    cross.style.transform = 'scale(1.2)'
                    cross.style.cursor = 'pointer'
                    cross.innerHTML = '&times;'
                        // remove selected attendance section
                    cross.addEventListener(
                            'click',
                            () => {
                                document.getElementById('sciPapPartakers').removeChild(ctr)
                            }
                        ) // addEventListener
                    partakerFrmGrp.classList = 'form-group col-6'
                    partFrmGrp.classList = 'form-group col-6'
                    partakerLbl.textContent = 'Sodelovalec'
                    partakerLbl.classList = 'w-100'
                    partLbl.textContent = 'Vloga'
                    partLbl.classList = 'w-100'
                    partakerInptEl.classList = 'form-control'
                    partakerInptEl.setAttribute('list', 'students')
                    partakerInptEl.name = `partakers[${index}][index]`
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
                    document.getElementById('sciPapPartakers').appendChild(ctr)
                }) // Promise
        } // addPartakerSect

    //  create and append additional form controls for providing data on mentors 
    let addMentorSect = () => {
            return new Promise((resolve) => {
                    let ctr = document.createElement('div'), // row
                        observer = new MutationObserver(() => resolve()),
                        // create form controls 
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
                        index = document.querySelectorAll('div#sciPapMentors > div.row').length // the following index for an array of data on documents of scientific paper  
                        // set observation criterion
                    observer.observe(document.getElementById('sciPapMentors'), {
                        attributes: false,
                        childList: true,
                        subtree: false
                    })
                    ctr.classList = 'row'
                    headline.classList = 'col-12 h6'
                    cross.style.float = 'right'
                    cross.style.transform = 'scale(1.2)'
                    cross.style.cursor = 'pointer'
                    cross.innerHTML = '&times;'
                        // remove selected attendance section
                    cross.addEventListener(
                            'click',
                            () => {
                                document.getElementById('sciPapMentors').removeChild(ctr)
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
                        .then(() => document.getElementById('sciPapMentors').appendChild(ctr))
                        .catch(error => alert(error))
                }) // Promise
        } // addMentorSect

    //  create and append additional form controls for uploading document of the scientific paper
    let addDocUpldSect = () => {
            return new Promise((resolve) => {
                    let observer = new MutationObserver(() => resolve()),
                        // form controls 
                        ctr = document.createElement('div'), // row
                        cross = document.createElement('span'), // removal sign
                        versionFrmGrp = document.createElement('div'), // form group
                        docFrmGrp = document.createElement('div'), // form group
                        versionLbl = document.createElement('label'), // version label
                        docLbl = document.createElement('label'), // document label
                        versionInptEl = document.createElement('input'), // version input
                        docInptEl = document.createElement('input'), // document input 
                        docNameInptEl = document.createElement('input'), // document hidden input 
                        index = document.querySelectorAll('div#sciPapDocs > div.row').length // the following index for an array of data on documents of scientific paper  
                        // set observation criterion
                    observer.observe(document.getElementById('sciPapDocs'), {
                        attributes: false,
                        childList: true,
                        subtree: false
                    })
                    docInptEl.addEventListener(
                            'input',
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
                    docLbl.classList = 'w-100 file-label'
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
                    cross.innerHTML = '&times;'
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
                }) // Promise
        } // addDocUpldSect

    /*
     *   rearrange form when interpolating data regarding scientific paper and uploading its documents    
     *   @param Event e
     */
    let toSciPapInsrFrm = e => {
            document.querySelector('div#sciPapInsrMdl div.modal-header > h4.modal-title').textContent = 'Vstavljanje znanstvenega dela'
                // clone from the existing form node
            let cloneFrm = sciPapInsrFrm.cloneNode(true)
                // replace form element node with its clone
            document.getElementById('sciPapInsrFrm').replaceWith(cloneFrm)
            cloneFrm.querySelector('input[type=submit]').value = 'Vstavi'
            listenSciPapInsrFrm()
            cloneFrm.addEventListener(
                    'submit',
                    e => { insertSciPap(e, cloneFrm) }
                ) // addEventListner
        } // toSciPapInsrFrm

    /*
     *   rearrange form and fill out form fields when updating student data
     *   @param Object sciPap
     */
    let toSciPapUpdtFrm = sciPap => {
            document.querySelector('div#sciPapInsrMdl div.modal-header > h4.modal-title').textContent = 'Urejanje podatkov znanstvenega dela'
                // clone from the existing form node
            let cloneFrm = sciPapInsrFrm.cloneNode(true),
                idScientificPapersInptEl = document.createElement('input')
            idScientificPapersInptEl.type = 'hidden'
            idScientificPapersInptEl.name = 'id_scientific_papers'
            idScientificPapersInptEl.value = sciPap.id_scientific_papers
                // replace form element node with its clone
            document.getElementById('sciPapInsrFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idScientificPapersInptEl)
            listenSciPapInsrFrm()
            cloneFrm.querySelector('input[name="topic"]').value = sciPap.topic
            cloneFrm.querySelector('select[name="type"]').value = sciPap.type
            cloneFrm.querySelector('input[name="written"]').value = sciPap.written
            cloneFrm.querySelector('input[type=submit]').value = 'Uredi'
                // remove determined element nodes 
            cloneFrm.querySelectorAll('div.row:nth-child(4), div#sciPapDocs').forEach(node => {
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
        } // toSciPapUpdtFrm

    /*
     *  rearrange form when inserting data of the scientific paper partaker   
     *  @param Event e
     */
    let toPartakerInsrFrm = e => {
            document.querySelector('div#sciPapInsrMdl div.modal-header > h4.modal-title').textContent = 'Dodeljevanje soavtorja znanstvenega dela'
                // clone from the existing form node
            let cloneFrm = sciPapInsrFrm.cloneNode(true),
                idScientificPapersInptEl = document.createElement('input')
            idScientificPapersInptEl.type = 'hidden'
            idScientificPapersInptEl.name = 'id_scientific_papers'
            idScientificPapersInptEl.value = e.target.getAttribute('data-id-scientific-papers')
                // replace form element node with its clone
            document.getElementById('sciPapInsrFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idScientificPapersInptEl)
                // widen form group across the whole grid
            cloneFrm.querySelector('#sciPapPartakers').classList = 'col-12'
            cloneFrm.querySelector('input[type=submit]').value = 'Dodeli'
            listenSciPapInsrFrm()
            addPartakerSect()
                .then(() => {
                    // remove nodes except those matching given selector expression 
                    cloneFrm.querySelectorAll('div.row:nth-child(3), div#sciPapDocs, p, button').forEach(node => {
                            node.parentElement.removeChild(node)
                        }) // forEach
                })
            cloneFrm.addEventListener(
                    'submit',
                    e => {
                        // cancel submitting partaker data by default
                        e.preventDefault()
                        insertPartakerOfSciPap(cloneFrm)
                    }
                ) // addEventListener
        } // toPartakerInsrFrm

    /*
     *  rearrange form when updating data with regard to partaker of the scientific paper 
     *  @param Event e
     */
    let toPartakerUpdtFrm = e => {
            document.querySelector('div#sciPapInsrMdl div.modal-header > h4.modal-title').textContent = 'Urejanje vloge soavtorja znanstvenega dela'
                // clone from the existing form node
            let cloneFrm = sciPapInsrFrm.cloneNode(true),
                idPartakingsInptEl = document.createElement('input')
            idPartakingsInptEl.type = 'hidden'
            idPartakingsInptEl.name = 'id_partakings'
            idPartakingsInptEl.value = e.target.getAttribute('data-id-partakings')
                // replace form node with its clone
            document.getElementById('sciPapInsrFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idPartakingsInptEl)
                // widen form group across the whole grid
            cloneFrm.querySelector('#sciPapPartakers').classList = 'col-12'
            listenSciPapInsrFrm()
            addPartakerSect()
                .then(() => {
                    // remove nodes except those matching given selector expression 
                    cloneFrm.querySelectorAll('div#particulars, div#sciPapMentors, div#sciPapDocs, p, div.d-flex, button').forEach(node => {
                            node.parentElement.removeChild(node)
                        }) // forEach
                        // populate form fields concerning data of the partaker
                    cloneFrm.querySelector('input[name="partakers[0][index]"]').value = e.target.getAttribute('data-index')
                    cloneFrm.querySelector('input[name="partakers[0][part]"]').value = e.target.getAttribute('data-part')
                    cloneFrm.querySelector('input[type=submit]').value = 'Uredi'
                })
            cloneFrm.addEventListener(
                    'submit',
                    e => {
                        // cancel submitting partaker data by default
                        e.preventDefault()
                        updatePartakerOfSciPap(cloneFrm)
                    }
                ) // addEventListener
        } // toPartakerUpdtFrm

    /*
     *  rearrange form when inserting data regarding mentor of the scientific paper 
     *  @param Event e
     */
    let toMentorInsrFrm = e => {
            document.querySelector('div#sciPapInsrMdl div.modal-header > h4.modal-title').textContent = 'Določanje mentorja znanstvenega dela'
                // clone from the existing form node
            let cloneFrm = sciPapInsrFrm.cloneNode(true),
                idScientificPapersInptEl = document.createElement('input')
            idScientificPapersInptEl.type = 'hidden'
            idScientificPapersInptEl.name = 'id_scientific_papers'
            idScientificPapersInptEl.value = e.target.getAttribute('data-id-scientific-papers')
                // replace form element node with its clone
            document.getElementById('sciPapInsrFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idScientificPapersInptEl)
                // widen form group across the whole grid
            cloneFrm.querySelector('#sciPapMentors').classList = 'col-12'
            cloneFrm.querySelector('input[type=submit]').value = 'Določi'
            listenSciPapInsrFrm()
            addMentorSect()
                .then(() => {
                    // remove nodes except those matching given selector expression 
                    cloneFrm.querySelectorAll('div#particulars, div#sciPapPartakers, div#sciPapDocs, p, button').forEach(node => {
                            node.parentElement.removeChild(node)
                        }) // forEach
                })
            cloneFrm.addEventListener(
                    'submit',
                    e => {
                        // cancel submitting mentor data by default
                        e.preventDefault()
                        insertMentorOfSciPap(cloneFrm)
                    }
                ) // addEventListener
        } // toMentorInsrFrm

    /*
     *  rearrange form when updating data with regard to mentor of the scientific paper  
     *  @param Event e
     */
    let toMentorUpdtFrm = e => {
            document.querySelector('div#sciPapInsrMdl div.modal-header > h4.modal-title').textContent = 'Urejanje podatkov mentorja znanstvenega dela'
            let cloneFrm = sciPapInsrFrm.cloneNode(true),
                idMentoringsInptEl = document.createElement('input')
            idMentoringsInptEl.type = 'hidden'
            idMentoringsInptEl.name = 'id_mentorings'
            idMentoringsInptEl.value = e.target.getAttribute('data-id-mentorings')
                // replace form element node with its clone
            document.getElementById('sciPapInsrFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idMentoringsInptEl)
            cloneFrm.querySelector('input[type=submit]').value = 'Uredi'
            listenSciPapInsrFrm()
            addMentorSect()
                .then(() => {
                    // remove DIV nodes except matching given selector expression 
                    cloneFrm.querySelectorAll('div#particulars, div#sciPapPartakers, div#sciPapDocs, p, button').forEach(node => {
                            node.parentElement.removeChild(node)
                        }) // forEach
                        // widen form group across the whole grid
                    cloneFrm.querySelector('#sciPapMentors').classList = 'col-12'
                })
                .then(() => request(
                    `/eArchive/Mentorings/select.php?id_mentorings=${e.target.getAttribute('data-id-mentorings')}`,
                    'GET',
                    'json'
                ))
                .then(response => {
                    // populate form fields with selected mentor data
                    cloneFrm.querySelector('input[name=id_mentorings]').value = e.target.getAttribute('data-id-mentorings')
                    cloneFrm.querySelector('input[name="mentors[0][mentor]"]').value = response.mentor
                    cloneFrm.querySelector('select[name="mentors[0][id_faculties]"]').value = response.id_faculties
                    cloneFrm.querySelector('input[name="mentors[0][taught]"]').value = response.taught
                    cloneFrm.querySelector('input[name="mentors[0][email]"]').value = response.email
                    cloneFrm.querySelector('input[name="mentors[0][telephone]"]').value = response.telephone
                })
                .catch(error => alert(error)) // catch
            cloneFrm.addEventListener(
                    'submit',
                    e => {
                        // prevent form from submitting updated mentor data 
                        e.preventDefault();
                        updateMentorOfSciPap(cloneFrm)
                    }
                ) // addEventListener
        } // toMentorUpdtFrm

    /*
     *   rearrange form for uploading document of the subject scientific paper
     *   @param Event e
     */
    let toSciPapDocUpldFrm = e => {
            document.querySelector('div#sciPapInsrMdl div.modal-header > h4.modal-title').textContent = 'Nalaganje dokumentov znanstvenega dela'
                // clone from the existing form node
            let cloneFrm = sciPapInsrFrm.cloneNode(true),
                idScientificPapersIntpEl = document.createElement('input')
            idScientificPapersIntpEl.type = 'hidden'
            idScientificPapersIntpEl.name = 'id_scientific_papers'
            idScientificPapersIntpEl.value = e.target.getAttribute('data-id-scientific-papers')
                // replace form node with its clone
            document.getElementById('sciPapInsrFrm').replaceWith(cloneFrm)
            cloneFrm.prepend(idScientificPapersIntpEl)
                // widen form group across the whole grid
            cloneFrm.querySelector('#sciPapDocs').classList = 'col-12 mb-3'
            cloneFrm.querySelector('input[type=submit]').value = 'Naloži'
            listenSciPapInsrFrm()
                // remove nodes except those matching given selector expression 
            cloneFrm.querySelectorAll('div#particulars, p, div.row:nth-child(4)').forEach(node => {
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
        } // toSciPapDocUpldFrm

    // attach event listeners to corresponding input element 
    let listenSciPapInsrFrm = () => {
            // get the form 
            let frm = document.getElementById('sciPapInsrFrm')
                // if button for subsequent partaker section additon exists
            if (frm.querySelector('#addPartakerBtn'))
                frm.querySelector('#addPartakerBtn').addEventListener(
                    'click',
                    addPartakerSect
                )
                // if file input is rendered 
            if (frm.querySelector('input[name="document[]"]'))
                frm.querySelector('input[name="document[]"]').addEventListener(
                    'input',
                    e => {
                        // assign the filename of the uploaded document to the hidden input type
                        frm.querySelector('input[name="documents[0][name]"]').value = e.target.files[0].name
                    }
                ) // addEventListener
                // if button for subsequent mentor section additon exists 
            if (frm.querySelector('#addMentorBtn'))
                frm.querySelector('#addMentorBtn').addEventListener(
                    'click',
                    addMentorSect
                )
                // if button for subsequent document section additon exists
            if (frm.querySelector('#addDocBtn'))
            // append controls for additional scientific paper document upload
                frm.querySelector('#addDocBtn').addEventListener(
                'click',
                addDocUpldSect
            )
        } // attachSciPapFrmListeners

    // attach event listeners to a scientific paper cards when rendered
    let listenSciPapEvidTbl = () => {
            // if anchor nodes for partaker insertion exist
            if (document.querySelectorAll('.par-ins-img'))
                document.querySelectorAll('.par-ins-img').forEach(anchor => {
                    // form will contain only control for partaker insertion
                    anchor.addEventListener('click', toPartakerInsrFrm)
                }) // forEach
                // if spans for scientific paper partaker deletion exist
            if (document.querySelectorAll('.par-del-a'))
                document.querySelectorAll('.par-del-a').forEach(span => {
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
                    anchor.addEventListener('click', toPartakerUpdtFrm) // addEventListener
                }) // forEach
                // if anchors for scientific paper partaker data update exist
            if (document.querySelectorAll('.par-upd-a'))
                document.querySelectorAll('.par-upd-a').forEach(anchor => {
                    // attempt deletion of a partaker
                    anchor.addEventListener('click', toPartakerUpdtFrm) // addEventListener
                }) // forEach
                // if anchors for mentor insertion are rendered
            if (document.querySelectorAll('.men-ins-img'))
                document.querySelectorAll('.men-ins-img').forEach(anchor => {
                    // restructure form for document upload
                    anchor.addEventListener('click', toMentorInsrFrm)
                }) // forEach
                // if anchor elements for mentor data update exist
            if (document.querySelectorAll('.men-upd-a'))
                document.querySelectorAll('.men-upd-a').forEach(anchor => {
                    // restructure form for document upload
                    anchor.addEventListener('click', toMentorUpdtFrm)
                }) // forEachF
                // if span elements for mentor deletion are rendered
            if (document.querySelectorAll('.men-del-a'))
                document.querySelectorAll('.men-del-a').forEach(anchor => {
                    // restructure form for document upload
                    anchor.addEventListener(
                            'click',
                            () => {
                                deleteMentorOfSciPap(anchor.getAttribute('data-id-mentorings'))
                            }
                        ) // addEventListener
                }) // forEach
                // if anchors for scientific paper update are rendered
            if (document.querySelectorAll('.sp-upd-img'))
                document.querySelectorAll('.sp-upd-img').forEach(anchor => {
                    // fill form fields and modify the form
                    anchor.addEventListener('click', e => {
                            request(
                                    `/eArchive/ScientificPapers/select.php?id_scientific_papers=${anchor.getAttribute('data-id-scientific-papers')}`,
                                    'GET',
                                    'json'
                                )
                                .then(response => toSciPapUpdtFrm(response))
                                .catch(error => alert(error)) // catch
                        }) // addEventListener
                }) // forEach
                // if anchors for scientific paper deletion are rendered
            if (document.querySelectorAll('.sp-del-img'))
                document.querySelectorAll('.sp-del-img').forEach(anchor => {
                    anchor.addEventListener(
                            'click',
                            () => {
                                deleteSciPap(anchor.getAttribute('data-id-scientific-papers'))
                            }
                        ) // addEventListener
                }) // forEach
                // if anchors for scientific paper document upload exist
            if (document.querySelectorAll('.doc-upl-img'))
                document.querySelectorAll('.doc-upl-img').forEach(span => {
                    // delete particular document
                    span.addEventListener('click', toSciPapDocUpldFrm)
                }) // forEach
                // if anchors for scientific paper documentation deletion are rendered
            if (document.querySelectorAll('.doc-del-a'))
                document.querySelectorAll('.doc-del-a').forEach(span => {
                    // delete particular document
                    span.addEventListener(
                            'click',
                            () => {
                                deleteDocsOfSciPap(span.getAttribute('data-source'))
                            }
                        ) // addEventListener
                }) // forEach
        } // listenSciPapEvidTbl

    listenSciPapEvidTbl()

    /*  
     *   report to the user on the performed action
     *   @param String mssg
     */
    let rprtOnAction = mssg => {
            $('div#rprtMdl').modal('show')
            $('div#rprtMdl > div.modal-dialog > div.modal-content > div.modal-body').text(mssg)
        } // rprtOnAction

    acctAvtrUpldFrm.addEventListener('submit', uploadAcctAvatar)

    fltrInptEl.addEventListener(
            'input',
            e => {
                request(
                        `/eArchive/ScientificPapers/filter.php?topic=${e.target.value}`,
                        'GET',
                        'document'
                    )
                    .then(response => {
                        // compose documents node tree structure
                        frag = response
                            // replace passive with the active node structures
                        document.querySelector('table').replaceWith(frag.body.querySelector('table'))
                    })
                    .then(() => listenSciPapEvidTbl())
                    .catch(error => alert(error))
            }
        ) // addEventListener

    let listenAcctRmvIcon = () => {
            // if icon for account avatar removal exists
            if (document.getElementById('acctAvtrRmvIcon'))
                document.getElementById('acctAvtrRmvIcon').addEventListener('click', deleteAcctAvatar)
        } // listenAcctRmvIcon

    listenAcctRmvIcon()

    sciPapInsrBtn.addEventListener('click', toSciPapInsrFrm)

    // enabling tooltips 
    $('[data-toggle="tooltip"]').tooltip()
})()