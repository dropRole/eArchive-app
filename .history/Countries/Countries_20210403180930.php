<?php

namespace Countries;

// table countries class definition
class Countries
{

    // encapsulation
    private $id_countries; // primary key
    private $name; // multi-value attribute
    private $iso_3_code; // single-value attribute

    /*
    *   constructs class instance 
    *   @param int $id_countries
    *   @param string $name
    *   @param string $iso_3_code 
    */
    public function __construct(int $id_countries, string $name, string $iso_3_code)
    {
        $this->id_countries = $id_countries;
        $this->name = $name;
        $this->iso_3_code = $iso_3_code;
    } // __construct

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
    *   set name of a country
    *   @param string $name   
    */
    public function setName(string $name)
    {
        $this->name = $name;
    } // setName

    // get name of a country
    public function getName()
    {
        return $this->name;
    } // getName

    /*
    *   set three letter ISO code of country 
    *   @param string $iso_3_code
    */
    public function setISO3Code(string $iso_3_code)
    {
        $this->iso_3_code = $iso_3_code;
    } // setISO3Code

    // get three letter ISO code of country
    public function getISO3Code()
    {
        return $this->iso_3_code;
    } // getISO3Code

} // Countries 
