// IIFE
(() => {
    // lightweight document object 
    let fragment = new DocumentFragment(),
        iFrm = document.getElementById('iFrm'),
        addBtn = document.getElementById('addSojourn'),
        addBtn1 = document.getElementById('addAttendance'),
        cSlct = document.getElementById('cSlct'),
        fSlct = document.getElementById('fSlct'),
        i = 1,
        j = 2
    iFrm.addEventListener('submit', insertStudent)
    addBtn.addEventListener('click', addSojourn)
    addBtn1.addEventListener('click', addAttendance)
    cSlct.addEventListener('change', e => {
            propagateSelectElement(document.getElementById('mSlct'), '/eArchive/PostalCodes/select.php?id_countries=' + e.target.selectedOptions[0].value)
        }) // addEventListener
    fSlct.addEventListener('change', e => {
            propagateSelectElement(pSlct, '/eArchive/Programs/select.php?id_faculties=' + e.target.selectedOptions[0].value)
        }) // addEventListener
        // create and append additional sojourn controls 
    function addSojourn() {
        // create form controls 
        let xmlhttp = new XMLHttpRequest(),
            div = document.getElementById('sojourns'),
            rDiv = document.createElement('div'),
            span = document.createElement('span'),
            fGDiv = document.createElement('div'),
            fGDiv1 = document.createElement('div'),
            fGDiv2 = document.createElement('div'),
            aLbl = document.createElement('label'),
            mLbl = document.createElement('label'),
            cLbl = document.createElement('label'),
            aInpt = document.createElement('input'),
            mSlct1 = document.createElement('select'),
            cSlct1 = document.createElement('select')
        rDiv.className = 'row'
        rDiv.style.position = 'relative'
        span.style.position = 'absolute'
        span.style.right = '15px'
        span.style.top = 0
        span.style.transform = 'scale(1.2)'
        span.style.cursor = 'pointer'
        span.style.zIndex = 1
        span.addEventListener('click', () => {
                div.removeChild(rDiv)
                i--
            }) // addEventListener
        span.innerHTML = '&#10007;'
        fGDiv.className = 'form-group col-4'
        fGDiv1.className = 'form-group col-4'
        fGDiv2.className = 'form-group col-4'
        aLbl.setAttribute('for', 'aInpt' + i)
        aLbl.textContent = (i + 1) + '. naslov'
        mLbl.setAttribute('for', 'mSlct' + i)
        mLbl.textContent = 'Kraj'
        cLbl.setAttribute('for', 'cSlct' + i)
        cLbl.textContent = 'Država'
        aInpt.id = 'aInpt' + i
        aInpt.classList = 'form-control'
        aInpt.type = 'text'
        aInpt.name = 'addresses[]'
        aInpt.required = true
        mSlct1.id = 'mSlct' + i
        mSlct1.classList = 'form-control'
        mSlct1.name = 'municipalities[]'
        mSlct1.required = true
        cSlct1.id = 'cSlct' + i
        cSlct1.classList = 'form-control'
        cSlct1.name = 'countries[]'
        cSlct1.required = true
            // attach event listener
        cSlct1.addEventListener('change', e => {
                propagateSelectElement(mSlct1, '/eArchive/PostalCodes/select.php?id_countries=' + e.target.selectedOptions[0].value)
            }) // addEventListener
            // instance of XMLHTTPRequest object
        xmlhttp.addEventListener('load', () => {
                fragment = xmlhttp.response
                    // traverse through nodes
                fragment.body.querySelectorAll('option').forEach(element => {
                        cSlct1.add(element)
                    }) // forEach
                    // append controls to a form sojourn section
                fGDiv.appendChild(aLbl)
                fGDiv.appendChild(aInpt)
                fGDiv1.appendChild(mLbl)
                fGDiv1.appendChild(mSlct1)
                fGDiv2.appendChild(cLbl)
                fGDiv2.appendChild(cSlct1)
                rDiv.appendChild(span)
                rDiv.appendChild(fGDiv)
                rDiv.appendChild(fGDiv1)
                rDiv.appendChild(fGDiv2)
                div.appendChild(rDiv)
            }) // addEventListener
        xmlhttp.responseType = 'document'
        xmlhttp.open('GET', '/eArchive/Countries/select.php')
        xmlhttp.send()
        i++
    } // addSojourn

    // propagate select control with suitable options
    function propagateSelectElement(mSlct, script) {
        // create new instance
        let xmlhttp = new XMLHttpRequest()
        xmlhttp.addEventListener('load', () => {
                fragment = xmlhttp.response
                    // remove options while on disposal
                while (mSlct.options.length) {
                    mSlct.remove(0)
                } // while
                // traverse through nodes 
                fragment.body.querySelectorAll('option').forEach(element => {
                        mSlct.add(element)
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
            // create form controls 
            let fGDiv = document.createElement('div'),
                fGDiv1 = document.createElement('div'),
                fLbl = document.createElement('label'),
                iLbl = document.createElement('label'),
                fInpt = document.createElement('input'),
                iInpt = document.createElement('input')
            fGDiv.className = 'form-group col-4'
            fGDiv1.className = 'form-group col-4'
            fLbl.textContent = 'Certifikat'
            fLbl.setAttribute('for', 'fInpt' + j)
            iLbl.textContent = 'Zagovor'
            iLbl.setAttribute('for', 'iInpt' + j)
            fInpt.id = 'fInpt' + j
            fInpt.type = 'file'
            fInpt.required = true
            iInpt.id = 'iInpt' + j
            iInpt.className = 'form-control'
            iInpt.type = 'date'
                // append graduation form controls to a particular attendance section
            fGDiv.appendChild(fLbl)
            fGDiv.appendChild(fInpt)
            fGDiv1.appendChild(iLbl)
            fGDiv1.appendChild(iInpt)
            pNode.appendChild(fGDiv)
            pNode.appendChild(fGDiv1)
        } // if
        else {
            pNode.removeChild(pNode.lastChild)
            pNode.removeChild(pNode.lastChild)
        } // else
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
            fLbl = document.createElement('label'),
            pLbl = document.createElement('label'),
            fSlct1 = document.createElement('select'),
            pSlct = document.createElement('select'),
            gLbl = document.createElement('label'),
            gCb = document.createElement('input')
            // initial propagation
        propagateSelectElement(fSlct1, '/eArchive/Faculties/select.php')
        setTimeout(() => {
                propagateSelectElement(pSlct, '/eArchive/Programs/select.php?id_faculties=' + fSlct1.selectedOptions[0].value)
            }, 500) // setTimeout
        fSlct1.addEventListener('change', e => {
                propagateSelectElement(pSlct, '/eArchive/Programs/select.php?id_faculties=' + e.target.selectedOptions[0].value)
            }) // addEventListener
        gCb.addEventListener('change', e => {
                addGraduation(e, rDiv)
            }) // addEventListener
        p.className = 'col-12 h6'
        p.textContent = j + '. študijski program'
        span.style.float = 'right'
        span.style.transform = 'scale(1.2)'
        span.style.cursor = 'pointer'
        span.innerHTML = '&#10007'
        span.addEventListener('click', () => {
                div.removeChild(rDiv)
                j--
            }) // addEventListener
        span.innerHTML = '&#10007;'
        rDiv.className = 'row'
        fGDiv.className = 'form-group col-4'
        fGDiv1.className = 'form-group col-4'
        fGDiv2.className = 'd-flex align-items-center justify-content-center form-group col-4'
        fLbl.setAttribute('for', 'fSlct' + j)
        fLbl.textContent = 'Fakulteta'
        pLbl.textContent = 'Program'
        pLbl.setAttribute('for', 'pSlct' + j)
        gLbl.textContent = 'Diplomiral'
        gLbl.setAttribute('for', 'gCb' + j)
        gLbl.className = 'mt-2'
        fSlct1.className = 'form-control'
        fSlct1.id = 'fSlct' + j
        fSlct1.name = 'faculties[]'
        fSlct1.required = true
        pSlct.className = 'form-control'
        pSlct.id = 'pSlct' + j
        pSlct.name = 'programs[]'
        pSlct.required = true
        gCb.id = 'gCb' + j
        gCb.type = 'checkbox'
        gCb.classList = 'mr-2'
            // append controls to a form attendances section
        fGDiv.appendChild(fLbl)
        fGDiv.appendChild(fSlct1)
        fGDiv1.appendChild(pLbl)
        fGDiv1.appendChild(pSlct)
        fGDiv2.appendChild(gCb)
        fGDiv2.appendChild(gLbl)
        p.appendChild(span)
        rDiv.appendChild(p)
        rDiv.appendChild(fGDiv)
        rDiv.appendChild(fGDiv1)
        rDiv.appendChild(fGDiv2)
        div.appendChild(rDiv)
        j++
    } // addAttendance 

    // pass and isert student data
    function insertStudent(e) {
        // prevent default event response
        e.preventDefault()
            // create new instances of implemented interaces
        let xmlhttp = new XMLHttpRequest,
            fData = new FormData(iFrm)
        xmlhttp.addEventListener('load', () => {

            }) // addEventListener
        xmlhttp.responseText = 'document'
        xmlhttp.open('POST', 'Accounts/authentication.php', true)
        xmlhttp.send(fData)
    } // insertStudent
})()