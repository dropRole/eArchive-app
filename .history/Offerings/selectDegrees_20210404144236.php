<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once '../Offerings/Offerings.php';

$id_universities = $_GET['id_universities'];
$id_programs = $_GET['id_programs'];

// if id of a program is successfully passed 
if (isset($id)) {
    // create a new instance
    $DBC = new DBC();
    $postalCodes = $DBC->selectDegrees($id_universities, $id_programs);
?>
    <?php
    foreach ($offerings as $offering) {
    ?>
        <option value="<?php echo $offering->getDegree(); ?>">
            <?php echo "{$offering->getDegree()}"; ?></option>
    <?php
    } // foreach
    ?>
<?php
} // if
