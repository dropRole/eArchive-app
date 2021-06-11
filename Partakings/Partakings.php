<?php

namespace Partakings;

// table partaking class definition
class Partakings
{

    // encapsulation
    private $id_partakings; // primary key
    private $id_students; // foreign key
    private $id_scientific_papers; // foreign key
    private $part; // multi-value attribute

    /*
    *   constructs class instance 
    *   @param int $id_partakings 
    *   @param int $id_students
    *   @param int $id_scientific_papers
    *   @param string $part
    */
    public function __construct($id_partakings,  $id_students, $id_scientific_papers,  $part)
    {
        $this->id_partakings = $id_partakings;
        $this->id_students = $id_students;
        $this->id_scientific_papers = $id_scientific_papers;
        $this->part = $part;
    } // __construct

    /*
    *   set id of partaking
    *   @param int $id_partakings
    */
    public function setIdPartakings(int $id_partakings)
    {
        $this->id_partakings = $id_partakings;
    } // setIdPartaking

    // get id of partaking
    public function getIdPartakings()
    {
        return $this->id_partakings;
    } // setIdPartaking

    /*
    *   set id of a student
    *   @param int $id_students   
    */
    public function setIdStudents(int $id_students)
    {
        $this->id_students = $id_students;
    } // setIdStudents

    // get id of a students
    public function getIdStudents()
    {
        return $this->id_students;
    } // getIdStudents

    /*
    *   set id of scientific papers 
    *   @param int $id_scientific_papers
    */
    public function setIdScientificPapers(int $id_scientific_papers)
    {
        $this->id_scientific_papers = $id_scientific_papers;
    } // setIdScientificPapers

    // get id of scientific papers
    public function getIdScientificPapers()
    {
        return $this->id_scientific_papers;
    } // getIdScientificPapers

    /*
    *   set part of partaking  
    *   @param string @part
    */
    public function setPart(string $part)
    {
        $this->part = $part;
    } // setPart

    // get part of partaking 
    public function getPart()
    {
        return $this->part;
    } // getPart

} // Partaking 
