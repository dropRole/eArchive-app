 <?php

    // namespace and class import declaration

    use DBC\DBC;

    // script import declaration

    require_once '../autoload.php';

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
        $insrtStudtRprt = $DBC->insertStudent($id_postal_codes, $name, $surname, $email, $telephone, $residences);
        echo $insrtStudtRprt['mssg'] . PHP_EOL;
        // if insertion is successful
        if ($insrtStudtRprt['id_students']) {
            // insert every student attendance
            foreach ($attendances as $attendance) {
                $insrtAttnRprt = $DBC->insertAttendance($insrtStudtRprt['id_students'], $attendance['id_faculties'], $attendance['id_programs'], (new DateTime($attendance['enrolled'])), $attendance['index']);
                // if insretion isn't successful
                if (!$insrtAttnRprt['id_attendances'])
                    echo 'Napaka: študijski program \'' . $DBC->selectStudentsByIndex($attendance['index'])[0]->program . '\' ni uspešno evidentiran.' . PHP_EOL;
                // if insertion is successful
                if ($insrtAttnRprt['id_attendances']) {
                    echo 'Študijski program \'' . $DBC->selectStudentsByIndex($attendance['index'])[0]->program. '\' je uspešno evidentiran.' . PHP_EOL;
                    // if student graduated
                    if (isset($attendance['certificate'])) {
                        $insrtGradRprt = $DBC->uploadGradCertificate($insrtAttnRprt['id_attendances'], $attendance['certificate'], (new DateTime($attendance['defended'])), (new DateTime($attendance['issued'])));
                        echo $insrtGradRprt . PHP_EOL;
                    } // if
                } // if 
            } // foreach
        } // if
    } // if 

    ?>