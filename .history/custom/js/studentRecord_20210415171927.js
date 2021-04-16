// IIFE
(() => {
    // global variable declaration
    var fragment = new DocumentFragment(), // minimal document object structure
        iFrm = document.getElementById('iFrm'),
        addBtn = document.getElementById('addSojourn'),
        addBtn1 = document.getElementById('addAttendance'),
        cSlct = document.getElementById('cSlct'),
        cSlct1 = document.getElementById('cSlct1'),
        fSlct = document.getElementById('fSlct'),
        gCb = document.getElementById('gCb'),
        rLblCntr = 2,
        sIndx = 1,
        aLblCntr = 1,
        aIndx = 1
    iFrm.addEventListener('submit', insertStudent)
    addBtn.addEventListener('click', addSojourn)
    addBtn1.addEventListener('click', addAttendance)
        // propagate postal codes by country selection
    cSlct.addEventListener('change', e => {
            propagateSelectElement(document.getElementById('pCSlct'), `/eArchive/PostalCodes/select.php?id_countries=${e.target.selectedOptions[0].value}`)
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
        p.textContent = `${rLblCntr}. začasno bivališče`
        span.style.float = 'right'
        span.style.transform = 'scale(1.2)'
        span.style.cursor = 'pointer'
            // remove selected residence section
        span.addEventListener('click', () => {
                div.removeChild(rDiv)
                rLblCntr--
            }) // addEventListener
        span.innerHTML = '&#10007;'
        fGDiv.className = 'form-group col-4'
        fGDiv1.className = 'form-group col-4'
        fGDiv2.className = 'form-group col-4'
        cLbl.setAttribute('for', `cSlct${rLblCntr}`)
        cLbl.textContent = 'Država'
        pCLbl.setAttribute('for', `pCSlct${rLblCntr}`)
        pCLbl.textContent = 'Kraj'
        aLbl.setAttribute('for', `aInpt${rLblCntr}`)
        aLbl.textContent = 'Naslov'
        aInpt.id = `aInpt${rLblCntr}`
        cSlct.id = `cSlct${rLblCntr}`
        cSlct.classList = 'form-control'
            // propagate postal codes by country selection
        cSlct.addEventListener('change', e => {
                propagateSelectElement(pCSlct, `/eArchive/PostalCodes/select.php?id_countries=${e.target.selectedOptions[0].value}`)
            }) // addEventListener
        pCSlct.id = `pCSlct${rLblCntr}`
        pCSlct.classList = 'form-control'
        pCSlct.name = `residences[${sIndx}][id_postal_codes]`
        pCSlct.required = true
        aInpt.classList = 'form-control'
        aInpt.type = 'text'
        aInpt.name = `residences[${sIndx}][address]`
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
        sIndx++
        rLblCntr++
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
            fInpt.name = 'certificate[]'
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
        p.textContent = `${aLblCntr + 1}. študijski program`
        span.style.float = 'right'
        span.style.transform = 'scale(1.2)'
        span.style.cursor = 'pointer'
        span.innerHTML = '&#10007'
            // remove selected attendance section
        span.addEventListener('click', () => {
                div.removeChild(rDiv)
                aLblCntr--
            }) // addEventListener
        span.innerHTML = '&#10007;'
        rDiv.className = 'row'
        fGDiv.className = 'form-group col-6'
        fGDiv1.className = 'form-group col-6'
        fGDiv2.className = 'form-group col-4'
        fGDiv3.className = 'form-group col-4'
        fGDiv4.className = 'd-flex align-items-center justify-content-center form-group col-4'
        fLbl.setAttribute('for', `fSlct${aLblCntr}`)
        fLbl.textContent = 'Fakulteta'
        pLbl.textContent = 'Program'
        pLbl.setAttribute('for', `pSlct${aLblCntr}`)
        eLbl.textContent = 'Vpisan'
        eLbl.setAttribute('for', `enInpt${aLblCntr}`)
        iLbl.textContent = 'Indeks'
        iLbl.setAttribute('for', `iInpt${aLblCntr}`)
        gLbl.textContent = 'Diplomiral'
        gLbl.setAttribute('for', `gCb${aLblCntr}`)
        gLbl.className = 'mt-2'
        fSlct.className = 'form-control'
        fSlct.id = `fSlct${aLblCntr}`
        fSlct.name = `attendances[${aIndx}][id_faculties]`
        fSlct.required = true
        pSlct.className = 'form-control'
        pSlct.id = `pSlct${aLblCntr}`
        pSlct.name = `attendances[${aIndx}][id_programs]`
        pSlct.required = true
        eInpt.className = 'form-control'
        eInpt.id = `enInpt${aLblCntr}`
        eInpt.type = 'date'
        eInpt.name = `attendances[${aIndx}][enrolled]`
        eInpt.required = true
        iInpt.className = 'form-control'
        iInpt.id = `iInpt${aLblCntr}`
        iInpt.type = 'text'
        iInpt.name = `attendances[${aIndx}][index]`
        iInpt.required = true
        gCb.id = `gCb${aLblCntr}`
        gCb.type = 'checkbox'
        gCb.classList = 'mr-2'
        gCb.setAttribute('data-index', aIndx)
        gCb.setAttribute('data-counter', aLblCntr)
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
        aLblCntr++
    } // addAttendance 

    // pass and isert student data
    function insertStudent(e) {
        // prevent default action by submitting student data insert form
        e.preventDefault()
        let xmlhttp = new XMLHttpRequest,
            fData = new FormData(iFrm)
            // report on student data insertion
        xmlhttp.addEventListener('load', () => {
                console.log(xmlhttp.responseText)
            }) // addEventListener
        xmlhttp.open('POST', '/eArchive/Students/insert.php', true)
        xmlhttp.send(fData)
    } // insertStudent
})()