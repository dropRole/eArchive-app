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
    $id_postal_codes = $_POST['id_postal_codes'];
    $sojourns = $_POST['sojourns'];
    $attendances = $_POST['attendances'];
    // if mandatory data is passed
    if (isset($name, $surname, $id_postal_codes, $sojourns, $attendances)) {
        // insertion report
        $report = [];
        // create a new instance
        $DBC = new DBC($_SESSION['user'], $_SESSION['pass']);
        $report = $DBC->insertStudent($id_postal_codes, $name, $surname, $email, $telephone, $sojourns);
        echo $report['message'];
        // if insertion is successful
        if ($report['id_students']) {
            // program counter
            $i = 1;
            // insert every student attendance
            foreach ($attendances as $attendance) {
                $report = $DBC->insertAttendances($report['id_students'], $attendance['id_faculties'], $attendance['id_programs'], $attendance['enrolled'], $attendance['index']);
                // if insertion is successful
                if ($report['id_attendances']) {
                    echo "{$i}. študijski program je uspešno vstavljen.";
                    
                } // if          
                if (!$report['id_attendances'])
                    echo "Napaka: {$i}. študijski program ni uspešno vstavljen.";
            } // foreach
        } // if
    } // if 

    ?>