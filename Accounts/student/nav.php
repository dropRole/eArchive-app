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
                    <a class="nav-link" href="#">Domov
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="scientificPaperReview.php">Znanstvena dela
                        <span class="sr-only">Znanstvena dela</span>
                    </a>
                </li>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="<?php echo $DBC->hasAcctAvatar($_SESSION['index']) ? $DBC->hasAcctAvatar($_SESSION['index']) : '/eArchive/custom/img/user.png'; ?>">
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">Avatar</a>
                        <a class="dropdown-item" href="../logout.php">Odjava</a>
                    </div>
                </div>
            </ul>
        </div>
    </div>
</nav>