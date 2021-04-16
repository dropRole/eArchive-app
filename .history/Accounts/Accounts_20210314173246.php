<?php

namespace Accounts;

// table accounts class definition
class Accounts
{

    // encapsulation
    private $id_accounts; // primary key
    private $id_students; // foreign key
    private $pass; // composite attribute
    private $granted; // composite attribute
    private $avatar; // composite attribute  

    /*
    *   constructs class instance 
    *   @param int $id_accounts
    *   @param int $id_students
    *   @param string $pass
    *   @param string $granted 
    *   @param string $avatar 
    */
    public function __construct($id_accounts, $id_students, $pass, $granted, $avatar = NULL)
    {
        $this->id_accounts = $id_accounts;
        $this->id_students = $id_students;
        $this->pass = $pass;
        $this->granted = $granted;
        $this->avatar = $avatar;
    } // __construct

    /*
    *   set id of an account
    *   @param int $id_accounts   
    */
    public function setIdAccounts(int $id_accounts){
        $this->id_accounts = $id_accounts;
    } // setIdAccounts

    // get id of an account
    public function getIdAccounts(){
        return $this->id_accounts;
    } // getIdAccounts

    /*
    *   set id of a student
    *   @param int $id_students
    */
    public function setIdStudents(int $id_students){
        $this->id_scientific_papers = $id_students;
    } // setIdStudents

    // get id of a student
    public function getIdStudents(){
        return $this->id_students;
    } // getIdStudents

    /*
    *   set pass of an account 
    *   @param string $pass  
    */
    public function setPass(string $pass){
        $this->pass = $pass;
    } // setPass
    
    // get pass of an account
    public function getPass(){
        return $this->pass;
    } // getPass

    /*
    *   set date of grant  
    *   @param string @granted  
    */
    public function setGranted(string $granted){
        $this->granted = $granted;
    } // setGranted

    // get date of grant
    public function getGranted(){
        return $this->granted;
    } // getGranted

    /*
    *   set avatar of an account 
    *   @param string $avatar
    */
    public function setAvatar(string $avatar){
        $this->avatar = $avatar;
    } // setAvatar

    // get avatar of an account
    public function getAvatar(){
        return $this->avatar;
    } // getAvatar

} // Accounts
