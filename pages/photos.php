<div
	style="padding: 30px 0px; text-align: center; font-weight: bold; font-size: 1.5em;">
	Photos</div>
<div class='clearfix' style='margin:10px; border:1px solid #000000; padding:20px;'>
<?php
$sDate = array();
$getS = $db->prepare(
        "SELECT DISTINCT showDate FROM photos ORDER BY showDate DESC");
$getS->execute();
while ($getSR = $getS->fetch()) {
    if ($getSR) {
        $sDate[] = $getSR['showDate'];
    }
}
if ($myId >= 1) {
    if (filter_input(INPUT_POST, 'newpic', FILTER_SANITIZE_NUMBER_INT) == 1) {
        $picShowDate = filter_input(INPUT_POST, 'showDate',
                FILTER_SANITIZE_NUMBER_INT);
        $picCaption = htmlentities(
                filter_input(INPUT_POST, 'caption', FILTER_SANITIZE_STRING),
                ENT_QUOTES);

        $sDate = explode("-", $picShowDate);
        $date = mktime(12, 0, 0, $sDate[1], $sDate[2], $sDate[0]);
        $u = $db->prepare("INSERT INTO photos VALUES(NULL,?,?,?,'0','0')");
        $u->execute(array(
                'x.jpg',
                $picCaption,
                $date
        ));
        $u2 = $db->prepare(
                "SELECT id FROM photos WHERE caption = ? AND showDate = ? ORDER BY id DESC LIMIT 1");
        $u2->execute(array(
                $picCaption,
                $date
        ));
        $u2R = $u2->fetch();
        $photoId = ($u2R) ? $u2R['id'] : 0;
        if (isset($_FILES['pic']['tmp_name']) && $_FILES['pic']['size'] >= 1000) {
            $tmpFile = $_FILES['pic']["tmp_name"];
            list ($width1, $height1) = (getimagesize($tmpFile) != null) ? getimagesize(
                    $tmpFile) : null;
            if ($width1 != null && $height1 != null) {
                $image1Type = getPicType($_FILES['pic']['type']);
                $image1Name = $time . "." . $image1Type;
                processPic("photos", $image1Name, $tmpFile, 800, 150);
                $p1stmt = $db->prepare(
                        "UPDATE photos SET photo = ? WHERE id = ?");
                $p1stmt->execute(array(
                        $image1Name,
                        $photoId
                ));
            }
        }
    }
    if (filter_input(INPUT_POST, 'picUp', FILTER_SANITIZE_NUMBER_INT) >= 1) {
        $picId = filter_input(INPUT_POST, 'picUp', FILTER_SANITIZE_NUMBER_INT);
        $picShowDate = filter_input(INPUT_POST, 'showDate',
                FILTER_SANITIZE_NUMBER_INT);
        $picCaption = htmlentities(
                filter_input(INPUT_POST, 'caption', FILTER_SANITIZE_STRING),
                ENT_QUOTES);
        $delpic = (filter_input(INPUT_POST, 'delpic', FILTER_SANITIZE_NUMBER_INT) ==
                1) ? 1 : 0;

        if ($delpic == 1) {
            $d = $db->prepare("DELETE FROM photos WHERE id = ?");
            $d->execute(array(
                    $picId
            ));
        } else {
            $sDate = explode("-", $picShowDate);
            $date = mktime(12, 0, 0, $sDate[1], $sDate[2], $sDate[0]);
            $u = $db->prepare(
                    "UPDATE photos SET caption = ?, showDate = ? WHERE id = ?");
            $u->execute(array(
                    $picCaption,
                    $date,
                    $picId
            ));
        }
    }
    echo "<div style='font-weight:bold;'>Upload a new picture:</div>";
    echo "<div style='border:1px solid #000000; margin:10px; padding:5px;'>\n";
    echo "<form action='index.php?page=pictures' method='post' enctype='multipart/form-data'>";
    echo "<input type='date' name='showDate' value='" . date('Y-m-d', $time) .
            "'><br><br>\n";
    echo "<input type='file' name='pic'><br><br>\n";
    echo "Caption:<br>\n";
    echo "<textarea name='caption' cols='30' rows='10'></textarea><br><br>\n";
    echo "<input type='submit' value=' Save Pic '><input type='hidden' name='newpic' value='1'>\n";
    echo "</form>";
    echo "</div>\n";
    foreach ($sDate as $v) {
        echo "<div class='clearfix' style='padding:20px 0px;'>\n";
        echo "<div style='font-weight:bold; font-size:1.5em;'>" .
                date("Y-m-d", $v) . "</div>\n";
        $getP = $db->prepare("SELECT * FROM photos WHERE showDate = ?");
        $getP->execute(array(
                $v
        ));
        while ($getPR = $getP->fetch()) {
            if ($getPR) {
                $id = $getPR['id'];
                $photo = $getPR['photo'];
                $caption = html_entities_decode($getPR['caption']);
                $date = date("Y-m-d", $v);

                echo "<div style='float:left; border:1px solid #000000; margin:10px; padding:5px; background-color:#cccccc;'>\n";
                echo "<form action='index.php?page=pictures' method='post'>";
                echo "<input type='date' name='showDate' value='$date'><br><br>\n";
                echo "<img src='images/photos/thumbs/$photo' alt=''/><br><br>\n";
                echo "<textarea name='caption' cols='30' rows='10'>$caption</textarea><br><br>\n";
                echo "Delete this picture: <input type='checkbox' name='delpic' value='1'><br><br>\n";
                echo "<input type='submit' value=' Save Changes '><input type='hidden' name='picUp' value='$id'>\n";
                echo "</form>";
                echo "</div>\n";
            }
        }
        echo "</div>\n";
    }
} else {
    foreach ($sDate as $v) {
        echo "<div class='clearfix' style='padding:20px 0px;'>\n";
        echo "<div style='font-weight:bold; font-size:1.5em;'>" .
                date("Y-m-d", $v) . "</div>\n";
        $getP = $db->prepare("SELECT * FROM photos WHERE showDate = ?");
        $getP->execute(array(
                $v
        ));
        while ($getPR = $getP->fetch()) {
            if ($getPR) {
                $id = $getPR['id'];
                $photo = $getPR['photo'];
                $caption = $getPR['caption'];
                $date = date("Y-m-d", $v);

                echo "<div style='float:left; border:1px solid #cccccc; margin:10px; padding:5px;'>\n";
                echo "$date<br>\n";
                echo "<a class='example-image-link' href='images/photos/$photo' data-lightbox='example-set' data-title='$date<br>$caption'><img class='example-image' src='images/photos/thumbs/$photo' alt='$caption'/></a>\n";
                echo "</div>\n";
            }
        }
        echo "</div>\n";
    }
    echo "<script src='js/lightbox-plus-jquery.min.js'></script>";
}
?>
</div>