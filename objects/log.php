<?php
class Logger{
 
    // database connection and table name
    private $conn;
    private $table_name = "tbl_log";
 
    // object properties
    public $post_data;
    public $timestamp;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
	
    function save(){
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    timestamp=:timestamp, post_data=:post_data";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->post_data=strip_tags($this->post_data);
        $this->timestamp=htmlspecialchars(strip_tags($this->timestamp));
    
        // bind values
        $stmt->bindParam(":post_data", $this->post_data);
        $stmt->bindParam(":timestamp", $this->timestamp);
    
        // execute query
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }
    
        return false;
        
    }
}