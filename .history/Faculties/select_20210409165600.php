<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once '../Faculties/Faculties.php';

// create a new instance
$DBC = new DBC();
$universities = $DBC->selectFaculties();
?>
<?php
foreach ($universities as $university) {
?>
    <option value="<?php echo $university->getIdUniversities(); ?>"><?php echo "{$university->getName()}"; ?></option>
<?php
} // foreach
?>