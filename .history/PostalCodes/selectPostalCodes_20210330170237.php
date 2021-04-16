<?php

// namespace and class import declaration

use DBC\DBC;

// script imprort declaration

require_once '../DBC/DBC.php';
require_once '../PostalCodes/PostalCodes.php';

$id_countries = $_GET['id_countries'];

// if id of a country is prosperously passed 
if (isset($id_countries)) {
    // create a new instance
    $DBC = new DBC();
    $postalCodes = $DBC->selectPostalCodes($id_countries);
?>
    <?php
    foreach ($postalCodes as $postalCode) {
    ?>
        <option value="<?php echo $postalCode->getIdPostalCodes(); ?>"><?php echo "{$postalCode->getMunicipality()}({$postalCode->getCode()})"; ?></option>
    <?php
    } // foreach
    ?>
<?php
} // if
