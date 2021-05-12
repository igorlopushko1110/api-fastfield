<?php
class FormSubmit{ 
    // database connection and table name
    private $conn;
    private $table_name = "tbl_form_submit";
 
    // object properties
	public $submitId;
	public $submissionId;
	public $accountId;
    public $formId;
    public $dispatchId;
    public $resubmit;
    public $userId;
    public $userName;
    public $form_start;
    public $form_end;
    public $app_type;
    public $app_version;
    public $device;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
	
    function save(){    
        if($this->isAlreadyExist()){
            return false;
        }				
		
        $query = "INSERT INTO
                    " . $this->table_name . "
					  SET
                    submit_id=:submit_id, submission_id=:submission_id,account_id=:account_id,
					form_id=:form_id, dispatch_id=:dispatch_id, resubmit=:resubmit, user_id=:user_id,
					username=:username, form_start=:form_start, form_end=:form_end, app_type=:app_type,
					app_version=:app_version, device=:device";
													
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        // $this->submitid=htmlspecialchars(strip_tags($this->submitid));
        // $this->submissionid=htmlspecialchars(strip_tags($this->submissionid));		
        // $this->account_id=htmlspecialchars(strip_tags($this->account_id));
        // $this->form_id=htmlspecialchars(strip_tags($this->form_id));
        // $this->dispatch_id=htmlspecialchars(strip_tags($this->dispatch_id));
        // $this->resubmit=htmlspecialchars(strip_tags($this->resubmit));
        // $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        // $this->username=htmlspecialchars(strip_tags($this->username));
        // $this->form_start=htmlspecialchars(strip_tags($this->form_start));
        // $this->form_end=htmlspecialchars(strip_tags($this->form_end));
        // $this->app_type=htmlspecialchars(strip_tags($this->app_type));
        // $this->app_version=htmlspecialchars(strip_tags($this->app_version));
        // $this->device=htmlspecialchars(strip_tags($this->device));
    
        // bind values
        $stmt->bindParam(":submit_id", $this->submitId);
        $stmt->bindParam(":submission_id", $this->submissionId);
        $stmt->bindParam(":account_id", $this->accountId);
        $stmt->bindParam(":form_id", $this->formId);        
        $stmt->bindParam(":dispatch_id", $this->dispatchId);
		$stmt->bindParam(":resubmit", $this->resubmit);
        $stmt->bindParam(":user_id", $this->userId);
         $stmt->bindParam(":username", $this->userName);
         $stmt->bindParam(":form_start", $this->form_start);
         $stmt->bindParam(":form_end", $this->form_end);
         $stmt->bindParam(":app_type", $this->app_type);
         $stmt->bindParam(":app_version", $this->app_version);
         $stmt->bindParam(":device", $this->device);       
    
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
                submit_id='".$this->submitId."'";
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