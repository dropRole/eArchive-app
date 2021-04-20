// IIFE
(() => {
    // global variable declaration
    var fragment = new DocumentFragment(), // minimal document object structure
        sIFrm = document.getElementById('sIFrm'), // student data insert form
        rMdl = document.getElementById('rMdl'), // report modal 
        aMdl = document.getElementById('aMdl'), // account modal 
        aRBtn = document.getElementById('aRBtn'), // add residence button 
        aABtn = document.getElementById('aABtn'), // add attendance button
        aBtnLst = document.querySelectorAll('.acc-btn'), // node list of buttons for account generation
        rMdlBtn = document.getElementById('rMdlBtn'), // button to toggle the report modal
        cSlct = document.getElementById('cSlct'), // country select input 
        fSlct = document.getElementById('fSlct'), // faculty select input
        gCb = document.getElementById('gCb'), // graduation checkbox
        rLbl = 2, // residence label counter
        rIndx = 1, // residence array index 
        aLbl = 1, // attendance label counter
        aIndx = 1 // attendance array index 
    sIFrm.addEventListener('submit', insertStudent)
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
        // populate modals body with created account insert form 
    aBtnLst.forEach(element => {
            element.addEventListener('click', createAccountForm) //addEventListener
        }) // forEach
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
        xmlhttp.open('POST', '/eArchive/Accounts/insert.php', true)
        xmlhttp.send(fData)
    } // insertAccount

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
            // append created form nodes 
        fGDiv.appendChild(pLbl)
        fGDiv.appendChild(pInpt)
        form.appendChild(hInpt)
        form.appendChild(fGDiv)
        form.appendChild(sInpt)
        aMdl.querySelector('.modal-body').appendChild(form)
        return
    } // createAccountForm
})()