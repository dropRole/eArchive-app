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
        $id_students = $DBC->insertStudent($id_postal_codes, $name, $surname, $email, $telephone, $residences);
        // if student data was sucessfully inserted 
        if ($id_students) {
            // insert every program attendance of the student
            foreach ($attendances as $attendance) {
                $id_attendances = $DBC->insertAttendance($id_students, $attendance['id_faculties'], $attendance['id_programs'], (new DateTime($attendance['enrolled'])), $attendance['index']);
                // if student program attendance data was prosperously inserted 
                if ($id_attendances) {
                    // if student graduated
                    if (isset($attendance['certificate'])) {
                        echo $DBC->uploadCertificate($id_attendances, $attendance['certificate'], (new DateTime($attendance['defended'])), (new DateTime($attendance['issued']))) . PHP_EOL;
                    } // if
                } // if 
            } // foreach
        } // if
    } // if 
    ?>