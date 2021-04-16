<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once '../Universities/Universities.php';

// create a new instance
$DBC = new DBC();
$universities = $DBC->selectUniversities();
?>
<?php
foreach ($universities as $university) {
?>
    <option value="<?php echo $university->getIdUniversities(); ?>"><?php echo "{$university->getName()"; ?></option>
<?php
} // foreach
?>