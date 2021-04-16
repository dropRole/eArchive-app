<?php

namespace Documents;

// table documents class definition
class Documents
{

    // encapsulation
    private $id_documents; // primary key
    private $id_scientific_papers; // foreign key
    private $source; // composite attribute
    private $published; // composite attribute
    private $version; // single-value attribute  

    /*
    *   constructs class instance 
    *   @param int $id_documents
    *   @param int $id_scientific_papers
    *   @param string $source
    *   @param string $published 
    *   @param string $version 
    */
    public function __construct($id_documents, $id_scientific_papers, $source, $published, $version = NULL)
    {
        $this->id_documents = $id_documents;
        $this->id_scientific_papers = $id_scientific_papers;
        $this->source = $source;
        $this->published = $published;
        $this->version = $version;
    } // __construct

    /*
    *   set id of a document
    *   @param int $id_documents   
    */
    public function setIdDocuments(int $id_documents){
        $this->id_documents = $id_documents;
    } // setIdDocuments

    // get id of scientific papers
    public function getIdDocuments(){
        return $this->id_documents;
    } // getIdDocuments

    /*
    *   set id of scientific papers
    *   @param int $id_scientific_papers
    */
    public function setIdScientificPapers(int $id_scientific_papers){
        $this->id_scientific_papers = $id_scientific_papers;
    } // setIdScientificPapers

    // get id of scientific papers
    public function getIdScientificPapers(){
        return $this->id_scientific_papers;
    } // getIdScientificPapers

    /*
    *   set source of a document 
    *   @param string $source   
    */
    public function setSource(string $source){
        $this->source = $source;
    } // setSource
    
    // get source of a document
    public function getSource(){
        return $this->source;
    } // getSource

    /*
    *   set date of publishing 
    *   @param string @published  
    */
    public function setPublished(string $published){
        $this->published = $published;
    } // setPublished

    // get date of publishing
    public function getPublished(){
        return $this->published;
    } // getPublished

    /*
    *   set verion of a document 
    *   @param string $version
    */
    public function setVersion(string $version){
        $this->version = $version;
    } // setVersion

    // get version of a document
    public function getVersion(){
        return $this->version;
    } // getVersion

} // Documents
