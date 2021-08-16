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
    foreach ($DBC->selectPartakings($id_scientific_papers) as $partaker) {
    ?>
        <div class="d-flex flex-column partaker-card p-3">
            <?php
            // if partaker student has an account 
            if ($DBC->checkAcctAssignment($DBC->selectStudentsByIndex($partaker->index)[0]->id_attendances)) {
                // if partaker student has an account avatar
                if ($avatar = $DBC->hasAcctAvatar($partaker->index)) {
            ?>
                    <div class="text-center">
                        <img class="acct-avtr-md" src="<?php echo "/eArchive/{$avatar}"; ?>" alt="Avatar">
                        <div><?php echo $partaker->fullname; ?></div>
                    </div>
            <?php
                } // if
            }
            ?>
            <div class="d-flex justify-content-around text-center mt-2">
                <div>
                    <span class="font-italic"><strong>Indeks</strong></span><br>
                    <?php echo $partaker->index; ?>
                </div>
                <div>
                    <span class="font-italic"><strong>Vloga</strong></span><br>
                    <?php echo $partaker->getPart(); ?>
            </div>
            </div>
        </div>
    <?php
    } // foreach
    ?>
    </div>
<?php
} // if

?>