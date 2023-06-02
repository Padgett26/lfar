<?php
$CATEGORIES = array();
$getC = $db->prepare("SELECT * FROM animalCategories");
$getC->execute();
while ($getCR = $getC->fetch()) {
    if ($getCR) {
        $cId = $getCR['id'];
        $cName = html_entities_decode($getCR['name']);
        $CATEGORIES[$cId] = $cName;
    }
}
$SOLD = array(
        0 => "Available",
        "Reserved",
        "Sold"
);

if ($myId >= 1) {
    if (filter_input(INPUT_POST, 'animalsUp', FILTER_SANITIZE_NUMBER_INT) == 1) {
        $newCategory = filter_input(INPUT_POST, 'category0',
                FILTER_SANITIZE_NUMBER_INT);
        $newCategoryNew = filter_input(INPUT_POST, 'categoryNew0',
                FILTER_SANITIZE_NUMBER_INT);
        $newSold = filter_input(INPUT_POST, 'sold0', FILTER_SANITIZE_NUMBER_INT);
        $newPrice = filter_input(INPUT_POST, 'price0',
                FILTER_SANITIZE_NUMBER_INT);
        $newName = htmlentities(
                filter_input(INPUT_POST, 'name0', FILTER_SANITIZE_NUMBER_INT),
                ENT_QUOTES);
        $newWeight = htmlentities(
                filter_input(INPUT_POST, 'weight0', FILTER_SANITIZE_NUMBER_INT),
                ENT_QUOTES);
        $newDescription = htmlentities(
                filter_input(INPUT_POST, 'description0',
                        FILTER_SANITIZE_NUMBER_INT), ENT_QUOTES);
        if ($newName != "" && $newName != " ") {
            if ($newCategory == 0 && $newCategoryNew != "" &&
                    $newCategoryNew != " ") {
                $cat = getCategory($newCategoryNew, $db);
            } else {
                $cat = $newCategory;
            }
            $x = $db->prepare(
                    "INSERT INTO animals4sale VALUES(NULL,?,?,?,?,?,?,?,'0','0')");
            $x->execute(
                    array(
                            $cat,
                            $newName,
                            $newWeight,
                            $newSold,
                            $newDescription,
                            "x.png",
                            $newPrice
                    ));
            $newId = $db->prepare(
                    "SELECT id FROM animals4sale WHERE name = ? ORDER BY id DESC LIMIT 1");
            $newId->execute(array(
                    $newName
            ));
            $newIdR = $newId->fetch();
            if ($newIdR) {
                $new = $newIdR['id'];
                if (isset($_FILES['image0']['tmp_name']) &&
                        $_FILES['image0']['size'] >= 1000) {
                    $tmpFile = $_FILES['image0']["tmp_name"];
                    list ($width1, $height1) = (getimagesize($tmpFile) != null) ? getimagesize(
                            $tmpFile) : null;
                    if ($width1 != null && $height1 != null) {
                        $image1Type = getPicType($_FILES['image0']['type']);
                        $image1Name = $time . "." . $image1Type;
                        processPic("$domain/store", $image1Name, $tmpFile, 800,
                                150);
                        $p1stmt = $db->prepare(
                                "UPDATE animals4sale SET image = ? WHERE id = ?");
                        $p1stmt->execute(array(
                                $image1Name,
                                $new
                        ));
                    }
                }
            }
        }

        foreach ($_POST as $key => $val) {
            if (preg_match("/^category([0-9][0-9]*)$/", $key, $match) &&
                    $val >= 1) {
                $m = $match[1];
                if (filter_var($val, FILTER_VALIDATE_NUMBER_INT,
                        FILTER_NULL_ON_FAILURE) && $m != 0) {
                    $x = $db->prepare(
                            "UPDATE animals4sale SET category = ? WHERE id = ?");
                    $x->execute(array(
                            $val,
                            $m
                    ));
                }
            }
            if (preg_match("/^categoryNew([0-9][0-9]*)$/", $key, $match)) {
                $m = $match[1];
                if ($val != "" && $val != " ") {
                    if (filter_var($val, FILTER_VALIDATE_STRING,
                            FILTER_NULL_ON_FAILURE) && $m != 0) {
                        $newId = getCategory($val, $db);
                        $x2 = $db->prepare(
                                "UPDATE animals4sale SET category = ? WHERE id = ?");
                        $x2->execute(array(
                                $newId,
                                $m
                        ));
                    }
                }
            }
            if (preg_match("/^sold([0-9][0-9]*)$/", $key, $match)) {
                $m = $match[1];
                if (filter_var($val, FILTER_VALIDATE_NUMBER_INT,
                        FILTER_NULL_ON_FAILURE) && $m != 0) {
                    $x = $db->prepare(
                            "UPDATE animals4sale SET sold = ? WHERE id = ?");
                    $x->execute(array(
                            $val,
                            $m
                    ));
                }
            }
            if (preg_match("/^price([0-9][0-9]*)$/", $key, $match)) {
                $m = $match[1];
                if (filter_var($val, FILTER_VALIDATE_NUMBER_FLOAT,
                        FILTER_NULL_ON_FAILURE) && $m != 0) {
                    $x = $db->prepare(
                            "UPDATE animals4sale SET price = ? WHERE id = ?");
                    $x->execute(array(
                            $val,
                            $m
                    ));
                }
            }
            if (preg_match("/^name([0-9][0-9]*)$/", $key, $match)) {
                $m = $match[1];
                if (filter_var($val, FILTER_VALIDATE_STRING,
                        FILTER_NULL_ON_FAILURE) && $m != 0) {
                    $v = htmlentities($val, ENT_QUOTES);
                    $x = $db->prepare(
                            "UPDATE animals4sale SET name = ? WHERE id = ?");
                    $x->execute(array(
                            $v,
                            $m
                    ));
                }
            }
            if (preg_match("/^image([0-9][0-9]*)$/", $key, $match)) {
                $fullM = $match[0];
                $m = $match[1];
                if (isset($_FILES[$fullM]['tmp_name']) &&
                        $_FILES[$fullM]['size'] >= 1000 && $m != 0) {
                    $tmpFile = $_FILES[$fullM]["tmp_name"];
                    list ($width1, $height1) = (getimagesize($tmpFile) != null) ? getimagesize(
                            $tmpFile) : null;
                    if ($width1 != null && $height1 != null) {
                        $image1Type = getPicType($_FILES[$fullM]['type']);
                        $image1Name = ($time + $m) . "." . $image1Type;
                        processPic("$domain/store", $image1Name, $tmpFile, 800,
                                150);
                        $p1stmt = $db->prepare(
                                "UPDATE animals4sale SET image = ? WHERE id = ?");
                        $p1stmt->execute(array(
                                $image1Name,
                                $m
                        ));
                    }
                }
            }
            if (preg_match("/^weight([0-9][0-9]*)$/", $key, $match)) {
                $m = $match[1];
                if (filter_var($val, FILTER_VALIDATE_STRING,
                        FILTER_NULL_ON_FAILURE) && $m != 0) {
                    $v = htmlentities($val, ENT_QUOTES);
                    $x = $db->prepare(
                            "UPDATE animals4sale SET weight = ? WHERE id = ?");
                    $x->execute(array(
                            $v,
                            $m
                    ));
                }
            }
            if (preg_match("/^description([0-9][0-9]*)$/", $key, $match)) {
                $m = $match[1];
                if (filter_var($val, FILTER_VALIDATE_STRING,
                        FILTER_NULL_ON_FAILURE) && $m != 0) {
                    $v = htmlentities($val, ENT_QUOTES);
                    $x = $db->prepare(
                            "UPDATE animals4sale SET description = ? WHERE id = ?");
                    $x->execute(array(
                            $v,
                            $m
                    ));
                }
            }
        }
        foreach ($_POST as $key => $val) {
            if (preg_match("/^delAnimal([0-9][0-9]*)$/", $key, $match) &&
                    $val == 1) {
                $m = $match[1];
                if (filter_var($val, FILTER_VALIDATE_NUMBER_INT,
                        FILTER_NULL_ON_FAILURE) && $m != 0) {
                    $x = $db->prepare("DELETE FROM animals4sale WHERE id = ?");
                    $x->execute(array(
                            $m
                    ));
                }
            }
        }
    }
}
?>
<div
	style="padding: 30px 0px; text-align: center; font-weight: bold; font-size: 1.5em;">
	Store</div>
