<?php
//include db configuration file
include_once("config.php");
session_start();

if(isset($_POST["email_txt"]) && strlen($_POST["email_txt"])>0) 
{	//check $_POST["content_txt"] is not empty

	//sanitize post value, PHP filter FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH Strip tags, encode special characters.
	$emailToSave = filter_var($_POST["email_txt"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
	$passwordToSave = filter_var($_POST["password_txt"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
	
	// Insert sanitize string in record
	$insert_row = $mysqli->query("INSERT INTO users(email, password) VALUES('".$emailToSave."','".$passwordToSave."')");
	
	if($insert_row)
	{
		 //Record was successfully inserted, respond result back to index page
		 // $my_id = $mysqli->insert_id; //Get ID of last inserted row from MySQL using MySQLi extension
		  $my_id = $mysqli->lastInsertId(); //Get ID of last inserted row from MySQL using PDO
		  //echo $my_id;
		  $_SESSION['user_session']=$mysqli->lastInsertId();//stores the last inserted id into a session variable
		  //$mysqli->close(); //close db connection MySQLi extension
		  $mysqli=null;//close db connection using PDO

	}else{
		
		//header('HTTP/1.1 500 '.mysql_error()); //display sql errors.. must not output sql errors in live mode.
		header('HTTP/1.1 500 Looks like mysql error, could not insert record!');
		exit();
	}

}
elseif(isset($_POST["recordToDelete"]) && strlen($_POST["recordToDelete"])>0 && is_numeric($_POST["recordToDelete"]))
{	//do we have a delete request? $_POST["recordToDelete"]

	//sanitize post value, PHP filter FILTER_SANITIZE_NUMBER_INT removes all characters except digits, plus and minus sign.
	$idToDelete = filter_var($_POST["recordToDelete"],FILTER_SANITIZE_NUMBER_INT); 
	
	//try deleting record using the record ID we received from POST
	$delete_row = $mysqli->query("DELETE FROM add_delete_record WHERE id=".$idToDelete);
	
	if(!$delete_row)
	{    
		//If mysql delete query was unsuccessful, output error 
		header('HTTP/1.1 500 Could not delete record!');
		exit();
	}
	$mysqli->close(); //close db connection
}
else
{
	//Output error
	header('HTTP/1.1 500 Error occurred, Could not process request!');
    exit();
}
?>