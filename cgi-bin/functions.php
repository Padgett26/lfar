<?php

function sendEmail ($subject, $mess, $email, $name)
{
    $headers[] = "MIME-Version: 1.0";
    $headers[] = "Content-Type: text/plain; charset=utf-8";
    $headers[] = "To: $name <$email>";
    $headers[] = "From: Luna Farm and Ranch <bernadette@lunafarmandranch.com>";
    mail($email, $subject, $mess, implode("\r\n", $headers));
}

function getPicType ($imageType)
{
    switch ($imageType) {
        case "image/gif":
            $picExt = "gif";
            break;
        case "image/jpeg":
            $picExt = "jpg";
            break;
        case "image/pjpeg":
            $picExt = "jpg";
            break;
        case "image/png":
            $picExt = "png";
            break;
        default:
            $picExt = "xxx";
            break;
    }
    return $picExt;
}

function processPic ($imageName, $tmpFile, $f)
{
    $folder = "image/$f";
    if (! is_dir("$folder")) {
        mkdir("$folder", 0777, true);
    }

    $saveto = "$folder/$imageName";

    list ($width, $height) = (getimagesize($tmpFile) != null) ? getimagesize(
            $tmpFile) : null;
    if ($width != null && $height != null) {
        $image = new Imagick($tmpFile);
        $image->thumbnailImage(800, 800, true);
        $image->writeImage($saveto);
    }
}

function processThumbPic ($imageName, $tmpFile, $f)
{
    $folder = "image/$f/thumbs";
    if (! is_dir("$folder")) {
        mkdir("$folder", 0777, true);
    }

    $saveto = "$folder/$imageName";

    list ($width, $height) = (getimagesize($tmpFile) != null) ? getimagesize(
            $tmpFile) : null;
    if ($width != null && $height != null) {
        $image = new Imagick($tmpFile);
        $image->thumbnailImage(150, 150, true);
        $image->writeImage($saveto);
    }
}

function sendPWResetEmail ($toId, $name, $email, $salt)
{
    $link = hash('sha512', ($salt . $name . $email), FALSE);
    $message = "$name,\n\n
        There has been a request on the Luna Farm and Ranch website for a password reset for this account.  If you initiated this request, click the link below, and you will be sent to a page where you will be able enter a new password. If you did not initiate this password reset request, simple ignore this email, and your password will not be changed.\n\n
        https://lunafarmandranch.com/index.php?page=forgotpwd&id=$toId&ver=$link\n\n
        Thank you,\nAdmin\nLuna Farm and Ranch";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= 'Content-Type: text/plain; charset=utf-8' . "\r\n";
    $headers .= "From: Luna Farm and Ranch Admin <bernadette@lunafarmandranch.com>" .
            "\r\n";
    mail($email, 'Luna Farm and Ranch website password reset request', $message,
            $headers);
}

function getCategory ($name, $db)
{
    $n = htmlentities($name, ENT_QUOTES);
    $x = $db->prepare("INSERT INTO animalCategoies VALUES(NULL, ?, '0', '0')");
    $x->execute(array(
            $n
    ));
    $y = $db->prepare(
            "SELECT id FROM animalCategories WHERE name = ? ORDER BY id DESC LIMIT 1");
    $y->execute(array(
            $n
    ));
    $yr = $y->fetch();
    if ($yr) {
        return $yr['id'];
    }
}