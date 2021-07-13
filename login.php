<?php

// script import declaration

include_once './header.php';

?>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
    <div class="container">
        <a class="navbar-brand" href="/eArchive/index.php">eArhiv</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<section id="login">
    <div class="d-flex justify-content-center">
        <form id="lgnFrm" method="POST" action="./Accounts/authentication.php">
            <p class="h1 mb-3">Prijava</p>
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

<!-- Custom core JavaScript -->
<script src="/eArchive/custom/js/authentication.js"></script>

<?php

// script import declaration

include_once './footer.php';
