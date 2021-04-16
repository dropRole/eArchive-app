<?php

namespace Programs;

// table programs class definition
class Programs
{

    // encapsulation
    private $id_programs; // primary key
    private $name; // simple-value attribute
    private $degree; // simple-value attribute
    private $duration; // simple-value attribute
    private $field; // multi-valued attribute

    /*
    *   constructs class instance 
    *   @param int $id_programs 
    *   @param string $name
    *   @param string $field
    */
    public function __construct($id_programs,  $name,  $field = NULL)
    {
        $this->id_programs = $id_programs;
        $this->name = $name;
        $this->degree = $field;
    } // __construct

    /*
    *   set id of a program
    *   @param int $id_programs
    */
    public function setIdPrograms(int $id_programs)
    {
        $this->id_programs = $id_programs;
    } // setIdPrograms

    // get id of a program
    public function getIdPrograms()
    {
        return $this->id_programs;
    } // getIdPrograms

    /*
    *   set name of a program
    *   @param string $name   
    */
    public function setName(string $name)
    {
        $this->name = $name;
    } // setName

    // get name of a program
    public function getName()
    {
        return $this->name;
    } // getName

    /*
    *   set field of a program 
    *   @param string $field
    */
    public function setField(string $field)
    {
        $this->field = $field;
    } // setField

    // get field of a program
    public function getField()
    {
        return $this->field;
    } // getField

} // Programs
