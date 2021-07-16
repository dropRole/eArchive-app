<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../autoload.php';

// instantiate PDO object carrying database connection
$DBC = new DBC();
foreach ($DBC->selectCountries() as $country) {
?>
    <option value="<?php echo $country->getIdCountries(); ?>"><?php echo "{$country->getName()}({$country->getISO3Code()})"; ?></option>
<?php
} // foreach
?>