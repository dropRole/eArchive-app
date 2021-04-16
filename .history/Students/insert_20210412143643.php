 <?php

    // namespace and class import declaration

    use DBC\DBC;

    // script import declaration

    require_once '../DBC/DBC.php';
    require_once '../Students/Students.php';

    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $sojourns = $_POST['sojourns'];
    $attendances = $_POST['attendances'];

    // if mandatory data is passed
    if (isset($name, $surname, $sojourns, $attendances)) {
        // create a new instance
        $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
        $DBC->insertStudent()
    } // if

    ?>