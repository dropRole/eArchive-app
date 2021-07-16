<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../autoload.php';

$id_faculties = $_GET['id_faculties'];

// if id of a faculty is prosperously passed 
if (isset($id_faculties)) {
    // create a new instance
    $DBC = new DBC();
    foreach ($DBC->selectPrograms($id_faculties) as $program) {
?>
        <option value="<?php echo $program->getIdPrograms(); ?>"><?php echo "{$program->getName()}({$program->getField()}, {$program->getDegree()}, {$program->getDuration()})"; ?></option>
    <?php
    } // foreach
    ?>
<?php
} // if
