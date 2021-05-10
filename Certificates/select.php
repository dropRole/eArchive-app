<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once '../Certificates/Certificates.php';

// proceed with the session
session_start();

$id_attendances = $_GET['id_attendances'];

// if an id of attendance was forwarded by URL query string 
if (isset($id_attendances)) {
    // establish a new database connection
    $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    // try to select certificate particulars
    $certificate = $DBC->selectCertificate($id_attendances)[0];
    if ($certificate !== NULL) {
?>
        <div class="card col-12">
            <div class="card-body">
                <h5 class="card-title">Certifikat</h5>
                <h6 class="card-subtitle mb-2 text-muted">Izdan: <?php echo $certificate->getIssued(); ?></h6>
                <p class="card-text"><a href="<?php echo "../../{$certificate->getSource()}"; ?>" target="_blank"><?php echo basename($certificate->getSource()); ?></a></p>
                <a href="#" class="card-link cert-del-a" data-att-id="<?php echo $id_attendances; ?>" data-source="<?php echo $certificate->getSource(); ?>">Izbriši</a>
            </div>
        </div>
<?php
    } // if
} // if
