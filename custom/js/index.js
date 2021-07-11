// IIFE
(() => {
    // globally encompassed variable declaration
    var frag = new DocumentFragment(), // minimal passive document object 
        searchInptEl = document.getElementById('searchInptEl'), // input element for search for scientific papers
        drpDwnItms = document.getElementsByClassName('dropdown-item') // dropdown items for denoting type of search

    searchInptEl.addEventListener(
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
                document.body.querySelector('div#sciPapSrchRslt').replaceWith(frag.body.querySelector('div#sciPapSrchRslt'))
            })
        ) // addEventListener

    Array.from(drpDwnItms, item => {
            item.addEventListener(
                    'click',
                    () => {
                        searchInptEl.dataset.criterion = item.dataset.criterion
                        searchInptEl.placeholder = item.dataset.placeholder
                    }
                ) // addEventListener
        }) // from 

    /* 
     *   listen to elements of the scientific paper evidence table
     *   @param HTMLTableElement tbl   
     */
    let listenSciPapEvidTbl = tbl => {
            // if sup elements for partaker view exist
            if (tbl.getElementsByClassName('par-vw-a'))
                Array.from(tbl.getElementsByClassName('par-vw-a'), sup => {
                    sup.addEventListener(
                            'click',
                            () => {
                                request(
                                        `/eArchive/Partakings/select.php?id_scientific_papers=${sup.dataset.idScientificPapers}`,
                                        'GET',
                                        'document'
                                    )
                                    .then(response => {
                                        // comprise a node tree structure
                                        frag = response
                                            // replace nodes of the active with the passive document nodes 
                                        document.body.querySelector('div#sciPapPrtViewMdl div.modal-content').innerHTML = frag.body.innerHTML
                                    })
                                    .catch(error => alert(error))
                            }
                        ) // addEventListener
                }) // from 
        } // listenSciPapEvidTbl

    listenSciPapEvidTbl(document.querySelector('table'))

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
})()