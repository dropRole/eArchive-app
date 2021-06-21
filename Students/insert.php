 <?php

    // namespace and class import declaration

    use DBC\DBC;

    // script import declaration

    require_once '../DBC/DBC.php';
    require_once './Students.php';

    session_start();

    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $id_postal_codes = $_POST['id_postal_codes'];
    $residences = $_POST['residences'];
    $attendances = $_POST['attendances'];
    // if mandatory data is passed
    if (isset($name, $surname, $id_postal_codes, $residences, $attendances)) {
        // create a new instance
        $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
        // insertion report
        $sRprt = $DBC->insertStudent($id_postal_codes, $name, $surname, $email, $telephone, $residences);
        echo $sRprt['message'] . PHP_EOL;
        // if insertion is successful
        if ($sRprt['id_students']) {
            // insert every student attendance
            foreach ($attendances as $attendance) {
                $aRprt = $DBC->insertAttendances($sRprt['id_students'], $attendance['id_faculties'], $attendance['id_programs'], (new DateTime($attendance['enrolled'])), $attendance['index']);
                // if insretion isn't successful
                if (!$aRprt['id_attendances'])
                    echo 'Napaka: študijski program \'' . $DBC->selectStudentsByIndex($attendance['index'])[0]->program . '\' ni uspešno evidentiran.' . PHP_EOL;
                // if insertion is successful
                if ($aRprt['id_attendances']) {
                    echo 'Študijski program \'' . $DBC->selectStudentsByIndex($attendance['index'])[0]->program. '\' je uspešno evidentiran.' . PHP_EOL;
                    // if student graduated
                    if (isset($attendance['certificate'])) {
                        $gRprt = $DBC->insertGraduation($aRprt['id_attendances'], $attendance['certificate'], (new DateTime($attendance['defended'])), (new DateTime($attendance['issued'])));
                        echo $gRprt . PHP_EOL;
                    } // if
                } // if 
            } // foreach
        } // if
    } // if 

    ?>