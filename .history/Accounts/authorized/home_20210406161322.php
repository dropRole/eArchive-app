<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../../DBC/DBC.php';
include_once '../../header.php';

$DBC = new DBC($_SESSION['role'], $_SESSION['pass']);

?>



<?php

// script import declaration

include_once '../../footer.php';

?>
