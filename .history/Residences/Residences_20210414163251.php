<?php

namespace Residences;

// table residences class definition
class Residences
{

    // encapsulation
    private $id_residences; // primary key
    private $id_postal_codes; // foreign key 
    private $id_students; // foreign key
    private $address; // composite attribute
    private $status; // simple attribute

    /*
    *   constructs class instance 
    *   @param int $id_residences
    *   @param int $id_postal_codes
    *   @param int $id_students
    *   @param string $address
    *   @param string $status
    */
    public function __construct($id_residences,  $id_postal_codes,  $id_students,  $address, $status)
    {
        $this->id_residences = $id_residences;
        $this->id_postal_codes = $id_postal_codes;
        $this->id_students = $id_students;
        $this->address = $address;
        $this->status = $status;
    } // __construct

    /*
    *   set id of sojourn 
    *   @param int $id_sojourns
    */
    public function setIdSojourns(int $id_sojourns)
    {
        $this->id_sojourns = $id_sojourns;
    } // setIdSojourns

    // get id of sojourn
    public function getIdSojourns()
    {
        return $this->id_sojourns;
    } // getIdSojourns

    /*
    *   set id of a postal code
    *   @param int $id_postal_codes
    */
    public function setIdPostalCodes(int $id_postal_codes)
    {
        $this->id_postal_codes = $id_postal_codes;
    } // setIdPostalCodes

    // get id of a postal code
    public function getIdPostalCodes()
    {
        return $this->id_postal_codes;
    } // getIdPostalCodes

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
    *   set address of sojourn     
    *   @param string @address
    */
    public function setAddress(string $address)
    {
        $this->address = $address;
    } // setAddress

    // get address of sojourn   
    public function getAddress()
    {
        return $this->address;
    } // getAddress

} // Sojourns
