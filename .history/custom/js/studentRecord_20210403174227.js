// IIFE
(() => {
    // lightweight document object 
    let fragment = new DocumentFragment()
    addBtn = document.getElementById('addSojourn'),
        addBtn1 = document.getElementById('addAttendance'),
        cSlct = document.getElementById('cSlct'),
        i = 1, j = 1
    addBtn.addEventListener('click', addSojourn)
    addBtn1.addEventListener('click', addAttendance)
    cSlct.addEventListener('change', e => {
            propagateSelectElement(e, document.getElementById('mSlct'), '/eArchive/PostalCodes/selectPostalCodes.php')
        }) // addEventListener

    // create and append additional sojourn controls 
    function addSojourn() {
        // create form controls 
        let xmlhttp = new XMLHttpRequest(),
            div = document.getElementById('sojourns'),
            div1 = document.createElement('div'),
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
        div1.className = 'row'
        div1.style.position = 'relative'
        span.style.position = 'absolute'
        span.style.right = '15px'
        span.style.top = 0
        span.style.transform = 'scale(1.2)'
        span.style.cursor = 'pointer'
        span.style.zIndex = 1
        span.addEventListener('click', () => {
                div.removeChild(div1)
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
                propagateSelectElement(e, document.getElementById(mSlct1.id), '/eArchive/PostalCodes/selectPostalCodes.php')
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
                div1.appendChild(span)
                div1.appendChild(fGDiv)
                div1.appendChild(fGDiv1)
                div1.appendChild(fGDiv2)
                div.appendChild(div1)
            }) // addEventListener
        xmlhttp.responseType = 'document'
        xmlhttp.open('GET', '/eArchive/Countries/selectCountries.php')
        xmlhttp.send()
        i++
    } // addSojourn

    // propagate select control with suitable options
    function propagateSelectElement(e, mSlct, script) {
        // create new instance
        let xmlhttp = new XMLHttpRequest()
            // if reference to an event exists
        if (e != null) {
            let id_countries = e.target.selectedOptions[0].value
            xmlhttp.addEventListener('load', () => {
                    fragment = xmlhttp.response
                        // while there are options 
                    while (mSlct.options.length) {
                        mSlct.remove(0)
                    } // while
                    // traverse througho nodes 
                    fragment.body.querySelectorAll('option').forEach(element => {
                            mSlct.add(element)
                        }) // forEach
                }) // addEventListener
            xmlhttp.open('GET', script + '?id_countries=' + id_countries)
        } else {
            xmlhttp.addEventListener('load', () => {
                    fragment = xmlhttp.response
                        // traverse througho nodes 
                    fragment.body.querySelectorAll('option').forEach(element => {
                            mSlct.add(element)
                        }) // forEach
                }) // addEventListener
            xmlhttp.open('GET', script)
        } // else
        xmlhttp.responseType = 'document'
        xmlhttp.send()
    } // propagateSelectElement

    // create and append attendance form controls 
    function addAttendance() {
        // create form controls
        let div = document.getElementById('attendances'),
            p = document.createElement('p'),
            div1 = document.createElement('div'),
            fGDiv = document.createElement('div'),
            fGDiv1 = document.createElement('div'),
            fGDiv2 = document.createElement('div'),
            uLbl = document.createElement('label'),
            pLbl = document.createElement('label'),
            dLbl = document.createElement('label'),
            uSlct = document.createElement('select'),
            pSlct = document.createElement('select')
        propagateSelectElement(null, uSlct, '/eArchive/Universities/selectUniversities.php')
        uSlct.addEventListener('change', (e) => {
                propagateSelectElement(e, pSlct, '/eArchive/Programs/selectPrograms.php')
            }) // addEventListener
        dInpt = document.createElement('input')
        p.className = 'h6'
        p.textContent = j + '. študijski program'
        div1.className = 'row'
        fGDiv.className = 'form-group col-4'
        fGDiv1.className = 'form-group col-4'
        fGDiv2.className = 'form-group col-4'
        uLbl.setAttribute('for', 'uSlct' + j)
        uLbl.textContent = 'Univerza'
        pLbl.textContent = 'Program'
        pLbl.setAttribute('for', 'pSlct' + j)
        dLbl.textContent = 'Stopnja'
        dLbl.setAttribute('for', 'pInpt' + j)
        uSlct.className = 'form-control'
        uSlct.id = 'uSlct' + j
        uSlct.name = 'universities[]'
        uSlct.required = true
        pSlct.className = 'form-control'
        pSlct.id = 'pSlct' + j
        pSlct.name = 'programs[]'
        pSlct.required = true
        dInpt.className = 'form-control'
        dInpt.id = 'dInpt' + j
        dInpt.disabled = true
            // append controls to a form attendances section
        fGDiv.appendChild(uLbl)
        fGDiv.appendChild(uSlct)
        fGDiv1.appendChild(pLbl)
        fGDiv1.appendChild(pSlct)
        fGDiv2.appendChild(dLbl)
        fGDiv2.appendChild(dInpt)
        div1.appendChild(fGDiv)
        div1.appendChild(fGDiv1)
        div1.appendChild(fGDiv2)
        div.appendChild(p)
        div.appendChild(div1)
        j++
    } // addAttendance
})()