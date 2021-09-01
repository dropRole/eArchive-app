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
            parent::__construct("pgsql:host=localhost;dbname=eArchive;port=5432;user={$user};password={$pass};");
        } // try
        catch (PDOException $e) {
            // compose an error message
            echo "Napaka: {$e->getMessage()}.";
        } // catch 
    } // __construct

    // <scientific_papers>

    //   select all scientific papers records to form an evidence table
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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return  $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, ScientificPapers::class, ['id_scientific_papers', 'id_attendances', 'topic', 'type', 'written']);
    } // selectScientificPapers

    /*
    *   select all scientific papers written while student attended a specific program
    *   @param int $id_attendances
    */
    public function selectPapersByProgramAttendance(int $id_attendances)
    {
        $stmt = '   SELECT 
                        *
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
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, ScientificPapers::class, ['id_scientific_papers', 'id_attendances', 'topic', 'type', 'written']);
    } // selectPapersByProgramAttendance

    /*
    *   select scientific papers written by the given author 
    *   @param string $author
    */
    public function selectScientificPapersByAuthor(string $author)
    {
        $stmt = "   SELECT 
                        (students.name || ' ' || students.surname) AS author,
                        scientific_papers.*,
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
        } // try
        catch (PDOException $e) {
            // output error message 
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, ScientificPapers::class, ['id_scientific_papers', 'id_attendances', 'type', 'topic', 'written']);
    } // selectScientificPapersByAuthor

    /*
    *   select scientific papers which mentor was 
    *   @param string $mentor
    */
    public function selectScientificPapersByMentor(string $mentor)
    {
        $stmt = "   SELECT 
                        (students.name || ' ' || students.surname) AS author,
                        scientific_papers.*,
                        mentorings.mentor,
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
        } // try
        catch (PDOException $e) {
            // output error message 
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, ScientificPapers::class, ['id_scientific_papers', 'id_attendances', 'type', 'topic', 'written']);
    } // selectScientificPapersByMentor

    /*
    *   select scientific papers written at the given year 
    *   @param string $year
    */
    public function selectScientificPapersByYear(string $year)
    {
        $stmt = "   SELECT 
                        (students.name || ' ' || students.surname) AS author,
                        scientific_papers.*,
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
        } // try
        catch (PDOException $e) {
            // output error message 
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, ScientificPapers::class, ['id_scientific_papers', 'id_attendances', 'type', 'topic', 'written']);
    } // selectScientificPapersByYear

    /*
    *   filter and select scientific papers by their topics
    *   @param string $topic
    */
    public function selectScientificPapersByTopic(string $topic)
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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage}.";
        } // catch
        return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, ScientificPapers::class, ['id_scientific_papers', 'id_attendances', 'topic', 'type', 'written']);
    } // selectScientificPapersByTopic

    /*
    *   select all scientific papers according to the index number of the student attending the program
    *   @param string $index
    */
    public function selectScientificPapersByIndex(string $index)
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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, ScientificPapers::class, ['id_scientific_papers', 'id_attendances', 'topic', 'type', 'written']);
    } // selectScientificPapersByIndex

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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, ScientificPapers::class, ['id_scientific_papers', 'id_attendances', 'topic', 'type', 'written'])[0];
    } // selectScientificPaper

    /*
    *   insert scientific paper record
    *   @param int $id_attendances
    *   @param string $topic
    *   @param string $type
    *   @param DateTime $written
    */
    public function insertScientificPaper(int $id_attendances, string $topic, string $type, DateTime $written)
    {
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
                echo "Delo '{$topic}' je uspešno evidentirano.";
                return $this->lastInsertId('scientific_papers_id_scientific_papers_seq');
            } // if
            echo "Napaka: delo '{$topic}' ni uspešno evidentirano.";
        } // try
        catch (PDOException $e) {
            // output error message 
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return FALSE;
    } // insertScientificPaper

    /*
    *   update given scientific paper record
    *   @param int $id_scientific_papers
    *   @param string $topic
    *   @param string $type
    *   @param DateTime $written
    */
    public function updateScientificPaper(int $id_scientific_papers, string $topic, string $type, DateTime $written)
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
            if ($prpStmt->rowCount() == 1) {
                echo 'Podatki znanstvenega dela so uspešno ažurirani.';
                return TRUE;
            } // if
            echo 'Napaka: podatki znanstvenega dela niso uspešno ažurirani.';
        } // try
        catch (PDOException $e) {
            // output error message 
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return FALSE;
    } // updateScientificPapers

    /*
    *   delete scientific paper record 
    *   @param int $id_scientific_papers
    */
    public function deleteScientificPaper(int $id_scientific_papers)
    {
        // physically and logically delete documents 
        $this->deleteDocuments($id_scientific_papers);
        // select and delete every single partaker on a scientific paper
        foreach ($this->selectPartakers($id_scientific_papers) as $partaker)
            $this->deletePartaker($partaker->getIdPartakings());
        // select and delete every single mentor of the scientific paper
        foreach ($this->selectMentors($id_scientific_papers) as $mentor)
            $this->deleteMentor($mentor->getIdMentorings());
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
            if ($prpStmt->rowCount() == 1) {
                echo 'Znanstveno delo je uspešno izbrisano.';
                return TRUE;
            } // if
            echo 'Napaka: znanstevno delo ni uspešno izbrisano.';
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return FALSE;
    } // deleteScientificPaper

    // </scientific_papers> 

    // <students> 

    /*
    *   select all students records to form an evidence table 
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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $prpStmt->fetchAll(PDO::FETCH_OBJ);
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
        } // if
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $prpStmt->fetchAll(PDO::FETCH_OBJ);
    } // selectStudentsByIndex 

    /*
    *   select particulars of a student
    *   @param int $id_students
    */
    public function selectStudentParticulars(int $id_students)
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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return json_encode($prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Students::class, ['id_students', 'id_postal_codes', 'name', 'surname', 'email', 'telephone'])[0]);
    } // selectStudentParticulars

    /*
    *   insert student basics  
    *   @param int $id_postal_codes
    *   @param string $name
    *   @param string $surname
    *   @param string $email
    *   @param string $telephone
    *   @param array $residences
    */
    public function insertStudent(int $id_postal_codes, string $name, string $surname, string $email = NULL, string $telephone = NULL, array $residences = [])
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
                $this->insertStudentResidences($id_students, $residences);
                return $id_students;
            } // if
            echo 'Napaka: osnovni podakti študenta ter podatki o prebivališču niso uspešno evidentirani.';
        } // try
        catch (PDOException $e) {
            echo  "Napaka: {$e->getMessage()}.";
        } // catch
        return FALSE;
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
    public function updateStudent(int $id_students, int $id_postal_codes, string $name, string $surname, string $email = NULL, string $telephone = NULL, array $residences = [])
    {
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
                echo 'Osnovni podatki študenta so uspešno posodobljeni.';
            else
                echo 'Napaka: osnovni podatki študenta niso uspešno posodobljeni.';
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $this->updateStudentResidence($id_students, $residences);
    } // updateStudent

    /*
    *   delete all data related to a particular program attendance by student 
    *   @param int $id_attendances 
    *   @param int $id_students 
    *   @param string $index 
    */
    public function deleteStudent(int $id_attendances, int $id_students, string $index)
    {
        // delete permanent and temporary student residences 
        $this->deleteStudentResidences($id_students);
        $certificate = $this->selectCertificate($id_attendances);
        // if graduated
        if ($certificate != NULL)
            $this->deleteGraduation($id_attendances, $certificate[0]->getSource());
        // if any scientific paper was written 
        $scientificPapers = $this->selectScientificPapers($id_attendances);
        if (count($scientificPapers) >= 1)
            foreach ($scientificPapers as $scientificPaper) {
                $this->deleteScientificPaper($scientificPaper->getIdScientificPapers());
            } // foreach
        // if account was granted to
        if ($this->assignedWithAccount($id_attendances))
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
        if ($prpStmt->rowCount() == 1) {
            echo 'Podatki o študentu ter znanstvenih dosežkih so uspešno izbrisani.';
            return TRUE;
        } // if
        echo 'Napaka: podatki o študentu ter znanstvenih dosežkih niso uspešno izbrisani.';
        return FALSE;
    } // deleteStudent

    // </students>

    // <residences>

    /* 
    *   select residence records of the given student
    *   @param int $id_students
    */
    public function selectStudentResidences(int $id_students)
    {
        // permanent and temporary residences
        $residences = [
            'permanentResidence' => NULL,
            'temporaryResidences' => []
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
                    $residences['permanentResidence'] = $residence;
                else
                    array_push($residences['temporaryResidences'], $residence);
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
    private function insertStudentResidences(int $id_students, array $residences)
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
                $prpStmt->bindValue(':id_postal_codes', $residence['id_postal_codes'], PDO::PARAM_INT);
                $prpStmt->bindParam(':id_students', $id_students, PDO::PARAM_INT);
                $prpStmt->bindValue(':address', $residence['address'], PDO::PARAM_STR);
                $prpStmt->bindValue(':status', $residence['status'], PDO::PARAM_STR);
                $prpStmt->execute();
                // if residence was inserted
                if ($prpStmt->rowCount() == 1)
                    echo "Bivališče na naslovu '{$residence['address']}' je evidentirano kot {$residence['status']}.";
                else
                    echo "Napaka: bivališče na naslovu '{$residence['address']}' ni evidentirano.";
            } // try
            catch (PDOException $e) {
                echo "Napaka: {$e->getMessage()}.";
            } // catch 
        } // foreach
    } // insertStudentResidences

    /*
    *   update permanent and temporary residence of a student 
    *   @param int $id_students
    *   @param Array $residences
    */
    private function updateStudentResidence(int $id_students, array $residences)
    {
        foreach ($residences as $residence) {
            // check whether student resides
            if ($this->residesAt($id_students, $residence['id_postal_codes'])) {
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
                        echo "Bivališče na naslovu {$residence['address']} je evidentirano kot {$residence['status']}.";
                    else
                        echo "Napaka: bivališče na naslovu {$residence['address']} ni evidentirano kot {$residence['status']}.";
                } // try
                catch (PDOException $e) {
                    echo "Napaka: {$e->getMessage()}.";
                } // catch
            } // if
            else
                $this->insertStudentResidences($id_students, [$residence]);
        } // foreach
    } // updateStudentResidence

    /*
    *   delete the given temporary residence of a student 
    *   @param int $id_residences
    */
    public function deleteStudentTemporaryResidence(int $id_residences)
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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected
        if ($prpStmt->rowCount() == 1) {
            echo 'Bivališče je uspešno izbrisano.';
            return TRUE;
        }
        echo 'Napaka: bivališče ni uspešno izbrisano.';
        return FALSE;
    } // deleteStudentTemporaryResidence

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
        try {
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_students', $id_students, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if student residences were deleted 
        if ($prpStmt->rowCount() >= 1)
            return TRUE;
        return FALSE;
    } // deleteStudentResidences

    /*
    *   check if student resides at 
    *   @param int id_students
    *   @param int id_postal_codes
    */
    private function residesAt(int $id_students, int $id_postal_codes)
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
        // if resides at
        if ($prpStmt->rowCount() == 1)
            return TRUE;
        return FALSE;
    } // residesAt

    // </residences>

    // <postal_codes>

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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, PostalCodes::class, ['id_postal_codes', 'id_countries', 'municipality', 'code']);
    } // selectPostalCodes

    // </postal_codes>

    // <countries>

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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Countries::class, ['id_countries', 'name', 'iso_3_code']);
    } // selectCountries

    // </countries>

    // <faculties>

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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Faculties::class, ['id_faculties', 'id_postal_codes', 'name', 'address', 'email', 'telephone', 'dean']);
    } // selectFaculties

    // </faculties>

    // <programs>

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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Programs::class, ['id_programs', 'name', 'degree', 'duration', 'field']);
    } // selectPrograms

    // </programs>

    // <attendances>

    /* 
    *   select particulars of the program attendance by the student
    *   @param int $id_attendances
    */
    public function selectProgramAttendanceParticulars(int $id_attendances)
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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $prpStmt->fetchAll(PDO::FETCH_OBJ)[0];
    } // selectProgramAttendanceParticulars

    /*
    *   insert attendance of a student
    *   @param int $id_students
    *   @param int $id_faculties
    *   @param int $id_programs
    *   @param DateTime $enrolled 
    *   @param string $index 
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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if attendance record was inserted
        if ($prpStmt->rowCount() == 1) {
            // form a report 
            echo 'Študijski program ' . $this->selectStudentsByIndex($index)[0]->program . ' je uspešno evidentiran.';
            return $this->lastInsertId('attendances_id_attendances_seq');
        } // if
        // form a report
        echo 'Napaka: študijski program ' . $this->selectStudentsByIndex($index)[0]->program . ' ni uspešno evidentiran.';
        return FALSE;
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
            if ($prpStmt->rowCount() == 1) {
                echo 'Podatki o poteku izobraževanja na danem študijskem programu so uspešno izbrisani.';
                return TRUE;
            } // if
            echo 'Napaka: podatki o poteku izobraževanja na danem študijskem programu niso uspešno izbrisani.';
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return FALSE;
    } // deleteAttendance

    // </attendance>

    // <certificate>

    /*
    *   select graduation certificate for the given program attendance
    *   @param int $id_attendances
    */
    public function selectCertificate(int $id_attendances)
    {
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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Certificates::class, ['id_certificates', 'source', 'issued'])[0];
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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if certificate record was inserted 
        if ($prpStmt->rowCount() == 1)
            return TRUE;
        return FALSE;
    } // insertCertficate

    /*
    *   upload graduation certificate
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
                        // if certificate is successfully uploaded 
                        if ($_FILES['certificate']['error'][$indx] == UPLOAD_ERR_OK) {
                            $finfo = new finfo();
                            $mimetype = $finfo->file($tmp_name, FILEINFO_MIME_TYPE);
                            // if MIME type is not application/pdf
                            if ($mimetype != 'application/pdf') {
                                echo "Napaka: certifikat '{$_FILES['certificate']['name'][$indx]}' ni uspešno naložen saj ni tipa .pdf .";
                                return FALSE;
                            } // if
                            // set destination of the uploaded certificate
                            $dir = 'uploads/certificates/';
                            $destination = $dir . (new DateTime())->format('dmYHsi') . basename($_FILES['certificate']['name'][$indx]);
                            // if certificate was inserted into db and moved to a new destination  
                            if ($this->insertCertficate($destination, $issued) && move_uploaded_file($tmp_name, "../{$destination}")) {
                                echo "Certifikat {$_FILES['certificate']['name'][$indx]} je uspešno naložen.";
                                $id_certificates = $this->lastInsertId('certificates_id_certificates_seq');
                                // if single row is affected 
                                if ($this->insertGraduation($id_certificates, $id_attendances, $defended)) {
                                    echo 'Datuma zagovora diplome ter izdajanja certifikata sta uspešno določena.';
                                    // commit current transaction
                                    return $this->commit();
                                } // if
                            } // if
                            echo 'Napaka: postopek nalaganja certifikata in določanja datuma zagovora ter izdajanja je bil neuspešen.';
                            // rollback current transaction
                            return $this->rollBack();
                        } // if
                        echo "Napaka: certifikat {$_FILES['certificate']['name'][$indx]} ni uspešno naložen.";
                    } // if
                } // foreach
            } // try
            catch (PDOException $e) {
                // output error message 
                echo "Napaka: {$e->getMessage()}.";
            } // catch 
        } // if
        echo 'Opozorilo: transakcija s podatkovno zbirko je v izvajanju.';
        return FALSE;
    } // uploadCertificate

    /*
    *   update date of certificate issuing 
    *   @param int $id_certificates
    *   @param DateTime $issued
    */
    public function updateCertificateIssuingDate(int $id_certificates, DateTime $issued)
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
        // if issuance date was updated
        if ($prpStmt->rowCount() == 1) {
            echo 'Datum izdajanja certifikata je uspešno spremenjen.';
            return TRUE;
        } // if
        echo 'Napaka: datum izdajanja certifikata ni uspešno spremenjen.';
        return FALSE;
    } // updateCertificateIssuingDate

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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if certificate was deleted 
        if ($prpStmt->rowCount() == 1)
            return TRUE;
        return FALSE;
    } // deleteCertificate

    // </certificates>

    // <graduations>

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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if graduation record was inserted
        if ($prpStmt->rowCount() == 1)
            return TRUE;
        return FALSE;
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
                        echo 'Podatki o zaključku študija ter certifikat ' . basename($source) . ' so uspešno izbrisani.';
                        // committ the current transation
                        return $this->commit();
                    } // if
                } // if 
                echo 'Napaka: podatki o zaključku študija ter certifikat niso uspešno izbrisani.';
                return $this->rollback();
            } // try
            catch (PDOException $e) {
                echo "Napaka: {$e->getMessage()}.";
            } // catch
        } // if
        echo 'Opozorilo: transkacija s podatkovno zbriko je v izvajanju.';
        return FALSE;
    } // deleteGraduation

    /*
    *   update date of graduation certificate defence 
    *   @param int $id_certificates
    *   @param DateTime $defended
    */
    public function updateCertificateDefenceDate(int $id_certificates, DateTime $defended)
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
        // if date was updated
        if ($prpStmt->rowCount() == 1) {
            echo 'Datum zagovora diplome je uspešno spremenjen.';
            return TRUE;
        } // if
        echo 'Napaka: datum zagovora diplome ni uspešno spremenjen.';
        return FALSE;
    } // updateCertificateDefenceDate

    // </graduations>

    // <partakings>

    /*
    *   select partakers on the scientific paper
    *   @param int $id_scientific_papers
    */
    public function selectPartakers(int $id_scientific_papers)
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
    } // selectPartakers

    /*
    *   delete partaker on the scientific paper
    *   @param int $id_partakings
    */
    public function deletePartaker(int $id_partakings)
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
        // if partakigns record was deleted
        if ($prpStmt->rowCount() == 1) {
            echo 'Soavtor je uspešno odstranjen.';
            return TRUE;
        } // if
        echo 'Napaka: soavtor ni uspešno odstranjen.';
        return FALSE;
    } // deletePartaker

    /*
    *   insert partaking on a scientific paper 
    *   @param int $id_scientific_papers
    *   @param int $id_attendances
    *   @param string $partaker
    *   @param string $part
    */
    public function insertPartaker(int $id_scientific_papers, int $id_attendances, string $partaker, string $part)
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
        // if partaking was inserted 
        if ($prpStmt->rowCount() == 1){
            echo "Soavtor {$partaker} je uspešno dodeljen.";
            return TRUE;
        } // if
        echo "Soavtor {$partaker} ni uspešno dodeljen.";
        return FALSE;
    } // insertPartaker

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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if part was updated 
        if ($prpStmt->rowCount() == 1) {
            echo 'Vloga soavtorja študija je uspešno ažurirana.';
            return TRUE;
        } // if
        echo 'Napaka: vloga soavtorja študija ni uspešno ažurirana.';
        return FALSE;
    } // updatePartInWriting

    // </partakings>

    // <mentorings>

    /*
    *   select mentors of the scientific paper
    *   @param int $id_scientific_papers
    */
    public function selectMentors(int $id_scientific_papers)
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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Mentorings::class, ['id_mentorings', 'id_scientific_papers', 'id_faculties', 'mentor', 'taught', 'email', 'telephone']);
    } // selectMentors

    /*
    *   insert mentor of the scientific paper
    *   @param int $id_scientific_papers
    *   @param int $id_faculties
    *   @param string $mentor
    *   @param string $taught
    *   @param string $email
    *   @param string $telephone
    */
    public function insertMentor(int $id_scientific_papers, int $id_faculties, string $mentor, string $taught, string $email, string $telephone)
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
        // if mentor record was inserted 
        if ($prpStmt->rowCount() == 1){
            echo "Mentor {$mentor} je uspešno izbrisan.";
            return TRUE;
        } // if
        echo "Mentor {$mentor} ni uspešno izbrisan.";
        return FALSE;
    } // insertMentor

    /*
    *   update mentor of the scientific paper
    *   @param int $id_mentorings
    *   @param int $id_faculties
    *   @param string $mentor
    *   @param string $taught
    *   @param string $email
    *   @param string $telephone
    */
    public function updateMentor(int $id_mentorings, int $id_faculties, string $mentor, string $taught, string $email, string $telephone)
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
        // if mentor data was updated 
        if ($prpStmt->rowCount() == 1) {
            echo 'Podatki o mentorju znanstvenega dela so uspešno ažurirani.';
            return TRUE;
        } // if
        echo 'Napaka: podatki o mentorju znanstvenega dela niso uspešno ažurirani.';
        return FALSE;
    } // updateMentor

    /*
    *   delete mentor of the scientific paper
    *   @param int $id_mentorings
    */
    public function deleteMentor(int $id_mentorings)
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
        // if mentoring was deleted
        if ($prpStmt->rowCount() == 1) {
            echo 'Podatki o mentorstvu so uspešno izbrisani.';
            return TRUE;
        } // if
        echo 'Napaka: podatki o mentorstvu niso uspešno izbrisani.';
        return FALSE;
    } // deleteMentor

    /*
    *   select mentor of the scientific paper 
    *   @param int $id_mentorings
    */
    public function selectMentor(int $id_mentorings)
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
        } // try
        catch (PDOException $e) {
            // output error message 
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return json_encode($prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Mentorings::class, ['id_mentorings', 'id_scientific_papers', 'id_faculties', 'mentor', 'taught', 'email', 'telephone'])[0]);
    } // selectMentor

    // </mentorings>

    // <documents>

    /*
    *   select the document belonging to the given scientific paper
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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Documents::class, ['id_documents', 'source', 'published', 'version']);
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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if document record was inserted
        if ($prpStmt->rowCount() == 1)
            return TRUE;
        return FALSE;
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
                            if ($mimetype != 'application/pdf') {
                                echo "Napaka: dokument '{$_FILES['document']['name'][$indx]}' ni uspešno naložen saj ni tipa .pdf .";
                                return FALSE;
                            } // if
                            // set destination of the uploded file
                            $dir = 'uploads/documents/';
                            $destination = $dir . (new DateTime())->format('dmYHsi') . basename($_FILES['document']['name'][$indx]);
                            // if document was logically and physically interpolated
                            if ($this->insertDocument($id_scientific_papers, $destination, new DateTime(), $version) == 1 && move_uploaded_file($tmp_name, "../{$destination}")) {
                                echo "Dokument {$_FILES['document']['name'][$indx]} je uspešno naložen.";
                                // commit current transaction
                                return $this->commit();
                            } // if
                            echo 'Napaka: podatki dokumenta niso uspešno vstavljeni v zbirko ali datoteka ni uspešno prenesena na strežnik.';
                            // rollback current transaction
                            return $this->rollBack();
                        } // if
                        echo "Napaka: dokument {$_FILES['document']['name'][$indx]} ni zadostil kriterij nalaganja.";
                    } // if
                    echo "Napaka: dokument {$_FILES['document']['name'][$indx]} ni uspešno naložen.";
                } // foreach
            } // try
            catch (PDOException $e) {
                // output error message 
                echo "Napaka: {$e->getMessage()}.";
            } // catch 
        } // if
        echo 'Opozorilo: transakcija s podatkovno zbirko je v izvajanju.';
        return FALSE;
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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if document was logically and physically deleted
        if ($prpStmt->rowCount() == 1 && unlink("../{$source}")) {
            echo 'Dokument ' . basename($source) . ' je uspešno izbrisan.';
            return TRUE;
        } // if
        echo 'Napaka: dokument ' . basename($source) . ' ni uspešno izbrisan.';
        return FALSE;
    } // deleteDocument

    /*
    *   delete all document records in a relationship with the given scientific paper id
    *   @param int $id_scientific_papers
    */
    public function deleteDocuments(int $id_scientific_papers)
    {
        // traverse through scientific paper documents
        foreach ($this->selectDocuments($id_scientific_papers) as $document) {
            $stmt = '   DELETE FROM 
                            documents
                        WHERE 
                            id_documents = :id_documents    ';
            try {
                // prepare, bind value to and execute stmt 
                $prpStmt = $this->prepare($stmt);
                $prpStmt->bindValue(':id_documents', $document->getIdDocuments(), PDO::PARAM_INT);
                $prpStmt->execute();
                // if document was logically and physically deleted
                if ($prpStmt->rowCount() == 1 && unlink("../{$document->getSource()}"))
                    echo 'Dokument ' . basename($document->getSource()) . ' je uspešno izbrisan.';
                else
                    echo 'Napaka: dokument ' . basename($document->getSource()) . ' ni uspešno izbrisan.';
            } // try
            catch (PDOException $e) {
                echo "Napaka: {$e->getMessage()}.";
            } // catch
        } // foreach
        return;
    } // deleteDocuments          

    // </documents> 

    // <accounts>

    /*
    *   checkout whether the student has been assigned an account to
    *   @param int $id_attendances
    */
    public function assignedWithAccount(int $id_attendances)
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
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if account has been assigned to
        if ($prpStmt->rowCount() == 1)
            return TRUE;
        return FALSE;
    } // assignedWithAccount

    /*
    *   check if account credentials are valid 
    *   @param string $index
    *   @param string $pass
    */
    public function checkAccountCredentials(string $index, string $pass)
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
                    $report['message'] = 'Prijava študenta je bila uspešna.';
                } // if
                else
                    $report['message'] = 'Geslo računa z dano indeks številko ni pravilno.';
            } // if
            // if superuser has attempted login 
            else if (strpos(self::SUPERUSER, $index) && self::PASS == $pass) {
                // register session variables
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
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // return JSON  
        return json_encode($report);
    } // checkAccountCredentials

    /*
    *   !DML 
    *   create database user in the cluster with the student role privileges
    *   @param string $index
    *   @param string $hash
    */
    private function createStudentUser(string $index, string $hash)
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
            // if stmt successfully executed  
            if ($prpStmt->execute())
                return TRUE;
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage}.";
        } // catch
        return FALSE;
    } // createStudentUser

    /*
    *   !DML 
    *   revoke privileges on database objects for the given student role  
    *   @param string $index
    */
    private function revokeStudentPrivileges(string $index)
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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if stmts were successfully executed
        if ($prpStmt->execute() && $prpStmt2->execute())
            return TRUE;
        return FALSE;
    } // revokeStudentPrivileges

    /*
    *   !DML 
    *   drop database user in the cluster with the student role privileges
    *   @param string $index
    */
    private function dropStudentUser(string $index)
    {
        $stmt = "   DROP USER 
                        stu_$index  ";
        try {
            // prepare and execute stmt
            $prpStmt = $this->prepare($stmt);
            // if stmt executed successfully 
            if ($prpStmt->execute())
                return TRUE;
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage}.";
        } // catch
        return FALSE;
    } // dropStudentUser

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
                if ($this->createStudentUser($index, $pass)) {
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
                        echo 'Račun je uspešno ustvarjen.';
                        // commit the changes
                        return $this->commit();
                    } // if 
                } // if
                echo 'Napaka: račun ni uspešno ustvarjen.';
                // rollback the changes
                return $this->rollback();
            } // try
            catch (PDOException $e) {
                // output error message
                echo "Napaka: {$e->getMessage()}.";
            } // catch
        } // if
        echo 'Opozorilo: transkacija s podatkovno zbirko je v izvajanju.';
        return FALSE;
    } // insertStudentAccount

    /*
    *   select grant date of the given account
    *   @param int $id_attendances
    */
    public function selectAccountGrantDate(int $id_attendances)
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
    } // selectAccountGrantDate

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
                if ($this->revokeStudentPrivileges($index) && $this->dropStudentUser($index)) {
                    // if student had account avatar 
                    if ($avatar = $this->hasAccountAvatar($index))
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
                        echo 'Račun je uspešno izbrisan.';
                        // commit the current transaction
                        return $this->commit();
                    } // if
                } // if
                echo 'Napaka: račun ni uspešno izbrisan.';
                // rollback the current transaction
                return $this->rollback();
            } // try
            catch (PDOException $e) {
                echo "Napaka: {$e->getMessage()}.";
            } // catch
        } // if
        echo 'Opozorilo: transakcija s podatkovno zbirko je v izvajanju.';
        return FALSE;
    } // deleteStudentAccount

    /*
    *   if student has an account avatar
    *   @param string $index
    */
    public function hasAccountAvatar(string $index)
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
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if avatar was uploaded
        if (isset($avatar))
            return $avatar;
        return FALSE;
    } // hasAccountAvatar

    /* 
    *   update account avatar location on the server
    *   @param int $id_attendances
    *   @param string $avatar
    */
    private function updateAccountAvatar(int $id_attendances, string $avatar)
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
        } // try
        catch (PDOException $e) {
            // output the excpetion error message
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if avatar location was updated
        if ($prpStmt->rowCount() == 1)
            return TRUE;
        return FALSE;
    } // updateAccountAvatar

    /* 
    *   upload avatar for the given account 
    *   @param int $id_attendances
    */
    public function uploadAccountAvatar(int $id_attendances)
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
                    if ($mimetype != 'image/jpeg'){
                        echo "Napaka: avatar '{$_FILES['avatar']['name']}' ni uspešno naložen saj ni tipa .jpg.";
                        return FALSE;
                    } // if
                        // set destination of the uploded file
                        $dir = 'uploads/avatars/';
                        $destination = $dir . (new DateTime())->format('dmYHsi') . basename($_FILES['avatar']['name']);
                        // if account record was updated and avatar moved to the server
                        if ($this->updateAccountAvatar($id_attendances, $destination) == 1 && move_uploaded_file($_FILES['avatar']['tmp_name'], "../../{$destination}")) {
                            echo "Avatar {$_FILES['avatar']['name']} je uspešno naložen.";
                            // commit current transaction
                            return $this->commit();
                        } // if
                        echo 'Napaka: lokacija ni uspešno spremenjena ali avatar ni uspešno naložen na strežnik.';
                        // rollback current transaction
                        return $this->rollBack();
                    } // if
                echo "Napaka: avatar {$_FILES['avatar']['name']} ni uspešno naložen.";
            } // try
            catch (PDOException $e) {
                // output error message 
                echo "Napaka: {$e->getMessage()}.";
            } // catch 
        } // if
        echo 'Opozorilo: transakcija s podatkovno zbirko je v izvajanju.';
        return FALSE;
    } // uploadAccountAvatar

    /* 
    *   delete the given account avatar from the server and nullify the location  
    *   @param int $id_attendances
    *   @param string $avatar 
    */
    public function deleteAccountAvatar(int $id_attendances, string $avatar)
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
                    echo "Avatar je uspešno izbrisan.";
                    // commit current transaction
                    $this->commit();
                } // if
                echo 'Napaka: avatar ni uspešno logično ali fizično odstranjen.';
                // rollback current transaction
                return $this->rollBack();
            } // try
            catch (PDOException $e) {
                // output error message 
                echo "Napaka: {$e->getMessage()}.";
            } // catch 
        } // if
        echo 'Opozorilo: transakcija s podatkovno zbirko je v izvajanju.';
        return FALSE;
    } // deleteAccountAvatar

    // ACCOUNTS

} // DBC