<?php
$msg = "";
$showReset = 0;
$errorMsg = "";
if (filter_input ( INPUT_POST, 'upPwd', FILTER_SANITIZE_NUMBER_INT )) {
	$upId = filter_input ( INPUT_POST, 'upPwd', FILTER_SANITIZE_NUMBER_INT );
	$upSalt = filter_input ( INPUT_POST, 'upS', FILTER_SANITIZE_NUMBER_INT );
	$pwd1 = filter_input ( INPUT_POST, 'pwd1', FILTER_SANITIZE_STRING );
	$pwd2 = filter_input ( INPUT_POST, 'pwd2', FILTER_SANITIZE_STRING );
	$getS = $db->prepare ( "SELECT salt FROM users WHERE id = ?" );
	$getS->execute ( array (
			$upId
	) );
	$getSR = $getS->fetch ();
	if ($getSR) {
		$s = $getSR ['salt'];
		if ($s === $upSalt) {
			if ($pwd1 != "" && $pwd1 != " " && $pwd1 === $pwd2) {
				$salt = mt_rand ( 100000, 999999 );
				$hidepwd = hash ( 'sha512', ($salt . $pwd1), FALSE );
				$stmt = $db->prepare ( "UPDATE users SET password = ?, salt = ? WHERE id = ?" );
				$stmt->execute ( array (
						$hidepwd,
						$salt,
						$upId
				) );
				$msg = "You password has been updated.";
			} else {
				$errorMsg = "There was either no password entered, or your passwords did not match.";
				$showReset = 1;
			}
		}
	}
}
if (filter_input ( INPUT_GET, 'ver', FILTER_SANITIZE_STRING )) {
	$id = filter_input ( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
	$ver = filter_input ( INPUT_GET, 'ver', FILTER_SANITIZE_STRING );
	$get = $db->prepare ( "SELECT name, email, salt FROM users WHERE id = ?" );
	$get->execute ( array (
			$id
	) );
	$getR = $get->fetch ();
	if ($getR) {
		$name = $getR ['name'];
		$email = $getR ['email'];
		$salt = $getR ['salt'];
		$link = hash ( 'sha512', ($salt . $name . $email), FALSE );
		if ($ver === $link) {
			$showReset = 1;
		}
	}
}
if (filter_input ( INPUT_POST, 'fEmail', FILTER_SANITIZE_EMAIL )) {
	$fEmail = filter_input ( INPUT_POST, 'fEmail', FILTER_SANITIZE_EMAIL );
	$getU = $db->prepare ( "SELECT id, name, salt FROM users WHERE email = ?" );
	$getU->execute ( array (
			$fEmail
	) );
	$getUR = $getU->fetch ();
	if ($getUR) {
		$toId = $getUR ['id'];
		$name = $getUR ['name'];
		$salt = $getUR ['salt'];
		sendPWResetEmail ( $toId, $name, $fEmail, $salt );
		$msg = "Email sent with a link to reset your password.";
	} else {
		$msg = "Email not found.";
	}
}
?>
<div style='margin:10px; border:1px solid #000000; padding:20px;'>
<?php
if ($showReset == 1) {
	?>
	<div style="text-align: center; padding: 50px 0px; font-weight: bold;">
	<?php
	echo $errorMsg;
	?>
	<form action="index.php?page=forgotpwd" method="post">
	<label for="pwd1">Password</label>
	<input type="password" name="pwd1" value="">
	<label for="pwd2">Password again</label>
	<input type="password" name="pwd2" value="">
	<input type="submit" value=" Update Password ">
	<input type="hidden" name="upPwd" value="<?php
	echo $id;
	?>">
	<input type="hidden" name="upS" value="<?php
	echo $salt;
	?>">
	</form>
	</div>
	<?php
} else {
	if ($msg == "") {
		?>
<div style="text-align: center; padding: 50px 0px; font-weight: bold;">
	<form action="index.php?page=forgotpwd" method="post">
		<label for="fEmail">Enter Email</label> <input type="email"
			name="fEmail" value=""> <input type="submit" value=" send ">
	</form>
</div>
<?php
	} else {
		echo "<div style='text-align:center; padding:50px 0px; font-weight:bold;'>$msg</div>";
	}
}
?>
</div>