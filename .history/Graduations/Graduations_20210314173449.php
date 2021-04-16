<?php

namespace Graduations;

// table graduations class definition
class Graduations
{

    // encapsulation
    private $id_certificates; // primary key
    private $id_attendances; // primary key
    private $defended; // composite attribute

    /*
    *   constructs class instance 
    *   @param int $id_certificates
    *   @param int $id_attendances
    *   @param string $defended 
    */
    public function __construct($id_certificates, $id_attendances, $defended)
    {
        $this->id_certificates = $id_certificates;
        $this->id_attendances = $id_attendances;
        $this->defended = $defended;
    } // __construct

    /*
    *   set id of a certificate
    *   @param int $id_certificates   
    */
    public function setIdCertificates(int $id_certificates)
    {
        $this->id_certificates = $id_certificates;
    } // setIdCertificates

    // get id of a certificate
    public function getIdCertificates()
    {
        return $this->id_certificates;
    } // getIdCertificates

    /*
    *   set id of attendance 
    *   @param int $id_attendances
    */
    public function setIdAttendance(int $id_attendances)
    {
        $this->id_attendances = $id_attendances;
    } // setIdAttendance

    // get id of attendance
    public function getIdAttendance()
    {
        return $this->id_attendances;
    } // getIdAttendance
    
    /*
    *   set date of defending 
    *   @param string @defended
    */
    public function setDefended(string $defended)
    {
        $this->defended = $defended;
    } // setDefended

    // get date of defending
    public function getDefended()
    {
        return $this->defended;
    } // getDefended

} // Graduations 
