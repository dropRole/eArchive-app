<?php

namespace Offerings;

// table offerings class definition
class Offerings
{

    // encapsulation
    private $id_offerings; // primary key
    private $id_universities; // foreign key 
    private $id_programs; // foreign key
    private $faculty; // simple attribute
    private $degree; // simple attribute
    private $duration; // composite attribute

    /*
    *   constructs class instance 
    *   @param int $id_offerings
    *   @param int $id_universities
    *   @param int $id_programs
    *   @param string $offered
    */
    public function __construct($id_offerings, $id_universities, $id_programs, $faculty, $)
    {
        $this->id_offerings = $id_offerings;
        $this->id_universities = $id_universities;
        $this->id_programs = $id_programs;
        $this->offered = $offered;
    } // __construct

    /*
    *   set id of an offering 
    *   @param int $id_offerings
    */
    public function setIdOfferings(int $id_offerings)
    {
        $this->id_offerings = $id_offerings;
    } // setIdOfferings

    // get id of an offering
    public function getIdOfferings()
    {
        return $this->id_offerings;
    } // getIdOffering

    /*
    *   set id of a university
    *   @param int $id_universities
    */
    public function setIdUniversities(int $id_universities)
    {
        $this->id_universities = $id_universities;
    } // setIdUniversities

    // get id of a university
    public function getIdUniversities()
    {
        return $this->name;
    } // getIdUniversities

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
    *   set date of program offering     
    *   @param string @offered
    */
    public function setOffered(string $offered)
    {
        $this->offered = $offered;
    } // setOffered

    // get date of program offering   
    public function getOffered()
    {
        return $this->offered;
    } // getOffered

} // Offerings
