<?php
class FormObj{
 
    // database connection and table name
    private $conn;
    private $table_name = "tbl_form";
 
    // object properties
    public $formId;
    public $formVersion;
    public $formName;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
	
    function save(){    
        if($this->isAlreadyExist()){
            return false;
        }
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    form_id=:form_id, form_name=:form_name, form_version=:form_version";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        // $this->formId=htmlspecialchars(strip_tags($this->formId));
        // $this->formVersion=htmlspecialchars(strip_tags($this->formVersion));
        // $this->formName=htmlspecialchars(strip_tags($this->formName));
    
        // bind values
        $stmt->bindParam(":form_id", $this->formId);
        $stmt->bindParam(":form_name", $this->formName);
        $stmt->bindParam(":form_version", $this->formVersion);
    
        // execute query
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }
    
        return false;
        
    }
	
    function isAlreadyExist(){
        $query = "SELECT *
            FROM
                " . $this->table_name . " 
            WHERE
                form_id='".$this->formId."'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            return true;
        }
        else{
            return false;
        }
    }
}