<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../autoload.php';

$id_countries = $_GET['id_countries'];

// if id of a country is prosperously passed 
if (isset($id_countries)) {
    // create a new instance
    $DBC = new DBC();
    foreach ($DBC->selectPostalCodes($id_countries) as $postalCode) {
?>
        <option value="<?php echo $postalCode->getIdPostalCodes(); ?>"><?php echo "{$postalCode->getMunicipality()}({$postalCode->getCode()})"; ?></option>
    <?php
    } // foreach
    ?>
<?php
} // if
