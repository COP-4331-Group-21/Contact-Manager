<?php

	$inData = getRequestInfo();
	
	$ID = $inData["ID"];
	$UserId = $inData["UserId"];
	$FirstName = $inData["FirstName"];
	$LastName = $inData["LastName"];
	$Email = $inData["Email"];
	$Phone = $inData["Phone"];
	$today = date("Y-m-d H:i:s");
	$DateCreated = $today;

	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331"); 	
	if( $conn->connect_error )
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		$stmt = $conn->prepare("UPDATE ContactList SET FirstName=?,LastName=?,Email=?,Phone=?, DateCreated=? WHERE ID=? AND UserId=?");
		$stmt->bind_param("sssssss", $FirstName, $LastName, $Email, $Phone, $DateCreated, $ID ,$UserId);
		$stmt->execute();
		$result = $stmt->get_result();
		
		$stmt->close();
		$conn->close();
	}
	
	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}


	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError( $err )
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	
?>
