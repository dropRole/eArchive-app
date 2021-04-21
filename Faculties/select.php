<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once './Faculties.php';

// create a new instance
$DBC = new DBC();
$faculties = $DBC->selectFaculties();
?>
<?php
foreach ($faculties as $faculty) {
?>
    <option value="<?php echo $faculty->getIdFaculties(); ?>"><?php echo "{$faculty->getName()}"; ?></option>
<?php
} // foreach
?>