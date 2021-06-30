// IIFE
(() => {
    // global variable declaration
    var frag = new DocumentFragment(), // minimal document object structure
        lgnFrm = document.getElementById('lgnFrm') // login form

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
            let xmlhttp = new XMLHttpRequest(),
                frmData = new FormData(lgnFrm),
                lgnRprt = document.getElementById('lgnRprt')
                // report result of login attempt
            xmlhttp.addEventListener('load', () => {
                    let report = JSON.parse(xmlhttp.responseText)
                    let p = document.createElement('p')
                    p.classList.add('text-warning')
                    p.classList.add('font-italic')
                    p.textContent = report.message
                    frag.appendChild(p)
                        // if report has been sent
                    if (lgnRprt.hasChildNodes())
                        lgnRprt.removeChild(lgnRprt.firstChild)
                    lgnRprt.appendChild(frag)
                        // if report contains script location
                    if (report.script.length > 0) {
                        setTimeout(() => {
                                window.location.href = report.script;
                            }, 2000) // setTimeout
                    } // if
                }) // addEventListener
            xmlhttp.open('POST', 'Accounts/authentication.php', true)
            xmlhttp.send(frmData)
        } // checkAcctCred
    lgnFrm.addEventListener('submit', checkAcctCred)
        // check out account credentials and respond respectively
})()