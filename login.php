<?php
//require("validate.php");
require("./dbUtils.php");
error_reporting(0);
// create a session or resume the current one
session_start();

if ($_REQUEST['logout'] == 1) {
	$userLoggingOut = $_SESSION['registeredUsername'];
	$_SESSION['registeredUsername'] = "";
	$_SESSION['registeredPassword'] = "";
	setcookie("cookieUsername", $userLoggingOut, time()-1);
}

$loginFailed = false;
// request variables from server
$loginHidden = $_REQUEST['loginHidden'];		// $loginHidden is set if the login form has already been submitted; used to check whether to redirect logged-in users to index.php or to display login form
$loginUsername = $_REQUEST['loginUsername'];
$loginPassword = $_REQUEST['loginPassword'];
$cookieUsername = $_REQUEST['cookieUsername'];

if(isset($loginHidden)){
	if(validateUser($loginUsername, $loginPassword)){
		$_SESSION['registeredUsername'] = $loginUsername;
		$_SESSION['registeredPassword'] = $loginPassword;

		setcookie("cookieUsername", $loginUsername, time() + 86400);
		header("Location: index.php");
	}else{
		$loginFailed = true;
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1">
  <title>PHP/SQL Demo</title>
  <!--<link rel="stylesheet" type="text/css" href="style.css">-->
 </head>

 <body>
	<p>For this demo, you can use the following credentials to log in:<br>
	username: test<br>
	password: test</p>
		<form name = "loginForm" method = "POST" action = "login.php">
			<input type = "hidden" name = "loginHidden" value = "yes">
			<table width = "300" align = "center">
				<tr>
					<td colspan = "2" align = "center">
						LOGIN
					</td>
				</tr>
				<tr>
					<td>
						Username:
					</td>
					<td>
						<?php
						if(isset($cookieUsername)){
							echo("<input type = 'text' name = 'loginUsername' value = '$cookieUsername'>");
						}else{
							echo("<input type = 'text' name = 'loginUsername'>");
						}
						?>
					</td>
				</tr>
				<tr>
					<td>
						Password:
					</td>
					<td>
						<input type = "password" name = "loginPassword">
					</td>
				</tr>
				<tr>
					<td colspan = "2" align = "center">
						<input type = "submit" value = "Submit">
					</td>
				</tr>
				<?php
				if($loginFailed){
					echo("<tr>");
					echo("<td colspan = '2' align = 'center'>");
					echo("<p style='color:red;'>Incorrect username/password pair. Please try again.</p>");
					echo("</td>");
					echo("</tr>");
				}
				?>
			</table>
		</form>
 </body>
</html>
