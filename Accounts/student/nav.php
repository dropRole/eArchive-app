<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
    <div class="container">
        <a class="navbar-brand" href="#">eArhiv</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="scientificPaperReview.php">Znanstvena dela
                        <span class="sr-only">Znanstvena dela</span>
                    </a>
                </li>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php
                        // if student has account avatar
                        if ($avatar = $DBC->hasAcctAvatar($_SESSION['index'])) {
                        ?>
                            <img id="acctAvtrRmvIcon" src="/eArchive/custom/img/removeAvatar.png" data-id-attendances="<?php echo $DBC->selectStudentsByIndex($_SESSION['index'])[0]->id_attendances; ?>" data-avatar="<?php echo $avatar; ?>">
                            <img id="usrAvtr" src="<?php echo "/eArchive/{$DBC->hasAcctAvatar($_SESSION['index'])}"; ?>">
                        <?php
                        } else {
                        ?>
                            <img id="defAvtr" src="/eArchive/custom/img/defaultAvatar.png">
                        <?php
                        }
                        ?>
                    </button>
                    <div class="dropdown-menu">
                        <?php
                        // if student doesn't have account avatar
                        if ($DBC->hasAcctAvatar($_SESSION['index']) == NULL) {
                        ?>
                            <a class="dropdown-item" href="#acctAvtrUpldMdl" data-toggle="modal">Avatar</a>
                        <?php
                        }
                        ?>
                        <a class="dropdown-item" href="../logout.php">Odjava</a>
                    </div>
                </div>
            </ul>
        </div>
    </div>
</nav>
<!-- Custom stylesheet for navigation bar -->
<link rel="stylesheet" href="/eArchive/custom/css/nav.css">