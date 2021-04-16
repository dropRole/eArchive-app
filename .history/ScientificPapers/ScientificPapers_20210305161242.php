<?php

namespace ScientificPapers;

// table scientific_papers class definition
class ScientificPapers
{

    // encapsulation
    private $id_scientific_papers; // primary key
    private $id_students; // foreign key
    private $topic; // multi-value attribute
    private $written; // composite attribute

    /*
    *   constructs class instance 
    *   @param int $id_scientific_papers 
    *   @param int $id_students
    *   @param string $topic 
    *   @param string $written 
    */
    public function __construct($id_scientific_papers, $id_students, $topic, $written)
    {
        $this->id_scientific_papers = $id_scientific_papers;
        $this->id_students = $id_students;
        $this->topic = $topic;
        $this->written = $written;
    } // __construct

    /*
    *   set id of scientific papers
    *   @param int $id_scientific_papers
    */
    public function setIdScientificPapers(int $id_scientific_papers){
        $this->id_scientific_papers = $id_scientific_papers;
    } // setScientificPapers

    // get id of scientific papers
    public function getIdScientificPapers(){
        return $this->id_scientific_papers;
    } // getScientificPapers

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
    *   set topic of scientific papers
    *   @param string $topic
    */
    public function setTopic(string $topic){
        $this->topic = $topic;
    } // setTopic
    
    // get topic of scientific papers
    public function getTopic(){
        return $this->topic;
    } // getTopic

    /*
    *   set date of writting
    *   @param string $written   
    */
    public function setWritten(string $written){
        $this->written = $written;
    } // setWritten

    // get date of writting 
    public function getWritten(){
        return $this->written;
    } // getWritten

} // ScientificPapers 
