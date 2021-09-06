<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../autoload.php';

$id_scientific_papers = $_GET['id_scientific_papers'];

// if id of record was interpolated into URL query string 
if (isset($id_scientific_papers)) {
    // instantiate a PDO object to retrieve database connection  
    $DBC = new DBC();
    // select partakers
?>
    <?php
    foreach ($DBC->selectPartakers($id_scientific_papers) as $partaker) {
    ?>
        <div class="d-flex flex-column partaker-card my-2 p-3">
            <?php
            // if partaker student has an account 
            if ($DBC->assignedWithAccount($DBC->selectStudentsByIndex($partaker->index)[0]->id_attendances)) {
                // if partaker student has an account avatar
                if ($avatar = $DBC->hasAccountAvatar($partaker->index)) {
            ?>
                    <div class="text-center">
                        <img class="acct-avtr-md" src="<?php echo "/eArchive/{$avatar}"; ?>" alt="Avatar">
                    </div>
            <?php
                } // if
            }
            ?>
            <div class="text-center">
                <strong><?php echo $partaker->fullname; ?></strong>
            </div>
            <div class="d-flex justify-content-around text-center mt-2">
                <div>
                    <span class="font-italic text-muted">Indeks</span><br>
                    <?php echo $partaker->index; ?>
                </div>
                <div>
                    <span class="font-italic text-muted">Vloga</span><br>
                    <?php echo $partaker->getPart(); ?>
                </div>
            </div>
        </div>
    <?php   
    } // foreach
} // if

?>