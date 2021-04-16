<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once '../Programs/Programs.php';

$id = $_GET['id'];

// if id of a university is prosperously passed 
if (isset($id)) {
    // create a new instance
    $DBC = new DBC();
    $programs = $DBC->selectPrograms($id_universities);
?>
    <?php
    foreach ($programs as $program) {
    ?>
        <option value="<?php echo $program->getIdPrograms(); ?>"><?php echo "{$program->getName()}({$program->getField()})"; ?></option>
    <?php
    } // foreach
    ?>
<?php
} // if
