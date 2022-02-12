<?php

  $inData = getRequestInfo();

  $searchResults = "";
  $searchCount = 0;

	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331"); 	
  if( $conn->connect_error )
  {
    returnWithError( $conn->connect_error );
  }
  else
  {
    $stmt = $conn->prepare(" SELECT ID,UserId,FirstName,LastName,Phone,Email FROM ContactList WHERE (FirstName like ? OR LastName like ? OR Phone Like ? OR Email like ?) AND UserId = ?");
    $searchInfo = "%" .$inData["search"] . "%";
    $stmt->bind_param("ssssi", $searchInfo , $searchInfo, $searchInfo, $searchInfo, $inData["UserId"]);
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