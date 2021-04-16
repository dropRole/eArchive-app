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
    private $id_faculties; // foreign key
    private $id_programs; // foreign key
    private $enrolled; // composite attribute  
    private $index; // single-value attribute  

    /*
    *   constructs class instance 
    *   @param int $id_attendances
    *   @param int $id_students 
    *   @param int $id_faculties
    *   @param int $id_programs 
    *   @param DatTime $enrolled 
    *   @param int $index 
    */
    public function __construct($id_attendances, $id_students, $id_faculties,  $id_programs, $enrolled,  $index)
    {
        $this->id_attendances = $id_attendances;
        $this->id_students = $id_students;
        $this->id_faculties = $id_faculties;
        $this->id_programs = $id_programs;
        $this->enrolled = $enrolled;
        $this->index = $index;
    } // __construct

    /*
    *   set id of attendance 
    *   @param int $id_attendances   
    */
    public function setIdAttendances(int $id_attendances)
    {
        $this->id_attendances = $id_attendances;
    } // setIdAttendances

    // get id of attendance
    public function getIdAttendances()
    {
        return $this->id_attendances;
    } // getIdAttendances

    /*
    *   set id of a student
    *   @param int $id_students  
    */
    public function setIdStudents(int $id_students)
    {
        $this->id_students = $id_students;
    } // setIdStudents

    // get id of a student
    public function getIdStudents()
    {
        return $this->id_students;
    } // getIdStudents

    /*
    *   set id of a faculty 
    *   @param int $id_faculties   
    */
    public function setIdFaculties(int $id_faculties)
    {
        $this->id_faculties = $id_faculties;
    } // setIdFaculties

    // get id of a faculty
    public function getIdFaculties()
    {
        return $this->id_faculties;
    } // getIdFaculties

    /*
    *   set id of a program 
    *   @param int $id_programs   
    */
    public function setIdPrograms(int $id_programs)
    {
        $this->id_programs = $id_programs;
    } // setIdPrograms

    // get id of a program
    public function getIdPrograms()
    {
        return $this->id_programs;
    } // getIdPrograms

    /*
    *   set date of enrollment 
    *   @param string $enrolled
    */
    public function setEnrolled(string $enrolled)
    {
        $this->enrolled = $enrolled;
    } // setEnrolled

    // get date of enrollment
    public function getEnrolled()
    {
        return $this->enrolled;
    } // getEnrolled

    /*
    *   set index of a student
    *   @param int $index   
    */
    public function setIndex(string $index)
    {
        $this->index = $index;
    } // setIndex

    // get index of a student
    public function getIndex()
    {
        return $this->index;
    } // getIndex

} // Attendances
