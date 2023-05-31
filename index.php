<?php
include "cgi-bin/config.php";
include "cgi-bin/functions.php";
?>
<!DOCTYPE html>
<html>
<head>
<?php
include "include/head.php";
?>
</head>
<body>
<?php
if ($loginErr != "x") {
	echo "<div style='text-align:center; padding:10px; font-weight:bold;'>$loginErr</div>";
}
?>
<!-- Navbar (sit on top) -->
<div class="w3-top">
  <div class="w3-bar" id="myNavbar">
    <a class="w3-bar-item w3-button w3-hover-black w3-hide-medium w3-hide-large w3-right" href="javascript:void(0);" onclick="toggleFunction()" title="Toggle Navigation Menu">
      <i class="fa fa-bars"></i>
    </a>
    <a href="index.php?page=home" class="w3-bar-item w3-button">HOME</a>
    <a href="index.php?page=photos" class="w3-bar-item w3-button w3-hide-small"><i class="fa fa-th"></i> PHOTOS</a>
    <?php
				if ($showStore == 1) {
					?>
    <a href="store/index.php" class="w3-bar-item w3-button w3-hide-small"><i class="fa fa-tag"></i> STORE</a>
    <?php
				} else {
					?>
				<a href="index.php?page=store" class="w3-bar-item w3-button w3-hide-small"><i class="fa fa-tag"></i> STORE</a>
    <?php
				}
				?>
    <a href="index.php?page=home" class="w3-bar-item w3-button w3-hide-small"><i class="fa fa-envelope"></i> CONTACT</a>
    <?php
				if ($myId >= 1) {
					?>
    <a href="index.php?page=settings" class="w3-bar-item w3-button w3-hide-small"><i class="fa fa-gear"></i> SETTINGS</a>
    <a href="index.php?page=home&logout=yep" class="w3-bar-item w3-button w3-hide-small"><i class="fa fa-pencil"></i> LOG OUT</a>
    <?php
				} else {
					?>
    <a onclick="document.getElementById('id01').style.display='block'" class="w3-bar-item w3-button w3-hide-small"><i class="fa fa-pencil"></i> LOG IN</a>
    <?php
				}
				?>
  </div>

  <!-- Navbar on small screens -->
  <div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium">
    <a href="index.php?page=home" class="w3-bar-item w3-button" onclick="toggleFunction()">HOME</a>
    <a href="index.php?page=photos" class="w3-bar-item w3-button" onclick="toggleFunction()">PHOTOS</a>
    <?php
				if ($showStore == 1) {
					?>
    <a href="store/index.html" class="w3-bar-item w3-button" onclick="toggleFunction()">STORE</a>
    <?php
				} else {
					?>
				<a href="https://cncofarmersmarket.com/index.php?page=Store" class="w3-bar-item w3-button" onclick="toggleFunction()">STORE</a>
    <?php
				}
				?>
    <a href="index.php?page=home" class="w3-bar-item w3-button" onclick="toggleFunction()">CONTACT</a>
    <?php
				if ($myId >= 1) {
					?>
    <a href="index.php?page=settings" class="w3-bar-item w3-button" onclick="toggleFunction()">SETTINGS</a>
    <a href="index.php?page=home&logout=yep" class="w3-bar-item w3-button" onclick="toggleFunction()">LOG OUT</a>
    <?php
				} else {
					?>
    <a onclick="document.getElementById('id01').style.display='block'" class="w3-bar-item w3-button" onclick="toggleFunction()">LOG IN</a>
    <?php
				}
				?>

  </div>
</div>
<div id="id01" class="modal">

		<form class="modal-content animate"
			action="index.php?page=home" method="post">

			<div class="container">
				<label for="email"><b>Email</b></label> <input type="text"
					placeholder="Enter Email" name="email" required> <label for="pwd"><b>Password</b></label>
				<input type="password" placeholder="Enter Password" name="pwd"
					required>

				<button type="submit">Login</button>
			</div>

			<div class="container" style="background-color: #f1f1f1">
				<button type="button"
					onclick="document.getElementById('id01').style.display='none'"
					class="cancelbtn">Cancel</button>
				<span class="psw">Forgot <a
					href="index.php?page=forgotpwd">password?</a></span>
			</div>
			<input type="hidden" name="login" value="1">
		</form>
	</div>

<?php
include "pages/" . $page . ".php";
?>

<!-- Footer -->
<footer class="w3-center w3-black w3-padding-64 w3-opacity w3-hover-opacity-off">
  <a href="#home" class="w3-button w3-light-grey"><i class="fa fa-arrow-up w3-margin-right"></i>To the top</a>
  <div class="w3-xlarge w3-section">
    <i class="fa fa-facebook-official w3-hover-opacity"></i>
    <i class="fa fa-instagram w3-hover-opacity"></i>
    <i class="fa fa-snapchat w3-hover-opacity"></i>
    <i class="fa fa-pinterest-p w3-hover-opacity"></i>
    <i class="fa fa-twitter w3-hover-opacity"></i>
    <i class="fa fa-linkedin w3-hover-opacity"></i>
  </div>
</footer>

<script>
// Modal Image Gallery
function onClick(element) {
  document.getElementById("img01").src = element.src;
  document.getElementById("modal01").style.display = "block";
  var captionText = document.getElementById("caption");
  captionText.innerHTML = element.alt;
}

// Change style of navbar on scroll
window.onscroll = function() {myFunction()};
function myFunction() {
    var navbar = document.getElementById("myNavbar");
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        navbar.className = "w3-bar" + " w3-card" + " w3-animate-top" + " w3-white";
    } else {
        navbar.className = navbar.className.replace(" w3-card w3-animate-top w3-white", "");
    }
}

// Used to toggle the menu on small screens when clicking on the menu button
function toggleFunction() {
    var x = document.getElementById("navDemo");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else {
        x.className = x.className.replace(" w3-show", "");
    }
}
</script>

</body>
</html>
