<?php

namespace DBC;

// namespace import declaration

use PDO, PDOException, finfo, DateTime, ScientificPapers\ScientificPapers, Certificates\Certificates, Documents\Documents, Faculties\Faculties, Programs\Programs, Countries\Countries, PostalCodes\PostalCodes;

class DBC extends PDO
{
    private const SUPERUSER = 'auth_69141238';
    private const PASS = 'gZ7NVqMHrE';

    // constructs class instance
    public function __construct(string $user = self::SUPERUSER, string $pass = self::PASS)
    {
        try {
            // call a parent object class method
            parent::__construct("pgsql:host=localhost;dbname=eArchive;port=5432;user={$user};password=" . password_hash($pass, PASSWORD_BCRYPT) . ";");
        } // try
        catch (PDOException $e) {
            // output error message
            echo 'Napaka pri vzpostavljanju povezave s podatkovno zbirko: ' . $e->getMessage();
        } // catch 
    } // __construct

    /*
    *   select every scientific paper by students faculty program attendance
    *   @param int $id_attendances
    */
    public function selectScientificPapers($id_attendances)
    {
        $resultSet = [];
        $stmt = '   SELECT 
                        scientific_papers.*
                    FROM
                        scientific_papers
                    WHERE 
                        id_attendances = :id_attendances    ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':id_attendances', $id_attendances, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            return "Napaka: {$e->getMessage()}.";
        } // catch
        // if single or more rows are affected
        if ($prpStmt->rowCount() >= 1)
            $resultSet = $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, ScientificPapers::class, ['id_scientific_papers', 'id_attendances', 'topic', 'type', 'written']);
        return $resultSet;
    } // selectScientificPapers

    /*
    *   select particular scientific paper 
    *   @param int $id_scientific_papers
    */
    public function selectScientificPaper($id_scientific_papers)
    {
        $result = NULL;
        $stmt = '   SELECT 
                        scientific_papers.*
                    FROM
                        scientific_papers
                    WHERE 
                        id_scientific_papers = :id_scientific_papers    ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':id_scientific_papers', $id_scientific_papers, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            return "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected
        if ($prpStmt->rowCount() == 1)
            $result = $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, ScientificPapers::class, ['id_scientific_papers', 'id_attendances', 'topic', 'type', 'written'])[0];
        return $result;
    } // selectScientificPaper


    /*
    *   select scientific papers by keywords 
    *   @param string $keywords
    */
    public function selectPapersByKeywords(string $keywords)
    {
        $resultSet = [];
        $stmt = "   SELECT 
                        documents.*,
                        certificates.*,
                        (name || ' ' || surname) AS fullname,
                        part,
                        topic,
                        written,
                        id_mentorings,
                        mentor
                    FROM 
                        students 
                        INNER JOIN scientific_papers 
                        USING (id_students)
                        INNER JOIN documents 
                        USING (id_scientific_papers)
                        INNER JOIN mentorings  
                        USING (id_scientific_papers)
                        INNER JOIN partaking 
                        USING (id_students)
                        INNER JOIN attendances 
                        USING(id_students) 
                        INNER JOIN graduations 
                        USING (id_attendances) 
                        LEFT JOIN certificates 
                        USING (id_certificates)
                    WHERE 
                        UPPER(name || surname) LIKE UPPER(:keywords)  ";
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':author', $author, PDO::PARAM_STR);
            $prpStmt->execute();
            $resultSet = $prpStmt->fetchAll(PDO::FETCH_OBJ);
        } // try
        catch (PDOException $e) {
            // output error message 
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $resultSet;
    } // selectPapersByKeywords

    /*
    *   select scientific papers by particular author 
    *   @param string $author
    */
    public function selectPapersByAutor(string $author)
    {
        $resultSet = [];
        $stmt = "   SELECT 
                        documents.*,
                        certificates.*,
                        (name || ' ' || surname) AS fullname,
                        part,
                        topic,
                        written,
                        id_mentorings,
                        mentor
                    FROM 
                        students 
                        INNER JOIN scientific_papers 
                        USING (id_students)
                        INNER JOIN documents 
                        USING (id_scientific_papers)
                        INNER JOIN mentorings  
                        USING (id_scientific_papers)
                        INNER JOIN partaking 
                        USING (id_students)
                        INNER JOIN attendances 
                        USING(id_students) 
                        INNER JOIN graduations 
                        USING (id_attendances) 
                        LEFT JOIN certificates 
                        USING (id_certificates)
                    WHERE 
                        UPPER(name || surname) LIKE UPPER(:author)  ";
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':author', $author, PDO::PARAM_STR);
            $prpStmt->execute();
            $resultSet = $prpStmt->fetchAll(PDO::FETCH_OBJ);
        } // try
        catch (PDOException $e) {
            // output error message 
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $resultSet;
    } // selectPapersByAutor

    /*
    *   select scientific papers by particular mentor 
    *   @param string $mentor
    */
    public function selectPapersByMentor($mentor)
    {
        $resultSet = [];
        $stmt = "   SELECT 
                        documents.*,
                        certificates.*,
                        (name || ' ' || surname) AS fullname,
                        part,
                        topic,
                        written,
                        id_mentorings,
                        mentor
                    FROM 
                        students 
                        INNER JOIN scientific_papers 
                        USING (id_students)
                        INNER JOIN documents 
                        USING (id_scientific_papers)
                        INNER JOIN mentorings  
                        USING (id_scientific_papers)
                        INNER JOIN partaking 
                        USING (id_students)
                        INNER JOIN attendances 
                        USING(id_students) 
                        INNER JOIN graduations 
                        USING (id_attendances) 
                        LEFT JOIN certificates 
                        USING (id_certificates)
                    WHERE 
                        UPPER(mentor) LIKE UPPER(:mentor)  ";
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':mentor', $mentor, PDO::PARAM_STR);
            $prpStmt->execute();
            $resultSet = $prpStmt->fetchAll(PDO::FETCH_OBJ);
        } // try
        catch (PDOException $e) {
            // output error message 
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $resultSet;
    } // selectPapersByMentor

    /*
    *   select all students 
    *   @param string $order
    */
    public function selectStudents(string $order = 'ASC')
    {
        $resultSet = [];
        switch ($order) {
            case 'ASC':
                $stmt = "   SELECT 
                                id_students,
                                id_attendances,
                                (students.name  || ' ' || students.surname) AS fullname, 
                                faculties.name AS faculty, 
                                programs.name AS program,
                                index,
                                degree
                            FROM 
                                students 
                                INNER JOIN attendances
                                USING(id_students)
                                INNER JOIN faculties
                                USING(id_faculties)
                                INNER JOIN programs
                                USING(id_programs)
                            ORDER BY
                                surname    ";
                break;
            case 'DESC':
                $stmt = "   SELECT 
                                id_students,
                                id_attendances,
                                (students.name || ' ' || surname) AS fullname, 
                                faculties.name AS faculty, 
                                programs.name AS program,
                                index,
                                degree
                            FROM 
                                students 
                                INNER JOIN attendances
                                USING(id_students)
                                INNER JOIN faculties
                                USING(id_faculties)
                                INNER JOIN programs
                                USING(id_programs)
                            ORDER BY
                                surname DESC    ";
                break;
        } // 
        try {
            // prepare and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->execute();
            $resultSet = $prpStmt->fetchAll(PDO::FETCH_OBJ);
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if there are no student in the evidence
        if ($prpStmt->rowCount() == 0)
            echo 'Opozorilo: študentov ni v evidenci.';
        return $resultSet;
    } // selectStudents

    /*
    *   select students by given name and surname 
    *   @param string $name
    *   @param string $surname
    *   @param string $order
    */
    public function selectStudentsByName(string $name, string $surname, string $order = 'ASC')
    {
        // restult set
        $resultSet = [];
        switch ($order) {
            case 'ASC':
                break;
                $stmt = "   SELECT 
                                id_students,
                                (students.name || ' ' || surname) AS fullname, 
                                faculties.name AS faculty, 
                                programs.name AS program,
                                index,
                                degree
                            FROM 
                                students 
                                INNER JOIN attendances
                                USING(id_students)
                                INNER JOIN faculties
                                USING(id_faculties)
                                INNER JOIN programs
                                USING(id_programs)
                            WHERE 
                                UPPER(name || surname) LIKE UPPER(:fullname)
                            ORDER BY
                                fullname    ";
            case 'DESC':
                $stmt = "   SELECT 
                                id_students,
                                (students.name || ' ' || surname) AS fullname, 
                                faculties.name AS faculty, 
                                programs.name AS program,
                                index,
                                degree
                            FROM 
                                students 
                                INNER JOIN attendances
                                USING(id_students)
                                INNER JOIN faculties
                                USING(id_faculties)
                                INNER JOIN programs
                                USING(id_programs)
                            WHERE 
                                UPPER(name || surname) LIKE UPPER(:fullname)
                            ORDER BY
                                fullname DESC   ";
                break;
        } // switch
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':fullname' . strtoupper($name . $surname), PDO::PARAM_STR);
            $prpStmt->execute();
            $resultSet = $prpStmt->fetchAll(PDO::FETCH_OBJ);
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if not student was selected
        if ($prpStmt->rowCount() == 0)
            echo 'Ni študentov po danem imenu.';
        return $resultSet;
    } // selectStudentsByName 

    /*
    *   select students by index number 
    *   @param int $index
    *   @param string $order
    */
    public function selectStudentsByIndex(string $index, string $order = 'ASC')
    {
        // restult set
        $resultSet = [];
        $stmt = '';
        switch ($order) {
            case 'ASC':
                $stmt = "   SELECT 
                            id_students,
                            id_attendances,
                            (students.name  || ' ' || students.surname) AS fullname, 
                            faculties.name AS faculty, 
                            programs.name AS program,
                            index,
                            degree
                        FROM 
                            students 
                            INNER JOIN attendances
                            USING(id_students)
                            INNER JOIN faculties
                            USING(id_faculties)
                            INNER JOIN programs
                            USING(id_programs)
                            WHERE 
                                index LIKE :index
                            ORDER BY
                                fullname    ";
                break;
            case 'DESC':
                $stmt = "   SELECT 
                                id_students,
                                id_attendances,
                                (students.name  || ' ' || students.surname) AS fullname, 
                                faculties.name AS faculty, 
                                programs.name AS program,
                                index,
                                degree
                            FROM 
                                students 
                                INNER JOIN attendances
                                USING(id_students)
                                INNER JOIN faculties
                                USING(id_faculties)
                                INNER JOIN programs
                                USING(id_programs)
                            WHERE 
                                index LIKE :index
                            ORDER BY
                                fullname DESC   ";
                break;
        } // switch
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindValue(':index', "{$index}%", PDO::PARAM_STR);
            $prpStmt->execute();
            $resultSet = $prpStmt->fetchAll(PDO::FETCH_OBJ);
            // if not student was selected
        } // if
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $resultSet;
    } // selectStudentsByIndex 

    /*
    *   select particulars of a student
    *   @param int $id_students
    */
    public function selectStudent($id_students)
    {
        $student = [
            'particulars' => NULL,
            'permResidence' => NULL,
            'tempResidence' => []
        ];
        $stmt = '   SELECT 
                        students.*,
                        id_countries
                    FROM
                        students
                        INNER JOIN postal_codes
                        USING(id_postal_codes)
                    WHERE 
                        id_students = :id_students  ';
        $stmt2 = '  SELECT 
                        id_residences,
                        id_postal_codes,
                        id_countries,
                        address,
                        status
                    FROM
                        residences
                        INNER JOIN postal_codes
                        USING(id_postal_codes)
                    WHERE 
                        id_students = :id_students
                    ORDER BY 
                        status ';
        try {
            // prepare, bind params to and execute stmts
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt2 = $this->prepare($stmt2, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':id_students', $id_students, PDO::PARAM_INT);
            $prpStmt2->bindParam(':id_students', $id_students, PDO::PARAM_INT);
            $prpStmt->execute();
            $prpStmt2->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if student particulars and merely one permanent residence were fetched    
        if ($prpStmt->rowCount() == 1 && $prpStmt2->rowCount() >= 0) {
            $student['particulars'] = $prpStmt->fetch(PDO::FETCH_ASSOC);
            foreach ($prpStmt2->fetchAll(PDO::FETCH_ASSOC) as $residence) {
                // designate residence according to its status
                if ($residence['status'] == 'STALNO')
                    $student['permResidence'] = $residence;
                else
                    array_push($student['tempResidence'], $residence);
            } // foreach
        } // if
        return json_encode($student);
    } // selectStudent

    /*
    *   check if student resides at 
    *   @param int id_students
    */
    private function checkIfResides(int $id_students, int $id_postal_codes)
    {
        $stmt = '   SELECT 
                        TRUE 
                    FROM 
                        residences
                    WHERE 
                        id_students = :id_students AND id_postal_codes = :id_postal_codes   ';
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_students', $id_students, PDO::PARAM_INT);
            $prpStmt->bindParam(':id_postal_codes', $id_postal_codes, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected
        if ($prpStmt->rowCount() == 1)
            return TRUE;
        return FALSE;
    } // checkIfResides

    /*
    *   select postal codes of the given country 
    *   @param int $id_countries
    */
    public function selectPostalCodes(int $id_countries)
    {
        $resultSet = [];
        $stmt = '   SELECT 
                        id_postal_codes,
                        municipality,
                        code 
                    FROM 
                        postal_codes    
                    WHERE 
                        id_countries = :id_countries    ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam('id_countries', $id_countries, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // is single or more rows are affected
        if ($prpStmt->rowCount() >= 1)
            $resultSet = $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, PostalCodes::class, ['id_postal_codes', 'id_countries', 'municipality', 'code']);
        return $resultSet;
    } // selectPostalCodes

    // select every country
    public function selectCountries()
    {
        $resultSet = [];
        $stmt = '   SELECT 
                        * 
                    FROM 
                        countries    ';
        try {
            // prepare and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->execute();
            $resultSet = $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Countries::class, ['id_countries', 'name', 'iso_3_code']);
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $resultSet;
    } // selectCountries

    /*
    *   delete the given student temporal residence 
    *   @param int $id_students
    *   @param int $id_residences
    */
    public function deleteStudentTemporalResidence($id_students, $id_residences)
    {
        $stmt = '   DELETE FROM
                        residences
                    WHERE 
                        id_students = :id_students AND id_residences = :id_residences   ';
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_students', $id_students, PDO::PARAM_INT);
            $prpStmt->bindParam(':id_residences', $id_residences, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            return "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected
        if ($prpStmt->rowCount() == 1)
            return 'Bivališče je uspešno izbrisano.';
        return 'Bivališče ni uspešno izbrisano.';
    } // deleteStudentTemporalResidence

    public function selectFaculties()
    {

        $resultSet = [];
        $stmt = '   SELECT 
                        * 
                    FROM 
                        faculties    ';
        try {
            // prepare and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->execute();
            $resultSet = $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Faculties::class, ['id_faculties', 'id_postal_codes', 'name', 'address', 'email', 'telephone', 'dean']);
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $resultSet;
    } // selectFaculties

    /*
    *   select faculty offered programs 
    *   @param int $id_faculties 
    */
    public function selectPrograms(int $id_faculties)
    {
        $resultSet = [];
        $stmt = '   SELECT 
                        DISTINCT(programs.*) 
                    FROM 
                        programs    
                        INNER JOIN faculties
                        USING(id_faculties)
                    WHERE 
                        id_faculties = :id_faculties  ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':id_faculties', $id_faculties, PDO::PARAM_INT);
            $prpStmt->execute();
            $resultSet = $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Programs::class, ['id_programs', 'name', 'degree', 'duration', 'field']);
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $resultSet;
    } // selectPrograms

    /*
    *   insert student fundemental data 
    *   @param int $id_postal_codes
    *   @param int $id_accounts
    *   @param string $name
    *   @param string $surname
    *   @param string $email
    *   @param string $telephone
    *   @param array $residences
    */
    public function insertStudent(int $id_postal_codes, string $name, string $surname, string $email = NULL, string $telephone = NULL, $residences = [])
    {
        // result
        $report = [
            'id_students' => 0,
            'message' => ''
        ];
        $stmt = '   INSERT INTO 
                                students 
                            (
                                id_postal_codes,
                                name,   
                                surname, 
                                email, 
                                telephone
                            )
                            VALUES(
                                :id_postal_codes,
                                :name,
                                :surname,
                                :email,
                                :telephone
                            )   ';
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindValue(':id_postal_codes', $id_postal_codes, PDO::PARAM_INT);
            $prpStmt->bindParam(':name', $name, PDO::PARAM_STR);
            $prpStmt->bindParam(':surname', $surname, PDO::PARAM_STR);
            $prpStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $prpStmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            $report['message'] = "Napaka: {$e->getMessage()}.";
            return $report;
        } // catch
        // if single row is affected
        if ($prpStmt->rowCount() == 1) {
            $id_students = $this->lastInsertId('students_id_students_seq');
            // form a report
            $report['id_students'] = $id_students;
            $report['message'] .= 'Osnovni podatki študenta uspešno vstavljeni.' . PHP_EOL;
            // flag for permament residence
            $permanent = TRUE;
            // temporal residences counter
            $i = 1;
            foreach ($residences as $residence) {
                $stmt = '   INSERT INTO 
                                residences
                            (
                                id_postal_codes,
                                id_students,
                                address,
                                status
                            ) 
                            VALUES(
                                :id_postal_codes,
                                :id_students,
                                :address,
                                :status
                            )   ';
                try {
                    // prepare, bind params to and execute stmt
                    $prpStmt = $this->prepare($stmt);
                    $prpStmt->bindValue(':id_postal_codes', $residence['id_postal_codes'], PDO::PARAM_INT);
                    $prpStmt->bindParam(':id_students', $id_students, PDO::PARAM_INT);
                    $prpStmt->bindValue(':address', $residence['address'], PDO::PARAM_STR);
                    $prpStmt->bindValue(':status', $permanent ? 'STALNO' : 'ZAČASNO', PDO::PARAM_STR);
                    $prpStmt->execute();
                    // if single row is affected
                    if ($prpStmt->rowCount() == 1) {
                        // if residence is temporal
                        if (!$permanent) {
                            $report['message'] .= "Podatki o {$i}. začasnem bivališču so uspešno dodani." . PHP_EOL;
                            $i++;
                        } // if
                        // if residence is permament
                        if ($permanent) {
                            $report['message'] .= 'Podatki o stalnem prebivališču so uspešno dodani.' . PHP_EOL;
                            $permanent = FALSE;
                        } // if
                    } //if
                } // try
                catch (PDOException $e) {
                    $report['message'] .= "Napaka: {$e->getMessage()}." . PHP_EOL;
                } // catch
            } // foreach
            return $report;
        } // if
        $report['message'] = 'Napaka: osnovni podakti študenta ter podatki o prebivališču niso uspešno vstavljeni.';
        return $report;
    } // insertStudent

    /*
    *   update student fundemental data
    *   @param int $id_students
    *   @param int $id_postal_codes
    *   @param int $id_accounts
    *   @param string $name
    *   @param string $surname
    *   @param string $email
    *   @param string $telephone
    *   @param array $residences
    */
    public function updateStudent(int $id_students, int $id_postal_codes, string $name, string $surname, string $email = NULL, string $telephone = NULL, $residences = [])
    {
        // report on action
        $report = '';
        $stmt = '   UPDATE 
                        students 
                    SET
                        id_postal_codes = :id_postal_codes,
                        name = :name,   
                        surname = :surname, 
                        email = :email, 
                        telephone = :telephone
                    WHERE 
                        id_students = :id_students  ';
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_postal_codes', $id_postal_codes, PDO::PARAM_INT);
            $prpStmt->bindParam(':name', $name, PDO::PARAM_STR);
            $prpStmt->bindParam(':surname', $surname, PDO::PARAM_STR);
            $prpStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $prpStmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $prpStmt->bindParam(':id_students', $id_students, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            return "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected
        if ($prpStmt->rowCount() == 1)
            $report = 'Osnovni podatki študenta so uspešno posodobljeni.' . PHP_EOL;
        else
            $report = 'Osnovni podatki študenta niso uspešno posodobljeni.' . PHP_EOL;

        return ($report .= $this->updateStudentResidences($id_students, $residences));
    } // updateStudent

    /*
    *   delete all data related to a particular program attendance by student 
    *   @param int $id_students 
    */
    public function deleteStudent(int $id_students, int $id_attendances)
    {
        // deletion report 
        $report = '';
        // if transaction with the server isn't already running
        if (!$this->inTransaction()) {
            try {
                // begin a new transaction
                $this->beginTransaction();
                $this->deleteStudentResidences($id_students);
                // if graduated, select certificate and delete it
                $certificate = $this->selectCertificate($id_attendances);
                if ($certificate)
                    $this->deleteGraduation($id_attendances, $certificate['source']);
                // if any scientific paper was written, delete it 
                $scientificPapers = $this->selectScientificPapers($id_attendances);
                if (count($scientificPapers) >= 1)
                    foreach ($scientificPapers as $scientificPaper) {
                        $this->deleteScientificPaper($scientificPaper->getIdScientificPapers());
                    } // foreach
                // if account was granted to, delete if
                if ($this->checkStudentAccount($id_attendances))
                    $this->deleteStudentAccount($id_attendances);
                $this->deleteStudentProgramAttendance($id_attendances);
                $stmt = '   DELETE FROM
                                students
                            WHERE 
                                id_students = :id_students  ';
                $prpStmt = $this->prepare($stmt);
                $prpStmt->bindParam(':id_students', $id_students, PDO::PARAM_INT);
                $prpStmt->execute();
            } // try
            catch (PDOException $e) {
                return "Napaka: {$e->getMessage()}.";
            } // catch
            // if single row is affected
            if ($prpStmt->rowCount() == 1)
                // if transaction was committed with success
                if ($this->commit())
                    return 'Podatki o študentu ter znanstvenih dosežkih so uspešno izbrisani.';
                else
                    $this->rollBack();
            return 'Podatki o študentu ter znanstvenih dosežkih niso uspešno izbrisani.';
        } // if
        else
            return 'Opomba: transakcija s podatkovno zbirko je že v izvajanju.';
    } // deleteStudent

    /*
    *   insert student temporal residence
    *   @param int $id_students
    *   @param Array $residence
    */
    private function insertStudentTemporalResidence(int $id_students, array $residence)
    {
        $stmt = '   INSERT INTO
                        residences
                    (
                        id_postal_codes,
                        id_students,
                        address,
                        status
                    )
                    VALUES(
                        :id_postal_codes,
                        :id_students,
                        :address,
                        :status
                    )   ';
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindValue(':id_postal_codes', $residence['id_postal_codes'], PDO::PARAM_INT);
            $prpStmt->bindParam(':id_students', $id_students, PDO::PARAM_INT);
            $prpStmt->bindValue(':address', $residence['address'], PDO::PARAM_STR);
            $prpStmt->bindValue(':status', 'ZAČASNO', PDO::PARAM_STR);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            return "Napaka {$e->getMessage()}.";
        } // catch
        // if single row is affected
        if ($prpStmt->rowCount() == 1)
            return 'so uspešno vstavljeni.' . PHP_EOL;
        return 'niso uspešno vstavljeni.' . PHP_EOL;
    } // insertStudentTemporalResidence

    /*
    *   update permanent and temporal residence of a student 
    *   @param int $id_students
    *   @param Array $residences
    */
    private function updateStudentResidences(int $id_students, array $residences)
    {
        // action report 
        $report = '';
        // flag for permament residence
        $permanent = TRUE;
        // temporal residences counter
        $i = 1;
        foreach ($residences as $residence) {
            // check whether student resides
            if ($this->checkIfResides($id_students, $residence['id_postal_codes'])) {
                $stmt = '   UPDATE
                                residences
                            SET 
                                id_postal_codes = :id_postal_codes,
                                address = :address
                            WHERE 
                                id_students = :id_students AND id_residences = :id_residences  ';
                try {
                    // prepare, bind params to and execute stmt
                    $prpStmt = $this->prepare($stmt);
                    $prpStmt->bindValue(':id_postal_codes', $residence['id_postal_codes'], PDO::PARAM_INT);
                    $prpStmt->bindValue(':address', $residence['address'], PDO::PARAM_STR);
                    $prpStmt->bindParam(':id_students', $id_students, PDO::PARAM_INT);
                    $prpStmt->bindValue(':id_residences', $residence['id_residences'], PDO::PARAM_INT);
                    $prpStmt->execute();
                } // try
                catch (PDOException $e) {
                    return "Napaka: {$e->getMessage()}." . PHP_EOL;
                } // catch
                // if single row is affected
                if ($prpStmt->rowCount() == 1)
                    if (!$permanent) {
                        $report .= "Podatki o {$i}. začasnem bivališču so uspešno posodobljeni." . PHP_EOL;
                        $i++;
                    } // if
                    else {
                        $report = 'Podatki o stalnem prebivališču so uspešno posodobljeni.' . PHP_EOL;
                        $permanent = FALSE;
                    } // else
            } else
                $report .= "Podatki o {$i}. začasnem bivališču {$this->insertStudentTemporalResidence($id_students,$residence)}";
        } // foreach
        return $report;
    } // updateStudentResidences

    /*
    *   delete all of student residences
    *   @param int $id_students
    */
    private function deleteStudentResidences(int $id_students)
    {
        $stmt = '   DELETE FROM 
                        residences
                    WHERE 
                        id_students = :id_students  ';
        $prpStmt = $this->prepare($stmt);
        $prpStmt->bindParam(':id_students', $id_students, PDO::PARAM_INT);
        $prpStmt->execute();
    } // deleteStudentResidences

    /*
    *   insert attendance of a student
    *   @param int $id_students
    *   @param int $id_faculties
    *   @param int $id_programs
    *   @param DateTime $enrolled 
    *   @param int $enrolled 
    */
    public function insertAttendances(int $id_students, int $id_faculties, int $id_programs, DateTime $enrolled, string $index)
    {
        // insertion report
        $report = [
            'id_attendances' => 0,
            'message' => ''
        ];
        $stmt = '   INSERT INTO 
                        attendances 
                    (
                        id_students,
                        id_faculties,
                        id_programs,
                        enrolled, 
                        index
                    )   
                    VALUES(
                        :id_students,
                        :id_faculties,
                        :id_programs,
                        :enrolled,
                        :index
                    )   ';
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_students', $id_students, PDO::PARAM_INT);
            $prpStmt->bindParam(':id_faculties', $id_faculties, PDO::PARAM_INT);
            $prpStmt->bindParam(':id_programs', $id_programs, PDO::PARAM_INT);
            $prpStmt->bindValue(':enrolled', $enrolled->format('d-m-Y'), PDO::PARAM_STR);
            $prpStmt->bindParam(':index', $index, PDO::PARAM_STR);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected
        if ($prpStmt->rowCount() == 1)
            $report['id_attendances'] = $this->lastInsertId('attendances_id_attendances_seq');
        return $report;
    } // insertAttendances

    /*
    *   update attendance of a student
    *   @param int $id_attendances
    *   @param int $id_students
    *   @param int $id_faculties
    *   @param int $id_programs
    *   @param DateTime $enrolled 
    *   @param int $enrolled 
    */
    public function updateAttendaces(int $id_attendances, int $id_students, int $id_faculties, int $id_programs, DateTime $enrolled, int $index)
    {
        $stmt = '   UPDATE 
                        attendances 
                    SET 
                        id_faculties = :id_faculties,
                        id_programs = :id_programs,
                        enrolled = :enrolled,
                        index = :index  
                    WHERE 
                        id_attendances = :id_attendances AND id_students = :id_students ';
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_faculties', $id_faculties, PDO::PARAM_INT);
            $prpStmt->bindParam(':id_programs', $id_programs, PDO::PARAM_INT);
            $prpStmt->bindParam(':enrolled', $enrolled, PDO::PARAM_STR);
            $prpStmt->bindParam(':index', $index, PDO::PARAM_STR);
            $prpStmt->bindParam(':id_attendances', $id_attendances, PDO::PARAM_INT);
            $prpStmt->bindParam(':id_students', $id_students, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected 
        if ($prpStmt->rowCount() == 1)
            return 'Podatki o študiranju so uspešno ažurirani.';
        else
            return 'Napaka: podatki o študiranju niso uspešno ažurirani.';
    } // updateAttendaces

    /*
    *   delete program attendance of a student
    *   @param int $id_attendances
    */
    public function deleteStudentProgramAttendance(int $id_attendances)
    {
        $stmt = '   DELETE FROM 
                        attendances 
                    WHERE 
                        id_attendances = :id_attendances  ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_attendances', $id_attendances, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected
        if ($prpStmt->rowCount() == 1)
            return 'Podatki o poteku izobraževanja na danem študijskem programu so uspešno izbrisani.';
        return 'Podatki o poteku izobraževanja na danem študijskem programu niso uspešno izbrisani.';
    } // deleteStudentProgramAttendance

    /*
    *   insert graduation of a student
    *   @param int $id_attendances
    *   @param DateTime $defended
    */
    public function insertGraduation(int $id_attendances, string $certificate, DateTime $issued, DateTime $defended)
    {
        // action report
        $report = '';
        // if not already running a transaction
        if (!$this->inTransaction()) {
            try {
                // begin a new transaction
                $this->beginTransaction();
                foreach ($_FILES['certificate']['tmp_name'] as $indx => $tmp_name) {
                    // if certificate is among uploaded files
                    if ($_FILES['certificate']['name'][$indx] == $certificate) {
                        // if certificate is uploaded successfully 
                        if ($_FILES['certificate']['error'][$indx] == UPLOAD_ERR_OK) {
                            $finfo = new finfo();
                            $mimetype = $finfo->file($tmp_name, FILEINFO_MIME_TYPE);
                            // if it's not a PDF document
                            if (strpos($mimetype, 'pdf') == FALSE)
                                return $report = "Napaka: certifikat '{$_FILES['certificate']['name'][$indx]}' ni uspešno dodan saj ni tipa .pdf .";
                            $upload = TRUE;
                            // if document meets the condition 
                            if ($upload) {
                                // set destination of the uploded file
                                $dir = 'uploads/certificates/';
                                $destination = $dir . (new DateTime())->format('dmYHsi') . basename($_FILES['certificate']['name'][$indx]);
                                $stmt = '   INSERT INTO
                                                certificates
                                            (
                                                source,
                                                issued
                                            )
                                            VALUES(
                                                :source,
                                                :issued
                                            )   ';
                                // prepare, bind params to and execute stmt
                                $prpStmt = $this->prepare($stmt);
                                $prpStmt->bindParam(':source', $destination, PDO::PARAM_STR);
                                $prpStmt->bindValue(':issued', $issued->format('d-m-Y'), PDO::PARAM_STR);
                                $prpStmt->execute();
                                // if certificate was inserted into db and moved to a new destination  
                                if ($prpStmt->rowCount() == 1 && move_uploaded_file($tmp_name, "../{$destination}")) {
                                    $report = "Certifikat {$_FILES['certificate']['name'][$indx]} je uspešno vstavljen." . PHP_EOL;
                                    $id_certificates = $this->lastInsertId('certificates_id_certificates_seq');
                                    $stmt = '   INSERT INTO
                                                    graduations
                                                (
                                                    id_certificates,
                                                    id_attendances,
                                                    defended
                                                )
                                                VALUES(
                                                    :id_certificates,
                                                    :id_attendances,
                                                    :defended
                                                )   ';
                                    // prepare, bind params to and execute stmt
                                    $prpStmt = $this->prepare($stmt);
                                    $prpStmt->bindParam(':id_certificates', $id_certificates, PDO::PARAM_INT);
                                    $prpStmt->bindParam(':id_attendances', $id_attendances, PDO::PARAM_INT);
                                    $prpStmt->bindValue(':defended', $defended->format('d-m-Y'), PDO::PARAM_STR);
                                    $prpStmt->execute();
                                    // if single row is affected 
                                    if ($prpStmt->rowCount() == 1) {
                                        $report .= 'Podatki o zaključku študiranja in certifikatu so uspešno vstavljeni.';
                                        // commit current transaction
                                        $this->commit();
                                        return $report;
                                    } // if
                                    // rollback current transaction
                                    $this->rollBack();
                                    return $report = 'Napaka: podatki o zaključku študiranja in certifikatu niso uspešno vstavljeni.';
                                } // if
                                // rollback current transaction
                                $this->rollBack();
                                return $report = 'Napaka: podatki certifikata niso uspešno vstavljeni v zbirko ali datoteka ni uspešno prenesena na strežnik.';
                            } // if
                        } // if
                        return $report = "Napaka: certifikat {$_FILES['certificate']['name'][$indx]} ni uspešno naložen.";
                    } // if
                } // foreach
            } // try
            catch (PDOException $e) {
                // output error message 
                return $report = "Napaka: {$e->getMessage()}.";
            } // catch 
        } // if
        return $report = 'Nakapa: transakcija s podatkovno zbirko je v izvajanju.';
    } // insertGraduation

    /*
    *   update graduation defense data 
    *   @param int $id_attendances
    *   @param int $id_students
    */
    public function updateGraduation(int $id_attendances, int $id_students)
    {
        $stmt = '   UPDATE 
                        graduations 
                    SET 
                        defended = :defended
                    WHERE 
                        id_attendances = :id_attendances AND id_students = :id_students ';
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_attendances', $id_attendances, PDO::PARAM_INT);
            $prpStmt->bindParam(':id_students', $id_students, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected 
        if ($prpStmt->rowCount() == 1)
            return 'Podatek o datumu zagovarjanja diplome je uspešno ažuriran.';
        else
            return 'Napaka: podatek o datumu zagovarjanja diplome ni uspešno ažuriran.';
    } // updateGraduation

    /*
    *   delete graduation of a student 
    *   @param int $id_attendances
    *   @param int $id_students
    */
    public function deleteGraduation(int $id_attendances, string $source)
    {
        // if not already issuing a transaction
        if (!$this->inTransaction()) {
            try {
                // initialize a new transaction
                $this->beginTransaction();
                $stmt = '   DELETE FROM
                                graduations 
                            WHERE 
                                id_attendances = :id_attendances    ';
                // prepare, bind param to and execute stmt
                $prpStmt = $this->prepare($stmt);
                $prpStmt->bindParam(':id_attendances', $id_attendances, PDO::PARAM_INT);
                $prpStmt->execute();
                // if single row is affected
                if ($prpStmt->rowCount() == 1) {
                    // attempt graduation certificate deletion
                    if ($this->deleteCertificate($source) && unlink("../{$source}")) {
                        // committ the current transation
                        $this->commit();
                        return 'Podatki o maturi ter certifikat ' . basename($source) . ' so uspešno izbrisani.';
                    } // if
                    // roll back current transaction
                    $this->rollBack();
                } // if 
                return 'Podatki o maturi ter certifiakt niso uspešno izbrisani.';
            } // try
            catch (PDOException $e) {
                return "Napaka: {$e->getMessage()}.";
            } // catch
        } // if
        return 'Opozorilo: druga transakcija je v izvajanju.';
    } // deleteGraduation

    /*
    *   select graduation certificate for given program attendance
    *   @param int $id_attendances
    */
    public function selectCertificate($id_attendances)
    {
        $certificate = NULL;
        $stmt = '   SELECT 
                        certificates.* 
                    FROM 
                        certificates
                        INNER JOIN graduations
                        USING(id_certificates)
                    WHERE
                        id_attendances = :id_attendances  ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_attendances', $id_attendances, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        $certificate = $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Certificates::class, ['id_certificates', 'source', 'issued']);
        return $certificate;
    } // selectCertificate

    /*
    *   update date of issuing 
    *   @param int $id_certificates
    *   @param DateTime $issued
    */
    public function updateCertificates(int $id_certificates, DateTime $issued)
    {
        $stmt = '   UPDATE
                        certificates 
                    SET
                        issued = :issued    
                    WHERE 
                        id_certificates = id_certificates   ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':issued', $issued, PDO::PARAM_STR);
            $prpStmt->bindParam(':id_certificates', $id_certificates, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected 
        if ($prpStmt->rowCount() == 1)
            return 'Certifiakt je uspešno ažuriran.';
        else
            return 'Certifiakt ni uspešno ažuriran.';
    } // updateCertificates

    /*
    *   delete particular certificate 
    *   @param string $source
    */
    public function deleteCertificate(string $source)
    {
        $stmt = '   DELETE FROM
                        certificates 
                    WHERE 
                        source = :source  ';
        $prpStmt = $this->prepare($stmt);
        $prpStmt->bindParam(':source', $source, PDO::PARAM_STR);
        $prpStmt->execute();
        // if single row is affected 
        if ($prpStmt->rowCount() == 1)
            return TRUE;
        return FALSE;
    } // deleteCertificate

    /*
    *   insert scientific paper particulars   
    *   @param string $topic
    *   @param DateTime $written
    *   @param string $type
    */
    public function insertScientificPaper(int $id_attendances, string $topic, string $type, DateTime $written)
    {
        // action report
        $report = [
            'id_scientific_papers' => 0,
            'message' => ''
        ];
        $stmt = '   INSERT INTO 
                        scientific_papers
                    (
                        id_attendances,
                        topic, 
                        type,
                        written
                    )
                    VALUES(
                        :id_attendances,
                        :topic,
                        :type,
                        :written
                    )   ';
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_attendances', $id_attendances, PDO::PARAM_INT);
            $prpStmt->bindParam(':topic', $topic, PDO::PARAM_STR);
            $prpStmt->bindParam(':type', $type, PDO::PARAM_STR);
            $prpStmt->bindValue(':written', $written->format('d-m-Y'), PDO::PARAM_STR);
            $prpStmt->execute();
            // if single row is affected
            if ($prpStmt->rowCount() == 1) {
                $report['id_scientific_papers'] = $this->lastInsertId('scientific_papers_id_scientific_papers_seq');
                $report['message'] = "Delo '{$topic}' je uspešno ustavljeno v zbirko." . PHP_EOL;
            } // if
        } // try
        catch (PDOException $e) {
            // output error message 
            $report['message'] = "Napaka: {$e->getMessage()}.";
        } // catch
        return $report;
    } // insertScientificPaper

    /*
    *   update scientific paper particulars
    *   @param int $id_scientific_papers
    *   @param string $topic
    *   @param DateTime $written
    *   @param string $type
    */
    public function updateScientificPapers(int $id_scientific_papers, string $topic, string $type, DateTime $written)
    {
        try {
            $stmt = '   UPDATE  
                            scientific_papers
                        SET 
                            topic = :topic, 
                            type  = :type,
                            written = :written 
                        WHERE 
                            id_scientific_papers = :id_scientific_papers    ';
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':topic', $topic, PDO::PARAM_STR);
            $prpStmt->bindParam(':type', $type, PDO::PARAM_STR);
            $prpStmt->bindValue(':written', $written->format('d-m-Y'), PDO::PARAM_STR);
            $prpStmt->bindParam(':id_scientific_papers', $id_scientific_papers, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            // output error message 
            return "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected
        if ($prpStmt->rowCount() == 1)
            return 'Podatki znanstvenega dela so uspešno ažurirani.';
        return 'Podatki znanstvenega dela niso uspešno ažurirani.';
    } // updateScientificPapers

    /*
    *   delete scientific paper and its belonging documents 
    *   @param int $id_scientific_papers
    */
    public function deleteScientificPaper(int $id_scientific_papers)
    {
        // action report
        $report = '';
        $report .= $this->deleteDocuments($id_scientific_papers);
        $report .= $this->deletePartakingsOnScientificPaper($id_scientific_papers);
        $stmt = '   DELETE FROM
                        scientific_papers 
                    WHERE 
                        id_scientific_papers = :id_scientific_papers    ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_scientific_papers', $id_scientific_papers, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected
        if ($prpStmt->rowCount() == 1)
            return $report .= 'Znanstveno delo je uspešno izbrisano.';
        return $report .= 'Znanstevno delo ni uspešno izbrisano.';
    } // deleteScientificPaper

    /*
    *   delete all partakings on a scientific paper
    *   @param int $id_scientific_papers
    */
    public function deletePartakingsOnScientificPaper(int $id_scientific_papers)
    {
        $stmt = '   DELETE FROM 
                        partakings
                    WHERE 
                        id_scientific_papers = :id_scientific_papers    ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_scientific_papers', $id_scientific_papers, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single or more rows are affected
        if ($prpStmt->rowCount() >= 1)
            return 'Podatki o soavtorstvih so uspešno izbrisani.' . PHP_EOL;
        return 'Podatki o soavtorstvih niso uspešno izbrisani.' . PHP_EOL;
    } // deletePartakings

    /*
    *   insert partaking particulars 
    *   @param int $id_students
    *   @param int $id_scientific_papers
    *   @param string $part
    */
    public function insertPartakingsOnScientificPaper(int $id_scientific_papers, int $id_attendances, string $part)
    {
        $stmt = '   INSERT INTO 
                        partakings
                    (
                        id_scientific_papers,
                        id_attendances, 
                        part
                    )
                    VALUES(
                        :id_scientific_papers,
                        :id_attendances,
                        :part
                    )   ';
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_attendances', $id_attendances, PDO::PARAM_INT);
            $prpStmt->bindParam(':id_scientific_papers', $id_scientific_papers, PDO::PARAM_INT);
            $prpStmt->bindParam(':part', $part, PDO::PARAM_STR);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected 
        if ($prpStmt->rowCount() == 1)
            return TRUE;
        return FALSE;
    } // insertPartakingsOnScientificPaper

    /*
    *   update part in writing 
    *   @param int $id_partakings
    *   @param string $part
    */
    public function updatePartakings(int $id_partakings, string $part)
    {
        $stmt = '   UPDATE 
                        partakings 
                    SET 
                        part = :part
                    WHERE 
                        id_partakings = :id_partakigns  ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':part', $part, PDO::PARAM_STR);
            $prpStmt->bindParam(':id_partakings', $id_partakings, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected 
        if ($prpStmt->rowCount() == 1)
            return 'Vloga soavtorja študija je uspešno ažurirana.';
        else
            return 'Napaka: vloga soavtorja študija ni uspešno ažurirana.';
    } // updatePartakings

    /*
    *   insert mentoring of scientific paper
    *   @param int $id_scientific_papers
    *   @param int $id_faculties
    *   @param string $mentor
    *   @param string $email
    *   @param string $telephone
    */
    public function insertMentorings(int $id_scientific_papers, int $id_faculties, string $mentor, string $email, string $telephone)
    {
        $stmt = '   INSERT INTO 
                        mentorings
                    (
                        id_faculties,
                        id_scientific_papers,
                        mentor, 
                        taught, 
                        email, 
                        telephone
                    )
                    VALUES(
                        :id_faculties,
                        :id_scientific_papers,
                        :mentor,
                        :taught,
                        :email,
                        :telephone
                    )   ';
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_faculties', $id_faculties, PDO::PARAM_INT);
            $prpStmt->bindParam(':id_scientific_papers,', $id_scientific_papers, PDO::PARAM_INT);
            $prpStmt->bindParam(':mentor,', $mentor, PDO::PARAM_STR);
            $prpStmt->bindParam(':taught,', $taught, PDO::PARAM_STR);
            $prpStmt->bindParam(':email,', $email, PDO::PARAM_STR);
            $prpStmt->bindParam(':telephone,', $telephone, PDO::PARAM_STR);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected 
        if ($prpStmt->rowCount() == 1)
            return 'Podatki o mentorju znanstvenega dela je uspešno vstavljeni.';
        else
            return 'Napaka: podatki o mentorju znanstvenega dela niso uspešno vstavljeni.';
    } // insertMentorings

    /*
    *   update mentoring of scientific paper
    *   @param int $id_mentorings
    *   @param int $id_scientific_papers
    *   @param int $id_faculties
    *   @param string $mentor
    *   @param string $email
    *   @param string $telephone
    */
    public function updateMentorings(int $id_mentorings, int $id_scientific_papers, int $id_faculties, string $mentor, string $email, string $telephone)
    {
        $stmt = '   UPDATE 
                        mentorings 
                    SET 
                        id_faculties = :id_faculties,
                        mentor = :mentor,
                        email = :email,
                        telephone = :telephone  
                    WHERE 
                        id_mentorings = :id_mentorings AND id_scientific_papers = :id_scientific_papers ';
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_faculties', $id_faculties, PDO::PARAM_INT);
            $prpStmt->bindParam(':mentor', $mentor, PDO::PARAM_STR);
            $prpStmt->bindParam(':taught', $taught, PDO::PARAM_STR);
            $prpStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $prpStmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $prpStmt->bindParam(':id_mentorings', $id_mentorings, PDO::PARAM_INT);
            $prpStmt->bindParam(':id_scientific_papers', $id_scientific_papers, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected 
        if ($prpStmt->rowCount() == 1)
            return 'Podatki o mentorju znanstvenega dela so uspešno ažurirani.';
        else
            return 'Napaka: podatki o mentorju znanstvenega dela niso uspešno ažurirani.';
    } // updateMentorings

    /*
    *   delete mentoring of scientific paper
    *   @param int $id_mentorings
    */
    public function deleteMentorings(int $id_mentorings)
    {
        $stmt = '   DELETE FROM 
                        mentorings 
                    WHERE 
                        id_mentorings = :id_mentorings  ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_mentorings', $id_mentorings, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            // output error message 
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected
        if ($prpStmt->rowCount())
            return 'Podatki o mentorstvu so uspešno izbrisani.';
        else
            return 'Podatki o mentorstvu niso uspešno izbrisani.';
    } // deleteMentorings

    /*
    *   select scientific paper belonging document
    *   @param int id_scientific_papers
    */
    public function selectDocuments($id_scientific_papers)
    {
        $resultSet = [];
        $stmt = 'SELECT 
                    *
                FROM 
                    documents
                WHERE 
                    id_scientific_papers = :id_scientific_papers    ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':id_scientific_papers', $id_scientific_papers, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single or more rows are affected
        if ($prpStmt->rowCount() >= 1)
            $resultSet = $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Documents::class, ['id_documents', 'source', 'published', 'version']);
        return $resultSet;
    } // selectDocuments

    /*
    *   insert document of scientific paper
    *   @param int $id_scientific_papers
    */
    public function insertDocument(int $id_scientific_papers, string $version, string $document)
    {
        // if not already running a transaction
        if (!$this->inTransaction()) {
            try {
                // begin a new transaction
                $this->beginTransaction();
                foreach ($_FILES['document']['tmp_name'] as $indx => $tmp_name) {
                    // if document is among uploaded files
                    if ($_FILES['document']['name'][$indx] == $document) {
                        // if document is uploaded successfully 
                        if ($_FILES['document']['error'][$indx] == UPLOAD_ERR_OK) {
                            $finfo = new finfo();
                            $mimetype = $finfo->file($tmp_name, FILEINFO_MIME_TYPE);
                            // if it's not a PDF document
                            if (strpos($mimetype, 'pdf') == FALSE)
                                return "Napaka: dokument '{$_FILES['document']['name'][$indx]}' ni uspešno dodan saj ni tipa .pdf .";
                            $upload = TRUE;
                            // if document meets the condition 
                            if ($upload) {
                                // set destination of the uploded file
                                $dir = 'uploads/documents/';
                                $destination = $dir . (new DateTime())->format('dmYHsi') . basename($_FILES['document']['name'][$indx]);
                                $stmt = '   INSERT INTO
                                                documents
                                            (
                                                id_scientific_papers,
                                                source,
                                                published,
                                                version
                                            )
                                            VALUES(
                                                :id_scientific_papers,
                                                :source,
                                                :published,
                                                :version
                                            )   ';
                                // prepare, bind params to and execute stmt
                                $prpStmt = $this->prepare($stmt);
                                $prpStmt->bindParam(':id_scientific_papers', $id_scientific_papers, PDO::PARAM_INT);
                                $prpStmt->bindParam(':source', $destination, PDO::PARAM_STR);
                                $prpStmt->bindValue(':published', (new DateTime())->format('d-m-Y'), PDO::PARAM_STR);
                                $prpStmt->bindValue(':version', $version, PDO::PARAM_STR);
                                $prpStmt->execute();
                                // if documnet was inserted into db and moved to a new destination  
                                if ($prpStmt->rowCount() == 1 && move_uploaded_file($tmp_name, "../{$destination}")) {
                                    // commit current transaction
                                    $this->commit();
                                    return "Dokument {$_FILES['document']['name'][$indx]} je uspešno vstavljen." . PHP_EOL;
                                } // if
                                // rollback current transaction
                                $this->rollBack();
                                return 'Napaka: podatki dokumenta niso uspešno vstavljeni v zbirko ali datoteka ni uspešno prenesena na strežnik.' . PHP_EOL;
                            } // if
                            return "Napaka: dokument {$_FILES['document']['name'][$indx]} ni zadostil kriterij nalaganja." . PHP_EOL;
                        } // if
                        return "Napaka: dokument {$_FILES['document']['name'][$indx]} ni uspešno naložen." . PHP_EOL;
                    } // if
                } // foreach
            } // try
            catch (PDOException $e) {
                // output error message 
                return "Napaka: {$e->getMessage()}." . PHP_EOL;
            } // catch 
        } // if
        else
            return 'Nakapa: transakcija s podatkovno zbirko je v izvajanju.' . PHP_EOL;
    } // insertDocument

    /*
    *   update version of a document
    *   @param int $id_documents
    *   @param string $version
    */
    public function updateDocument(int $id_documents, string $version)
    {
        try {
            $stmt = '   UPDATE  
                                documents
                        SET 
                            version = :version 
                        WHERE 
                            id_documents = :id_documents    ';
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':version', $version, PDO::PARAM_STR);
            $prpStmt->bindParam(':id_documents', $id_documents, PDO::PARAM_INT);
            $prpStmt->execute();
            // if single one document is affected
            if ($prpStmt->rowCount() == 1)
                return 'Verzija dokumenta je uspešno ažurirana.';
            else
                return 'Napaka: Verzija dokumenta ni uspešno ažurirana.';
        } // try
        catch (PDOException $e) {
            // output error message 
            return "Napaka: {$e->getMessage()}.";
        } // catch
    } // updateDocuments

    /*
    *   delete particular document of a scientific paper
    *   @param int $id_documents
    */
    public function deleteDocument(string $source)
    {
        $stmt = '   DELETE FROM 
                            documents
                    WHERE 
                            source = :source    ';
        try {
            // prepare, bind param to and execute stmt 
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':source', $source, PDO::PARAM_STR);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected and document physically deleted
        if ($prpStmt->rowCount() == 1 && unlink("../{$source}"))
            return 'Dokument ' . basename($source) . ' je uspešno izbrisan.';
        return 'Dokument ' . basename($source) . ' ni uspešno izbrisan.';
    } // deleteDocument

    /*
    *   delete all documents of scientific paper
    *   @param int $id_scientific_papers
    */
    public function deleteDocuments(int $id_scientific_papers)
    {
        // action report
        $report = '';
        // belonging documents of a scientific paper
        $documents = $this->selectDocuments($id_scientific_papers);
        foreach ($documents as $document) {
            $stmt = '   DELETE FROM 
                            documents
                        WHERE 
                            id_documents = :id_documents    ';
            // prepare, bind param to and execute stmt 
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindValue(':id_documents', $document->getIdDocuments(), PDO::PARAM_INT);
            $prpStmt->execute();
            // if single row is affected and document deleted
            if ($prpStmt->rowCount() == 1 && unlink("../{$document->getSource()}")) {
                $report .= 'Dokument ' . basename($document->getSource()) . ' je uspešno izbrisan.' . PHP_EOL;
                continue;
            } // if
            $report .= 'Dokument ' . basename($document->getSource()) . ' ni uspešno izbrisan.' . PHP_EOL;
            continue;
        } // foreach
        return $report;
    } // deleteDocuments          

    /*
    *   check for student account 
    *   @param int $id_attendances
    */
    public function checkStudentAccount($id_attendances)
    {
        $stmt = '   SELECT 
                        * 
                    FROM 
                        accounts 
                    WHERE 
                    id_attendances = :id_attendances  ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':id_attendances', $id_attendances, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            return "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected
        if ($prpStmt->rowCount() == 1) {
            return TRUE;
        } // if
        return FALSE;
    } // checkStudentAccount

    /*
    *   check for account credentials
    *   @param int $index
    *   @param int $pass
    */
    public function checkAccountCredentials(string $index, string $pass)
    {
        // action report
        $report = [
            'script' => '',
            'message' => ''
        ];
        $stmt = '   SELECT 
                        pass 
                    FROM 
                        students
                        INNER JOIN attendances 
                        USING(id_students) 
                    WHERE 
                        index = :index  ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':index', $index, PDO::PARAM_INT);
            $prpStmt->execute();
            // if single row is affected
            if ($prpStmt->rowCount() == 1) {
                $credentials = $prpStmt->fetch(PDO::FETCH_COLUMN);
                // if hash is composed of given pass
                if (password_verify($pass, $credentials['pass'])) {
                    // register var and assign index
                    $_SESSION['user'] = 'stu_' . $index;
                    // register var and assign pass
                    $_SESSION['pass'] = $pass;
                    $_SESSION['index'] = $index;
                    $report['message'] = 'Prijava študenta je bila uspešna.';
                    $report['script'] = 'Accounts/student/home.php';
                } // if
                else
                    $report['message'] = 'Geslo računa z dano indeks številko ni pravilno.';
            } // if
            else {
                // if credentials from superuser
                if (strpos(self::SUPERUSER, $index) && self::PASS == $pass) {
                    // register var and assign index
                    $_SESSION['user'] = self::SUPERUSER;
                    // register var and assign pass
                    $_SESSION['pass'] = self::PASS;
                    $_SESSION['authorized'] = TRUE;
                    $report['message'] = 'Prijava pooblaščenega je bila uspešna.';
                    $report['script'] = 'Accounts/authorized/home.php';
                } // if
                else
                    $report['message'] = 'Račun z dano indeks številko ne obstaja.';
            } // else
        } // try
        catch (PDOException $e) {
            $report['message'] = "Napaka: {$e->getMessage()}.";
        } // catch
        // return JSON value 
        return json_encode($report);
    } // checkAccountCredentials

    /*
    *   insert student account
    *   @param int $id_attendances
    *   @param string $pass
    */
    public function insertAccount(int $id_attendances, string $pass)
    {
        $stmt = '   INSERT INTO 
                        accounts
                    (  
                        id_attendances,
                        pass,
                        granted,
                        avatar
                    )
                    VALUES(
                        :id_attendances,
                        :pass,
                        :granted,
                        DEFAULT
                    )   ';
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_attendances', $id_attendances, PDO::PARAM_INT);
            $prpStmt->bindValue(':pass', password_hash($pass, PASSWORD_BCRYPT), PDO::PARAM_STR);
            $prpStmt->bindValue(':granted', (new DateTime())->format('d-m-Y'), PDO::PARAM_STR);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            // output error message
            return "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected 
        if ($prpStmt->rowCount() == 1)
            return 'Račun je uspešno ustvarjen.';
        return 'Račun ni uspešno ustvarjen.';
    } // insertAccount

    /*
    *   get particulars of the given account
    *   @param int $id_attendances
    */

    public function getAccountParticulars($id_attendances)
    {
        $stmt = '   SELECT
                        granted
                    FROM 
                        accounts
                    WHERE 
                        id_attendances = :id_attendances  ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':id_attendances', $id_attendances, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            return "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected
        if ($prpStmt->rowCount() == 1) {
            $account = $prpStmt->fetch(PDO::FETCH_COLUMN);
            return (new DateTime($account))->format('d-m-Y');
        } // if
        return NULL;
    } // getAccountParticulars

    /*
    *   update an account password
    *   @param int $id_accounts
    *   @param string $new
    *   @param string $confirmation
    */
    public function updateAccountPass($id_accounts, $new, $confirmation)
    {
        // if passwords match 
        if ($new == $confirmation) {
            $stmt = '   UPDATE 
                        accounts 
                    SET
                        pass = :pass    
                    WHERE 
                        id_account = :id_account    ';
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindValue(':pass', password_hash($confirmation, PASSWORD_BCRYPT), PDO::PARAM_STR);
            $prpStmt->bindParam(':id_account', $id_accounts, PDO::PARAM_INT);
            $prpStmt->execute();
            // if single row is affected
            if ($prpStmt->rowCount() == 1)
                return 'Geslo računa je uspešno spremenjeno.';
            else
                return 'Napaka: geslo računa ni uspešno spremenjeno.';
        } // if
    } // alterAccountPass

    /*
    *   delete student account 
    *   @param int $id_accounts
    */
    public function deleteStudentAccount($id_attendances)
    {
        $stmt = '   DELETE FROM 
                        accounts
                    WHERE 
                        id_attendances = :id_attendances    ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_attendances', $id_attendances, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected
        if ($prpStmt->rowCount() == 1)
            return 'Račun je uspešno izbrisan.';
        return 'Račun ni uspešno izbrisan.';
    } // deleteStudentAccount

} // DBC
