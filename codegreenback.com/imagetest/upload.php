<?php

//upload.php




if(isset($_POST["image"]))
{

    print_r($_POST);
 $data = $_POST["image"];

 $image_array_1 = explode(";", $data);

 $image_array_2 = explode(",", $image_array_1[1]);

 $base64 = $image_array_2[1];

 

//  $imageName = time() . '.png';

//  file_put_contents($imageName, $data);

//  echo '<img src="imagetest/'.$imageName.'" class="img-thumbnail" />';
}

// $base64 = "[insert base64 code here]";
if (check_base64_image($base64)) {
    print 'Image!';
} else {
    print 'Not an image!';
}

function check_base64_image($base64) {
    $img = imagecreatefromstring(base64_decode($base64));
    if (!$img) {
        return false;
    }

    imagepng($img, 'tmp.png');
    $info = getimagesize('tmp.png');
    // print_r($info);

    // unlink('tmp.png');

    if ($info[0] > 0 && $info[1] > 0 && $info['mime']) {
        return true;
    }

    return false;
}

?>