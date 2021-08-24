<?php

namespace DBC;

// namespace and class import declaration

use PDO, PDOException, finfo, DateTime, ScientificPapers\ScientificPapers, Students\Students, Certificates\Certificates, Documents\Documents, Partakings\Partakings, Mentorings\Mentorings, Faculties\Faculties, Programs\Programs, Countries\Countries, PostalCodes\PostalCodes;

// extend intergrated PDO interface
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

    // SCIENTIFIC_PAPERS

    //   select all scientific papers to form scientific papers evidence table
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
    public function selectSciPapsByProgAttendance(int $id_attendances)
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
    *   select scientific papers which mentor was 
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
    public function selectSciPapsByYear(string $year)
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
    *   @param string $index
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
    *   select the given scientific paper 
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
    *   insert scientific paper    
    *   @param int $id_attendances
    *   @param string $topic
    *   @param string $type
    *   @param DateTime $written
    */
    public function insertScientificPaper(int $id_attendances, string $topic, string $type, DateTime $written)
    {
        // insertion report
        $report = [
            'id_scientific_papers' => 0,
            'mssg' => ''
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
            // if scientific paper record was inserted
            if ($prpStmt->rowCount() == 1) {
                $report['id_scientific_papers'] = $this->lastInsertId('scientific_papers_id_scientific_papers_seq');
                $report['mssg'] = "Delo '{$topic}' je uspešno evidentirano." . PHP_EOL;
            } // if
        } // try
        catch (PDOException $e) {
            // output error message 
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $report;
    } // insertScientificPaper

    /*
    *   update scientific paper
    *   @param int $id_scientific_papers
    *   @param string $topic
    *   @param string $type
    *   @param DateTime $written
    */
    public function updateScientificPapers(int $id_scientific_papers, string $topic, string $type, DateTime $written)
    {
        $stmt = '   UPDATE  
                            scientific_papers
                        SET 
                            topic = :topic, 
                            type  = :type,
                            written = :written 
                        WHERE 
                            id_scientific_papers = :id_scientific_papers    ';
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':topic', $topic, PDO::PARAM_STR);
            $prpStmt->bindParam(':type', $type, PDO::PARAM_STR);
            $prpStmt->bindValue(':written', $written->format('d-m-Y'), PDO::PARAM_STR);
            $prpStmt->bindParam(':id_scientific_papers', $id_scientific_papers, PDO::PARAM_INT);
            $prpStmt->execute();
            // if scientific paper records was updated
            if ($prpStmt->rowCount() == 1)
                return 'Podatki znanstvenega dela so uspešno ažurirani.';
            return 'Podatki znanstvenega dela niso uspešno ažurirani.';
        } // try
        catch (PDOException $e) {
            // output error message 
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // updateScientificPapers

    /*
    *   delete scientific paper and its belonging documents 
    *   @param int $id_scientific_papers
    */
    public function deleteScientificPaper(int $id_scientific_papers)
    {
        // deletion report
        $report = '';
        $report .= $this->deleteDocuments($id_scientific_papers);
        // select and delete every single partaker on a scientific paper
        foreach ($this->selectPartakings($id_scientific_papers) as $partaker)
            $this->deletePartakings($partaker->getIdPartakings());
        // select and delete every single mentor of the scientific paper
        foreach ($this->selectSciPapMentors($id_scientific_papers) as $mentor)
            $this->deleteSciPapMentor($mentor->getIdMentorings());
        $stmt = '   DELETE FROM
                        scientific_papers 
                    WHERE 
                        id_scientific_papers = :id_scientific_papers    ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_scientific_papers', $id_scientific_papers, PDO::PARAM_INT);
            $prpStmt->execute();
            // if scientific paper was deleted
            if ($prpStmt->rowCount() == 1)
                return $report .= 'Znanstveno delo je uspešno izbrisano.';
            return $report .= 'Znanstevno delo ni uspešno izbrisano.';
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // deleteScientificPaper

    // SCIENTIFIC_PAPERS 

    // STUDENTS 

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
    *   @param string $index
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
    *   insert student basics  
    *   @param int $id_postal_codes
    *   @param string $name
    *   @param string $surname
    *   @param string $email
    *   @param string $telephone
    *   @param array $residences
    */
    public function insertStudent(int $id_postal_codes, string $name, string $surname, string $email = NULL, string $telephone = NULL, $residences = [])
    {
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
                // form a report 
                echo 'Osnovni podatki študenta so uspešno evidentirani.';
                $id_students = $this->lastInsertId('students_id_students_seq');
                $this->insertStudtResidences($id_students, $residences);
                return $id_students;
            } // if
            echo 'Napaka: osnovni podakti študenta ter podatki o prebivališču niso uspešno evidentirani.';
            return FALSE;
        } // try
        catch (PDOException $e) {
            echo  "Napaka: {$e->getMessage()}.";
        } // catch
    } // insertStudent

    /*
    *   update student basics
    *   @param int $id_students
    *   @param int $id_postal_codes
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
            return ($report .= $this->updateStudtResidences($id_students, $residences));
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
        $this->deleteStudtResidences($id_students);
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
        if ($this->checkAcctAssignment($id_attendances))
            $this->deleteStudentAccount($id_attendances, $index);
        $this->deleteAttendance($id_attendances);
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
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected
        if ($prpStmt->rowCount() == 1)
            return 'Podatki o študentu ter znanstvenih dosežkih so uspešno izbrisani.';
        return 'Podatki o študentu ter znanstvenih dosežkih niso uspešno izbrisani.';
    } // deleteStudent

    // STUDENTS

    // RESIDENCES

    /* 
    *   select residences of the given student
    *   @param int $id_students
    */
    public function selectStudtResidences(int $id_students)
    {
        // permanent and temporary residences
        $residences = [
            'permResidence' => NULL,
            'tempResidences' => []
        ];
        $stmt = '  SELECT 
                        residences.id_residences,
                        residences.id_postal_codes,
                        residences.address,
                        residences.status,
                        postal_codes.id_countries
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
                    array_push($residences['tempResidences'], $residence);
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
                    echo "Bivališče na naslovu '{$residence['address']}' je evidentirano kot {$residence['status']}." . PHP_EOL;
                else
                    echo "Bivališče na naslovu '{$residence['address']}' ni evidentirano." . PHP_EOL;
            } // try
            catch (PDOException $e) {
                echo "Napaka: {$e->getMessage()}.";
            } // catch 
        } // foreach
    } // insertStudtResidences

    /*
    *   update permanent and temporary residence of a student 
    *   @param int $id_students
    *   @param Array $residences
    */
    private function updateStudtResidences(int $id_students, array $residences)
    {
        // update report 
        $report = '';
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
                    // if residence record was updated
                    if ($prpStmt->rowCount() == 1)
                        $report .= "Bivališče na naslovu {$residence['address']} evidentirano kot {$residence['status']}." . PHP_EOL;
                    else
                        $report .= "Bivališče na naslovu {$residence['address']} ni evidentirano kot {$residence['status']}." . PHP_EOL;
                } // try
                catch (PDOException $e) {
                    echo "Napaka: {$e->getMessage()}.";
                } // catch
            } // if
        } // foreach
        return $report;
    } // updateStudtResidences

    /*
    *   insert student temporary residence
    *   @param int $id_students
    *   @param Array $residence
    */
    private function insertStudtTempResidence(int $id_students, array $residence)
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
            // if single residence record was inserted 
            if ($prpStmt->rowCount() == 1)
                return 'so uspešno vstavljeni.' . PHP_EOL;
            return 'niso uspešno vstavljeni.' . PHP_EOL;
        } // try
        catch (PDOException $e) {
            echo "Napaka {$e->getMessage()}.";
        } // catch
    } // insertStudtTempResidence

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
    *   delete all of student residences
    *   @param int $id_students
    */
    private function deleteStudtResidences(int $id_students)
    {
        $stmt = '   DELETE FROM 
                        residences
                    WHERE 
                        id_students = :id_students  ';
        try {
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_students', $id_students, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // deleteStudtResidences

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

    // RESIDENCES

    // POSTAL_CODES

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

    // POSTAL_CODES

    // COUNTRIES

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

    // COUNTRIES

    // FACULTIES

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

    // FACULTIES

    // PROGRAMS

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

    // PROGRAMS

    // ATTENDANCES

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
                        attendances.id_attendances = :id_attendances    ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_attendances', $id_attendances, PDO::PARAM_INT);
            $prpStmt->execute();
            return $prpStmt->fetchAll(PDO::FETCH_OBJ)[0];
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // selectProgAttnParticulars

    /*
    *   insert attendance of a student
    *   @param int $id_students
    *   @param int $id_faculties
    *   @param int $id_programs
    *   @param DateTime $enrolled 
    *   @param int $index 
    */
    public function insertAttendance(int $id_students, int $id_faculties, int $id_programs, DateTime $enrolled, string $index)
    {
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
            // if attendance record was inserted
            if ($prpStmt->rowCount() == 1) {
                // form a report 
                echo 'Študijski program ' . $this->selectStudentsByIndex($index)[0]->program . ' je uspešno evidentiran.';
                return $this->lastInsertId('attendances_id_attendances_seq');
            } // if
            // form a report
            echo 'Študijski program ' . $this->selectStudentsByIndex($index)[0]->program . ' ni uspešno evidentiran.';
            return FALSE;
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // insertAttendance

    /*
    *   delete program attendance of a student
    *   @param int $id_attendances
    */
    public function deleteAttendance(int $id_attendances)
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
            // if single attendance record was deleted
            if ($prpStmt->rowCount() == 1)
                return 'Podatki o poteku izobraževanja na danem študijskem programu so uspešno izbrisani.';
            return 'Podatki o poteku izobraževanja na danem študijskem programu niso uspešno izbrisani.';
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // deleteAttendance

    // ATTENDANCES

    // CERTIFICATES

    /*
    *   select graduation certificate for the given program attendance
    *   @param int $id_attendances
    */
    public function selectCertificate(int $id_attendances)
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
                        graduations.id_attendances = :id_attendances  ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_attendances', $id_attendances, PDO::PARAM_INT);
            $prpStmt->execute();
            $certificate = $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Certificates::class, ['id_certificates', 'source', 'issued']);
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $certificate;
    } // selectCertificate

    /* 
    *   insert graduation certificate of the student
    *   @param string $source
    *   @param DateTime $issued
    */
    private function insertCertficate(string $source, DateTime $issued)
    {
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
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':source', $source, PDO::PARAM_STR);
            $prpStmt->bindValue(':issued', $issued->format('d-m-Y'), PDO::PARAM_STR);
            $prpStmt->execute();
            // if certificate record was inserted 
            if ($prpStmt->rowCount() == 1)
                return TRUE;
            return FALSE;
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // insertCertficate

    /*
    *   insert graduation of a student
    *   @param int $id_attendances
    *   @param string $certificate
    *   @param DateTime $defended
    *   @param DateTime $issued
    */
    public function uploadCertificate(int $id_attendances, string $certificate, DateTime $defended, DateTime $issued)
    {
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
                            // if MIME type is not application/pdf
                            if ($mimetype != 'application/pdf')
                                return "Napaka: certifikat '{$_FILES['certificate']['name'][$indx]}' ni uspešno naložen saj ni tipa .pdf .";
                            // set destination of the uploaded certificate
                            $dir = 'uploads/certificates/';
                            $destination = $dir . (new DateTime())->format('dmYHsi') . basename($_FILES['certificate']['name'][$indx]);
                            // if certificate was inserted into db and moved to a new destination  
                            if ($this->insertCertficate($destination, $issued) && move_uploaded_file($tmp_name, "../{$destination}")) {
                                echo "Certifikat {$_FILES['certificate']['name'][$indx]} je uspešno naložen." . PHP_EOL;
                                $id_certificates = $this->lastInsertId('certificates_id_certificates_seq');
                                // if single row is affected 
                                if ($this->insertGraduation($id_certificates, $id_attendances, $defended)) {
                                    // commit current transaction
                                    $this->commit();
                                    return 'Datuma zagovora diplome ter izdajanja certifikata sta uspešno določena.';
                                } // if
                            } // if
                            // rollback current transaction
                            $this->rollBack();
                            return 'Napaka: postopek nalaganja certifikata in določanja datuma zagovora ter izdajanja je bil neuspešen.';
                        } // if
                        return "Napaka: certifikat {$_FILES['certificate']['name'][$indx]} ni uspešno naložen.";
                    } // if
                } // foreach
            } // try
            catch (PDOException $e) {
                // output error message 
                echo "Napaka: {$e->getMessage()}.";
            } // catch 
        } // if
        return 'Opozorilo: transakcija s podatkovno zbirko je v izvajanju.';
    } // uploadCertificate

    /*
    *   update date of certificate issuing 
    *   @param int $id_certificates
    *   @param DateTime $issued
    */
    public function updateCertIssDate(int $id_certificates, DateTime $issued)
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
            // if issuance date was updated
            if ($prpStmt->rowCount() == 1)
                return 'Datum izdajanja certifikata je uspešno spremenjen.';
            return 'Datum izdajanja certifikata ni uspešno spremenjen.';
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // updateCertIssDate

    /*
    *   delete certificate by its server location 
    *   @param string $source
    */
    public function deleteCertificate(string $source)
    {
        $stmt = '   DELETE FROM
                        certificates 
                    WHERE 
                        source = :source  ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':source', $source);
            $prpStmt->execute();
            // if certificate was deleted 
            if ($prpStmt->rowCount() == 1)
                return TRUE;
            return FALSE;
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // deleteCertificate

    // CERTIFICATES

    // GRADUATIONS

    /* 
    *   insert graduation of the student
    *   @param int $id_certificates
    *   @param int $id_attendandes
    *   @param DateTime defended
    */
    private function insertGraduation(int $id_certificates, int $id_attendances, DateTime $defended)
    {
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
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_certificates', $id_certificates, PDO::PARAM_INT);
            $prpStmt->bindParam(':id_attendances', $id_attendances, PDO::PARAM_INT);
            $prpStmt->bindValue(':defended', $defended->format('d-m-Y'), PDO::PARAM_STR);
            $prpStmt->execute();
            // if graduation record was inserted
            if ($prpStmt->rowCount() == 1)
                return TRUE;
            return FALSE;
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // insertGraduation

    /*
    *   delete graduation of the student 
    *   @param int $id_attendances
    *   @param string $source
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
                echo "Napaka: {$e->getMessage()}.";
            } // catch
        } // if
        return 'Opozorilo: transkacija s podatkovno zbriko je v izvajanju.';
    } // deleteGraduation

    /*
    *   update date of graduation certificate defence 
    *   @param int $id_certificates
    *   @param DateTime $defended
    */
    public function updateGradDefDate(int $id_certificates, DateTime $defended)
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
            // if date was updated
            if ($prpStmt->rowCount() == 1)
                return 'Datum zagovora diplome je uspešno spremenjen.';
            return 'Datum zagovora diplome ni uspešno spremenjen.';
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // updateGradDefDate

    // GRADUATIONS

    // PARTAKINGS

    /*
    *   select partakings on the scientific paper
    *   @param int $id_scientific_papers
    */
    public function selectPartakings(int $id_scientific_papers)
    {
        $stmt = "   SELECT 
                        attendances.id_attendances,
                        attendances.index,
                        partakings.id_partakings,
                        partakings.part,
                        (students.name || ' ' || students.surname) AS fullname
                    FROM 
                        partakings
                        INNER JOIN attendances
                        USING(id_attendances)
                        INNER JOIN students
                        USING(id_students) 
                    WHERE 
                        partakings.id_scientific_papers = :id_scientific_papers    ";
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':id_scientific_papers', $id_scientific_papers, PDO::PARAM_INT);
            $prpStmt->execute();
            return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Partakings::class, ['id_partakings', 'id_scientific_papers', 'id_attendances', 'part']);
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // selectPartakings

    /*
    *   delete partaking on the scientific paper
    *   @param int $id_partakings
    */
    public function deletePartakings(int $id_partakings)
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
            // if partakigns record was deleted
            if ($prpStmt->rowCount() == 1)
                return 'Soavtor je uspešno odstranjen.';
            return 'Soavtor ni uspešno odstranjen.';
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // deletePartakings

    /*
    *   insert partaking on a scientific paper 
    *   @param int $id_scientific_papers
    *   @param int $id_attendances
    *   @param string $part
    */
    public function insertPartakings(int $id_scientific_papers, int $id_attendances, string $part)
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
            // if partaking was inserted 
            if ($prpStmt->rowCount() == 1)
                return TRUE;
            return FALSE;
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // insertPartakings

    /*
    *   update partakers role in writing of the scientific paper 
    *   @param int $id_partakings
    *   @param string $part
    */
    public function updatePartInWriting(int $id_partakings, string $part)
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
            // if part was updated 
            if ($prpStmt->rowCount() == 1)
                return 'Vloga soavtorja študija je uspešno ažurirana.';
            return 'Napaka: vloga soavtorja študija ni uspešno ažurirana.';
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // updatePartInWriting

    // PARTAKINGS

    // MENTORINGS

    /*
    *   select mentors of the scientific paper
    *   @param int $id_scientific_papers
    */
    public function selectSciPapMentors(int $id_scientific_papers)
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
                        mentorings.id_scientific_papers = :id_scientific_papers    ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':id_scientific_papers', $id_scientific_papers, PDO::PARAM_INT);
            $prpStmt->execute();
            return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Mentorings::class, ['id_mentorings', 'id_scientific_papers', 'id_faculties', 'mentor', 'taught', 'email', 'telephone']);
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // selectSciPapMentors

    /*
    *   insert mentoring of the scientific paper
    *   @param int $id_scientific_papers
    *   @param int $id_faculties
    *   @param string $mentor
    *   @param string $taught
    *   @param string $email
    *   @param string $telephone
    */
    public function insertSciPapMentor(int $id_scientific_papers, int $id_faculties, string $mentor, string $taught, string $email, string $telephone)
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
            // if mentor record was inserted 
            if ($prpStmt->rowCount() == 1)
                return TRUE;
            return FALSE;
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // insertSciPapMentor

    /*
    *   update mentoring of the scientific paper
    *   @param int $id_mentorings
    *   @param int $id_faculties
    *   @param string $mentor
    *   @param string $taught
    *   @param string $email
    *   @param string $telephone
    */
    public function updateMentoring(int $id_mentorings, int $id_faculties, string $mentor, string $taught, string $email, string $telephone)
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
            // if mentoring was updated 
            if ($prpStmt->rowCount() == 1)
                return 'Podatki o mentorju znanstvenega dela so uspešno ažurirani.';
            return 'Napaka: podatki o mentorju znanstvenega dela niso uspešno ažurirani.';
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // updateMentoring

    /*
    *   delete mentoring of the scientific paper
    *   @param int $id_mentorings
    */
    public function deleteSciPapMentor(int $id_mentorings)
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
            // if mentoring was deleted
            if ($prpStmt->rowCount() == 1)
                return 'Podatki o mentorstvu so uspešno izbrisani.';
            return 'Podatki o mentorstvu niso uspešno izbrisani.';
        } // try
        catch (PDOException $e) {
            // output error message 
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // deleteSciPapMentor

    /*
    *   select mentoring of the scientific paper 
    *   @param int $id_mentorings
    */
    public function selectMentoring(int $id_mentorings)
    {
        $stmt = '   SELECT 
                        mentorings.id_faculties,
                        mentorings.mentor,
                        mentorings.taught,
                        mentorings.email,
                        mentorings.telephone
                    FROM 
                        mentorings
                        INNER JOIN faculties
                        USING(id_faculties) 
                    WHERE 
                        mentorings.id_mentorings = :id_mentorings  ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_mentorings', $id_mentorings, PDO::PARAM_INT);
            $prpStmt->execute();
            return json_encode($prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Mentorings::class, ['id_mentorings', 'id_scientific_papers', 'id_faculties', 'mentor', 'taught', 'email', 'telephone'])[0]);
        } // try
        catch (PDOException $e) {
            // output error message 
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // selectMentoring

    // MENTORINGS

    // DOCUMENTS

    /*
    *   select the documents belonging to the scientific paper
    *   @param int id_scientific_papers
    */
    public function selectDocuments(int $id_scientific_papers)
    {
        $stmt = '   SELECT 
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
            return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Documents::class, ['id_documents', 'source', 'published', 'version']);
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // selectDocuments

    /* 
    *   insert scientific paper document record
    *   @param int $id_scientific_papers
    *   @param string $source
    *   @param DateTime $published
    *   @param string $version
    */
    private function insertDocument(int $id_scientific_papers, string $source, DateTime $published, string $version)
    {
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
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_scientific_papers', $id_scientific_papers, PDO::PARAM_INT);
            $prpStmt->bindParam(':source', $source, PDO::PARAM_STR);
            $prpStmt->bindValue(':published', $published->format('d-m-Y'), PDO::PARAM_STR);
            $prpStmt->bindParam(':version', $version, PDO::PARAM_STR);
            $prpStmt->execute();
            // if document record was inserted
            if ($prpStmt->rowCount() == 1)
                return TRUE;
            return FALSE;
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // insertDocument

    /*
    *   uplaod document of the scientific paper to the server
    *   @param int $id_scientific_papers
    *   @param string $version
    *   @param string $document
    */
    public function uploadDocument(int $id_scientific_papers, string $version, string $document)
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
                            // if document is not of the application/pdf mimetype
                            if ($mimetype != 'application/pdf')
                                return "Napaka: dokument '{$_FILES['document']['name'][$indx]}' ni uspešno naložen saj ni tipa .pdf .";
                            $upload = TRUE;
                            // if document meets the condition 
                            if ($upload) {
                                // set destination of the uploded file
                                $dir = 'uploads/documents/';
                                $destination = $dir . (new DateTime())->format('dmYHsi') . basename($_FILES['document']['name'][$indx]);
                                // if document was logically and physically interpolated
                                if ($this->insertDocument($id_scientific_papers, $destination, new DateTime(), $version) == 1 && move_uploaded_file($tmp_name, "../{$destination}")) {
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
                echo "Napaka: {$e->getMessage()}." . PHP_EOL;
            } // catch 
        } // if
        return 'Nakapa: transakcija s podatkovno zbirko je v izvajanju.' . PHP_EOL;
    } // uploadDocument

    /*
    *   delete the record of the given scientific paper document and remove it from the server
    *   @param string $source
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
            // if document was logically and physically deleted
            if ($prpStmt->rowCount() == 1 && unlink("../{$source}"))
                return 'Dokument ' . basename($source) . ' je uspešno izbrisan.';
            return 'Dokument ' . basename($source) . ' ni uspešno izbrisan.';
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // deleteDocument

    /*
    *   delete all document records the given of scientific paper and remove them from the server
    *   @param int $id_scientific_papers
    */
    public function deleteDocuments(int $id_scientific_papers)
    {
        // deletion report
        $report = '';
        // traverse through scientific paper documents
        foreach ($this->selectDocuments($id_scientific_papers) as $doc) {
            $stmt = '   DELETE FROM 
                            documents
                        WHERE 
                            id_documents = :id_documents    ';
            try {
                // prepare, bind param to and execute stmt 
                $prpStmt = $this->prepare($stmt);
                $prpStmt->bindValue(':id_documents', $doc->getIdDocuments(), PDO::PARAM_INT);
                $prpStmt->execute();
                // if document was logically and physically deleted
                if ($prpStmt->rowCount() == 1 && unlink("../{$doc->getSource()}"))
                    $report .= 'Dokument ' . basename($doc->getSource()) . ' je uspešno izbrisan.' . PHP_EOL;
                else
                    $report .= 'Dokument ' . basename($doc->getSource()) . ' ni uspešno izbrisan.' . PHP_EOL;
            } // try
            catch (PDOException $e) {
                echo "Napaka: {$e->getMessage()}.";
            } // catch
        } // foreach
        return $report;
    } // deleteDocuments          

    // DOCUMENTS 

    // ACCOUNTS

    /*
    *   checkout whether the student has been assigned an account to
    *   @param int $id_attendances
    */
    public function checkAcctAssignment(int $id_attendances)
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
            // if account has been assigned to
            if ($prpStmt->rowCount() == 1)
                return TRUE;
            return FALSE;
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // checkAcctAssignment

    /*
    *   check if account credentials are valid 
    *   @param string $index
    *   @param string $pass
    */
    public function checkAcctCredentials(string $index, string $pass)
    {
        // authentication report
        $report = [
            'logged' => FALSE,
            'mssg' => ''
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
            // if account credentials are valid
            if ($prpStmt->rowCount() == 1) {
                $hash = $prpStmt->fetch(PDO::FETCH_COLUMN);
                // if hash is composed of the given password
                if (password_verify($pass, $hash)) {
                    // register session variables
                    $_SESSION['user'] = 'stu_' . $index;
                    // register var and assign pass
                    $_SESSION['pass'] = $pass;
                    $_SESSION['index'] = $index;
                    $report['logged'] = TRUE;
                    $report['mssg'] = 'Prijava študenta je bila uspešna.';
                } // if
                else
                    $report['mssg'] = 'Geslo računa z dano indeks številko ni pravilno.';
            } // if
            // if superuser has attempted login 
            else if (strpos(self::SUPERUSER, $index) && self::PASS == $pass) {
                // register session variables
                $_SESSION['user'] = self::SUPERUSER;
                // register var and assign pass
                $_SESSION['pass'] = self::PASS;
                $_SESSION['authorized'] = TRUE;
                $report['logged'] = TRUE;
                $report['mssg'] = 'Prijava pooblaščenega je bila uspešna.';
            } // else if
            else
                $report['mssg'] = 'Račun z dano indeks številko ne obstaja.';
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // return JSON  
        return json_encode($report);
    } // checkAcctCredentials

    /*
    *   !DML 
    *   create database user in the cluster with the student role privileges
    *   @param string $index
    *   @param string $hash
    */
    private function createStudtUser(string $index, string $hash)
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
    } // createStudtUser

    /*
    *   !DML 
    *   revoke privileges on database objects for the given student role  
    *   @param string $index
    */
    private function revokeStudtPrivileges(string $index)
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
            if ($prpStmt->execute() && $prpStmt2->execute())
                return TRUE;
            return FALSE;
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // revokeStudtPrivileges

    /*
    *   !DML 
    *   drop database user in the cluster with the student role privileges
    *   @param string $index
    */
    private function dropStudtUser(string $index)
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
            echo "Napaka: {$e->getMessage}.";
        } // catch
    } // dropStudtUser

    /*
    *   insert account record with credentials for database access and assign it to the student
    *   @param int $id_attendances
    *   @param string $index
    *   @param string $pass
    */
    public function insertStudentAccount(int $id_attendances, string $index, string $pass)
    {
        // check if not already in a transaction
        if (!$this->inTransaction()) {
            try {
                // establish a new transaction
                $this->beginTransaction();
                $hash = password_hash($pass, PASSWORD_BCRYPT);
                // if database user with the student privileges has been created
                if ($this->createStudtUser($index, $hash)) {
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
                    // prepare, bind params to and execute stmt
                    $prpStmt = $this->prepare($stmt);
                    $prpStmt->bindParam(':id_attendances', $id_attendances, PDO::PARAM_INT);
                    $prpStmt->bindValue(':pass', $hash, PDO::PARAM_STR);
                    $prpStmt->bindValue(':granted', (new DateTime())->format('d-m-Y'), PDO::PARAM_STR);
                    $prpStmt->execute();
                    // if account records was inserted 
                    if ($prpStmt->rowCount() == 1) {
                        // commit the changes
                        $this->commit();
                        return 'Račun je uspešno ustvarjen.';
                    } // if 
                } // if
                // rollback the changes
                $this->rollback();
                return 'Račun ni uspešno ustvarjen.';
            } // try
            catch (PDOException $e) {
                // output error message
                echo "Napaka: {$e->getMessage()}.";
            } // catch
        } // if
        return 'Opozorilo: transkacija s podatkovno zbirko je v izvajanju.';
    } // insertStudentAccount

    /*
    *   select grant date of the given account
    *   @param int $id_attendances
    */
    public function selectAcctGrantDate(int $id_attendances)
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
            // if account was granted
            if ($prpStmt->rowCount() == 1)
                return (new DateTime($prpStmt->fetch(PDO::FETCH_COLUMN)))->format('d-m-Y');
            return NULL;
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // selectAcctGrantDate

    /*
    *   drop the subject user and delete account credentials
    *   @param int $id_attendances
    *   @param string $index
    */
    public function deleteStudentAccount(int $id_attendances, string $index)
    {
        // if not in a transation
        if (!$this->inTransaction()) {
            try {
                // begin a new one
                $this->beginTransaction();
                // if the user was droped
                if ($this->revokeStudtPrivileges($index) && $this->dropStudtUser($index)) {
                    // if student had account avatar 
                    if ($avatar = $this->hasAcctAvatar($index))
                        unlink("../../{$avatar}");
                    $stmt = '   DELETE FROM 
                                    accounts
                                WHERE 
                                    id_attendances = :id_attendances    ';
                    // prepare, bind param to and execute stmt
                    $prpStmt = $this->prepare($stmt);
                    $prpStmt->bindParam(':id_attendances', $id_attendances, PDO::PARAM_INT);
                    $prpStmt->execute();
                    // if account record was deleted
                    if ($prpStmt->rowCount() == 1) {
                        // commit the current transaction
                        $this->commit();
                        return 'Račun je uspešno izbrisan.';
                    } // if
                    // rollback the current transaction
                    $this->rollback();
                    return 'Napaka: račun ni uspešno izbrisan.';
                } // if
            } // try
            catch (PDOException $e) {
                echo "Napaka: {$e->getMessage()}.";
            } // catch
            // rollback the current transaction
            $this->rollback();
            return 'Napaka: račun ni uspešno izbrisan.';
        } // if
        return 'Opozorilo: transakcija s podatkovno zbirko je v izvajanju.';
    } // deleteStudentAccount

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
    *   update account avatar location on the server
    *   @param int $id_attendances
    *   @param string $avatar
    */
    private function updateAcctAvatar(int $id_attendances, string $avatar)
    {
        $stmt = '   UPDATE 
                        accounts
                    SET 
                        avatar = :avatar 
                    WHERE 
                        id_attendances = :id_attendances    ';
        try {
            // prepare, bind paramas to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':avatar', $avatar, PDO::PARAM_STR);
            $prpStmt->bindParam(':id_attendances', $id_attendances, PDO::PARAM_INT);
            $prpStmt->execute();
            // if avatar location was updated
            if ($prpStmt->rowCount() == 1)
                return TRUE;
            return FALSE;
        } // try
        catch (PDOException $e) {
            // output the excpetion error message
            echo "Napaka: {$e->getMessage()}.";
        } // catch
    } // updateAcctAvatar

    /* 
    *   upload avatar for the given account 
    *   @param int $id_attendances
    */
    public function uploadAcctAvatar(int $id_attendances)
    {
        // if not already running a transaction
        if (!$this->inTransaction()) {
            try {
                // begin a new transaction
                $this->beginTransaction();
                // if avatar was uploaded successfully 
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
                        // if account record was updated and avatar moved to the server
                        if ($this->updateAcctAvatar($id_attendances, $destination) == 1 && move_uploaded_file($_FILES['avatar']['tmp_name'], "../../{$destination}")) {
                            // commit current transaction
                            $this->commit();
                            return "Avatar {$_FILES['avatar']['name']} je uspešno naložen." . PHP_EOL;
                        } // if
                        // rollback current transaction
                        $this->rollBack();
                        return 'Napaka: lokacija ni uspešno spremenjena ali avatar ni uspešno naložen na strežnik.' . PHP_EOL;
                    } // if
                    return "Napaka: avatar {$_FILES['avatar']['name']} ni zadostil kriterij nalaganja." . PHP_EOL;
                } // if
                return "Napaka: avatar {$_FILES['avatar']['name']} ni uspešno naložen." . PHP_EOL;
            } // try
            catch (PDOException $e) {
                // output error message 
                echo "Napaka: {$e->getMessage()}." . PHP_EOL;
            } // catch 
        } // if
        return 'Opozorilo: transakcija s podatkovno zbirko je v izvajanju.' . PHP_EOL;
    } // uploadAcctAvatar

    /* 
    *   delete the given account avatar from the server and nullify the location  
    *   @param int $id_attendances
    *   @param string $avatar 
    */
    public function deleteAcctAvatar(int $id_attendances, string $avatar)
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
                // prepare, bind param to and execute stmt
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
                return 'Napaka: avatar ni uspešno logično ali fizično odstranjen.';
            } // try
            catch (PDOException $e) {
                // output error message 
                echo "Napaka: {$e->getMessage()}." . PHP_EOL;
            } // catch 
        } // if
        return 'Opozorilo: transakcija s podatkovno zbirko je v izvajanju.' . PHP_EOL;
    } // deleteAcctAvatar

    // ACCOUNTS

} // DBC