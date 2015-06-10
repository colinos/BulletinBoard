<?php

require("./dbUtils.php");
//require("./validate.php");

session_start();	// creates a session or resumes the current one based on the current session id thats being passed via a request, such as GET, POST, or a cookie

if( !isset($_SESSION['registeredUsername']) ){
	$_SESSION['registeredUsername'] = "";
}
if( !isset($_SESSION['registeredPassword']) ){
	$_SESSION['registeredPassword'] = "";
}

$registeredUsername=$_SESSION['registeredUsername'];
$registeredPassword=$_SESSION['registeredPassword'];

if (!validateUser($registeredUsername, $registeredPassword)) {
	header("Location: login.php");
} else {
    if (isset($_REQUEST['name'], $_REQUEST['link'])) {
        $name = prepareDataForDBEntry($_REQUEST['name']);
        $link = prepareDataForDBEntry($_REQUEST['link']);
        $comments = prepareDataForDBEntry($_REQUEST['comments']);
        if ($comments == "") {
            $comments = "&nbsp;";    // cosmetic - comment string can be empty, but we still want the table cell to render the same as others, so non-breaking space is used as comment
        }

      $dbLink = connectToStudentsDatabase();
      $queryString = "INSERT INTO colin_bb SET poster = '$name', url = '$link', comments = '$comments'";
      $query = mysql_query($queryString, $dbLink);

      if(!$query){
        die("Could not query the database");
      }

      mysql_close($dbLink);
    }

	echo <<<END
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1">
  <title>PHP/SQL Demo</title>
  <!--<link rel="stylesheet" type="text/css" href="style.css">-->
 </head>

 <body>

<table border="1">
 <tr>
  <th><b>Link:</b></th>
  <th><b>Posted by:</b></th>
  <th><b>Comments:</b></th>
 </tr>
END;

	$dbLink = connectToStudentsDatabase();
	$queryString = "SELECT * FROM colin_bb";
	$query = mysql_query($queryString, $dbLink);

	if(!$query){
	  die("Could not query the database");
	}

	while($row = mysql_fetch_array($query)){
	  outputDBRow($row);
	}

	mysql_close($dbLink);

	echo <<<END
</table>

<p>&nbsp;</p>

<p>Enter a new link:</p>

<form method="POST" action="index.php">
	<table border="0">
		<tr>
			<td>Link:</td>
			<td><input type="text" name="link" size="30"></td>
		</tr>
		<tr>
			<td>Posted by:</td>
			<td><input type="text" name="name" size="20"></td>
		</tr>
		<tr>
			<td>Comments:</td>
			<td><input type="text" name="comments" size="30"></td>
		</tr>
	</table>

 <input type="submit" value="Submit" name="mySubmitButton">
 <input type="reset" value="Reset Form" name="myResetButton">
</form>

<p>&nbsp;</p>

<form method="POST" action="login.php">
 <input type="hidden" name="logout" value="1">
 <input type="submit" value="Logout">
</form>

 </body>
</html>
END;
}

?>
