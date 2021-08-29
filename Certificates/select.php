<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../autoload.php';

// proceed with the session
session_start();

$id_attendances = $_GET['id_attendances'];

// if an id of attendance was forwarded by URL query string 
if (isset($id_attendances)) {
    // establish a new database connection
    $DBC = new DBC();
    // try to select certificate particulars
    $certificate = $DBC->selectCertificate($id_attendances)[0];
    if ($certificate !== NULL) {
?>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-center">Certifikat</h5>
                <ul class="list-group-flush p-0">
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="font-italic"><strong>Zagovorjen</strong></span>
                        <span><?php echo (new DateTime($certificate->defended))->format('d-m-Y'); ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="font-italic"><strong>Izdan</strong></span>
                        <span><?php echo (new DateTime($certificate->getIssued()))->format('d-m-Y'); ?></span>
                    </li>
                    <li class="list-group-item text-center">
                        <a href="<?php echo "/eArchive/{$certificate->getSource()}"; ?>" target="_blank" class="btn btn-primary">Pregled</a>
                    </li>
                </ul>
                <?php
                // if the authorized is logged in
                if (isset($_SESSION['authorized'])) {
                ?>
                    <div class="d-flex justify-content-around">
                        <a href="#certUplMdl" class="card-link cert-upd-a" data-id-certificates="<?php echo $certificate->getIdCertificates(); ?>" data-defended="<?php echo $certificate->defended; ?>" data-issued="<?php echo $certificate->getIssued(); ?>" data-toggle="modal">Uredi</a>
                        <a href="#" class="card-link cert-del-a" data-id-attendances="<?php echo $id_attendances; ?>" data-source="<?php echo $certificate->getSource(); ?>">Izbriši</a>
                    </div>
                <?php
                } // if
                ?>
            </div>
        </div>
<?php
    } // if
} // if
