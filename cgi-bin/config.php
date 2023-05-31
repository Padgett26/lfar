<?php
session_start ();

include "../globalFunctions.php";

$db = db_lfar();

$debugging = 0; // 1 for debug info showing, 0 for not showing

date_default_timezone_set ( 'America/Chicago' );
$time = time ();
$domain = "lunafarmandranch.com";

// *** Log out ***
if (filter_input ( INPUT_GET, 'logout', FILTER_SANITIZE_STRING ) == 'yep') {
	setcookie ( "staySignedIn", '', $time - 1209600, "/", $domain, 0 );
	$_SESSION ['myId'] = '0';
}

// *** Sign in ***
$loginErr = "x";
if (filter_input ( INPUT_POST, 'login', FILTER_SANITIZE_NUMBER_INT ) == "1") {
	$email = (filter_input ( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL )) ? strtolower ( filter_input ( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL ) ) : '0';
	$login1stmt = $db->prepare ( "SELECT id,salt FROM users WHERE email = ?" );
	$login1stmt->execute ( array (
			$email
	) );
	$login1row = $login1stmt->fetch ();
	if ($login1row) {
		$salt = ($login1row) ? $login1row ['salt'] : 000000;
		$checkId = (isset ( $login1row ['id'] ) && $login1row ['id'] > 0) ? $login1row ['id'] : '0';
		$pwd = filter_input ( INPUT_POST, 'pwd', FILTER_SANITIZE_STRING );
		$hidepwd = hash ( 'sha512', ($salt . $pwd), FALSE );
		$login2stmt = $db->prepare ( "SELECT id, name FROM users WHERE email = ? AND password = ?" );
		$login2stmt->execute ( array (
				$email,
				$hidepwd
		) );
		$login2row = $login2stmt->fetch ();
		if ($login2row) {
			if ($login2row ['id']) {
				$x = $login2row ['id'];
				$_SESSION ['myId'] = $x;
				setcookie ( "staySignedIn", $_SESSION ['myId'], $time + 1209600, "/", $domain, 0 ); // set for 14 days
			} else {
				$loginErr = "Your email / password combination isn't correct.";
			}
		}
	}
}

// *** User settings ***
$myId = (isset ( $_SESSION ['myId'] ) && ($_SESSION ['myId'] >= '1')) ? $_SESSION ['myId'] : '0'; // are they logged in
if ($myId == '0' && (empty ( filter_input ( INPUT_GET, 'logout', FILTER_SANITIZE_STRING ) ))) {
	$myId = (filter_input ( INPUT_COOKIE, 'staySignedIn', FILTER_SANITIZE_NUMBER_INT ) >= '1') ? filter_input ( INPUT_COOKIE, 'staySignedIn', FILTER_SANITIZE_NUMBER_INT ) : '0'; // are they logged in
}

$checkId = $db->prepare ( "SELECT COUNT(*) FROM users WHERE id = ?" );
$checkId->execute ( array (
		$myId
) );
$checkIdR = $checkId->fetch ();
$idCount = $checkIdR [0];
if ($idCount == 0) {
	$_SESSION ['myId'] = '0';
	setcookie ( "staySignedIn", '', $time - 1209600, "/", $domain, 0 );
	$myId = 0;
}

if ($myId != 0) {
	$lastUpdate = $db->prepare ( "UPDATE users SET lastLogin = ? WHERE id = ?" );
	$lastUpdate->execute ( array (
			$time,
			$myId
	) );
}

// *** page settings ***
$page = (filter_input ( INPUT_GET, 'page', FILTER_SANITIZE_STRING )) ? filter_input ( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) : "home";
if (! file_exists ( "pages/" . $page . ".php" )) {
	$page = "home";
}

$com = $db->prepare ( "SELECT * FROM company WHERE id = '1'" );
$com->execute ();
$comR = $com->fetch ();
$companyName = $comR ['companyName'];
$address1 = $comR ['address1'];
$address2 = $comR ['address2'];
$phoneNumber = $comR ['phoneNumber'];
$showStore = $comR ['showStore'];
$companyEmail = $comR ['email'];
$homeImg1 = $comR ['homeImg1'];
$homeImg2 = $comR ['homeImg2'];
$aboutPic = $comR ['aboutPic'];
$contactPic = $comR ['contactPic'];
$aboutText = html_entity_decode ( $comR ['aboutText'], ENT_QUOTES );

$WEEKDAYS = array (
		"Monday",
		"Tuesday",
		"Wednesday",
		"Thursday",
		"Friday",
		"Saturday",
		"Sunday"
);
$MONTHS = array (
		1 => "January",
		"February",
		"March",
		"April",
		"May",
		"June",
		"July",
		"August",
		"September",
		"October",
		"November",
		"December"
);