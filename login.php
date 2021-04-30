<?php

// script import declaration

include_once './header.php';

?>

<section id="login">
    <div class="d-flex justify-content-center">
        <form id="lFrm" method="POST" action="./Accounts/authentication.php">
            <p class="h1 mb-3">Prijava</p>
            <div class="form-group">
                <label for="iInpt">Indeks</label>
                <input id="iInpt" class="form-control" type="text" name="index" autocomplete="on" required>
            </div>
            <div class="form-group">
                <label for="pInpt">Geslo</label>
                <input id="pInpt" class="form-control" type="password" name="pass" autocomplete="current-password" required>
            </div>
            <div id="lRprt"></div>
            <input id="lBtn" class="btn btn-dark" type="submit" value="Potrdi">
        </form>
    </div>
</section>

<!-- Custom core JavaScript -->
<script src="/eArchive/custom/js/authentication.js"></script>

<?php

// script import declaration

include_once './footer.php';
