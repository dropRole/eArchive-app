 <?php

    // namespace and class import declaration

    use DBC\DBC;

    // script import declaration

    require_once '../DBC/DBC.php';
    require_once '../Universities/Universities.php';

    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $sojourns = $_POST['sojourns'];
    $attendances = $_POST['attendances'];
    $graduations = $_POST['graduations'];

    print_r($sojourns);

    /* // if mandatory data is passed
    if (isset($name, $surname, $addresses, $attendances)) {
        // create a new instance
        $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
    } // if */

    ?>