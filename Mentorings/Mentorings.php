<?php

namespace Mentorings;

use JsonSerializable;

// table mentorings class definition
class Mentorings implements JsonSerializable
{

    // encapsulation
    private $id_mentorings; // primary key
    private $id_faculties; // foreign key
    private $id_scientific_papers; // foreign key attribute
    private $mentor; // composite attribute
    private $taught; //  multi-value attribute 
    private $email; // single-value attibute  
    private $telephone; // multi-value attribute

    /*
    *   constructs class instance 
    *   @param int $id_mentorings
    *   @param int $id_faculties 
    *   @param int $id_scientific_papers
    *   @param string $mentor
    *   @param string $taught 
    *   @param string $email 
    *   @param string $telephone
    */
    public function __construct($id_mentorings, $id_scientific_papers, $id_faculties, $mentor,  $taught, $email,  $telephone)
    {
        $this->id_mentorings = $id_mentorings;
        $this->id_faculties = $id_faculties;
        $this->id_scientific_papers = $id_scientific_papers;
        $this->mentor = $mentor;
        $this->taught = $taught;
        $this->email = $email;
        $this->telephone = $telephone;
    } // __construct

    // implement jsonSerialize function

    public function jsonSerialize()
    {
        return get_object_vars($this);
    } // jsonSerialize

    /*
    *   set id of mentoring
    *   @param int $id_mentorings   
    */
    public function setIdMentorings(int $id_mentorings)
    {
        $this->id_mentorings = $id_mentorings;
    } // setIdMentorings

    // get id of mentoring
    public function getIdMentorings()
    {
        return $this->id_mentorings;
    } // getIdMentorings

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
    *   set mentor 
    *   @param string $mentor   
    */
    public function setMentor(string $mentor)
    {
        $this->mentor = $mentor;
    } // setMentor

    // get mentor
    public function getMentor()
    {
        return $this->mentor;
    } // getMentor

    /*
    *   set subject of teaching 
    *   @param string $taught
    */
    public function setTaught(string $taught)
    {
        $this->taught = $taught;
    } // setTaught

    // get subject of teaching
    public function getTaught()
    {
        return $this->taught;
    } // getTaught

    /*
    *   set mentors email
    *   @param string $email   
    */
    public function setEmail(string $email)
    {
        $this->email = $email;
    } // setEmail

    // get mentors email
    public function getEmail()
    {
        return $this->email;
    } // getEmail

    /*
    *   set mentors telephone
    *   @param string @telephone   
    */
    public function setTelephone(string $telephone)
    {
        $this->telephone = $telephone;
    } // setTelephone

    // get mentors telephone
    public function getTelephone()
    {
        return $this->telephone;
    } // getTelephone

} // Mentorings
