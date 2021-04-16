<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once '../Offerings/Offerings.php';

$id_universities = $_GET['id_universities'];
$id_programs = $_GET['id_programs'];

// if ids of university and program are successfully passed 
if (isset($id_universities, $id_programs)) {
    // create a new instance
    $DBC = new DBC();
    $postalCodes = $DBC->selectDuration($id_universities, $id_programs);
?>
    <?php
    foreach ($offerings as $offering) {
    ?>
        <input class="form-control" type="text" value="<?php echo $offering->getDuration(); ?>">
    <?php
    } // foreach
    ?>
<?php
} // if
