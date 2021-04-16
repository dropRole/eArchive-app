<?php

namespace DBC;

// namespace import declaration

use PDO, PDOException, finfo, DateTime, Documents\Documents, PostalCodes\PostalCodes, Countries\Countries, Faculties\Faculties, Programs\Programs;

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
    *   select scientific papers by keywords 
    *   @param string $keywords
    */
    public function selectPapersByKeywords(string $keywords)
    {
        // result set
        $resultSet = [];
        $stmt = '   SELECT 
                        documents.*,
                        certificates.*,
                        (name || // // || surname) AS fullname,
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
                        UPPER(name || surname) LIKE UPPER(:keywords)  ';
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
        // result set
        $resultSet = [];
        $stmt = '   SELECT 
                        documents.*,
                        certificates.*,
                        (name || // // || surname) AS fullname,
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
                        UPPER(name || surname) LIKE UPPER(:author)  ';
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
        // result set
        $resultSet = [];
        $stmt = '   SELECT 
                        documents.*,
                        certificates.*,
                        (name || // // || surname) AS fullname,
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
                        UPPER(mentor) LIKE UPPER(:mentor)  ';
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
    *   select scientific papers by year of writing 
    *   @param DateTime $year
    */
    public function selectPapersByYear(DateTime $year)
    {
        // result set
        $resultSet = [];
        $stmt = '   SELECT 
                        documents.*,
                        certificates.*,
                        (name || // // || surname) AS fullname,
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
                        DATE_PART(//year//, written) = :year ';
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':year', $mentor, PDO::PARAM_STR);
            $prpStmt->execute();
            $resultSet = $prpStmt->fetchAll(PDO::FETCH_OBJ);
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $resultSet;
    } // selectPapersByPublishing

    /*
    *   select all students 
    *   @param string $order
    */
    public function selectStudents(string $order = 'ASC')
    {
        // restult set
        $resultSet = [];
        switch ($order) {
            case 'ASC':
                $stmt = '   SELECT 
                                id_students,
                                (students.name || // // || surname) AS fullname, 
                                faculty.name AS faculty, 
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
                                surname    ';
                break;
            case 'DESC':
                $stmt = '   SELECT 
                                id_students,
                                (students.name || // // || surname) AS fullname, 
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
                                surname DESC';
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
        // if not student was selected
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
                $stmt = '   SELECT 
                                id_students,
                                (students.name || // // || surname) AS fullname, 
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
                                fullname    ';
            case 'DESC':
                $stmt = '   SELECT 
                                id_students,
                                (students.name || // // || surname) AS fullname, 
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
                                fullname DESC';
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
    public function selectStudentsByIndex(int $index, string $order = 'ASC')
    {
        // restult set
        $resultSet = [];
        switch ($order) {
            case 'ASC':
                break;
                $stmt = '   SELECT 
                                id_students,
                                (students.name || // // || surname) AS fullname, 
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
                                index = :index
                            ORDER BY
                                fullname    ';
            case 'DESC':
                $stmt = '   SELECT 
                                id_students,
                                (students.name || // // || surname) AS fullname, 
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
                                index = :index
                            ORDER BY
                                fullname DESC';
                break;
        } // switch
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':index' . $index, PDO::PARAM_INT);
            $prpStmt->execute();
            $resultSet = $prpStmt->fetchAll(PDO::FETCH_OBJ);
            // if not student was selected
        } // if
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        if ($prpStmt->rowCount() == 0)
            echo 'Ni študenta z danim indeksom.';
        return $resultSet;
    } // selectStudentsByIndex 

    /*
    *   select postal codes of given country 
    *   @param int $id_countries
    */
    public function selectPostalCodes(int $id_countries)
    {
        // result set
        $resultSet = [];
        $stmt = '   SELECT 
                        * 
                    FROM 
                        postal_codes    
                    WHERE 
                        id_countries = :id_countries    ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam('id_countries', $id_countries, PDO::PARAM_INT);
            $prpStmt->execute();
            $resultSet = $prpStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, PostalCodes::class, ['id_postal_codes', 'id_countries', 'municipality', 'code']);
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        return $resultSet;
    } // selectPostalCodes

    // select every country
    public function selectCountries()
    {
        // result set
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

    public function selectFaculties()
    {
        // result set
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
        // result set
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
    *   @param array $sojourns
    */
    public function insertStudent(int $id_postal_codes, string $name, string $surname, string $email = NULL, string $telephone = NULL, $sojourns = [])
    {
        // result
        $report = '';
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
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected
        if ($prpStmt->rowCount() == 1) {
            $report .= 'Osnovni podatki študenta uspešno vstavljeni.' . PHP_EOL;
            $i = 1; // counter
            foreach ($sojourns as $sojourn) {
                $stmt = '   INSERT INTO 
                                sojourns
                            (
                                id_postal_codes,
                                id_students,
                                address
                            ) 
                            VALUES(
                                :id_students,
                                :id_postal_codes,
                                address
                            )   ';
                try {
                    // prepare, bind params to and execute stmt
                    $prpStmt = $this->prepare($stmt);
                    $prpStmt->bindParam(':id_postal_codes', $sojourn['id_postal_codes'], PDO::PARAM_INT);
                    $prpStmt->bindParam(':id_students', $id_students, PDO::PARAM_INT);
                    $prpStmt->bindParam(':address', $sojourn['address'], PDO::PARAM_STR);
                    $prpStmt->execute();
                } // try
                catch (PDOException $e) {
                    echo "Napaka: {$e->getMessage()}.";
                } // catch
                // if single row is affected 
                if ($prpStmt->rowCount() == 1)
                    $report .= "Podatki o {$i}. bivališču so uspešno vstavljeni.";
                else
                    $report .= "Podatki o {$i}. bivališču niso uspešno vstavljeni.";
            } // foreach
        } // if
        else
            return 'Napaka: osnovni podakti študenta ter podatki o bivališču niso uspešno vstavljeni.' . PHP_EOL;
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
    *   @param array $sojourns
    */
    public function updateStudent(int $id_students, int $id_postal_codes, int $id_accounts, string $name, string $surname, string $email = NULL, string $telephone = NULL, $sojourns = [])
    {
        // result
        $report = '';
        $stmt = '   UPDATE 
                        students 
                    SET
                        id_postal_codes = :id_postal_codes,
                        id_accounts = :id_accounts, 
                        name = :name, 
                        surname = :surname, 
                        email = :email, 
                        telephone = :telephone
                    WHERE 
                        id_students = :id_students  ';
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindValue(':id_students', $id_students, PDO::PARAM_INT);
            $prpStmt->bindValue(':id_postal_codes', $id_postal_codes, PDO::PARAM_INT);
            $prpStmt->bindParam(':id_accounts', $id_accounts, PDO::PARAM_INT);
            $prpStmt->bindParam(':name', $name, PDO::PARAM_STR);
            $prpStmt->bindParam(':surname', $surname, PDO::PARAM_STR);
            $prpStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $prpStmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected
        if ($prpStmt->rowCount() == 1) {
            $report .= 'Osnovni podatki študenta uspešno ažurirani.' . PHP_EOL;
            $i = 1; // counter
            foreach ($sojourns as $sojourn) {
                $stmt = '   UPDATE 
                                sojourns
                            SET 
                                id_postal_codes = :id_postal_codes,
                                address = :address
                            WHERE 
                                id_students = :id_students  ';
                try {
                    // prepare, bind params to and execute stmt
                    $prpStmt = $this->prepare($stmt);
                    $prpStmt->bindParam(':id_postal_codes', $id_postal_codes, PDO::PARAM_INT);
                    $prpStmt->bindParam(':address', $address, PDO::PARAM_STR);
                    $prpStmt->bindParam(':id_students', $id_students, PDO::PARAM_INT);
                    $prpStmt->execute();
                } // try
                catch (PDOException $e) {
                    echo "Napaka: $e->getMessage()";
                } // catch
                // if single row is affected 
                if ($prpStmt->rowCount() == 1)
                    $report .= "Podatki o {$i}. bivališču so uspešno ažurirani.";
                else
                    $report .= "Podatki o {$i}. bivališču niso uspešno ažurirani.";
            } // foreach         
        } // if
        else
            return 'Napaka: osnovni podakti študenta ter podatki o bivališču niso uspešno ažurirani.' . PHP_EOL;
    } // updateStudent

    /*
    *   delete all student data  
    *   @param int $id_students 
    */
    public function deleteStudent(int $id_students)
    {
    } // deleteStudent

    /*
    *   insert attendance of a student
    *   @param int $id_students
    *   @param int $id_faculties
    *   @param int $id_programs
    *   @param DateTime $enrolled 
    *   @param int $enrolled 
    */
    public function insertAttendances(int $id_students, int $id_faculties, int $id_programs, DateTime $enrolled, int $index)
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
            $prpStmt->bindParam(':enrolled', $enrolled, PDO::PARAM_STR);
            $prpStmt->bindParam(':index', $index, PDO::PARAM_STR);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected 
        if ($prpStmt->rowCount() == 1)
            return 'Podatki o študiranju so uspešno vstavljeni.';
        else
            return 'Napaka: podatki o študiranju niso uspešno vstavljeni.';
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
    *   delete attendance of a student
    *   @param int $id_attendances
    */
    public function deleteAttendances(int $id_attendances)
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
            return 'Podatki o študiranju so uspešno izbrisani.';
        else
            return 'Podakti o študiranju niso uspešno izbrisani.';
    } // deleteAttendances

    /*
    *   insert graduation of a student
    *   @param int $id_attendances
    *   @param DateTime $defended
    */
    public function insertGraduation(int $id_attendances, DateTime $defended)
    {
        // report
        $report = '';
        // if not already running a transaction
        if ($this->inTransaction()) {
            try {
                // begin a new transaction
                $this->beginTransaction();
                // if certificate is uploaded successfully 
                if ($_FILES['certificate']['error'] == UPLOAD_ERR_OK) {
                    $finfo = new finfo();
                    $mimetype = $finfo->file($_FILES['certificate']['tmp_name'], FILEINFO_EXTENSION);
                    // if it's a PDF document
                    if (strpos(strtoupper($mimetype), 'PDF'))
                        $upload = TRUE;
                    else
                        $report .= 'Napaka: dokument ni naložen saj ni tipa PDF.';
                    // if document meets the condition 
                    if ($upload) {
                        // set destination of the uploded file
                        $dir = 'uploads/certificates/';
                        $destination = $dir . new DateTime('dmYHsi') . basename($_FILES['certificate']['name']);
                        $stmt = '   INSERT INTO
                                        certificates
                                    (
                                        source,
                                        issued
                                    )
                                    VALUES(
                                        :source,
                                        :issued,
                                    )   ';
                        // prepare, bind params to and execute stmt
                        $prpStmt = $this->prepare($stmt);
                        $prpStmt->bindValue(':source', $destination, PDO::PARAM_STR);
                        $prpStmt->bindParam(':issued', $issued, PDO::PARAM_STR);
                        $prpStmt->execute();
                        // if certificate was inserted into db and moved to a new destination  
                        if ($prpStmt->rowCount() == 1 && move_uploaded_file($_FILES['certificate']['tmp_name'], $destination)) {
                            $report .= 'Diploma je uspešno vstavljena.';
                            $id_certificates = $this->lastInsertId('certificates_id_certificates_seq');
                            $stmt = '   INSERT INTO
                                    graduations
                                (
                                    id_certificate,
                                    id_attendance,
                                    defended
                                )
                                VALUES(
                                    :id_certificate,
                                    :id_attendance,
                                    defended
                                )   ';
                            // prepare, bind params to and execute stmt
                            $prpStmt = $this->prepare($stmt);
                            $prpStmt->bindParam('id_certificate', $id_certificates, PDO::PARAM_INT);
                            $prpStmt->bindParam('id_attendances', $id_attendances, PDO::PARAM_INT);
                            $prpStmt->bindParam('defended', $defended, PDO::PARAM_STR);
                            $prpStmt->execute();
                            // if single row is affected 
                            if ($prpStmt->rowCount() == 1) {
                                $report .= 'Podatki o zaključku študiranja si uspešno vstavljeni.';
                                // commit transaction
                                $this->commit();
                            } // if
                            else
                                // rollback transaction
                                $this->rollBack();
                        } // if
                        else {
                            // rollback transaction
                            $this->rollBack();
                            return 'Napaka: podatki certifikata niso uspešno vstavljeni v zbirko ali datoteka ni uspešno prenesena na strežnik.';
                        } // else
                    } // if
                } // if
                else
                    return 'Napaka: certifikat ni uspešno naložen.';
            } // trt
            catch (PDOException $e) {
                // output error message 
                return "Napaka: {$e->getMessage()}.";
            } // catch 
        } // if
        return $report;
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
    public function deleteGraduation(int $id_attendances, int $id_certificates)
    {
        $stmt = '   DELETE FROM 
                            graduations 
                        WHERE 
                            id_attendances = :id_attendance AND id_certificates = :id_certificates  ';
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_attendance', $id_attendances, PDO::PARAM_INT);
            $prpStmt->bindParam(':id_attendance', $id_certificates, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected
        if ($prpStmt->rowCount()) {
            $this->deleteCertificate($id_certificates);
            return 'Podatki o zaključku študiranja so uspešno izbrisani.';
        } // if
        else
            return 'Napaka: podatki o zaključku študiranja ni uspešno izbrisan.';
    } // deleteGraduation

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
    *   @param int $id_certificates
    */
    public function deleteCertificate(int $id_certificates)
    {
        // report
        $report = '';
        $stmt = '   DELETE FROM
                        certificates 
                    WHERE 
                        id_certificates = :id_certificates  ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_certificates', $id_certificates, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected 
        if ($prpStmt->rowCount() == 1)
            $report .= 'Certifiakt je uspešno izbrisan.';
        else
            $report .= 'Certifiakt ni uspešno izbrisan.';
        return $report;
    } // deleteCertificate

    /*
    *   insert scientific paper particulars   
    *   @param string $topic
    *   @param DateTime $written
    *   @param string $type
    */
    public function insertScientificPapers(string $topic, DateTime $written, string $type)
    {
        // report
        $report = '';
        // if not already running a transaction
        if ($this->inTransaction()) {
            try {
                // begin new transaction
                $this->beginTransaction();
                $stmt = '   INSERT INTO 
                                scientific_papers
                            (
                                topic, 
                                wirtten,
                                type
                            )
                            VALUES(
                                :topic,
                                :written,
                                :type
                            )   ';
                // prepare, bind params to and execute stmt
                $prpStmt = $this->prepare($stmt);
                $prpStmt->bindParam(':topic', $topic, PDO::PARAM_STR);
                $prpStmt->bindParam(':written', $written, PDO::PARAM_STR);
                $prpStmt->bindParam(':type', $type, PDO::PARAM_STR);
                $prpStmt->execute();
                // if single row is affected
                if ($prpStmt->rowCount() == 1) {
                    $report .= "{$topic} je uspešno ustavljeno v zbirko. ";
                    $id_scientific_papers = $this->lastInsertId('scientific_papers_id_scientific_papers_seq');
                    $this->insertDocuments($id_scientific_papers);
                    // commit the transaction
                    $this->commit();
                } // if
                else {
                    // rollback the transaction
                    $this->rollBack();
                    $report .= "{$topic} ni uspešno vstavljen v zbirko. ";
                } // else
            } // try
            catch (PDOException $e) {
                // output error message 
                return "Napaka: {$e->getMessage()}.";
            } // catch
        } // if
        else
            return 'Opozorilo: Transakcija s podatkovno zbirko je v izvajanju.';
        return $report;
    } // insertScientificPapers

    /*
    *   update scientific paper particulars
    *   @param int $id_scientific_papers
    *   @param string $topic
    *   @param DateTime $written
    *   @param string $type
    */
    public function updateScientificPapers(int $id_scientific_papers, string $topic, DateTime $written, string $type)
    {
        try {
            $stmt = '   UPDATE  
                                scientific_papers
                        SET 
                            topic = :topic, 
                            written = :written, 
                            type  = :type 
                        WHERE 
                            id_scientific_papers = :id_scientific_papers    ';
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':topic', $topic, PDO::PARAM_STR);
            $prpStmt->bindParam(':written', $written, PDO::PARAM_STR);
            $prpStmt->bindParam(':type', $type, PDO::PARAM_STR);
            $prpStmt->bindParam(':id_scientific_papers', $id_scientific_papers, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            // output error message 
            return "Napaka: {$e->getMessage()}.";
        } // catch
    } // updateScientificPapers

    /*
    *   delete scientific paper particulars 
    *   @param int $id_scientific_papers
    */
    public function deleteScientificPapers(int $id_scientific_papers)
    {
        // action report
        $report = '';
        $stmt = '   DELETE FROM 
                            scientific_papers 
                        WHERE 
                            id_scientific_papers = :id_scientific_papers ';
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
        if ($prpStmt->rowCount()) {
            $report .= 'Podatki o znanstvenem delu so uspešno izbrisani.';
            $report .= $this->deleteDocuments($id_scientific_papers);
        } // if
        else
            $report .= 'Podatki o znanstvenem delu niso uspešno izbrisani.';
        return $report;
    } // deleteScientificPapers

    /*
    *   insert partaking particulars 
    *   @param int $id_students
    *   @param int $id_scientific_papers
    *   @param string $part
    */
    public function insertPartakings(int $id_students, int $id_scientific_papers, string $part)
    {
        $stmt = '   INSERT INTO 
                        partakings
                    (
                        id_students, 
                        id_scientific_papers,
                        part
                    )
                    VALUES(
                        :id_students,
                        :id_scientific_papers,
                        :part
                    )   ';
        try {
            // prepare, bind params to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_stundents', $id_students, PDO::PARAM_INT);
            $prpStmt->bindParam(':id_scientific_papers', $id_scientific_papers, PDO::PARAM_INT);
            $prpStmt->bindParam(':part', $part, PDO::PARAM_STR);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected 
        if ($prpStmt->rowCount() == 1)
            return 'Soavtor študija je uspešno dodan.';
        else
            return 'Napaka: soavtor študija ni uspešno dodan.';
    } // insertPartakings

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
    *   delete partaking particulars
    *   @param int $id_partakings
    */
    public function deletePartakings(int $id_partakings)
    {
        $stmt = '   DELETE FROM 
                        partakings
                    WHERE 
                        id_partakings = :id_partakings  ';
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
            return 'Podatki o soavtorstvu so uspešno izbrisani.';
        else
            return 'Podatki o soavtorstvu niso uspešno izbrisani.';
    } // deletePartakings

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
    *   insert documents of scientific paper
    *   @param int $id_scientific_papers
    */
    public function insertDocuments(int $id_scientific_papers)
    {
        // report
        $report = '';
        // check if any file is uploaded 
        $upload = FALSE;
        foreach ($_FILES['documents']['tmp_name'] as $index => $tmpName) {
            // if there was no error while uploading
            if ($_FILES['documents']['error'][$index] == UPLOAD_ERR_OK) {
                $finfo = new finfo();
                $mimetype = $finfo->file($tmpName, FILEINFO_EXTENSION);
                // if it's a PDF document
                if (strpos(strtoupper($mimetype), 'PDF'))
                    $upload = TRUE;
                else
                    $report .= 'Napaka: dokument ni naložen saj ni tipa PDF.' . PHP_EOL;
                // if document meets the condition 
                if ($upload) {
                    // set destination of the uploded file
                    $dir = 'uploads/documents/';
                    $destination = $dir . new DateTime('dmYHsi') . basename($_FILES['documents']['name'][$index]);
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
                        $prpStmt->bindValue(':published', (new DateTime('d-m-Y')), PDO::PARAM_STR);
                        $prpStmt->bindParam(':version', $version, PDO::PARAM_STR);
                        $prpStmt->execute();
                    } // try
                    catch (PDOException $e) {
                        echo "Napaka: {$e->getMessage()}.";
                    } // catch
                    // if documents were inserted into db and moved to a new destination  
                    if ($prpStmt->rowCount() == 1 && move_uploaded_file($tmpName, $destination))
                        $report .= "Dokument {$_FILES['documents']['name'][$index]} je uspešno naložen." . PHP_EOL;
                    else
                        $report .= "Napaka: dokument {$_FILES['documents']['name'][$index]} ni naložen." . PHP_EOL;
                } // if
            } // if
            else
                return "Napaka: dokument {$_FILES['documents']['name'][$index]} ni uspešno naložen.";
        } // foreach
    } // insertDocuments

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
    *   delete all documents of scientific paper
    *   @param int $id_scientific_papers
    */
    public function deleteDocuments(int $id_scientific_papers)
    {
        // action report
        $report = '';
        // result set
        $resultSet = [];
        $stmt = '   SELECT 
                        id_documents,
                        source
                    FROM 
                        documents 
                    WHERE 
                        id_scientific_papers = :id_scientific_papers    ';
        try {
            // prepare, bind param to and execute stmt 
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':id_scientific_papers', $id_scientific_papers, PDO::PARAM_INT);
            $prpStmt->execute();
            $resultSet = $prpStmt->fetchAll(PDO::FETCH_CLASS, Documents::class, ['id_documents', 'id_scientific_papers', 'source', 'published', 'version']);
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        foreach ($resultSet as $document) {
            $stmt = '   DELETE FROM 
                            documents
                        WHERE 
                            id_documents = :id_documents    ';
            // prepare, bind param to and execute stmt 
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindValue(':id_documents', $document->getIdDocuments(), PDO::PARAM_INT);
            $prpStmt->execute();
            // if single row is affected and document deleted
            if ($prpStmt->rowCount() == 1 && unlink($document->getSource()))
                $report .= 'Dokument' . basename($document->getSource()) . ' je uspešno izbrisan.' . PHP_EOL;
            else
                $report .= 'Dokument' . basename($document->getSource()) . ' ni uspešno izbrisan.' . PHP_EOL;
        } // foreach
        return $report;
    } // deleteDocuments     

    /*
    *   delete particular document of a scientific paper
    *   @param int $id_documents
    */
    public function deleteDocument(int $id_documents)
    {
        $stmt = '   SELECT 
                        source
                    FROM 
                        documents 
                    WHERE 
                        id_documents = :id_documents    ';
        try {
            // prepare, bind param to and execute stmt 
            $prpStmt = $this->prepare($stmt, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $prpStmt->bindParam(':id_documents', $id_documents, PDO::PARAM_INT);
            $prpStmt->execute();
            $document = $prpStmt->fetch(PDO::FETCH_CLASS, Documents::class, ['id_documents', 'id_scientific_papers', 'source', 'published', 'version']);
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        $stmt = '   DELETE FROM 
                            documents
                    WHERE 
                            id_documents = :id_documents    ';
        try {
            // prepare, bind param to and execute stmt 
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindValue(':id_documents', $document->getIdDocuments(), PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected and document deleted
        if ($prpStmt->rowCount() == 1 && unlink($document->getSource()))
            return 'Dokument' . basename($document->getSource()) . ' je uspešno izbrisan.';
        else
            return 'Dokument' . basename($document->getSource()) . ' ni uspešno izbrisan.';
    } // deleteDocument     

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
    *   @param int $id_students
    *   @param string $pass
    */
    public function insertAccount(int $id_students, string $pass)
    {
        // if not already in a transaction
        if ($this->inTransaction()) {
            try {
                // begin new transaction
                $this->beginTransaction();
                $stmt = '   INSERT INTO 
                        accounts
                    (  
                        id_students,
                        pass,
                        granted,
                        avatar
                    )
                    VALUES(
                        :id_students,
                        :pass,
                        :granted,
                        DEFAULT
                    )   ';
                // prepare, bind params to and execute stmt
                $prpStmt = $this->prepare($stmt);
                $prpStmt->bindParam(':id_students', $id_students, PDO::PARAM_INT);
                $prpStmt->bindValue(':pass', password_hash($pass, PASSWORD_BCRYPT), PDO::PARAM_STR);
                $prpStmt->bindValue(':granted', (new DateTime())->format('d-m-Y'), PDO::PARAM_STR);
                $prpStmt->execute($stmt);
                // if single row is affected 
                if ($prpStmt->rowCount() == 1) {
                    $id_accounts = $this->lastInsertId('accounts_id_accounts_seq');
                    $stmt = '   UPDATE
                                students 
                            SET 
                                id_accounts = :id_accounts
                            WHERE 
                                id_students = :id_students  ';
                    // prepare, bind params to and execute stmt
                    $prpStmt = $this->prepare($stmt);
                    $prpStmt->bindParam(':id_accounts', $id_accounts, PDO::PARAM_INT);
                    $prpStmt->bindParam(':id_students', $id_students, PDO::PARAM_INT);
                    $prpStmt->execute();
                    // if single one row was affected
                    if ($prpStmt->rowCount() == 1) {
                        return 'Račun je študentu uspešno dodeljen.';
                        // commit transaction
                        $this->commit();
                    } // if
                    else {
                        return 'Napaka: račun študentu ni uspešno dodeljen.';
                        // rollback transaction
                        $this->rollBack();
                    } // else
                } // if
                else
                    return 'Napaka: Račun ni uspešno ustvarjen.';
            } // try
            catch (PDOException $e) {
                // output error message
                echo "Napaka: {$e->getMessage()}.";
            } // catch
        } // if 
    } // insertAccount

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
    public function deleteAccount($id_accounts)
    {
        $stmt = '   DELETE FROM 
                        accounts
                    WHERE 
                        id_accoutn = :id_account    ';
        try {
            // prepare, bind param to and execute stmt
            $prpStmt = $this->prepare($stmt);
            $prpStmt->bindParam(':id_account', $id_accounts, PDO::PARAM_INT);
            $prpStmt->execute();
        } // try
        catch (PDOException $e) {
            echo "Napaka: {$e->getMessage()}.";
        } // catch
        // if single row is affected
        if ($prpStmt->rowCount() == 1)
            return 'Račun je uspešno izbrisan.';
        else
            return 'Račun ni uspešno izbrisan.';
    } // deleteAccount

} // DBC
