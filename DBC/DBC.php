<?php

namespace DBC;

// namespace and class import declaration

use PDO, PDOException, finfo, DateTime, ScientificPapers\ScientificPapers, Certificates\Certificates, Documents\Documents, Partakings\Partakings, Mentorings\Mentorings, Faculties\Faculties, Programs\Programs, Countries\Countries, PostalCodes\PostalCodes;

// extend integtated PDO interface
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
            // compose an error message
            echo "Napaka: {$e->getMessage()}.";
        } // catch 
    } // __construct

    /*
    *   select all scientific papers
    *   @param int $id_attendances
    */
    public function selectScientificPapers()
    {
        $stmt = "   SELECT 
                        (students.name || ' ' || students.surname) AS author,
                        scientific_papers.*,
                        attendances.id_attendances,
                        graduations.id_certificates
                    FROM
                        students    
                        INNER JOIN attendances 
                        USING(id_students)
                        INNER JOIN scientific_papers
                        USING(id_attendances)
                        LEFT JOIN graduations 
                        USING(id_attendances)    
                    ORDER BY
                        scientific_papers.written DESC    ";
        try {
            // prepare and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->execute();
            return  $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, ScientificPapers::class, ['id_scientific_papers', 'id_attendances', 'topic', 'type', 'written']);
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // selectScientificPapers


    /*
    *   select all scientific papers written while student attended a specific program
    *   @param int $id_attendances
    */
    public function selectSciPapsByProgAttendance($id_attendances)
    {
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
            return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, ScientificPapers::class, ['id_scientific_papers', 'id_attendances', 'topic', 'type', 'written']);
        } // try
        catch (PDOException $e) {
            return "Napaka: {$e->getMessage()}.";
        } // catch
    } // selectScientificPapers

    /*
    *   select scientific papers written by the given author 
    *   @param string $author
    */
    public function selectSciPapsByAuthor(string $author)
    {
        $stmt = "   SELECT 
                        (students.name || ' ' || students.surname) AS author,
                        scientific_papers.*,
                        attendances.id_attendances,
                        graduations.id_certificates
                    FROM
                        students    
                        INNER JOIN attendances 
                        USING(id_students)
                        INNER JOIN scientific_papers
                        USING(id_attendances)
                        LEFT JOIN graduations 
                        USING(id_attendances)    
                    WHERE 
                        UPPER(students.name || ' ' || students.surname) LIKE UPPER(:author)
                    ORDER BY
                        scientific_papers.written DESC  ";
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindValue(':author', "{$author}%", PDO::PARAM_STR);
            $prpStmt->execute();
            return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, ScientificPapers::class, ['id_scientific_papers', 'id_attendances', 'type', 'topic', 'written']);
        } // try
        catch (PDOException $e) {
            // output error message 
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // selectSciPapsByAuthor

    /*
    *   select scientific papers mentored by the given mentor 
    *   @param string $mentor
    */
    public function selectSciPapsByMentor(string $mentor)
    {
        $stmt = "   SELECT 
                        (students.name || ' ' || students.surname) AS author,
                        scientific_papers.*,
                        mentorings.mentor,
                        attendances.id_attendances,
                        graduations.id_certificates
                    FROM
                        students    
                        INNER JOIN attendances 
                        USING(id_students)
                        INNER JOIN scientific_papers
                        USING(id_attendances)
                        INNER JOIN mentorings
                        USING(id_scientific_papers)
                        LEFT JOIN graduations 
                        USING(id_attendances)    
                    WHERE 
                        UPPER(mentorings.mentor) LIKE UPPER(:mentor)
                    ORDER BY
                        scientific_papers.written DESC  ";
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindValue(':mentor', "%{$mentor}%", PDO::PARAM_STR);
            $prpStmt->execute();
            return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, ScientificPapers::class, ['id_scientific_papers', 'id_attendances', 'type', 'topic', 'written']);
        } // try
        catch (PDOException $e) {
            // output error message 
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // selectSciPapsByMentor

    /*
    *   select scientific papers written at the given year 
    *   @param string $year
    */
    public function selectScientificPapersByYear(string $year)
    {
        $stmt = "   SELECT 
                        (students.name || ' ' || students.surname) AS author,
                        scientific_papers.*,
                        attendances.id_attendances,
                        graduations.id_certificates
                    FROM
                        students    
                        INNER JOIN attendances 
                        USING(id_students)
                        INNER JOIN scientific_papers
                        USING(id_attendances)
                        LEFT JOIN graduations 
                        USING(id_attendances)    
                    WHERE 
                        DATE_PART('year', written) = :year
                    ORDER BY
                        scientific_papers.written DESC  ";
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':year', $year, PDO::PARAM_STR);
            $prpStmt->execute();
            return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, ScientificPapers::class, ['id_scientific_papers', 'id_attendances', 'type', 'topic', 'written']);
        } // try
        catch (PDOException $e) {
            // output error message 
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // selectSciPapsByYear

    /*
    *   filter and select scientific papers by their topics
    *   @param string $topic
    */
    public function selectSciPapsByTopic(string $topic)
    {
        $stmt = '   SELECT 
                        scientific_papers.id_scientific_papers,
                        scientific_papers.topic,
                        scientific_papers.type,
                        scientific_papers.written
                    FROM 
                        scientific_papers
                        INNER JOIN attendances
                        USING(id_attendances)
                    WHERE
                        id_attendances = 
                        (
                            SELECT 
                                id_attendances
                            FROM 
                                attendances
                            WHERE 
                                index = :index
                        )
                        AND 
                        UPPER(scientific_papers.topic) LIKE UPPER(:topic)   ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindValue(':index', $_SESSION['index'], PDO::PARAM_STR);
            $prpStmt->bindValue(':topic', "{$topic}%", PDO::PARAM_STR);
            $prpStmt->execute();
            return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, ScientificPapers::class, ['id_scientific_papers', 'id_attendances', 'topic', 'type', 'written']);
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage}.";
        } // catch
    } // selectSciPapsByTopic

    /*
    *   select all scientific papers according to the index number of the student attending the program
    *   @param int $index
    */
    public function selectStudtSciPapers(string $index)
    {
        $stmt = '   SELECT 
                        scientific_papers.id_scientific_papers,
                        scientific_papers.topic,
                        scientific_papers.type,
                        scientific_papers.written
                    FROM
                        scientific_papers
                        INNER JOIN attendances
                        USING(id_attendances)
                    WHERE 
                        attendances.index = :index    ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':index', $index, PDO::PARAM_STR);
            $prpStmt->execute();
            return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, ScientificPapers::class, ['id_scientific_papers', 'id_attendances', 'topic', 'type', 'written']);
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // selectStudtSciPapers

    /*
    *   select the given scientific paper record 
    *   @param int $id_scientific_papers
    */
    public function selectScientificPaper(int $id_scientific_papers)
    {
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
            return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, ScientificPapers::class, ['id_scientific_papers', 'id_attendances', 'topic', 'type', 'written'])[0];
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // selectScientificPaper

    /*
    *   select all students 
    *   @param string $order
    */
    public function selectStudents(string $order = 'ASC')
    {
        $stmt = "   SELECT 
                        students.id_students,
                        (students.name || ' ' || students.surname) AS fullname, 
                        attendances.id_attendances,
                        attendances.index,
                        faculties.name AS faculty, 
                        programs.name AS program,
                        programs.degree
                    FROM 
                        students 
                        INNER JOIN attendances
                        USING(id_students)
                        INNER JOIN faculties
                        USING(id_faculties)
                        INNER JOIN programs
                        USING(id_programs)
                    ORDER BY
                        students.surname {$order}   ";
        try {
            // prepare and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->execute();
            return $prpStmt->fetchAll(PDO::FETCH_OBJ);
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // selectStudents

    /*
    *   select students by index number 
    *   @param int $index
    *   @param string $order
    */
    public function selectStudentsByIndex(string $index, string $order = 'ASC')
    {
        $stmt = "   SELECT 
                        students.id_students,
                        (students.name || ' ' || students.surname) AS fullname, 
                        attendances.id_attendances,
                        attendances.index,
                        faculties.name AS faculty, 
                        programs.name AS program,
                        programs.degree
                    FROM 
                        students 
                        INNER JOIN attendances
                        USING(id_students)
                        INNER JOIN faculties
                        USING(id_faculties)
                        INNER JOIN programs
                        USING(id_programs)
                    WHERE 
                        attendances.index LIKE :index
                    ORDER BY
                        fullname {$order}   ";
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindValue(':index', "{$index}%", PDO::PARAM_STR);
            $prpStmt->execute();
            return $prpStmt->fetchAll(PDO::FETCH_OBJ);
        } // if
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // selectStudentsByIndex 

    /*
    *   select particulars of a student
    *   @param int $id_students
    */
    public function selectStudtParticulars(int $id_students)
    {
        $stmt = '   SELECT 
                        students.*,
                        postal_codes.id_countries
                    FROM
                        students
                        INNER JOIN postal_codes
                        USING(id_postal_codes)
                    WHERE 
                        students.id_students = :id_students  ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_students', $id_students, PDO::PARAM_INT);
            $prpStmt->execute();
            return json_encode($prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Students::class, ['id_students', 'id_postal_codes', 'name', 'surname', 'email', 'telephone'])[0]);
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // selectStudtParticulars

    /* 
    *   select residences of the given student
    *   @param int $id_students
    */
    public function selectStudtResidences(int $id_students)
    {
        // permanent and temporary residences
        $residences = [
            'permResidence' => NULL,
            'tempResidence' => []
        ];
        $stmt = '  SELECT 
                        residences.id_residences,
                        residences.id_postal_codes,
                        residences.address,
                        residences.status
                        postal_codes.id_countries,
                    FROM
                        residences
                        INNER JOIN postal_codes
                        USING(id_postal_codes)
                    WHERE 
                        residences.id_students = :id_students
                    ORDER BY 
                        residences.status ';
        try {
            // prepare, bind params to and execute stmts
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':id_students', $id_students, PDO::PARAM_INT);
            $prpStmt->execute();
            foreach ($prpStmt->fetchAll(PDO::FETCH_ASSOC) as $residence) {
                // designate residence according to its status
                if ($residence['status'] == 'STALNO')
                    $residences['permResidence'] = $residence;
                else
                    array_push($residences['tempResidence'], $residence);
            } // foreach
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return json_encode($residences);
    } // selectStudtResidences

    /*
    *   insert permanent and temporary residences of a student
    *   @param int $id_students
    *   @param Array $residenes
    */
    private function insertStudtResidences(int $id_students, array $residences)
    {
        // insert report
        $report = '';
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
        foreach ($residences as $residence) {
            try {
                // prepare, bind params to and execute stmt
                $prpStmt = $this->prepare($stmt);
                $prpStmt->bindParam(':id_postal_codes', $residence['id_postal_codes'], PDO::PARAM_INT);
                $prpStmt->bindParam(':id_students', $id_students, PDO::PARAM_INT);
                $prpStmt->bindParam(':address', $residence['address'], PDO::PARAM_STR);
                $prpStmt->bindParam(':status', $residence['status'], PDO::PARAM_STR);
                $prpStmt->execute();
                // if single row is affected
                if ($prpStmt->rowCount() == 1)
                    $report .= "Bivališče na naslovu '{$residence['address']}' je evidentirano kot {$residence['status']}." . PHP_EOL;
                else
                    $report .= "Bivališče na naslovu '{$residence['address']}' ni evidentirano." . PHP_EOL;
            } // try
            catch (PDOException $e) {
                echo "Napaka: {$e->getMessage()}.";
            } // catch 
        } // foreach
        return $report;
    } // insertStudtResidences

    /*
    *   delete the given temporary residence of a student 
    *   @param int $id_residences
    */
    public function deleteStudtTempResidence(int $id_residences)
    {
        $stmt = '   DELETE FROM
                        residences
                    WHERE 
                        id_residences = :id_residences   ';
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_residences', $id_residences, PDO::PARAM_INT);
            $prpStmt->execute();
            // if single row is affected
            if ($prpStmt->rowCount() == 1)
                return 'Bivališče je uspešno izbrisano.';
            return 'Bivališče ni uspešno izbrisano.';
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // deleteStudtTempResidence

    /*
    *   check if student resides at 
    *   @param int id_students
    *   @param int id_postal_codes
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
            // if single row is affected
            if ($prpStmt->rowCount() == 1)
                return TRUE;
            return FALSE;
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // checkIfResides

    /*
    *   select postal codes of the given country 
    *   @param int $id_countries
    */
    public function selectPostalCodes(int $id_countries)
    {
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
            return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, PostalCodes::class, ['id_postal_codes', 'id_countries', 'municipality', 'code']);
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // selectPostalCodes

    // select all countries
    public function selectCountries()
    {
        $stmt = '   SELECT 
                        * 
                    FROM 
                        countries    ';
        try {
            // prepare and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->execute();
            return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Countries::class, ['id_countries', 'name', 'iso_3_code']);
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // selectCountries

    // select all faculties 
    public function selectFaculties()
    {
        $stmt = '   SELECT 
                        * 
                    FROM 
                        faculties    ';
        try {
            // prepare and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->execute();
            return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Faculties::class, ['id_faculties', 'id_postal_codes', 'name', 'address', 'email', 'telephone', 'dean']);
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // selectFaculties

    /*
    *   select faculty offered programs 
    *   @param int $id_faculties 
    */
    public function selectPrograms(int $id_faculties)
    {
        $stmt = '   SELECT 
                        DISTINCT(programs.*) 
                    FROM 
                        programs    
                        INNER JOIN faculties
                        USING(id_faculties)
                    WHERE 
                        faculties.id_faculties = :id_faculties  ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':id_faculties', $id_faculties, PDO::PARAM_INT);
            $prpStmt->execute();
            return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Programs::class, ['id_programs', 'name', 'degree', 'duration', 'field']);
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // selectPrograms

    /*
    *   insert student basics  
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
        // insertion report
        $report = [
            'id_students' => 0,
            'mssg' => ''
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
            // if student record was inserted
            if ($prpStmt->rowCount() == 1) {
                $id_students = $this->lastInsertId('students_id_students_seq');
                // form a report
                $report['id_students'] = $id_students;
                $report['mssg'] = 'Osnovni podatki študenta so uspešno evidentirani.' . PHP_EOL;
                return $report['mssg'] .= $this->insertStudtResidences($id_students, $residences);
            } // if
            return $report['message'] = 'Napaka: osnovni podakti študenta ter podatki o prebivališču niso uspešno vstavljeni.';
        } // try
        catch (PDOException $e) {
            echo  "Napaka: {$e->getMessage()}.";
        } // catch
    } // insertStudent

    /*
    *   update student basics
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
            // if values of the single record were updated
            if ($prpStmt->rowCount() == 1)
                $report = 'Osnovni podatki študenta so uspešno posodobljeni.' . PHP_EOL;
            else
                $report = 'Osnovni podatki študenta niso uspešno posodobljeni.' . PHP_EOL;
            return ($report .= $this->updateStudentResidences($id_students, $residences));
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // updateStudent

    /*
    *   delete all data related to a particular program attendance by student 
    *   @param int $id_attendances 
    *   @param int $id_students 
    *   @param string $index 
    */
    public function deleteStudent(int $id_attendances, int $id_students, string $index)
    {
        $this->deleteStudentResidences($id_students);
        // if graduated
        $certificate = $this->selectCertificate($id_attendances);
        if (count($certificate) == 1)
            $this->deleteGraduation($id_attendances, $certificate[0]->getSource());
        // if any scientific paper was written 
        $scientificPapers = $this->selectScientificPapers($id_attendances);
        if (count($scientificPapers) >= 1)
            foreach ($scientificPapers as $scientificPaper) {
                $this->deleteScientificPaper($scientificPaper->getIdScientificPapers());
            } // foreach
        // if account was granted to
        if ($this->checkStudtAcct($id_attendances))
            $this->deleteStudtAcct($id_attendances, $index);
        $this->deleteStudentProgramAttendance($id_attendances);
        try {
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
            return 'Podatki o študentu ter znanstvenih dosežkih so uspešno izbrisani.';
        return 'Podatki o študentu ter znanstvenih dosežkih niso uspešno izbrisani.';
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
    *   select particulars of the program attendance by the student
    *   @param int $id_attendances
    */
    public function selectProgAttnParticulars(int $id_attendances)
    {
        $stmt = '   SELECT 
                        (students.name || students.surname) AS student,
                        students.email, 
                        attendances.index, 
                        attendances.enrolled,
                        graduations.defended,
                        faculties.name AS program, 
                        programs.name AS faculty
                    FROM 
                        students
                        INNER JOIN attendances
                        USING(id_students)
                        LEFT JOIN graduations 
                        USING(id_attendances) 
                        INNER JOIN faculties
                        USING(id_faculties)
                        INNER JOIN programs 
                        USING(id_programs)
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
        return $prpStmt->fetch(PDO::FETCH_OBJ);
    } // selectProgAttnParticulars

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
    public function insertGraduation(int $id_attendances, string $certificate, DateTime $defended, DateTime $issued)
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
                                    $report = "Certifikat {$_FILES['certificate']['name'][$indx]} je uspešno naložen." . PHP_EOL;
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
                                        $report .= 'Datuma zagovora diplome ter izdajanja certifikata sta uspešno določena.';
                                        // commit current transaction
                                        $this->commit();
                                        return $report;
                                    } // if
                                } // if
                                // rollback current transaction
                                $this->rollBack();
                                return $report = 'Napaka: postopek nalaganja certifikata in določanja datuma zagovora ter izdajanja je bil neuspešen.';
                            } // if
                        } // if
                        return $report = "Napaka: certifikat {$_FILES['certificate']['name'][$indx]} ni uspešno naložen.";
                    } // if
                } // foreach
            } // try
            catch (PDOException $e) {
                // output error message 
                return "Napaka: {$e->getMessage()}.";
            } // catch 
        } // if
        else
            return 'Nakapa: druga transakcija s podatkovno zbirko je v izvajanju.';
    } // insertGraduation

    /*
    *   update graduation defence data 
    *   @param int $id_attendances
    *   @param int $id_students
    */
    public function updateGraduationDefenceDate(int $id_certificates, DateTime $defended)
    {
        $stmt = '   UPDATE 
                        graduations 
                    SET 
                        defended = :defended
                    WHERE 
                        id_certificates = :id_certificates';
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindValue(':defended', $defended->format('d-m-Y'), PDO::PARAM_INT);
            $prpStmt->bindParam(':id_certificates', $id_certificates, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected 
        if ($prpStmt->rowCount() == 1)
            return 'Datum zagovora diplome je uspešno spremenjen.';
        return 'Datum zagovora diplome ni uspešno spremenjen.';
    } // updateGraduationDefenceDate

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
                        return 'Podatki o zaključku študija ter certifikat ' . basename($source) . ' so uspešno izbrisani.';
                    } // if
                    // roll back current transaction
                    $this->rollBack();
                } // if 
                return 'Podatki o zaključku študija ter certifikat niso uspešno izbrisani.';
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
                        certificates.*,
                        graduations.defended 
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
    public function updateGradCertIssuingDate(int $id_certificates, DateTime $issued)
    {
        $stmt = '   UPDATE
                        certificates 
                    SET
                        issued = :issued    
                    WHERE 
                        id_certificates = :id_certificates   ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindValue(':issued', $issued->format('d-m-Y'), PDO::PARAM_STR);
            $prpStmt->bindParam(':id_certificates', $id_certificates, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected 
        if ($prpStmt->rowCount() == 1)
            return 'Datum izdajanja certifikata je uspešno spremenjen.';
        return 'Datum izdajanja certifikata ni uspešno spremenjen.';
    } // updateGradCertIssuingDate

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
                $report['message'] = "Delo '{$topic}' je uspešno evidentirano." . PHP_EOL;
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
        // select and delete every single partaker on a scientific paper
        foreach ($this->selectSciPapPartakers($id_scientific_papers) as $partaker)
            $this->deletePartakerOfScientificPaper($partaker->getIdPartakings());
        // select and delete every single mentor of the scientific paper
        foreach ($this->selectSciPapMentors($id_scientific_papers) as $mentor)
            $this->deleteMentorOfScientificPaper($mentor->getIdMentorings());
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
    *   select partakers on a scientific paper
    *   @param int $id_scientific_papers
    */
    public function selectSciPapPartakers(int $id_scientific_papers)
    {
        $stmt = "   SELECT 
                        id_attendances,
                        id_partakings,
                        (name || ' ' || surname) as fullname,
                        part,
                        index
                    FROM 
                        partakings
                        INNER JOIN attendances
                        USING(id_attendances)
                        INNER JOIN students
                        USING(id_students) 
                    WHERE 
                        id_scientific_papers = :id_scientific_papers    ";
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':id_scientific_papers', $id_scientific_papers, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Partakings::class, ['id_partakings', 'id_scientific_papers', 'id_attendances', 'part']);
    } // selectSciPapPartakers

    /*
    *   delete partaker of a scientific papersf
    *   @param int $id_partakings
    */
    public function deletePartakerOfScientificPaper(int $id_partakings)
    {
        $stmt = '   DELETE FROM 
                        partakings
                    WHERE 
                        id_partakings = :id_partakings    ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_partakings', $id_partakings, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected 
        if ($prpStmt->rowCount() == 1)
            return 'Soavtor je uspešno odstranjen.';
        return 'Soavtor ni uspešno odstranjen.';
    } // deletePartakerOfScientificPaper

    /*
    *   insert partaking particulars 
    *   @param int $id_students
    *   @param int $id_scientific_papers
    *   @param string $part
    */
    public function insertPartakerOnScientificPaper(int $id_scientific_papers, int $id_attendances, string $part)
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
    } // insertPartakerOnScientificPaper

    /*
    *   update part in writing 
    *   @param int $id_partakings
    *   @param string $part
    */
    public function updatePartInScientificPaper(int $id_partakings, string $part)
    {
        $stmt = '   UPDATE 
                        partakings 
                    SET 
                        part = :part
                    WHERE 
                        id_partakings = :id_partakings  ';
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
        return 'Napaka: vloga soavtorja študija ni uspešno ažurirana.';
    } // updatePartInScientificPaper

    /*
    *   select mentors of the scientific paper
    *   @param int $id_scientific_papers
    */
    public function selectSciPapMentors($id_scientific_papers)
    {
        $stmt = '   SELECT 
                        mentorings.id_mentorings,
                        mentorings.mentor,
                        mentorings.taught,
                        mentorings.email,
                        faculties.name AS faculty
                    FROM 
                        mentorings
                        INNER JOIN faculties
                        USING(id_faculties)
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
        return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Mentorings::class, ['id_mentorings', 'id_scientific_papers', 'id_faculties', 'mentor', 'taught', 'email', 'telephone']);;
    } // selectSciPapMentors

    /*
    *   insert mentoring of scientific paper
    *   @param int $id_scientific_papers
    *   @param int $id_faculties
    *   @param string $mentor
    *   @param string $email
    *   @param string $telephone
    */
    public function insertMentorOfScientificPaper(int $id_scientific_papers, int $id_faculties, string $mentor, string $taught, string $email, string $telephone)
    {
        $stmt = '   INSERT INTO 
                        mentorings
                    (
                        id_scientific_papers,
                        id_faculties,
                        mentor, 
                        taught, 
                        email, 
                        telephone
                    )
                    VALUES(
                        :id_scientific_papers,
                        :id_faculties,
                        :mentor,
                        :taught,
                        :email,
                        :telephone
                    )   ';
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_scientific_papers', $id_scientific_papers, PDO::PARAM_INT);
            $prpStmt->bindParam(':id_faculties', $id_faculties, PDO::PARAM_INT);
            $prpStmt->bindParam(':mentor', $mentor, PDO::PARAM_STR);
            $prpStmt->bindParam(':taught', $taught, PDO::PARAM_STR);
            $prpStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $prpStmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected 
        if ($prpStmt->rowCount() == 1)
            return TRUE;
        return FALSE;
    } // insertMentorOfScientificPaper

    /*
    *   update mentoring of scientific paper
    *   @param int $id_mentorings
    *   @param int $id_scientific_papers
    *   @param int $id_faculties
    *   @param string $mentor
    *   @param string $email
    *   @param string $telephone
    */
    public function updateMentorOfScientificPaper(int $id_mentorings, int $id_faculties, string $mentor, string $taught, string $email, string $telephone)
    {
        $stmt = '   UPDATE 
                        mentorings 
                    SET 
                        id_faculties = :id_faculties,
                        mentor = :mentor,
                        taught = :taught,
                        email = :email,
                        telephone = :telephone  
                    WHERE 
                        id_mentorings = :id_mentorings  ';
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_faculties', $id_faculties, PDO::PARAM_INT);
            $prpStmt->bindParam(':mentor', $mentor, PDO::PARAM_STR);
            $prpStmt->bindParam(':taught', $taught, PDO::PARAM_STR);
            $prpStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $prpStmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $prpStmt->bindParam(':id_mentorings', $id_mentorings, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected 
        if ($prpStmt->rowCount() == 1)
            return 'Podatki o mentorju znanstvenega dela so uspešno ažurirani.';
        return 'Napaka: podatki o mentorju znanstvenega dela niso uspešno ažurirani.';
    } // updateMentorOfScientificPaper

    /*
    *   delete mentoring of scientific paper
    *   @param int $id_mentorings
    */
    public function deleteMentorOfScientificPaper(int $id_mentorings)
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
        if ($prpStmt->rowCount() == 1)
            return 'Podatki o mentorstvu so uspešno izbrisani.';
        return 'Podatki o mentorstvu niso uspešno izbrisani.';
    } // deleteMentorOfScientificPaper

    /*
    *   select data concerning the given mentor of scientific paper 
    *   @param int $id_mentorings
    */
    public function selectMentorOfScientificPaper(int $id_mentorings)
    {
        $stmt = '   SELECT 
                        mentor,
                        id_faculties,
                        taught,
                        mentorings.email,
                        mentorings.telephone
                    FROM 
                        mentorings
                        INNER JOIN faculties
                        USING(id_faculties) 
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
        if ($prpStmt->rowCount() == 1)
            return json_encode($prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Mentorings::class, ['id_mentorings', 'id_scientific_papers', 'id_faculties', 'mentor', 'taught', 'email', 'telephone'])[0]);
        return [];
    } // selectMentorOfScientificPaper

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
                                    return "Dokument {$_FILES['document']['name'][$indx]} je uspešno naložen." . PHP_EOL;
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
    *   if the student has been assigned with an account
    *   @param int $id_attendances
    */
    public function checkStudtAcct(int $id_attendances)
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
        if ($prpStmt->rowCount() == 1)
            return TRUE;
        return FALSE;
    } // checkStudtAcct

    /*
    *   check for account credentials
    *   @param int $index
    *   @param int $pass
    */
    public function checkAcctCredentials(string $index, string $pass)
    {
        // authentication report
        $report = [
            'logged' => FALSE,
            'message' => ''
        ];
        $stmt = '   SELECT 
                        pass 
                    FROM 
                        accounts 
                    WHERE 
                        id_attendances = (
                                            SELECT 
                                                id_attendances
                                            FROM 
                                                attendances
                                            WHERE 
                                                index = :index 
                                        )   ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':index', $index, PDO::PARAM_INT);
            $prpStmt->execute();
            // if single row is affected
            if ($prpStmt->rowCount() == 1) {
                $hash = $prpStmt->fetch(PDO::FETCH_COLUMN);
                // if hash is composed of given pass
                if (password_verify($pass, $hash)) {
                    // register vars and assign index
                    $_SESSION['user'] = 'stu_' . $index;
                    // register var and assign pass
                    $_SESSION['pass'] = $pass;
                    $_SESSION['index'] = $index;
                    $report['logged'] = TRUE;
                    $report['message'] = 'Prijava študenta je bila uspešna.';
                } // if
                else
                    $report['message'] = 'Geslo računa z dano indeks številko ni pravilno.';
            } // if
            // if passed credentials are for superuser 
            else if (strpos(self::SUPERUSER, $index) && self::PASS == $pass) {
                // register vars and denote the authorized
                $_SESSION['user'] = self::SUPERUSER;
                // register var and assign pass
                $_SESSION['pass'] = self::PASS;
                $_SESSION['authorized'] = TRUE;
                $report['logged'] = TRUE;
                $report['message'] = 'Prijava pooblaščenega je bila uspešna.';
            } // else if
            else
                $report['message'] = 'Račun z dano indeks številko ne obstaja.';
        } // try
        catch (PDOException $e) {
            $report['message'] = "Napaka: {$e->getMessage()}.";
        } // catch
        // return JSON value 
        return json_encode($report);
    } // checkAcctCredentials

    /*
    *   !DML 
    *   create database user in the cluster with student role privileges
    *   @param string $index
    *   @param string $hash
    */
    private function createDBUser(string $index, string $hash)
    {
        $stmt = "   CREATE USER 
                        stu_$index
                    WITH 
                        PASSWORD '$hash'
                        IN ROLE student
                        VALID UNTIL 'infinity'  ";
        try {
            // prepare and execute stmt
            $prpStmt = $this->prepare($stmt);
            // if stmt executed successfully 
            if ($prpStmt->execute())
                return TRUE;
            return FALSE;
        } // try
        catch (PDOException $e) {
            return "Napaka: {$e->getMessage}.";
        } // catch
    } // createDBUser

    /*
    *   !DML 
    *   revoke privileges on database object for the given student role  
    *   @param string $index
    */
    private function revokeUserPrivileges(string $index)
    {
        $stmt = "   REVOKE  
                        ALL PRIVILEGES 
                    ON TABLE
                            students, 
                            faculties, 
                            programs, 
                            postal_codes, 
                            countries,
                            scientific_papers, 
                            partakings, 
                            mentorings, 
                            documents, 
                            residences,
                            attendances, 
                            graduations, 
                            certificates,
                            accounts    
                        FROM 
                            stu_$index  ";
        $stmt2 = "  REVOKE  
                        ALL PRIVILEGES 
                    ON SEQUENCE
                        students_id_students_seq,
                        scientific_papers_id_scientific_papers_seq,
                        partakings_id_partakings_seq, 
                        mentorings_id_mentorings_seq, 
                        documents_id_documents_seq, 
                        residences_id_residences_seq,
                        faculties_id_faculties_seq, 
                        programs_id_programs_seq, 
                        postal_codes_id_postal_codes_seq, 
                        countries_id_countries_seq,
                        attendances_id_attendances_seq,  
                        certificates_id_certificates_seq  
                    FROM 
                            stu_$index  ";
        try {
            // prepare and execute stmts
            $prpStmt = $this->prepare($stmt);
            $prpStmt2 = $this->prepare($stmt2);
            // if stmts were successfully executed
            if ($prpStmt->execute() && $prpStmt2->execute()) {
                return TRUE;
            }
            echo $prpStmt->errorInfo()[2];
            echo $prpStmt2->errorInfo()[2];
            return FALSE;
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // revokeUserPrivileges

    /*
    *   !DML 
    *   drop database user in the cluster with student role privileges
    *   @param string $index
    */
    private function dropDBUser(string $index)
    {
        $stmt = "   DROP USER 
                        stu_$index  ";
        try {
            // prepare and execute stmt
            $prpStmt = $this->prepare($stmt);
            // if stmt executed successfully 
            if ($prpStmt->execute())
                return TRUE;
            return FALSE;
        } // try
        catch (PDOException $e) {
            return "Napaka: {$e->getMessage}.";
        } // catch
    } // dropDBUser

    /*
    *   create new database user and insert account credentials 
    *   @param int $id_attendances
    *   @param string $index
    *   @param string $pass
    */
    public function insertStudtAcct(int $id_attendances, string $index, string $pass)
    {
        // check if not already in a transaction
        if (!$this->inTransaction()) {
            // establish a new transaction
            $this->beginTransaction();
            $hash = password_hash($pass, PASSWORD_BCRYPT);
            if ($this->createDBUser($index, $hash)) {
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
                    $prpStmt->bindValue(':pass', $hash, PDO::PARAM_STR);
                    $prpStmt->bindValue(':granted', (new DateTime())->format('d-m-Y'), PDO::PARAM_STR);
                    $prpStmt->execute();
                    // if single row is affected 
                    if ($prpStmt->rowCount() == 1) {
                        // commit the changes
                        $this->commit();
                        return 'Račun je uspešno ustvarjen.';
                    } // if 
                    // rollback the changes
                    $this->rollback();
                    return 'Račun ni uspešno ustvarjen.';
                } // try
                catch (PDOException $e) {
                    // output error message
                    return "Napaka: {$e->getMessage()}.";
                } // catch
            } // if
            // rollback the changes
            $this->rollback();
            return 'Napaka: uporabniški račun ni uspešno ustvarjen.';
        } // if
        return 'Opozorilo: transkacija s podatkovno zbirko je v izvajanju.';
    } // insertStudtAcct

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
    *   drop the subject user and delete account credentials
    *   @param int $id_attendances
    *   @param string $index
    */
    public function deleteStudtAcct(int $id_attendances, string $index)
    {
        // if not in a transation
        if (!$this->inTransaction()) {
            // begin a new one
            $this->beginTransaction();
            // if the user was droped
            if ($this->revokeUserPrivileges($index) && $this->dropDBUser($index)) {
                $stmt = '   DELETE FROM 
                                accounts
                            WHERE 
                                id_attendances = :id_attendances    ';
                try {
                    // prepare, bind param to and execute stmt
                    $prpStmt = $this->prepare($stmt);
                    $prpStmt->bindParam(':id_attendances', $id_attendances, PDO::PARAM_INT);
                    $prpStmt->execute();
                    // if single row is affected
                    if ($prpStmt->rowCount() == 1) {
                        // commit the current transaction
                        $this->commit();
                        return 'Račun je uspešno izbrisan.';
                    }
                    // rollback the current transaction
                    $this->rollback();
                    return 'Račun ni uspešno izbrisan.';
                } // try
                catch (PDOException $e) {
                    echo "Napaka: {$e->getMessage()}.";
                } // catch
            }
            // rollback the current transaction
            $this->rollback();
            return 'Napaka: uporabniški račun ni uspešno izbrisan.';
        } // if
        return 'Opozorilo: transakcija s podatkovno zbirko je v izvajanju.';
    } // deleteStudtAcct

    /*
    *   if student has an account avatar
    *   @param string $index
    */
    public function hasAcctAvatar(string $index)
    {
        $stmt = '   SELECT 
                        avatar 
                    FROM 
                        accounts
                    WHERE 
                        id_attendances = (
                                            SELECT 
                                                id_attendances
                                            FROM 
                                                attendances
                                            WHERE 
                                                index = :index
                                        )   ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':index', $index, PDO::PARAM_STR);
            $prpStmt->execute();
            $avatar = $prpStmt->fetch(PDO::FETCH_COLUMN);
            // if avatar was uploaded
            if (isset($avatar))
                return $avatar;
            return FALSE;
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // hasAcctAvatar

    /* 
    *   upload avatar for the given account 
    *   @param int $id_attendances
    */
    public function uploadAcctAvtr(int $id_attendances)
    {
        // if not already running a transaction
        if (!$this->inTransaction()) {
            try {
                // begin a new transaction
                $this->beginTransaction();
                // if document is uploaded successfully 
                if ($_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
                    $finfo = new finfo();
                    $mimetype = $finfo->file($_FILES['avatar']['tmp_name'], FILEINFO_MIME_TYPE);
                    // if it's not a PNG document
                    if ($mimetype != 'image/jpeg')
                        return "Napaka: avatar '{$_FILES['avatar']['name']}' ni uspešno naložen saj ni tipa .jpg.";
                    $upload = TRUE;
                    // if document meets the condition 
                    if ($upload) {
                        // set destination of the uploded file
                        $dir = 'uploads/avatars/';
                        $destination = $dir . (new DateTime())->format('dmYHsi') . basename($_FILES['avatar']['name']);
                        $stmt = '   UPDATE 
                                        accounts
                                    SET 
                                        avatar = :avatar 
                                    WHERE 
                                        id_attendances = :id_attendances    ';
                        // prepare, bind params to and execute stmt
                        $prpStmt = $this->prepare($stmt);
                        $prpStmt->bindParam(':avatar', $destination, PDO::PARAM_STR);
                        $prpStmt->bindParam(':id_attendances', $id_attendances, PDO::PARAM_INT);
                        $prpStmt->execute();
                        // if data was inserted into the database and document moved to the server  
                        if ($prpStmt->rowCount() == 1 && move_uploaded_file($_FILES['avatar']['tmp_name'], "../../{$destination}")) {
                            // commit current transaction
                            $this->commit();
                            return "Avatar {$_FILES['avatar']['name']} je uspešno naložen." . PHP_EOL;
                        } // if
                        // rollback current transaction
                        $this->rollBack();
                        return 'Napaka: podatki dokumenta niso uspešno vstavljeni v zbirko ali datoteka ni uspešno prenesena na strežnik.' . $prpStmt->errorInfo()[2] . PHP_EOL;
                    } // if
                    return "Napaka: avatar {$_FILES['avatar']['name']} ni zadostil kriterij nalaganja." . PHP_EOL;
                } // if
                return "Napaka: avatar {$_FILES['avatar']['name']} ni uspešno naložen." . PHP_EOL;
            } // try
            catch (PDOException $e) {
                // output error message 
                return "Napaka: {$e->getMessage()}." . PHP_EOL;
            } // catch 
        } // if
        return 'Nakapa: transakcija s podatkovno zbirko je v izvajanju.' . PHP_EOL;
    } // uploadAcctAvatar

    /* 
    *   delete avatar for the given account 
    *   @param string $id_attendances
    *   @param string $avatar 
    */
    public function deleteAcctAvatar(string $id_attendances, string $avatar)
    {
        // if not already running a transaction
        if (!$this->inTransaction()) {
            try {
                // begin a new transaction
                $this->beginTransaction();
                $stmt = '   UPDATE 
                                accounts
                            SET 
                                avatar = NULL 
                            WHERE 
                                id_attendances = :id_attendances    ';
                // prepare, bind params to and execute stmt
                $prpStmt = $this->prepare($stmt);
                $prpStmt->bindParam(':id_attendances', $id_attendances, PDO::PARAM_INT);
                $prpStmt->execute();
                // if avatar was set to NULL and document removed from the server  
                if ($prpStmt->rowCount() == 1 && unlink("../../{$avatar}")) {
                    // commit current transaction
                    $this->commit();
                    return "Avatar je uspešno izbrisan.";
                } // if
                // rollback current transaction
                $this->rollBack();
                return 'Napaka: lokacija avatarja ni uspešno logično ali fizično odstranjena.';
            } // try
            catch (PDOException $e) {
                // output error message 
                return "Napaka: {$e->getMessage()}." . PHP_EOL;
            } // catch 
        } // if
        return 'Nakapa: transakcija s podatkovno zbirko je v izvajanju.' . PHP_EOL;
    } // deleteAcctAvatar
} // DBC
