<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../../DBC/DBC.php';
require_once '../../PostalCodes/PostalCodes.php';
require_once '../../Countries/Countries.php';
include_once '../../header.php';

$DBC = new DBC($_SESSION['authorized'], $_SESSION['pass']);
F
                                $countries = $DBC->selectCountries();
                                
                                ?>

<!-- Custom core JavaScript -->
<script src="/eArchive/custom/js/studentRecord.js"></script>
<?php

// script import declaration

include_once '../../footer.php';

?>