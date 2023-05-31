<?php
if ($myId >= 1) {
	$errorMsg = "";
	if (filter_input ( INPUT_POST, 'usersUp', FILTER_SANITIZE_NUMBER_INT ) == 1) {
		$name = filter_var ( htmlEntities ( trim ( $_POST ['nameNew'] ), ENT_QUOTES ), FILTER_SANITIZE_STRING );
		$pwd1 = filter_input ( INPUT_POST, 'pwdoneNew', FILTER_SANITIZE_STRING );
		$pwd2 = filter_input ( INPUT_POST, 'pwdtwoNew', FILTER_SANITIZE_STRING );

		if (filter_input ( INPUT_POST, 'emailNew', FILTER_VALIDATE_EMAIL )) {
			$newEmail = strtolower ( filter_input ( INPUT_POST, 'emailNew', FILTER_SANITIZE_EMAIL ) );
			$stmt = $db->prepare ( "SELECT COUNT(*) FROM users WHERE email=?" );
			$stmt->execute ( array (
					$newEmail
			) );
			$row = $stmt->fetch ();
			if ($row) {
				$email = ($row [0] >= 1) ? '0' : $newEmail;
				if ($email == '0') {
					$errorMsg = "The email you entered seems to already be in use.";
				} else {
					if ($pwd1 != "" && $pwd1 != " " && $pwd1 === $pwd2) {
						$salt = mt_rand ( 100000, 999999 );
						$hidepwd = hash ( 'sha512', ($salt . $pwd1), FALSE );
						$stmt = $db->prepare ( "INSERT INTO users VALUES" . "(NULL, ?, ?, ?, ?, ?, ?, ?)" );
						$stmt->execute ( array (
								$name,
								$email,
								$hidepwd,
								$salt,
								"0",
								"0",
								"0"
						) );
					} else {
						$errorMsg = "There was either no password entered, or your passwords did not match.";
					}
				}
			}
		}
		foreach ( $_POST as $key => $val ) {
			if (preg_match ( "/^name([1-9][0-9]*)$/", $key, $match )) {
				$upId = $match [1];
				$upVal = htmlentities ( filter_var ( $val, FILTER_SANITIZE_STRING ), ENT_QUOTES );
				$upName = $db->prepare ( "UPDATE users SET name = ? WHERE id = ?" );
				$upName->execute ( array (
						$upVal,
						$upId
				) );
			}
			if (preg_match ( "/^email([1-9][0-9]*)$/", $key, $match )) {
				$upId = $match [1];
				$upVal = filter_var ( $val, FILTER_SANITIZE_EMAIL );
				$upEmail = $db->prepare ( "UPDATE users SET email = ? WHERE id = ?" );
				$upEmail->execute ( array (
						$upVal,
						$upId
				) );
			}
			if (preg_match ( "/^pwdone([1-9][0-9]*)$/", $key, $match )) {
				$upId = $match [1];
				$upVal1 = filter_var ( $val, FILTER_SANITIZE_STRING );
				$upVal2 = filter_input ( INPUT_POST, "pwdtwo$upId", FILTER_SANITIZE_STRING );
				if ($upVal1 != "" && $upVal1 != " " && $upVal1 === $upVal2) {
					$salt = mt_rand ( 100000, 999999 );
					$hidepwd = hash ( 'sha512', ($salt . $upVal1), FALSE );
					$upPwd = $db->prepare ( "UPDATE users SET password = ?, salt = ? WHERE id = ?" );
					$upPwd->execute ( array (
							$hidepwd,
							$salt,
							$upId
					) );
				}
			}
			if (preg_match ( "/^deleteone([1-9][0-9]*)$/", $key, $match )) {
				$upId = $match [1];
				$upVal1 = filter_var ( $val, FILTER_SANITIZE_NUMBER_INT );
				$upVal2 = filter_input ( INPUT_POST, "deletetwo$upId", FILTER_SANITIZE_NUMBER_INT );
				if ($upVal1 == 1 && $upVal2 == 1) {
					$upDel = $db->prepare ( "DELETE FROM users WHERE id = ?" );
					$upDel->execute ( array (
							$upId
					) );
				}
			}
		}
	}

	if (filter_input ( INPUT_POST, 'store', FILTER_SANITIZE_NUMBER_INT ) == 1) {
		$showStore = filter_input ( INPUT_POST, 'showStore', FILTER_SANITIZE_NUMBER_INT );
		$store = $db->prepare ( "UPDATE company SET showStore = ? WHERE id = ?" );
		$store->execute ( array (
				$showStore,
				'1'
		) );
	}
	?>
<div
	style="padding: 30px 0px; text-align: center; font-weight: bold; font-size: 1.5em;">
	Settings</div>
	<div style='margin:10px; border:1px solid #000000; padding:20px;'>
<div
	style="font-weight: bold; font-size: 1em; padding: 10px; color: #ff0000;"><?php
	echo $errorMsg;
	?></div>
<form action="index.php?page=settings" method="post">
	<div style="font-weight: bold; font-size: 1.25em; padding: 10px;">Users</div>
	<div style="font-weight: bold; font-size: 1em; padding: 10px;">Add,
		Edit, Delete Users</div>
	<table cellspacing="0px">
		<tr>
			<td>Name</td>
			<td>Email</td>
			<td>Last Access</td>
			<td>New Password</td>
			<td>Password Again</td>
			<td>Delete User</td>
			<td>Verify Delete</td>
		</tr>
	<?php
	$getU = $db->prepare ( "SELECT * FROM users ORDER BY name" );
	$getU->execute ();
	while ( $getUR = $getU->fetch () ) {
		if ($getUR) {
			$id = $getUR ['id'];
			$name = html_entity_decode ( $getUR ['name'], ENT_QUOTES );
			$email = html_entity_decode ( $getUR ['email'], ENT_QUOTES );
			$lastLogin = $getUR ['lastLogin'];
			$loginDate = ($lastLogin >= 1) ? date ( "M j, Y - H:i:s", $lastLogin ) . " CST" : "Never";

			echo "<tr>";
			echo "<td><input type='text' name='name$id' value='$name' required></td>";
			echo "<td><input type='email' name='email$id' value='$email' required></td>";
			echo "<td>$loginDate</td>";
			if ($id == 1) {
				echo "<td colspan='4'>&nbsp;</td>";
			} else {
				echo "<td><input type='password' name='pwdone$id' value=''></td>";
				echo "<td><input type='password' name='pwdtwo$id' value=''></td>";
				echo "<td><input type='checkbox' name='deleteone$id' value='1'></td>";
				echo "<td><input type='checkbox' name='deletetwo$id' value='1'></td>";
				echo "</tr>";
			}
		}
	}
	echo "<tr>";
	echo "<td><input type='text' name='nameNew' value='' placeholder='New Name'></td>";
	echo "<td><input type='email' name='emailNew' value='' placeholder='New Email'></td>";
	echo "<td>&nbsp;</td>";
	echo "<td><input type='password' name='pwdoneNew' value=''></td>";
	echo "<td><input type='password' name='pwdtwoNew' value=''></td>";
	echo "<td>&nbsp;</td>";
	echo "<td>&nbsp;</td>";
	echo "</tr>";
	?>
	<tr>
			<td colspan='7'><input type='submit' value=' Update Users '><input
				type='hidden' name='usersUp' value='1'></td>
		</tr>
		</table>
		</form>
		</div>
		<div style='margin:10px; border:1px solid #000000; padding:20px;'>
		<div style="font-weight: bold; font-size: 1.25em; padding: 10px;">Store</div>
		<form action="index.php?page=settings" method="post">
		<input type="radio" name="showStore" value="0"<?php
	echo ($showStore == 0) ? " checked" : "";
	?>> Store link points to the Farmers Market store.<br><br>
		<input type="radio" name="showStore" value="1"<?php
	echo ($showStore == 1) ? " checked" : "";
	?>> Store link points to the LFaR website store.<br><br>
		<input type="submit" value=" Update "><input type="hidden" name="store" value="1">
		</form><br><br>
		Link to website store:<br>
		<a href="https://lunafarmandranch.com/store/index.php">https://lunafarmandranch.com/store/index.php</a><br><br>
		Link to website store admin:<br>
		<a href="https://lunafarmandranch.com/store/admin/index.php">https://lunafarmandranch.com/store/admin/index.php</a><br><br>
		</div>
	<?php
} else {
	echo "You don't have access to this page. Please log in.";
}