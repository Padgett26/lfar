<?php
$msgTY = "";
if (filter_input(INPUT_POST, 'msgUp', FILTER_SANITIZE_NUMBER_INT) == 1) {
    $msgName = filter_input(INPUT_POST, 'Name', FILTER_SANITIZE_STRING);
    $msgEmail = filter_input(INPUT_POST, 'Email', FILTER_SANITIZE_STRING);
    $msg = htmlentities(
            filter_input(INPUT_POST, 'Message', FILTER_SANITIZE_STRING),
            ENT_QUOTES);

    $msgUp = $db->prepare("INSERT INTO messages VALUES(NULL,?,?,?,?,?,?,?)");
    $msgUp->execute(array(
            $msgName,
            $msgEmail,
            $msg,
            $time,
            '0',
            '0',
            '0'
    ));
    $msgTY = "Thank you for contacting us. We will reply soon.";
}
if ($myId >= 1) {
    if (filter_input(INPUT_POST, 'homePictures', FILTER_SANITIZE_NUMBER_INT) == 1) {
        if (isset($_FILES['imageTop']['tmp_name'])) {
            $tmpFile = $_FILES['imageTop']["tmp_name"];
            list ($width1, $height1) = (getimagesize($tmpFile) != null) ? getimagesize(
                    $tmpFile) : null;
            if ($width1 != null && $height1 != null) {
                $image1Type = getPicType($_FILES['imageTop']['type']);
                $image1Name = $time . "." . $image1Type;
                processPic("$domain/pages", $image1Name, $tmpFile, 800, 150);
                $p1stmt = $db->prepare(
                        "UPDATE company SET homeImg1 = ? WHERE id = ?");
                $p1stmt->execute(array(
                        $image1Name,
                        '1'
                ));
            }
        }
        if (isset($_FILES['imageBottom']['tmp_name'])) {
            $tmpFile = $_FILES['imageBottom']["tmp_name"];
            list ($width2, $height2) = (getimagesize($tmpFile) != null) ? getimagesize(
                    $tmpFile) : null;
            if ($width2 != null && $height2 != null) {
                $image2Type = getPicType($_FILES['imageBottom']['type']);
                $image2Name = $time . "." . $image2Type;
                processPic("$domain/pages", $image2Name, $tmpFile, 800, 150);
                $p2stmt = $db->prepare(
                        "UPDATE company SET homeImg2 = ? WHERE id = ?");
                $p2stmt->execute(array(
                        $image2Name,
                        '1'
                ));
            }
        }
    }
    if (filter_input(INPUT_POST, 'homeAbout', FILTER_SANITIZE_NUMBER_INT) == 1) {
        if (isset($_FILES['imageAbout']['tmp_name'])) {
            $tmpFile = $_FILES['imageAbout']["tmp_name"];
            list ($width1, $height1) = (getimagesize($tmpFile) != null) ? getimagesize(
                    $tmpFile) : null;
            if ($width1 != null && $height1 != null) {
                $image1Type = getPicType($_FILES['imageAbout']['type']);
                $image1Name = $time . "." . $image1Type;
                processPic("$domain/pages", $image1Name, $tmpFile, 800, 150);
                $p1stmt = $db->prepare(
                        "UPDATE company SET aboutPic = ? WHERE id = ?");
                $p1stmt->execute(array(
                        $image1Name,
                        '1'
                ));
            }
        }
        $a = filter_input(INPUT_POST, 'aboutText', FILTER_SANITIZE_STRING);
        $at = htmlentities($a, ENT_QUOTES);
        $p2stmt = $db->prepare("UPDATE company SET aboutText = ? WHERE id = ?");
        $p2stmt->execute(array(
                $at,
                '1'
        ));
    }
    if (filter_input(INPUT_POST, 'homeContact', FILTER_SANITIZE_NUMBER_INT) == 1) {
        if (isset($_FILES['imageContact']['tmp_name'])) {
            $tmpFile = $_FILES['imageContact']["tmp_name"];
            list ($width1, $height1) = (getimagesize($tmpFile) != null) ? getimagesize(
                    $tmpFile) : null;
            if ($width1 != null && $height1 != null) {
                $image1Type = getPicType($_FILES['imageContact']['type']);
                $image1Name = $time . "." . $image1Type;
                processPic("$domain/pages", $image1Name, $tmpFile, 800, 150);
                $p1stmt = $db->prepare(
                        "UPDATE company SET contactPic = ? WHERE id = ?");
                $p1stmt->execute(array(
                        $image1Name,
                        '1'
                ));
            }
        }
        $cn = filter_input(INPUT_POST, 'companyName', FILTER_SANITIZE_STRING);
        $a1 = filter_input(INPUT_POST, 'address1', FILTER_SANITIZE_STRING);
        $a2 = filter_input(INPUT_POST, 'address2', FILTER_SANITIZE_STRING);
        $pn = filter_input(INPUT_POST, 'phoneNumber', FILTER_SANITIZE_STRING);
        $em = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $p2stmt = $db->prepare(
                "UPDATE company SET companyName = ?, address1 = ?, address2 = ?, phoneNumber = ?, email = ? WHERE id = ?");
        $p2stmt->execute(array(
                $cn,
                $a1,
                $a2,
                $pn,
                $em,
                '1'
        ));
    }
    ?>
	<div style='margin:10px; padding:20px;'>&nbsp;</div>
		<div style="font-weight: bold; font-size: 1.5em; padding: 10px;">Home page settings</div>
		<div style='margin:10px; border:1px solid #000000; padding:20px;'>
		<div style="font-weight: bold; font-size: 1.25em; padding: 10px;">Background Pictures</div>
		<form action="index.php?page=home" method="post" enctype='multipart/form-data'>
		Top home page pic:<br>
		<?php
    if (file_exists("image/pages/$homeImg1")) {
        echo "<img src='image/pages/thumbs/$homeImg1' alt=''><br>\n";
    }
    ?>
		Upload a new top home pic: <input type="file" name="imageTop"><br><br>
		Bottom home page pic:<br>
		<?php
    if (file_exists("image/pages/$homeImg2")) {
        echo "<img src='image/pages/thumbs/$homeImg2' alt=''><br>\n";
    }
    ?>
		Upload a new bottom home pic: <input type="file" name="imageBottom"><br><br>
		<input type="submit" value=" Update "><input type="hidden" name="homePictures" value="1">
		</form>
		</div>
		<div style='margin:10px; border:1px solid #000000; padding:20px;'>
		<div style="font-weight: bold; font-size: 1.25em; padding: 10px;">About LFaR</div>
		<form action="index.php?page=home" method="post" enctype='multipart/form-data'>
		About us pic:<br>
		<?php
    if (file_exists("image/pages/$aboutPic")) {
        echo "<img src='image/pages/thumbs/$aboutPic' alt=''><br>\n";
    }
    ?>
		Upload a new about us pic: <input type="file" name="imageAbout"><br><br>
		About LFaR text:<br>
		<textarea name="aboutText"><?php
    echo $aboutText;
    ?></textarea>
		<input type="submit" value=" Update "><input type="hidden" name="homeAbout" value="1">
		</form>
		</div>
		<div style='margin:10px; border:1px solid #000000; padding:20px;'>
		<div style="font-weight: bold; font-size: 1.25em; padding: 10px;">Contact Info</div>
		<form action="index.php?page=home" method="post" enctype='multipart/form-data'>
		Contact pic:<br>
		<?php
    if (file_exists("image/pages/$contactPic")) {
        echo "<img src='image/pages/thumbs/$contactPic' alt=''><br>\n";
    }
    ?>
		Upload a new contact pic: <input type="file" name="imageContact"><br><br>
		Company Name: <input type="text" name="companyName" value="<?php
    echo $companyName;
    ?>">
		Address1: <input type="text" name="address1" value="<?php
    echo $address1;
    ?>">
		Address2: <input type="text" name="address2" value="<?php
    echo $address2;
    ?>">
		Phone Number: <input type="text" name="phoneNumber" value="<?php
    echo $phoneNumber;
    ?>">
		Email: <input type="email" name="email" value="<?php
    echo $companyEmail;
    ?>">
		<input type="submit" value=" Update "><input type="hidden" name="homeContact" value="1">
		</form>
		</div>
	<?php
} else {
    ?><!-- First Parallax Image with Logo Text -->
<div class="bgimg-1 w3-display-container w3-opacity-min" id="home">
  <div class="w3-display-middle" style="white-space:nowrap;">
    <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity"><?php
    echo $companyName;
    ?></span>
  </div>
</div>

<!-- Container (About Section) -->
<div class="w3-content w3-container w3-padding-64" id="about">
  <h3 class="w3-center">ABOUT<?php
    echo " " . strtoupper($companyName);
    ?></h3>
  <div class="clearfix">
  <?php
    if (file_exists("image/pages/$aboutPic")) {
        echo "<div style='float:left;'><img src='image/pages/$aboutPic' alt='' style='margin:10px 10px 10px 0px; border:1px solid #444444; padding:5px;'></div>";
    }
    ?>
  <p><?php
    echo $aboutText;
    ?></p>
</div>
</div>

<!-- Modal for full size images on click-->
<div id="modal01" class="w3-modal w3-black" onclick="this.style.display='none'">
  <span class="w3-button w3-large w3-black w3-display-topright" title="Close Modal Image"><i class="fa fa-remove"></i></span>
  <div class="w3-modal-content w3-animate-zoom w3-center w3-transparent w3-padding-64">
    <img id="img01" class="w3-image">
    <p id="caption" class="w3-opacity w3-large"></p>
  </div>
</div>

<!-- Third Parallax Image with Portfolio Text -->
<div class="bgimg-3 w3-display-container w3-opacity-min">
  <div class="w3-display-middle">
     <span class="w3-xxlarge w3-text-white w3-wide">CONTACT</span>
  </div>
</div>

<!-- Container (Contact Section) -->
<div class="w3-content w3-container w3-padding-64" id="contact">
  <p class="w3-center"><em>I'd love your feedback!</em></p>

  <div class="w3-row w3-padding-32 w3-section">
  <?php
    if (file_exists("image/pages/$contactPic")) {
        ?>
    <div class="w3-col m4 w3-container">
      <img src="image/pages/<?php
        echo $contactPic;
        ?>" class="w3-image w3-round" style="width:100%">
    </div>
    <?php
    }
    ?>
    <div class="w3-col m8 w3-panel">
      <div class="w3-large w3-margin-bottom">
        <i class="fa fa-map-marker fa-fw w3-hover-text-black w3-xlarge w3-margin-right"></i> <?php
    echo $address1 . ", " . $address2;
    ?><br>
        <i class="fa fa-phone fa-fw w3-hover-text-black w3-xlarge w3-margin-right"></i> Phone: <?php
    echo $phoneNumber;
    ?><br>
        <i class="fa fa-envelope fa-fw w3-hover-text-black w3-xlarge w3-margin-right"></i> Email: <a href="mailto:<?php
    echo $companyEmail;
    ?>"><?php
    echo $companyEmail;
    ?></a><br>
      </div>
      <?php
    if ($msgTY != "") {
        echo "<div style='margin:10px 0px; color:#ff0000;'>$msgTY</div>";
    }
    ?>
      <form action="index.php?page=home" method="post" target="_blank">
        <div class="w3-row-padding" style="margin:0 -16px 8px -16px">
          <div class="w3-half">
            <input class="w3-input w3-border" type="text" placeholder="Name" required name="Name">
          </div>
          <div class="w3-half">
            <input class="w3-input w3-border" type="email" placeholder="Email" required name="Email">
          </div>
        </div>
        <input class="w3-input w3-border" type="text" placeholder="Message" required name="Message">
        <button class="w3-button w3-black w3-right w3-section" type="submit">
          <i class="fa fa-paper-plane"></i> SEND MESSAGE
        </button>
        <input type="hidden" name="msgUp" value="1">
      </form>
    </div>
  </div>
</div>
<?php
}
?>