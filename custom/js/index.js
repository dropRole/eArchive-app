// IIFE
(() => {
    // globally encompassed variable declaration
    var frag = new DocumentFragment(), // minimal passive document object 
        search = document.getElementById('search'), // input element for search of scientific papers
        drpDwnItms = document.getElementsByClassName('dropdown-item') // dropdown items for denoting type of search

    search.addEventListener(
            'input',
            e => request(
                `/eArchive/ScientificPapers/filter.php?${e.target.dataset.criterion}=${e.target.value}`,
                'GET',
                'document'
            )
            .then(response => {
                // comprise a node tree structure
                frag = response
                    // replace nodes of the active with the passive document nodes 
                document.body.querySelector('table > tbody').replaceWith(frag.body.querySelector('table > tbody'))
                    // enable tooltips on images
                $('[data-toggle="tooltip"]').tooltip()
            })
            .then(() => listenScientificPaperEvidenceTable(document.querySelector('table')))
            .catch(error => alert(error))
        ) // addEventListener

    Array.from(drpDwnItms, item => {
            item.addEventListener(
                    'click',
                    () => {
                        search.dataset.criterion = item.dataset.criterion
                        search.placeholder = item.dataset.placeholder
                    }
                ) // addEventListener
        }) // from 

    /* 
     *   listen to elements of the scientific paper evidence table
     *   @param HTMLTableElement tbl   
     */
    let listenScientificPaperEvidenceTable = table => {
            // if superscript elements for partaker view exist
            if (table.getElementsByClassName('par-sel-a'))
                Array.from(
                    table.getElementsByClassName('par-sel-a'),
                    sup => {
                        sup.addEventListener(
                                'click',
                                () => request(
                                    `/eArchive/Partakings/select.php?id_scientific_papers=${sup.dataset.idScientificPapers}`,
                                    'GET',
                                    'document'
                                )
                                .then(response => exposeResp(response, document.getElementById('partSelMdl')))
                                .catch(error => alert(error))
                            ) // addEventListener
                    }) // from
                // if anchor elements for document view exist
            if (table.getElementsByClassName('doc-sel-img'))
                Array.from(
                    table.getElementsByClassName('doc-sel-img'),
                    image => {
                        image.addEventListener(
                            'click',
                            () => request(
                                `/eArchive/Documents/select.php?id_scientific_papers=${image.dataset.idScientificPapers}`,
                                'GET',
                                'document'
                            )
                            .then(response => exposeResp(response, document.getElementById('docSelMdl')))
                            .catch(error => alert(error))
                        )
                    }) // from
                // if anchor elements for graduation certificate insight exist
            if (table.getElementsByClassName('cert-sel-img'))
                Array.from(
                    table.getElementsByClassName('cert-sel-img'),
                    image => {
                        image.addEventListener(
                            'click',
                            () => request(
                                `/eArchive/Certificates/select.php?id_attendances=${image.dataset.idAttendances}`,
                                'GET',
                                'document'
                            )
                            .then(response => exposeResp(response, document.getElementById('certSelMdl')))
                            .catch(error => alert(error))
                        )
                    }
                ) // from
                // if anchor elements for student particulars view exist
            if (table.getElementsByClassName('stu-sel-a'))
                Array.from(
                    table.getElementsByClassName('stu-sel-a'),
                    anchor => {
                        anchor.addEventListener(
                            'click',
                            () => request(
                                `/eArchive/Attendances/select.php?id_attendances=${anchor.dataset.idAttendances}`,
                                'GET',
                                'document'
                            )
                            .then(response => exposeResp(response, document.getElementById('stuSelMdl')))
                            .catch(error => alert(error))
                        )
                    }
                ) // from
                // if anchor elements for scientific paper mentor view exist
            if (table.getElementsByClassName('men-sel-a'))
                Array.from(
                    table.getElementsByClassName('men-sel-a'),
                    anchor => {
                        anchor.addEventListener(
                            'click',
                            () => request(
                                `/eArchive/Mentorings/select.php?id_scientific_papers=${anchor.dataset.idScientificPapers}`,
                                'GET',
                                'document'
                            )
                            .then(response => exposeResp(response, document.getElementById('mentSelMdl')))
                            .catch(error => alert(error))
                        )
                    }
                ) // from
        } // listenSciPapEvidTbl

    listenScientificPaperEvidenceTable(document.querySelector('table'))

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

    /* 
     *  expose the response of the sent request through the given modal 
     *  @param Document response
     *  @param HTMLDivElement modal
     */
    let exposeResp = (response, modal) => {
            // comprise a node tree structure
            frag = response
                // replace nodes of the active with the passive document nodes 
            modal.querySelector('div.modal-body').innerHTML = frag.body.innerHTML
        } // exposeResp

    // enable tooltip toggle on elements
    $('[data-toggle="tooltip"]').tooltip()
})()