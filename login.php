<?php

// script import declaration

include_once './header.php';

include_once './nav.php';

?>

<!-- Login form stylesheet -->
<link href="/eArchive/custom/css/login.css" rel="stylesheet" />

<!-- User authentication script -->
<script defer src="/eArchive/custom/js/authentication.js"></script>

<section>
    <div class="d-flex justify-content-center my-4">
        <form id="loginForm" method="POST" action="./Accounts/authentication.php" class="p-3">
            <p class="h2">Prijava</p>
            <div class="form-group">
                <label>Indeks
                    <input class="form-control" type="text" name="index" autocomplete="on" required>
                </label>
            </div>
            <div class="form-group">
                <label>Geslo
                    <input class="form-control" type="password" name="pass" autocomplete="current-password" required>
                </label>
            </div>
            <div id="loginReport"></div>
            <input id="loginButton" class="btn btn-dark" type="submit" value="Potrdi">
        </form>
    </div>
</section>

<?php

// script import declaration

include_once './footer.php';
