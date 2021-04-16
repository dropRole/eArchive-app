<?php

namespace Attendances;

// namespace and class import declaration

use DateTime;

// table attendances class definition
class Attendances
{

    // encapsulation
    private $id_attendances; // primary key
    private $id_students; // foreign key
    private $id_universities; // foreign key
    private $id_programs; // foreign key
    private $enrolled; // composite attribute  
    private $index; // single-value attribute  

    /*
    *   constructs class instance 
    *   @param int $id_attendances
    *   @param int $id_students 
    *   @param int $id_universities
    *   @param int $id_programs 
    *   @param DatTime $enrolled 
    *   @param int $index 
    */
    public function __construct(int $id_attendances, int $id_students, int $id_universities, int $id_programs, DateTime $enrolled, string $index)
    {
        $this->id_attendances = $id_attendances;
        $this->id_students = $id_students;
        $this->id_universities = $id_universities;
        $this->id_programs = $id_programs;
        $this->enrolled = $enrolled;
        $this->index = $index;
    } // __construct

    /*
    *   set id of attendance 
    *   @param int $id_attendances   
    */
    public function setIdAttendances(int $id_attendances){
        $this->id_attendances = $id_attendances;
    } // setIdAttendances

    // get id of attendance
    public function getIdAttendances(){
        return $this->id_attendances;
    } // getIdAttendances

    /*
    *   set id of a student
    *   @param int $id_students  
    */
    public function setIdStudents(int $id_students){
        $this->id_students = $id_students;
    } // setIdStudents

    // get id of a student
    public function getIdStudents(){
        return $this->id_students;
    } // getIdStudents

    /*
    *   set id of a university 
    *   @param int $id_universities   
    */
    public function setIdUniversities(int $id_universities){
        $this->id_universities = $id_universities;
    } // setIdUniversities
    
    // get id of a university
    public function getIdUniversities(){
        return $this->id_universities;
    } // getIdUniversities

    /*
    *   set id of a program 
    *   @param int $id_programs   
    */
    public function setIdPrograms(int $id_programs){
        $this->id_programs = $id_programs;
    } // setIdPrograms

    // get id of a program
    public function getIdPrograms(){
        return $this->id_programs;
    } // getIdPrograms

    /*
    *   set date of enrollment 
    *   @param string $enrolled
    */
    public function setEnrolled(string $enrolled){
        $this->enrolled = $enrolled;
    } // setEnrolled

    // get date of enrollment
    public function getEnrolled(){
        return $this->enrolled;
    } // getEnrolled

    /*
    *   set index of a student
    *   @param int $index   
    */
    public function setIndex(string $index){
        $this->index = $index;
    } // setIndex

    // get index of a student
    public function getIndex(){
        return $this->index;
    } // getIndex

} // Attendances
