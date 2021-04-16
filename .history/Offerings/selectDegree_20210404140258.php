<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once '../Offerings/Offerings.php';

$id = $_GET['id'];

// if id of a program is successfully passed 
if (isset($id)) {
    // create a new instance
    $DBC = new DBC();
    $postalCodes = $DBC->selectOfferings($id);
?>
    <?php
    foreach ($offerings as $offering) {
    ?>
        <option class="offerings-degree" value="<?php echo $offering->getIdOfferings(); ?>"><?php echo "{$offering->getDegree()}"; ?></option>
    <?php
    } // foreach
    ?>
<?php
} // if
