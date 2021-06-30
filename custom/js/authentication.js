// IIFE
(() => {
    // global variable declaration
    var frag = new DocumentFragment(), // minimal document object structure
        lgnFrm = document.getElementById('lgnFrm') // login form
    let checkAcctCred = e => {
            // prevent default action of form submit
            e.preventDefault();
            let xmlhttp = new XMLHttpRequest(),
                fData = new FormData(lgnFrm),
                lReport = document.getElementById('lRprt')
                // report result of login attempt
            xmlhttp.addEventListener('load', () => {
                    let report = JSON.parse(xmlhttp.responseText)
                    let p = document.createElement('p')
                    p.classList.add('text-warning')
                    p.classList.add('font-italic')
                    p.textContent = report.message
                    frag.appendChild(p)
                        // if report has been sent
                    if (lReport.hasChildNodes())
                        lReport.removeChild(lReport.firstChild)
                    lReport.appendChild(frag)
                        // if report contains script location
                    if (report.script.length > 0) {
                        setTimeout(() => {
                                window.location.href = report.script;
                            }, 2000) // setTimeout
                    } // if
                }) // addEventListener
            xmlhttp.open('POST', 'Accounts/authentication.php', true)
            xmlhttp.send(fData)
        } // checkAcctCred
    lgnFrm.addEventListener('submit', checkAcctCred)
        // check out account credentials and respond respectively
})()