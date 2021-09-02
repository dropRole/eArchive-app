<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../autoload.php';

$id_scientific_papers = $_GET['id_scientific_papers'];

// if id of a scientific paper was successfully passed via URL query string
if (isset($id_scientific_papers)) {
    // retrieve an instance of PDO holding database server connection
    $DBC = new DBC();
?>
    <div class="row p-2">
        <?php
        // select documents of the givne scientific paper
        foreach ($DBC->selectDocuments($id_scientific_papers) as $doc) {
        ?>
            <div class="card p-0 col-lg-6 col-12">
                <div class="card-header">
                    Verzija <?php echo $doc->getVersion(); ?>
                </div>
                <div class="card-body">
                    <p class="card-text">Dokument je objavljen <span class="font-italic"><?php echo (new DateTime($doc->getPublished()))->format('d-m-Y'); ?></span>.</p>
                    <a href="<?php echo "/eArchive/{$doc->getSource()}"; ?>" class="btn btn-primary" target="_blank">Pregled</a>
                </div>
            </div>
        <?php
        } // foreach
        ?>
    </div>
<?php
} // if

?>