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
    <div class="table-responsive">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>Objavljen</th>
                    <th>Verzija</th>
                    <th>Lokacija</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // select documents of the givne scientific paper
                foreach ($DBC->selectDocuments($id_scientific_papers) as $doc) {
                ?>
                    <tr>
                        <td><?php echo (new DateTime($doc->getPublished()))->format('d-m-Y'); ?></td>
                        <td><?php echo $doc->getVersion(); ?></td>
                        <td>
                            <a href="<?php echo "/eArchive/{$doc->getSource()}"; ?>" target="_blank"><?php echo basename($doc->getSource()); ?></a>
                        </td>
                    </tr>
                <?php
                } // foreach
                ?>
            </tbody>
        </table>
    </div>
<?php
} // if

?>