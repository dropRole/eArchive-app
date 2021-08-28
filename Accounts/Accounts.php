<?php

namespace Accounts;

// class import declaration

use DateTime;

// table accounts class definition
class Accounts
{

    // encapsulation
    private $id_attendances; // primary key
    private $pass; // composite attribute
    private $granted; // composite attribute
    private $avatar; // composite attribute  

    /*
    *   constructs class instance 
    *   @param int $id_attendances
    *   @param string $pass
    *   @param DateTime $granted 
    *   @param string $avatar 
    */
    public function __construct($id_attendances, $pass, $granted, $avatar = NULL)
    {
        $this->id_attendances = $id_attendances;
        $this->pass = $pass;
        $this->granted = $granted;
        $this->avatar = $avatar;
    } // __construct

    /*
    *   set id of attendance
    *   @param int $id_attendances
    */
    public function setIdAttendances(int $id_attendances)
    {
        $this->id_attendances = $id_attendances;
    } // setIdAttendances

    // get id of attendance
    public function getIdAttendances()
    {
        return $this->id_attendances;
    } // getIdAttendances

    /*
    *   set pass of an account 
    *   @param string $pass  
    */
    public function setPass(string $pass)
    {
        $this->pass = $pass;
    } // setPass

    // get pass of an account
    public function getPass()
    {
        return $this->pass;
    } // getPass

    /*
    *   set date of grant  
    *   @param DateTime @granted  
    */
    public function setGranted(DateTime $granted)
    {
        $this->granted = $granted;
    } // setGranted

    // get date of grant
    public function getGranted()
    {
        return $this->granted;
    } // getGranted

    /*
    *   set avatar of an account 
    *   @param string $avatar
    */
    public function setAvatar(string $avatar)
    {
        $this->avatar = $avatar;
    } // setAvatar

    // get avatar of an account
    public function getAvatar()
    {
        return $this->avatar;
    } // getAvatar

} // Accounts
