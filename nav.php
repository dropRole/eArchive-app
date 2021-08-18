<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
    <div class="container">
        <a class="navbar-brand" href="/eArchive/index.php">
            <img src="/eArchive/custom/img/eArchive.png">&nbsp;eArhiv
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="d-lg-flex align-items-lg-center navbar-nav ml-auto">
                <?php
                // if authorized has logged in
                if (isset($_SESSION['authorized'])) {
                ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="/eArchive/Accounts/authorized/studtEvidence.php">Evidenca študentov
                            <span class="sr-only">Evidenca študentov</span>
                        </a>
                    </li>
                <?php
                } // if
                // if student has logged in
                if (isset($_SESSION['index'])) {
                ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="/eArchive/Accounts/student/sciPapEvidence.php">Znanstvena dela
                            <span class="sr-only">Znanstvena dela</span>
                        </a>
                    </li>
                <?php
                } // if
                // if authorized or student has logged 
                if (isset($_SESSION['authorized']) || isset($_SESSION['index'])) {
                ?>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php
                            // if student has logged in
                            if (isset($_SESSION['index'])) {
                                // if student has account avatar
                                if ($avatar = $DBC->hasAcctAvatar($_SESSION['index'])) {
                            ?>
                                    <img id="avtrRmvSign" src="/eArchive/custom/img/removeAvatar.png" data-id-attendances="<?php echo $DBC->selectStudentsByIndex($_SESSION['index'])[0]->id_attendances; ?>" data-avatar="<?php echo $avatar; ?>">
                                    <img id="usrAvtr" src="<?php echo "/eArchive/{$DBC->hasAcctAvatar($_SESSION['index'])}"; ?>">
                                <?php
                                } else {
                                ?>
                                    <img id="defAvtr" src="/eArchive/custom/img/defaultAvatar.png">
                            <?php
                                } // else
                            } // if
                            // if authorized has logged in 
                            else if (isset($_SESSION['authorized']))
                                echo 'Račun';
                            ?>
                        </button>
                        <div class="dropdown-menu">
                            <?php
                            // if student has logged in
                            if (isset($_SESSION['index'])) {
                                // if student doesn't have account avatar
                                if ($DBC->hasAcctAvatar($_SESSION['index']) == NULL) {
                            ?>
                                    <a class="dropdown-item" href="#acctAvtrUpldMdl" data-toggle="modal">Avatar</a>
                            <?php
                                } // if
                            } // if
                            ?>
                            <a class="dropdown-item" href="/eArchive/Accounts/logout.php">Odjava</a>
                        </div>
                    </div>
                <?php
                } // if
                else {
                ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="./login.php">
                        <img src="/eArchive/custom/img/login.png" alt="Prijava" class="login mr-1">Prijava
                            <span class="sr-only">Prijava</span>
                        </a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </div>
</nav>