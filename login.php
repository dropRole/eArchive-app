<?php

// script import declaration

include_once './header.php';

include_once './nav.php';

?>

<!-- User authentication script -->
<script defer src="/eArchive/custom/js/authentication.js"></script>

<section>
    <div class="d-flex justify-content-center my-4">
        <form id="lgnFrm" method="POST" action="./Accounts/authentication.php" class="p-3">
            <p class="h2">Prijava</p>
            <div class="form-group">
                <label>Indeks
                    <input id="indexInptEl" class="form-control" type="text" name="index" autocomplete="on" required>
                </label>
            </div>
            <div class="form-group">
                <label>Geslo
                    <input id="pаssInptEl" class="form-control" type="password" name="pass" autocomplete="current-password" required>
                </label>
            </div>
            <div id="lgnRprt"></div>
            <input id="lgnBtn" class="btn btn-dark" type="submit" value="Potrdi">
        </form>
    </div>
</section>

<!-- Login form stylesheet -->
<link href="/eArchive/custom/css/login.css" rel="stylesheet" />

<?php

// script import declaration

include_once './footer.php';
