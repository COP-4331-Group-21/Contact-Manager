
<?php

	$inData = getRequestInfo();
	
	$ID = 0;
	$UserId = 0;
	$FirstName = "";
	$LastName = "";
	$Email = "";
	$Phone = "";
	$DateCreated = "";

	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331"); 	
	if( $conn->connect_error )
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		$stmt = $conn->prepare("SELECT ID,UserId,FirstName,LastName,Email,Phone,DateCreated FROM ContactList WHERE UserId=?");
		$stmt->bind_param("i", $inData["UserId"]);
		$stmt->execute();
		$result = $stmt->get_result();
		
		$dbdata = array();

		while ( $row = $result->fetch_assoc())  
		{
			$dbdata[]=$row;
		}
		
		if (!empty($dbdata))
		{
			sendArrayInfoAsJson( $dbdata );
		}
		else
		{
			returnWithError("No Records Found");
		}

		$stmt->close();
		$conn->close();
	}
	
	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}
	
	function sendArrayInfoAsJson ( $dbdata )
	{
		header('Content-type: application/json');
		echo json_encode($dbdata);
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
