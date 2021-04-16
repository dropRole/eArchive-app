<?php

namespace Programs;

// table programs class definition
class Programs
{

    // encapsulation
    private $id_programs; // primary key
    private $name; // simple-value attribute
    private $degree; // multi-value attribute
    private $faculty; // multi-value attribute

    /*
    *   constructs class instance 
    *   @param int $id_programs 
    *   @param string $name
    *   @param string $degree
    *   @param string $faculty
    */
    public function __construct($id_programs, $name, $degree, $faculty = NULL)
    {
        $this->id_programs = $id_programs;
        $this->name = $name;
        $this->degree = $degree;
        $this->faculty = $faculty;
    } // __construct

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
    *   set name of a program
    *   @param string $name   
    */
    public function setName(string $name)
    {
        $this->name = $name;
    } // setName

    // get name of a program
    public function getName()
    {
        return $this->name;
    } // getName

    /*
    *   set degree of a program 
    *   @param string $degree
    */
    public function setDegree(string $degree)
    {
        $this->degree = $degree;
    } // setDegree

    // get degree of a program
    public function getDegree()
    {
        return $this->degree;
    } // getDegree
    
    /*
    *   set faculty on which programs is   
    *   @param string @part
    */
    public function setFaculty(string $faculty)
    {
        $this->faculty = $faculty;
    } // setFaculty

    // get faculty on which programs is   
    public function getFaculty()
    {
        return $this->faculty;
    } // getFaculty

} // Programs
