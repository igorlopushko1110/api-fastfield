<?php
class FormSubmitItem{
 
    // database connection and table name
    private $conn;
    private $table_name = "tbl_form_submit_answer";
 
    // object properties
    public $submit_id;
    public $field_name;
    public $field_value;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
	
    function save(){
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    field_name=:field_name, field_value=:field_value, submit_id=:submit_id";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->submit_id=$this->submit_id;
        $this->field_name=htmlspecialchars(strip_tags($this->field_name));
        $this->field_value=htmlspecialchars(strip_tags($this->field_value));
    
        // bind values        
        $stmt->bindParam(":field_name", $this->field_name);
        $stmt->bindParam(":field_value", $this->field_value);
		$stmt->bindParam(":submit_id", $this->submit_id);
    
        // execute query
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }
    
        return false;
        
    }

}