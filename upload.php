<?php

include "dbconnect.php";

// if (isset($_POST['image'])) {

// 	$tb_value = "";
// 	$data = $_POST['image'];
// 	$image_array_1 = explode(";", $data);
// 	$image_array_2 = explode(",", $image_array_1[1]);
// 	$data = base64_decode($image_array_2[1]);

// 	$dirname = 'upload/';
// 	$image_name =  time() . '.png';
// 	file_put_contents($dirname . $image_name, $data);


// 	if ($_POST['imgIdentify'] == "cover") {
// 		$tb_value = "profile";
// 		$sql = "SELECT `profile` FROM `business_staff_members` WHERE `id`='20'";
// 		$result = mysqli_query($conn, $sql);
// 		$row = mysqli_fetch_assoc($result);
// 		if ($row['profile']!="") {
// 			unlink($dirname.$row[$tb_value]);
// 		}
// 	}

// 	if ($_POST['imgIdentify'] == "profile") {
// 		$tb_value = "logo";
// 		$sql = "SELECT `logo` FROM `business_staff_members` WHERE `id`='20'";
// 		$result = mysqli_query($conn, $sql);
// 		$row = mysqli_fetch_assoc($result);
// 		if ($row['logo']!="") {
// 			unlink($dirname.$row[$tb_value]);
// 		}
// 	}

// 	$compressd_image = compressImage($dirname . $image_name, "cpx_" . $image_name);

// 	$sql = "UPDATE `business_staff_members` SET `$tb_value` = '$compressd_image' WHERE `business_staff_members`.`id`=20";
// 	$result = mysqli_query($conn, $sql);


// 	unlink($dirname . $image_name);

// }

// function compressImage($source_image, $compress_image)
// {
// 	$dirname = 'upload/';
// 	$image_info = getimagesize($source_image);
// 	if ($image_info['mime'] == 'image/jpeg') {
// 		$source_image = imagecreatefromjpeg($source_image);
// 		imagejpeg($source_image, $dirname . $compress_image, 75);
// 	} else if ($image_info['mime'] == 'image/png') {
// 		$source_image = imagecreatefrompng($source_image);
// 		imagepng($source_image, $dirname . $compress_image, 5);
// 	}
// 	return $compress_image;
// }

if (isset($_POST['imageUpdate'])) {

	// profile image data
	$profile_img_data = $_POST['image_profile'];
	$image_array_1 = explode(";", $profile_img_data);
	$image_array_2 = explode(",", $image_array_1[1]);
	$profile_img_data = base64_decode($image_array_2[1]);

	$dirname = 'upload/';
	$image_name_profile =  "profile_" . time() . '.png';
	file_put_contents($dirname . $image_name_profile, $profile_img_data);


	// cover image data
	$cover_img_data = $_POST['image_cover'];
	$image_array_cover = explode(";", $cover_img_data);
	$image_array_cover2 = explode(",", $image_array_cover[1]);
	$cover_img_data = base64_decode($image_array_cover2[1]);

	$dirname = 'upload/';
	$image_name_cover =  "cover_" . rand() . '.png';
	file_put_contents($dirname . $image_name_cover, $cover_img_data);


	$sql = "SELECT `profile`,`logo` FROM `business_staff_members` WHERE `id`='20'";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);
	if ($row['profile'] != "") {
		unlink($dirname . $row['profile']);
	}

	if ($row['logo'] != "") {
		unlink($dirname . $row['logo']);
	}

	$sql = "UPDATE `business_staff_members` SET `logo` = '$image_name_profile',`profile` = '$image_name_cover' WHERE `business_staff_members`.`id`=20";
	$result = mysqli_query($conn, $sql);

	$res = array($dirname . $image_name_profile, $dirname . $image_name_cover);
	echo json_encode($res);
}
