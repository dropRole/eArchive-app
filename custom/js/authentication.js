// IIFE
(() => {
    // global variable declaration
    var lgnFrm = document.getElementById('lgnFrm') // login form
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

    let checkAcctCred = e => {
            // prevent default action of form submit
            e.preventDefault();
            request(
                    '/eArchive/Accounts/authentication.php',
                    'POST',
                    'text',
                    (new FormData(lgnFrm))
                )
                .then(response => {
                    let observer = new MutationObserver(() => {
                            // if credentials are valid
                            if (response.script.length)
                                setTimeout(() => {
                                    this.location.href = 'index.php'
                                }, 2000) // setTimeout
                        }),
                        rprt = document.createElement('p')
                    observer.observe(
                        document.getElementById('lgnRprt'), {
                            attributes: false,
                            childList: true,
                            subtree: false
                        }
                    )
                    rprt.classList = 'text-warning font-italic'
                    rprt.textContent = response
                    document.getElementById('lgnRprt').innerHTML = ''
                    document.getElementById('lgnRprt').appendChild(rprt)
                })
                .catch(error => alert(error))
        } // checkAcctCred
    lgnFrm.addEventListener('submit', checkAcctCred)
        // check out account credentials and respond respectively
})()