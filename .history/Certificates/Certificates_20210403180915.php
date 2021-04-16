<?php

namespace Certificates;

// namespace and class import declaration

use DateTime;

// table certificates class definition
class Certificates
{

    // encapsulation
    private $id_certificates; // primary key
    private $source; // multi-value attribute
    private $issued; // composite attribute

    /*
    *   constructs class instance 
    *   @param int $id_certificates 
    *   @param int $source
    *   @param DateTime $issued 
    */
    public function __construct(int $id_certificates, string $source, DateTime $issued)
    {
        $this->id_certificates = $id_certificates;
        $this->source = $source;
        $this->issued = $issued;
    } // __construct

    /*
    *   set id of a certificate
    *   @param int $id_certificates
    */
    public function setIdCertificate(int $id_certificates)
    {
        $this->id_certificates = $id_certificates;
    } // setIdCertificate

    // get id of a certificate
    public function getIdCertificate()
    {
        return $this->id_certificates;
    } // getIdCertificate

    /*
    *   set source of a certificate
    *   @param string $source   
    */
    public function setSource(string $source)
    {
        $this->source = $source;
    } // setSource

    // get source of a certificate
    public function getSource()
    {
        return $this->source;
    } // getSource

    /*
    *   set date of issuing 
    *   @param string $issued
    */
    public function setIssued(DateTime $issued)
    {
        $this->issued = $issued;
    } // setIssued

    // get date of issuing
    public function getIssued()
    {
        return $this->issued;
    } // getIssued

} // Certificates 
