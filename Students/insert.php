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

    // if mandatory data was passed
    if (isset($name, $surname, $id_postal_codes, $residences, $attendances)) {
        // create a new instance of PDO retrieving database connection 
        $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
        // insertion report
        $studtRprt = $DBC->insertStudent($id_postal_codes, $name, $surname, $email, $telephone, $residences);
        echo $studtRprt['mssg'];
        // if insertion is successful
        if ($studtRprt['id_students']) {
            // insert every program attendance of the student
            foreach ($attendances as $attendance) {
                $attnRprt = $DBC->insertAttendance($studtRprt['id_students'], $attendance['id_faculties'], $attendance['id_programs'], (new DateTime($attendance['enrolled'])), $attendance['index']);
                // if insertion was successful
                if ($attnRprt['id_attendances']) {
                    echo 'Študijski program \'' . $DBC->selectStudentsByIndex($attendance['index'])[0]->program . '\' je uspešno evidentiran.' . PHP_EOL;
                    // if student graduated
                    if (isset($attendance['certificate'])) {
                        $certRprt = $DBC->uploadCertificate($attnRprt['id_attendances'], $attendance['certificate'], (new DateTime($attendance['defended'])), (new DateTime($attendance['issued'])));
                        echo $certRprt . PHP_EOL;
                    } // if
                } // if 
            } // foreach
        } // if
    } // if 
    ?>