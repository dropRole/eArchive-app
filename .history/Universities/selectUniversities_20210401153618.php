<?php

// namespace and class import declaration

use DBC\DBC;

// script imprort declaration

require_once '../DBC/DBC.php';
require_once '../Universities/Universities.php';

// create a new instance
$DBC = new DBC();
$universities = $DBC->selectUniversities();
?>
<?php
foreach ($universities as $university) {
?>
    <option value="<?php echo $university->getIdPostalCodes(); ?>"><?php echo "{$university->getMunicipality()}({$university->getCode()})"; ?></option>
<?php
} // foreach
?>