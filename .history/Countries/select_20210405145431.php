<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once '../Countries/Countries.php';
// create a new instance
$DBC = new DBC();
$countries = $DBC->selectCountries();
?>
<?php
foreach ($countries as $country) {
?>
    <option value="<?php echo $country->getIdCountries(); ?>"><?php echo "{$country->getName()}({$country->getISO3Code()})"; ?></option>
<?php
} // foreach
?>