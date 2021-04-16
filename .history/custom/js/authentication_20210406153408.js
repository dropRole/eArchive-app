// IIFE
(() => {
    // lightweight document object
    let fragment = new DocumentFragment(),
        loginForm = document.getElementById('lFrm')
    loginForm.addEventListener('submit', checkAccountCredentials)
        // check out account credentials and respond respectively
    function checkAccountCredentials(e) {
        // prevent default action
        e.preventDefault();
        // exract new instances
        let xmlhttp = new XMLHttpRequest(),
            fData = new FormData(loginForm),
            lReport = document.getElementById('lRprt')
        xmlhttp.addEventListener('load', () => {
                console.log(xmlhttp.responseText)
                let report = JSON.parse(xmlhttp.responseText)
                let p = document.createElement('p')
                p.classList.add('text-warning')
                p.classList.add('font-italic')
                p.textContent = report.message
                fragment.appendChild(p)
                    // if report has been sent
                if (lReport.hasChildNodes())
                    lReport.removeChild(lReport.firstChild)
                lReport.appendChild(fragment)
                    // if report contains script location
                if (report.script.length > 0) {
                    setTimeout(() => {
                            window.location.href = report.script;
                        }, 2000) // setTimeout
                } // if
            }) // addEventListener
        xmlhttp.open('POST', 'Accounts/authentication.php', true)
        xmlhttp.send(fData)
    } // checkAccountCredentials
})()