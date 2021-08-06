<?php

namespace Students;

use JsonSerializable;

// class definition of students table with custom JSON representation 
class Students implements JsonSerializable
{

    // encapsulation
    private $id_students; // primary key
    private $id_postal_codes; // foreign key
    private $id_accounts; // foreign key
    private $name; // single-value attribute
    private $surname; // multi-value attribute 
    private $email; // single-valued attibute  
    private $telephone; // multi-valued attribute

    /*
    *   constructs class instance 
    *   @param int $id_students 
    *   @param int $id_postal_codes 
    *   @param int $id_accounts 
    *   @param string $name 
    *   @param string $surname 
    *   @param string $email 
    *   @param string $telephone
    */
    public function __construct($id_students,  $id_postal_codes,  $id_accounts,  $name,  $surname,  $email = NULL, $telephone = NULL)
    {
        $this->id_students = $id_students;
        $this->id_postal_code = $id_postal_codes;
        $this->id_accounts = $id_accounts;
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->telephone = $telephone;
    } // __construct

    // redefine implemented function 
    public function jsonSerialize()
    {
        return get_object_vars($this);
    } // jsonSerialize

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
    *   set id of an account
    *   @param int $id_accounts   
    */
    public function setIdAccounts(int $id_accounts)
    {
        $this->id_accounts = $id_accounts;
    } // setIdAccounts

    // get id of an account
    public function getIdAccounts()
    {
        return $this->id_accounts;
    } // getIdAccounts

    /*
    *   set name of a student
    *   @param string $name   
    */
    public function setName(string $name)
    {
        $this->name = $name;
    } // setName

    // get name of a student
    public function getName()
    {
        return $this->name;
    } // getName

    /*
    *   set surname of a student
    *   @param string $surname   
    */
    public function setSurname(string $surname)
    {
        $this->surname = $surname;
    } // setSurname

    // get surname of a student
    public function getSurname()
    {
        return $this->surname;
    } // getSurname

    /*
    *   set email of a student
    *   @param string $email   
    */
    public function setEmail(string $email)
    {
        $this->email = $email;
    } // setEmail

    // get email of a student
    public function getEmail()
    {
        return $this->email;
    } // getEmail

    /*
    *   set telephone of a student
    *   @param string @telephone   
    */
    public function setTelephone(string $telephone)
    {
        $this->telephone = $telephone;
    } // setTelephone

    // get telephone of a student
    public function getTelephone()
    {
        return $this->telephone;
    } // getTelephone

} // Students 
