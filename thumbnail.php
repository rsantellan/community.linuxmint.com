<?php function LoadPNG($imgname)
{
    $im = @imagecreatefrompng($imgname); /* Attempt to open with png */
    if (!$im) { /* See if it failed */
    	$im = @imagecreatefromjpeg($imgname); /* Attempt to open with jpeg */
        if (!$im) { /* See if it failed */
        	$im  = imagecreatetruecolor(150, 30); /* Create a blank image */
        	$bgc = imagecolorallocate($im, 255, 255, 255);
        	$tc  = imagecolorallocate($im, 0, 0, 0);
        	imagefilledrectangle($im, 0, 0, 150, 30, $bgc);
        	/* Output an errmsg */
        	//imagestring($im, 1, 5, 5, "Error loading picture: $imgname", $tc);
        }
    }
    return $im;
}
if (isset($_GET['pic'])) {
    if (file_exists($_GET['pic'])) {
        header("Content-Type: image/png");
        $img = LoadPNG($_GET['pic']);
        $thumb_w = 100;
        //$thumb_h = 100;
        if (isset($_GET['w'])) {
            $thumb_w = intval($_GET['w']);
        }
        //if (isset($_GET['h'])) {
        //    $thumb_h = intval($_GET['h']);
        //}
        $old_x=imageSX($img);
        $ratio = $thumb_w / $old_x;
        $old_y=imageSY($img);
        $thumb_h = $ratio * $old_y;
        $dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
        imagecopyresampled($dst_img, $img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
        imagepng($dst_img);
    }
}
?> 

