<?php

namespace Faculties;

// table faculties class definition
class Faculties
{

    // encapsulation
    private $id_faculties; // primary key
    private $id_postal_codes; // foreign key
    private $name; // multi-value attribute
    private $address; // composite attribute
    private $email; // single-value attribute 
    private $telephone; // multi-value attibute  
    private $dean; // multi-value attribute

    /*
    *   constructs class instance 
    *   @param int $id_faculties
    *   @param int $id_postal_codes 
    *   @param string $name
    *   @param string $address 
    *   @param string $email 
    *   @param string $telephone 
    *   @param string $dean
    */
    public function __construct($id_faculties, $id_postal_codes, $name, $address,  $email,  $telephone,  $dean)
    {
        $this->id_faculties = $id_faculties;
        $this->id_postal_code = $id_postal_codes;
        $this->name = $name;
        $this->email = $email;
        $this->address = $address;
        $this->telephone = $telephone;
        $this->dean = $dean;
    } // __construct

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
    } // setIdFaculties

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
    *   set name of a faculty 
    *   @param string $name   
    */
    public function setName(string $name)
    {
        $this->name = $name;
    } // setName

    // get name of a faculty
    public function getName()
    {
        return $this->name;
    } // getName

    /*
    *   set address of a faculty
    *   @param string $address   
    */
    public function setAddress(string $address)
    {
        $this->address = $address;
    } // setAddress

    // get address of a faculty
    public function getAddress()
    {
        return $this->address;
    } // getAddress

    /*
    *   set email of a faculty
    *   @param string $email
    */
    public function setEmail(string $email)
    {
        $this->email = $email;
    } // setEmail

    // get email of a faculty
    public function getEmail()
    {
        return $this->email;
    } // getEmail

    /*
    *   set telephone of a faculty
    *   @param string $telephone   
    */
    public function setTelephone(string $telephone)
    {
        $this->telephone = $telephone;
    } // setTelephone

    // get telephone of a faculty
    public function getTelephone()
    {
        return $this->telephone;
    } // getTelephone

    /*
    *   set rector of a faculty
    *   @param string @rector   
    */
    public function setDean(string $dean)
    {
        $this->dean = $dean;
    } // setRector

    // get rector of a faculty
    public function getDean()
    {
        return $this->dean;
    } // getDean

} // Faculties
