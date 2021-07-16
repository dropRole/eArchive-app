<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../autoload.php';

// retrieve a database connection
$DBC = new DBC();
?>
<?php
foreach ($DBC->selectFaculties() as $faculty) {
?>
    <option value="<?php echo $faculty->getIdFaculties(); ?>"><?php echo "{$faculty->getName()}"; ?></option>
<?php
} // foreach
?>