<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once '../Programs/Programs.php';

$id_faculties = $_GET['id_faculties'];

// if id of a faculty is prosperously passed 
if (isset($id_faculties)) {
    // create a new instance
    $DBC = new DBC();
    $programs = $DBC->selectPrograms($id_faculties);
?>
    <?php
    foreach ($programs as $program) {
    ?>
        <option value="<?php echo $program->getIdPrograms(); ?>"><?php echo $program->getName();
        echo $program->getField() != NULL ? "({$program->getField()})" : ''; ?></option>
    <?php
    } // foreach
    ?>
<?php
} // if
