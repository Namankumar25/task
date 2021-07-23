<?php

include "dbconnect.php";

if (isset($_POST['image'])) {

	$tb_value = "";
	$data = $_POST['image'];
	$image_array_1 = explode(";", $data);
	$image_array_2 = explode(",", $image_array_1[1]);
	$data = base64_decode($image_array_2[1]);

	$dirname = 'upload/';
	$image_name =  time() . '.png';
	file_put_contents($dirname . $image_name, $data);


	if ($_POST['imgIdentify'] == "cover") {
		$tb_value = "profile";
		$sql = "SELECT `profile` FROM `business_staff_members` WHERE `id`='20'";
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);
		if ($row['profile']!="") {
			unlink($dirname.$row[$tb_value]);
		}
	}

	if ($_POST['imgIdentify'] == "profile") {
		$tb_value = "logo";
		$sql = "SELECT `logo` FROM `business_staff_members` WHERE `id`='20'";
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);
		if ($row['logo']!="") {
			unlink($dirname.$row[$tb_value]);
		}
	}

	$compressd_image = compressImage($dirname . $image_name, "cpx_" . $image_name);

	$sql = "UPDATE `business_staff_members` SET `$tb_value` = '$compressd_image' WHERE `business_staff_members`.`id`=20";
	$result = mysqli_query($conn, $sql);


	unlink($dirname . $image_name);
	$res = array($dirname . $compressd_image);
	echo json_encode($res);
}

function compressImage($source_image, $compress_image)
{
	$dirname = 'upload/';
	$image_info = getimagesize($source_image);
	if ($image_info['mime'] == 'image/jpeg') {
		$source_image = imagecreatefromjpeg($source_image);
		imagejpeg($source_image, $dirname . $compress_image, 75);
	} else if ($image_info['mime'] == 'image/png') {
		$source_image = imagecreatefrompng($source_image);
		imagepng($source_image, $dirname . $compress_image, 5);
	}
	return $compress_image;
}
