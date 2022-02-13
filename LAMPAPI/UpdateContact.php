<?php

	$inData = getRequestInfo();
	
	$ID = $inData["ID"];
	$UserId = $inData["UserId"];
	$FirstName = $inData["FirstName"];
	$LastName = $inData["LastName"];
	$Email = $inData["Email"];
	$Phone = $inData["Phone"];

	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331"); 	
	if( $conn->connect_error )
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		$stmt = $conn->prepare("UPDATE ContactList SET FirstName=?,LastName=?,Email=?,Phone=? WHERE ID=? AND UserId=?");
		$stmt->bind_param("ssssii", $FirstName, $LastName, $Email, $Phone, $ID ,$UserId);
		$stmt->execute();
		$result = $stmt->get_result();

        returnWithInfo( $ID, $UserId, $FirstName, $LastName, $Email, $Phone, $DateCreated );
		
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

    function returnWithInfo( $ID, $UserId, $FirstName, $LastName, $Email, $Phone, $DateCreated )
	{
		$retValue = '{"ID":"' . $ID . '","UserId":"' . $UserId . '","FirstName":"' . $FirstName . '","LastName":"' . $LastName . '","Email":"' . $Email . '","Phone":"' . $Phone . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}
	
	
?>