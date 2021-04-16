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
        $this->id_universities = $id_universities;
        $this->id_postal_code = $id_postal_codes;
        $this->name = $name;
        $this->type = $type;
        $this->email = $email;
        $this->address = $address;
        $this->telephone = $telephone;
        $this->rector = $rector;
    } // __construct

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
        return $this->id_universities;
    } // getIdUniversities

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
    *   set name of a university 
    *   @param string $name   
    */
    public function setName(string $name)
    {
        $this->name = $name;
    } // setName

    // get name of a university
    public function getName()
    {
        return $this->name;
    } // getName

    /*
    *   set type of a university 
    *   @param string $type   
    */
    public function setType(string $type)
    {
        $this->type = $type;
    } // setType

    // get type of a university
    public function getType()
    {
        return $this->type;
    } // getType

    /*
    *   set address of a university
    *   @param string $address   
    */
    public function setAddress(string $address)
    {
        $this->address = $address;
    } // setAddress

    // get address of a university
    public function getAddress()
    {
        return $this->address;
    } // getAddress

    /*
    *   set email of a university
    *   @param string $email
    */
    public function setEmail(string $email)
    {
        $this->email = $email;
    } // setEmail

    // get email of a university
    public function getEmail()
    {
        return $this->email;
    } // getEmail

    /*
    *   set telephone of a university
    *   @param string $telephone   
    */
    public function setTelephone(string $telephone)
    {
        $this->telephone = $telephone;
    } // setTelephone

    // get telephone of a university
    public function getTelephone()
    {
        return $this->telephone;
    } // getTelephone

    /*
    *   set rector of a university
    *   @param string @rector   
    */
    public function setRector(string $rector)
    {
        $this->rector = $rector;
    } // setRector

    // get rector of a university
    public function getRector()
    {
        return $this->rector;
    } // getRector

} // Universities
