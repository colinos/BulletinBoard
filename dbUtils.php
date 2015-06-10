<?php

function connectToStudentsDatabase(){
  $dbHost = "localhost";
  $dbUsername = "***";	// commented out for upload to GitHub
  $dbPassword = "***";	// commented out for upload to GitHub
  $dbName = "***";		// commented out for upload to GitHub
  $dbLink = mysql_connect($dbHost, $dbUsername, $dbPassword);
  if(!$dbLink){
    echo(mysql_error());
    return 0;
  }

  $selected = mysql_select_db($dbName, $dbLink);
  if(!$selected){
    echo(mysql_error());
    return 0;
  }
  return $dbLink;
}

function prepareDataForDBEntry($string) {
    $string = str_replace("\\", "\\\\", $string);		// escape backslashes in user input
    $string = htmlspecialchars($string, ENT_QUOTES);	// convert & " ' < > to HTML entity references
    return $string;
}

function outputDBRow($row){
  echo(" <tr>");
  echo("  <td>".$row["url"]."</td>");
  echo("  <td>".$row["poster"]."</td>");
  echo("  <td>".$row["comments"]."</td>");
  echo(" </tr>");
}

function validateUser($username, $password){
	$dbLink = connectToStudentsDatabase();

	// get the password from the database for this username
	$queryString = "SELECT password FROM colin_users WHERE username = '$username'";
	$query = mysql_query($queryString, $dbLink);
	if(!$query){
		die("Could not query the database");
	}

	$row = mysql_fetch_array($query);

	mysql_close($dbLink);

	if( !$row ) {
		return false;	// username does not exist in Users Table
	} else {
		$correctPassword = $row["password"];	// username exists, correct password from DB assigned to $correctPassword
	}

	// check to see if entered password matches correct password
	if ($correctPassword == $password) {
		return true;
	}

	return false;
}

?>