<div class='clearfix' style='margin:10px; border:1px solid #000000; padding:20px;'>
Find my products for sale on the <a href="https://cncofarmersmarket.com/index.php?page=Store" target="_blank">Cheyenne County Farmer's Market</a> website.
<div style="margin:30px 0px;">
<span style="font-weight:bold">Animals for sale:</span><br>
<form action='index.php?page=store' method='post'  enctype='multipart/form-data'>
<input type='hidden' name='animalsUp' value='1'>
<table cellspacing="0px" style="border:1px solid black;">
<?php
if ($myId >= 1) {
    echo "<tr>\n";
    echo "<td style='padding:5px; text-align:left;' colspan='2'>";
    echo "<div style='font-weight:bold;'>New animal for sale</div>";
    echo "<select name='category0' size='1'>";
    foreach ($CATEGORIES as $k => $v) {
        echo "<option value='$k'>$v</option>";
    }
    echo "<option value='0'>New category below</option>";
    echo "</select><br><br>-Or add a new category-<br><br><input type='text' value='categoryNew0' value=''></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td style='border-top:1px solid black; font-weight:bold; text-align:left; padding:5px;'>";
    echo "<select name='sold0' size='1'>";
    for ($i = 0; $i <= 2; ++ $i) {
        echo "<option value='$i'>$SOLD[$i]</option>";
    }
    echo "</select></td>\n";
    echo "<td style='border-top:1px solid black; font-weight:bold; text-align:right; padding:5px;'>";
    echo "Price: <input type='number name='price0' value='0.00' min='0.00' step='.01'>";
    echo "</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td style='padding:5px; text-align:center;' colspan='2'><input type='text' value='name0' value='' required></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td style='padding:5px; text-align:left;'>";
    echo "Upload a new pic:<br><input type='file' name='image0'></td>\n";
    echo "<td style='padding:5px; text-align:right;'>Weight:<br><input type='text' name='weight0' value='0 lbs'></td>\n";
    echo "</tr>\n";
    echo "<tr><td colspan='2' style='padding:5px; text-align:justify;'><textarea name='description0'></textarea></td></tr>\n";
    echo "<tr><td colspan='2' style='text-align:left;'> <input type='submit' value='Save Changes'></td></tr>\n";
}
$getA = $db->prepare("SELECT * FROM animals4sale ORDER BY category, sold");
$getA->execute();
while ($getAR = $getA->fetch()) {
    if ($getAR) {
        $id = $getAR['id'];
        $cat = $getAR['category'];
        $name = html_entities_decode($getAR['name'], ENT_QUOTES);
        $weight = html_entities_decode($getAR['weight'], ENT_QUOTES);
        $sold = $getAR['sold'];
        $description = html_entities_decode($getAR['description'], ENT_QUOTES);
        $image = $getAR['image'];
        $price = $getAR['price'];

        if ($myId >= 1) {
            echo "<tr>\n";
            echo "<td style='padding:5px; text-align:left;' colspan='2'><select name='category$id' size='1'>";
            foreach ($CATEGORIES as $k => $v) {
                echo "<option value='$k'";
                echo ($k == $cat) ? " selected" : "";
                echo ">$v</option>";
            }
            echo "<option value='0'>New category below</option>";
            echo "</select><br><br>-Or add a new category-<br><br><input type='text' value='categoryNew$id' value=''></td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td style='border-top:1px solid black; font-weight:bold; text-align:left; padding:5px;'>";
            echo "<select name='sold$id' size='1'>";
            for ($i = 0; $i <= 2; ++ $i) {
                echo "<option value='$i'";
                echo ($i == $sold) ? " selected" : "";
                echo ">$SOLD[$i]</option>";
            }
            echo "</select></td>\n";
            echo "<td style='border-top:1px solid black; font-weight:bold; text-align:right; padding:5px;'>";
            echo "Price: <input type='number name='price$id' value='$price' min='0.00' step='.01'>";
            echo "</td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td style='padding:5px; text-align:center;' colspan='2'><input type='text' value='name$id' value='$name' required></td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td style='padding:5px; text-align:left;'><image src='image/store/thumbs/$image' alt='$name'><br><br>";
            echo "Upload a new pic:<br><input type='file' name='image$id'><br><br>";
            echo "Delete this pic: <input type='checkbox' name='delPic$id' value='1'></td>\n";
            echo "<td style='padding:5px; text-align:right;'>Weight:<br><input type='text' name='weight$id' value='$weight'></td>\n";
            echo "</tr>\n";
            echo "<tr><td colspan='2' style='padding:5px;'><textarea name='description$id'>$description</textarea></td></tr>\n";
            echo "<tr><td colspan='2' style='padding:5px;'>Delete this listing: <input type='checkbox' name='delAnimal$id' value='1'></td></tr>\n";
            echo "<tr><td colspan='2' style='text-align:left;'> <input type='submit' value='Save Changes'><input type='hidden' name='animalsUp' value='1'></td></tr>\n";
        } else {
            echo "<tr>\n";
            echo "<td style='padding:5px; text-align:center; font-weight:bold;' colspan='2'>$CATEGORIES[$cat]</td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td style='border-top:1px solid black; font-weight:bold; text-align:left; padding:5px;'>$SOLD[$sold]</td>\n";
            echo "<td style='border-top:1px solid black; font-weight:bold; text-align:right; padding:5px;'>";
            echo ($sold >= 1) ? "" : money($price);
            echo "</td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td style='padding:5px; text-align:center; font-weight:bold;' colspan='2'>$name</td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td style='padding:5px; text-align:left;'><a class='example-image-link' href='images/store/$image' data-lightbox='example-set' data-title='$name'><img class='example-image' src='images/store/thumbs/$image' alt='$name'/></a></td>\n";
            echo "<td style='padding:5px; text-align:right;'>Weight:<br>$weight</td>\n";
            echo "</tr>\n";
            echo "<tr><td colspan='2' style='padding:5px; text-align:justify;'>$description</td></tr>\n";
            echo "<tr><td colspan='2' style='padding:5px; text-align:left;'>";
            echo ($sold >= 1) ? "" : "To purchace this animal, please contact Luna Farm and Ranch <a href='mailto:$companyEmail'>HERE</a>";
            echo "</td></tr>\n";
        }
    }
}
?>
</table>
</form>
</div>
</div>