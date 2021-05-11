<?php
$headers = getallheaders();

$apiKey = $headers["X-Api-Key"];
$auth = $headers['Authorization'];

// echo "api:" . json_encode($headers);

if($apiKey != "fb141be8-d266-42e8-b768-ad51610ba93d"){
	header("HTTP/1.1 403 Forbidden ");
	exit;
}

// get database connection
include_once '../config/database.php';

include_once '../objects/log.php';
include_once '../objects/formSubmitItem.php';
include_once '../objects/formSubmit.php';
include_once '../objects/form.php';

$database = new Database();
$db = $database->getConnection(); 
$log = new Logger($db); 

$json = file_get_contents('php://input');

//$log->post_data = $_POST['post_data'];
$log->post_data = $json;//json_decode($json);
$log->timestamp = date('Y-m-d H:i:s');
$log->save();

$formItems = array('submissionId', 'accountId', 'formId', 'formName', 'formVersion', 'dispatchId', 'emailRecipientsOnSubmit', 'resubmit', 'userId', 'userName', 'alerts', 'formMetaData', 'updatedAt', 'workflowData', 'submitId'); 
$arr = json_decode($json, true);

$formSubmit = new FormSubmit($db);
$formSubmit->formId = $arr["formId"];

$form = new FormObj($db);
$form->formId = $arr["formId"];
$form->formName = $arr["formName"];
$form->formVersion = $arr["formVersion"];
$form->save();

foreach ($arr as $key => $value) {	
	if(!in_array($key, $formItems)){  	
		try{
			$answer = new FormSubmitItem($db); 
			$answer->submit_id = $arr["submitId"];
			$answer->field_name = $key;
			
			if(gettype($value) == "array")
			$answer->field_value = json_encode($value);
			else
			$answer->field_value = $value;			
			
			$answer->save();	
		}catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
			
			$log2 = new Logger($db); 
			$log2->post_data = $e->getMessage();
			$log2->timestamp = date('Y-m-d H:i:s');
			$log2->save();
		}		
	} else {
		if($key == "formMetaData"){
			$metaData = json_decode($value, true);
			$formSubmit->form_start =$value["startFormTimeStamp"];
			$formSubmit->form_end = $value["endFormTimeStamp"];
			$formSubmit->app_type = $value["deviceMetaData"]["appType"];
			$formSubmit->app_version = $value["deviceMetaData"]["appVersion"];
			$formSubmit->device = $value["deviceMetaData"]["device"];
			
			$metaData = json_decode($value, true);			
			$formSubmit->form_start = $value->startFormTimeStamp;
			$formSubmit->form_end = $value->endFormTimeStamp;
			$formSubmit->app_type = $value->deviceMetaData->appType;
			$formSubmit->app_version = $value->deviceMetaData->appVersion;
			$formSubmit->device = json_encode($metaData["deviceMetaData"]); //->device;
		}
		else{
			$formSubmit->{$key} = $value; 			
		}
	}	
}
$formSubmit->save();

?>