<?php

namespace ScientificPapers;

// namespace and class import declaration

// table scientific_papers class definition
class ScientificPapers
{

    // encapsulation
    private $id_scientific_papers; // primary key
    private $id_attendances; // foreign key
    private $topic; // multi-value attribute
    private $type; // simple attribute
    private $written; // composite attribute

    /*
    *   constructs class instance 
    *   @param int $id_scientific_papers 
    *   @param int $id_attendances
    *   @param string $topic 
    *   @param string $type 
    *   @param DateTime $written 
    */
    public function __construct($id_scientific_papers, $id_attendances, $topic, $type, $written)
    {
        $this->id_scientific_papers = $id_scientific_papers;
        $this->id_attendances = $id_attendances;
        $this->topic = $topic;
        $this->type = $type;
        $this->written = $written;
    } // __construct

    /*
    *   set id of scientific papers
    *   @param int $id_scientific_papers
    */
    public function setIdScientificPapers(int $id_scientific_papers)
    {
        $this->id_scientific_papers = $id_scientific_papers;
    } // setScientificPapers

    // get id of scientific papers
    public function getIdScientificPapers()
    {
        return $this->id_scientific_papers;
    } // getScientificPapers

    /*
    *   set id of an attendance
    *   @param int $id_attendances   
    */
    public function setIdAttendances(int $id_attendances)
    {
        $this->id_attendances = $id_attendances;
    } // setIdAttendances

    // get id of an attendance
    public function getIdAttendances()
    {
        return $this->id_attendances;
    } // getIdAttendances

    /*
    *   set type of scientific papers
    *   @param string $type
    */
    public function setType(string $type)
    {
        $this->type = $type;
    } // setType

    // get type of scientific papers
    public function getType()
    {
        return $this->type;
    } // getType

    /*
    *   set topic of scientific papers
    *   @param string $topic
    */
    public function setTopic(string $topic)
    {
        $this->topic = $topic;
    } // setTopic

    // get topic of scientific papers
    public function getTopic()
    {
        return $this->topic;
    } // getTopic

    /*
    *   set date of writting
    *   @param string $written   
    */
    public function setWritten(string $written)
    {
        $this->written = $written;
    } // setWritten

    // get date of writting 
    public function getWritten()
    {
        return $this->written;
    } // getWritten

} // ScientificPapers 
