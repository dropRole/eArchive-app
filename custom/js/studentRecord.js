// IIFE
(() => {
    // global scope variable declaration
    var fragment = new DocumentFragment(), // minimal document object structure
        sIFrm = document.getElementById('sIFrm'), // student data insert form
        sPFrm = document.getElementById('sPFrm'), // scientific paper insert form
        certFrm = document.getElementById('certFrm'), // certificate upload/insertion form
        sPFrmClone = sPFrm.cloneNode(true), // sPFrm clone node
        aMdl = document.getElementById('aMdl'), // account modal 
        rMdl = document.getElementById('rMdl'), // report modal 
        rMdlBtn = document.getElementById('rMdlBtn'), // button for report modal toggle
        aRBtn = document.getElementById('aRBtn'), // button for residence addition 
        aABtn = document.getElementById('aABtn'), // button for attendance addition
        sPVALst = document.querySelectorAll('.sp-vw-a'), // anchor list for scientific papers selection
        sPIALst = document.querySelectorAll('.sp-ins-a'), // anchor list for scientific papers insertion
        certIALst = document.querySelectorAll('.cert-ins-a'), // anchor list for certificate insertion
        certVALst = document.querySelectorAll('.cert-vw-a'), // anchor list for certificate view
        aIBtnLst = document.querySelectorAll('.acc-ins-btn'), // button list for account generation
        aDBtnLst = document.querySelectorAll('.acc-del-btn'), // button list for account deletion
        cSlct = document.getElementById('cSlct'), // country select input 
        fSlct = document.getElementById('fSlct'), // faculty select input
        gCb = document.getElementById('gCb'), // graduation checkbox
        docInpt = document.getElementById('docInpt'), // document input
        rLbl = 2, // residence label counter
        rIndx = 1, // residence array index 
        aLbl = 1, // attendance label counter
        aIndx = 1, // attendance array index 
        dLbl = 1 // documentation label counter
    sIFrm.addEventListener('submit', insertStudent)
    sPFrm.addEventListener('submit', insertScientificPaper)
    certFrm.addEventListener('submit', insertCertificate)
    certFrm.querySelector('input[type=file').addEventListener('change', () => {
            certFrm.querySelector('input[name=certificate]').value = certFrm.querySelector('input[type=file]').files[0].name
        }) // addEventListener
    aRBtn.addEventListener('click', addSojourn)
    aABtn.addEventListener('click', addAttendance)
        // propagate postal codes by country selection
    cSlct.addEventListener('change', e => {
            propagateSelectElement(document.getElementById(e.target.getAttribute('data-target')), `/eArchive/PostalCodes/select.php?id_countries=${e.target.selectedOptions[0].value}`)
        }) // addEventListener
        // propagate postal codes by country selection
    cSlct1.addEventListener('change', e => {
            propagateSelectElement(document.getElementById('pCSlct1'), `/eArchive/PostalCodes/select.php?id_countries=${e.target.selectedOptions[0].value}`)
        }) // addEventListener
        // propagate programs by faculty selection
    fSlct.addEventListener('change', e => {
            propagateSelectElement(document.getElementById('pSlct'), `/eArchive/Programs/select.php?id_faculties=${fSlct.selectedOptions[0].value}`)
        }) // addEventListener
        // append graduation section if graduated
    gCb.addEventListener('change', e => {
            addGraduation(e, document.getElementById('rDiv'))
        }) // addEventListener
    aIBtnLst.forEach(btn => {
            // populate modals body with created account insert form 
            btn.addEventListener('click', createAccountForm) //addEventListener
        }) // forEach
    aDBtnLst.forEach(btn => {
            // delete particular account 
            btn.addEventListener('click', () => {
                    deleteAccount(btn.getAttribute('data-id'))
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
        // give hidden input type value of chosens document name
    docInpt.addEventListener('change', e => {
            document.getElementById('docHInpt').value = e.target.files[0].name
        }) // addEventListener        
        // create and append additional form residence section controls 
    function addSojourn() {
        // create form controls 
        let xmlhttp = new XMLHttpRequest(),
            div = document.getElementById('residences'),
            rDiv = document.createElement('div'),
            p = document.createElement('p'),
            span = document.createElement('span'),
            fGDiv = document.createElement('div'),
            fGDiv1 = document.createElement('div'),
            fGDiv2 = document.createElement('div'),
            aLbl = document.createElement('label'),
            pCLbl = document.createElement('label'),
            cLbl = document.createElement('label'),
            aInpt = document.createElement('input'),
            pCSlct = document.createElement('select'),
            cSlct = document.createElement('select')
        rDiv.className = 'row'
        rDiv.style.position = 'relative'
        p.classList = 'col-12 h6'
        p.textContent = `${rLbl - 1}. začasno bivališče`
        span.style.float = 'right'
        span.style.transform = 'scale(1.2)'
        span.style.cursor = 'pointer'
            // remove selected residence section
        span.addEventListener('click', () => {
                div.removeChild(rDiv)
                rLbl--
            }) // addEventListener
        span.innerHTML = '&#10007;'
        fGDiv.className = 'form-group col-4'
        fGDiv1.className = 'form-group col-4'
        fGDiv2.className = 'form-group col-4'
        cLbl.setAttribute('for', `cSlct${rLbl}`)
        cLbl.textContent = 'Država'
        pCLbl.setAttribute('for', `pCSlct${rLbl}`)
        pCLbl.textContent = 'Kraj'
        aLbl.setAttribute('for', `aInpt${rLbl}`)
        aLbl.textContent = 'Naslov'
        aInpt.id = `aInpt${rLbl}`
        cSlct.id = `cSlct${rLbl}`
        cSlct.classList = 'form-control'
            // propagate postal codes by country selection
        cSlct.addEventListener('change', e => {
                propagateSelectElement(pCSlct, `/eArchive/PostalCodes/select.php?id_countries=${e.target.selectedOptions[0].value}`)
            }) // addEventListener
        pCSlct.id = `pCSlct${rLbl}`
        pCSlct.classList = 'form-control'
        pCSlct.name = `residences[${rIndx}][id_postal_codes]`
        pCSlct.required = true
        aInpt.classList = 'form-control'
        aInpt.type = 'text'
        aInpt.name = `residences[${rIndx}][address]`
        aInpt.required = true
            // propagate countries by adding new residence
        xmlhttp.addEventListener('load', () => {
                fragment = xmlhttp.response
                    // traverse through nodes
                fragment.body.querySelectorAll('option').forEach(element => {
                        cSlct.add(element)
                    }) // forEach
                    // append controls to a form residence section
                p.appendChild(span)
                fGDiv.appendChild(cLbl)
                fGDiv.appendChild(cSlct)
                fGDiv1.appendChild(pCLbl)
                fGDiv1.appendChild(pCSlct)
                fGDiv2.appendChild(aLbl)
                fGDiv2.appendChild(aInpt)
                rDiv.appendChild(p)
                rDiv.appendChild(fGDiv)
                rDiv.appendChild(fGDiv1)
                rDiv.appendChild(fGDiv2)
                div.appendChild(rDiv)
            }) // addEventListener
        xmlhttp.responseType = 'document'
        xmlhttp.open('GET', '/eArchive/Countries/select.php')
        xmlhttp.send()
        rIndx++
        rLbl++
    } // addSojourn

    // propagate select control with suitable options
    function propagateSelectElement(pSlct, script) {
        let xmlhttp = new XMLHttpRequest()
        xmlhttp.addEventListener('load', () => {
                fragment = xmlhttp.response
                    // remove options while on disposal
                while (pSlct.options.length) {
                    pSlct.remove(0)
                } // while
                // traverse through nodes 
                fragment.body.querySelectorAll('option').forEach(element => {
                        pSlct.add(element)
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
                fGDiv = document.createElement('div'),
                fGDiv1 = document.createElement('div'),
                fGDiv2 = document.createElement('div'),
                fLbl = document.createElement('label'),
                dLbl = document.createElement('label'),
                fInpt = document.createElement('input'),
                dInpt = document.createElement('input'),
                cInpt = document.createElement('input')
            iLbl = document.createElement('label'),
                iInpt = document.createElement('input')
            fGDiv.className = 'form-group col-4'
            fGDiv1.className = 'form-group col-4'
            fGDiv2.className = 'form-group col-4'
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
            fGDiv.appendChild(fLbl)
            fGDiv.appendChild(fInpt)
            fGDiv1.appendChild(dLbl)
            fGDiv1.appendChild(dInpt)
            fGDiv2.appendChild(iLbl)
            fGDiv2.appendChild(iInpt)
            pNode.appendChild(cInpt)
            pNode.appendChild(fGDiv)
            pNode.appendChild(fGDiv1)
            pNode.appendChild(fGDiv2)
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
            fGDiv = document.createElement('div'),
            fGDiv1 = document.createElement('div'),
            fGDiv2 = document.createElement('div'),
            fGDiv3 = document.createElement('div'),
            fGDiv4 = document.createElement('div'),
            fLbl = document.createElement('label'),
            pLbl = document.createElement('label'),
            eLbl = document.createElement('label'),
            iLbl = document.createElement('label'),
            fSlct = document.createElement('select'),
            pSlct = document.createElement('select'),
            eInpt = document.createElement('input'),
            iInpt = document.createElement('input'),
            gLbl = document.createElement('label'),
            gCb = document.createElement('input')
            // initial propagation
        propagateSelectElement(fSlct, '/eArchive/Faculties/select.php')
        setTimeout(() => {
                propagateSelectElement(pSlct, `/eArchive/Programs/select.php?id_faculties=${fSlct.selectedOptions[0].value}`)
            }, 500) // setTimeout
            // propagate programs by faculty selection
        fSlct.addEventListener('change', e => {
                propagateSelectElement(pSlct, `/eArchive/Programs/select.php?id_faculties=${e.target.selectedOptions[0].value}`)
            }) // addEventListener
            // append graduation section if graduated        
        gCb.addEventListener('change', e => {
                addGraduation(e, rDiv)
            }) // addEventListener
        p.className = 'col-12 h6'
        p.textContent = `${aLbl + 1}. študijski program`
        span.style.float = 'right'
        span.style.transform = 'scale(1.2)'
        span.style.cursor = 'pointer'
        span.innerHTML = '&#10007'
            // remove selected attendance section
        span.addEventListener('click', () => {
                div.removeChild(rDiv)
                aLbl--
            }) // addEventListener
        span.innerHTML = '&#10007;'
        rDiv.className = 'row'
        fGDiv.className = 'form-group col-6'
        fGDiv1.className = 'form-group col-6'
        fGDiv2.className = 'form-group col-4'
        fGDiv3.className = 'form-group col-4'
        fGDiv4.className = 'd-flex align-items-center justify-content-center form-group col-4'
        fLbl.setAttribute('for', `fSlct${aLbl}`)
        fLbl.textContent = 'Fakulteta'
        pLbl.textContent = 'Program'
        pLbl.setAttribute('for', `pSlct${aLbl}`)
        eLbl.textContent = 'Vpisan'
        eLbl.setAttribute('for', `enInpt${aLbl}`)
        iLbl.textContent = 'Indeks'
        iLbl.setAttribute('for', `iInpt${aLbl}`)
        gLbl.textContent = 'Diplomiral'
        gLbl.setAttribute('for', `gCb${aLbl}`)
        gLbl.className = 'mt-2'
        fSlct.className = 'form-control'
        fSlct.id = `fSlct${aLbl}`
        fSlct.name = `attendances[${aIndx}][id_faculties]`
        fSlct.required = true
        pSlct.className = 'form-control'
        pSlct.id = `pSlct${aLbl}`
        pSlct.name = `attendances[${aIndx}][id_programs]`
        pSlct.required = true
        eInpt.className = 'form-control'
        eInpt.id = `enInpt${aLbl}`
        eInpt.type = 'date'
        eInpt.name = `attendances[${aIndx}][enrolled]`
        eInpt.required = true
        iInpt.className = 'form-control'
        iInpt.id = `iInpt${aLbl}`
        iInpt.type = 'text'
        iInpt.name = `attendances[${aIndx}][index]`
        iInpt.required = true
        gCb.id = `gCb${aLbl}`
        gCb.type = 'checkbox'
        gCb.classList = 'mr-2'
        gCb.setAttribute('data-index', aIndx)
        gCb.setAttribute('data-counter', aLbl)
            // append controls to a form attendances section
        fGDiv.appendChild(fLbl)
        fGDiv.appendChild(fSlct)
        fGDiv1.appendChild(pLbl)
        fGDiv1.appendChild(pSlct)
        fGDiv2.appendChild(eLbl)
        fGDiv2.appendChild(eInpt)
        fGDiv3.appendChild(iLbl)
        fGDiv3.appendChild(iInpt)
        fGDiv4.appendChild(gCb)
        fGDiv4.appendChild(gLbl)
        p.appendChild(span)
        rDiv.appendChild(p)
        rDiv.appendChild(fGDiv)
        rDiv.appendChild(fGDiv1)
        rDiv.appendChild(fGDiv2)
        rDiv.appendChild(fGDiv3)
        rDiv.appendChild(fGDiv4)
        div.appendChild(rDiv)
        aIndx++
        aLbl++
    } // addAttendance 

    // pass and insert student data
    function insertStudent(e) {
        // prevent default action by submitting insert form
        e.preventDefault()
        let xmlhttp = new XMLHttpRequest,
            fData = new FormData(sIFrm)
            // report on data insertion
        xmlhttp.addEventListener('load', () => {
                rMdl.querySelector('.modal-body').innerHTML = xmlhttp.responseText
                rMdlBtn.click()
            }) // addEventListener
        xmlhttp.open('POST', '/eArchive/Students/insert.php', true)
        xmlhttp.send(fData)
    } // insertStudent

    // create a student account insert form
    function createAccountForm(e) {
        // if modal already contains form 
        if (aMdl.querySelector('.modal-body').firstChild)
            aMdl.querySelector('.modal-body').removeChild(aMdl.querySelector('.modal-body').firstChild)
            // create form and form controls
        let form = document.createElement('form'), // insert form
            hInpt = document.createElement('input'), // hidden input    
            fGDiv = document.createElement('div'), // form group div
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
        fGDiv.classList = 'form-group'
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
        fGDiv.appendChild(pLbl)
        fGDiv.appendChild(pInpt)
        form.appendChild(hInpt)
        form.appendChild(fGDiv)
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
                rMdl.querySelector('.modal-body').innerHTML = xmlhttp.responseText
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
                rMdl.querySelector('.modal-body').innerHTML = xmlhttp.responseText
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
            fGDiv = document.createElement('div'), // form group
            fGDiv1 = document.createElement('div'), // form group
            vLbl = document.createElement('label'), // version label
            vInpt = document.createElement('input'), // version input
            docLbl = document.createElement('label'), // document label
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
        fGDiv.classList = 'form-group col-6'
        fGDiv1.classList = 'form-group col-6'
        vLbl.setAttribute('for', `vInpt${dLbl}`)
        vLbl.textContent = 'Verzija'
        docLbl.setAttribute('for', `docInpt${dLbl}`)
        docLbl.textContent = 'Dokument'
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
        fGDiv.appendChild(vLbl)
        fGDiv.appendChild(vInpt)
        fGDiv1.appendChild(docHInpt)
        fGDiv1.appendChild(docLbl)
        fGDiv1.appendChild(docInpt)
        rDiv.appendChild(span)
        rDiv.appendChild(fGDiv)
        rDiv.appendChild(fGDiv1)
            // append controls to scientific paper insert form
        document.getElementById('sPDocs').appendChild(rDiv)
        dLbl++
        return
    } // addDocuments

    /*
     *  modify form for scientific paper insertion or data alteration 
     *  @param frm
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
     *   asynchronous script execution for selection of scientific papers per student program attendance 
     *   @param idAttendances
     */
    function selectScientificPapers(idAttendances) {
        let xmlhttp = new XMLHttpRequest()
            // report on scientific papers selection
        xmlhttp.addEventListener('load', () => {
                // compose node tree structure
                fragment = xmlhttp.response
                    // reflect body of a fragment into modals inner HTML    
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
            frm = new FormData(this)
            // report on scientific papers selection
        xmlhttp.addEventListener('load', () => {
                // report the result
                rMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                rMdlBtn.click()
            }) // addEventListener
        xmlhttp.open('POST', '/eArchive/ScientificPapers/insert.php', true)
        xmlhttp.send(frm)
        return
    } // insertScientificPaper

    // asynchronous script execution for scientific paper data alteration 
    function updateScientificPapers(e) {
        // prevent default action of submitting scientific paper data    
        e.preventDefault()
        let xmlhttp = new XMLHttpRequest,
            frm = new FormData(this)
            // report on scientific paper update
        xmlhttp.addEventListener('load', () => {
                console.log(xmlhttp.responseText)
                    // report the result
                rMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                rMdlBtn.click()
            }) // addEventListener
        xmlhttp.open('POST', '/eArchive/ScientificPapers/update.php', true)
        xmlhttp.send(frm)
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
            frmData = new FormData(this)
            // report on the deletion
        xmlhttp.addEventListener('load', () => {
                // report the result
                rMdl.querySelector('.modal-body').textContent = xmlhttp.responseText
                rMdlBtn.click()
            }) // addEventListener
        xmlhttp.open('POST', '/eArchive/Graduations/insert.php', true)
        xmlhttp.send(frmData)
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
                    // reflect body of a fragment into modals inner HTML    
                document.getElementById('certMdl').querySelector('.modal-content').innerHTML = fragment.body.innerHTML
            }) // addEventListener
        xmlhttp.open('GET', `/eArchive/Certificates/select.php?id_attendances=${idAttendances}`, true)
        xmlhttp.responseType = 'document'
        xmlhttp.send()
        return
    } // selectCertificate
})()