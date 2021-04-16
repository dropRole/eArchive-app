<?php

namespace PostalCodes;

// table postal_codes class definition
class PostalCodes
{

    // encapsulation
    private $id_postal_codes; // primary key
    private $id_countries; // foreign key
    private $municipality; // multi-value attribute
    private $code; // multi-value attribute

    /*
    *   constructs class instance 
    *   @param int $id_postal_codes 
    *   @param int $id_countries
    *   @param string $municipality 
    *   @param string $code 
    */
    public function __construct($id_postal_codes, $id_countries, $municipality, $code)
    {
        $this->id_postal_codes = $id_postal_codes;
        $this->id_countries = $id_countries;
        $this->municipality = $municipality;
        $this->code = $code;
    } // __construct

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
    *   set id of a country
    *   @param int $id_countries   
    */
    public function setIdCountries(int $id_countries)
    {
        $this->id_countries = $id_countries;
    } // setIdCountries

    // get id of a country
    public function getIdCountries()
    {
        return $this->id_countries;
    } // getIdCountries

    /*
    *   set municipality  
    *   @param string $municipality
    */
    public function setMunicipality(string $municipality)
    {
        $this->municipality = $municipality;
    } // setMunicipality

    // get municipality
    public function getMunicipality()
    {
        return $this->municipality;
    } // getMunicipality

    /*
    *   set code  
    *   @param string $code
    */
    public function setCode(string $code)
    {
        $this->code = $code;
    } // setCode

    // get code
    public function getCode()
    {
        return $this->code;
    } // getCode
    
} // PostalCodes 
