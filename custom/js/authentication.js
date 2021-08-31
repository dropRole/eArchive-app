// IIFE
(() => {
    // global variable declaration
    var loginForm = document.getElementById('loginForm') // login form
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

    let checkAccountCredentials = e => {
            // prevent default action of form submit
            e.preventDefault();
            request(
                    '/eArchive/Accounts/authentication.php',
                    'POST',
                    'json',
                    (new FormData(loginForm))
                )
                .then(response => {
                    let observer = new MutationObserver(() => {
                            // if credentials are valid
                            if (response.logged)
                                setTimeout(() => {
                                    this.location.href = 'index.php'
                                }, 2000) // setTimeout
                        }),
                        report = document.createElement('p')
                    observer.observe(
                        document.getElementById('loginReport'), {
                            attributes: false,
                            childList: true,
                            subtree: false
                        }
                    )
                    report.classList = 'text-info font-italic'
                    report.textContent = response.message
                    document.getElementById('loginReport').innerHTML = ''
                    document.getElementById('loginReport').appendChild(report)
                })
                .catch(error => alert(error))
        } // checkAccountCredentials

    loginForm.addEventListener('submit', checkAccountCredentials)
        // check out account credentials and respond respectively
})()