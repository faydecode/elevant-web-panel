<?php
/*
	return array("return"=> "false", "data" => "	")
*/
include "conn.php";
header("Content-type: application/json; charset=utf-8");

//$rootPath = "http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER['SERVER_PORT'] . "/bbpbat/";
$rootPath = "http://" . $_SERVER["SERVER_NAME"] . "/api/";


$post = !empty($_POST["FN"]) ? $_POST["FN"] : "";

if($post == "" | !$post)
	returnJSON(100, null, "Fungsi tidak ditemukan");

switch ($post){
	case "login":
		login($mysqli, $rootPath);	
		break;
	case "send_progress":
		sendProgress($mysqli);
		break;
	case "reset_password":
		resetPassword($mysqli);
		break;
	case "change_password":
		changePassword($mysqli);
		break;
	case "change_email":
		changeEmail($mysqli);
		break;
	case "change_profile_picture":
		changeProfilePicture($mysqli);
		break;
	case "verifikasi":
		verifikasi($mysqli);
		break;
	default:
		returnJSON(100, null, "Fungsi tidak ditemukan");
		break;
}

function changePassword($mysqli){
	$email = $_POST["email"];
	$hashedPassword = md5($_POST["password"]);
	$result = mysqli_query($mysqli, "UPDATE m_user SET PASSWORD = '$hashedPassword' WHERE EMAIL_USER = '$email'");
	if ($result) {
		returnJSON(1, null, "Sukses");
	}else{
		returnJSON(2, null, "Ganti email gagal");
	}
}

function changeEmail($mysqli){
	$emailBaru = $_POST["email_baru"];
	$emailLama = $_POST["email_lama"];
	$result = mysqli_query($mysqli, "UPDATE m_user SET EMAIL_USER = '$emailBaru' WHERE EMAIL_USER = '$emailLama'");
	if ($result) {
		returnJSON(1, null, "Sukses");
	}else{
		returnJSON(2, null, "Ganti email gagal");
	}
}

function changeProfilePicture($mysqli){
	$userId = $_POST["user_id"];
	$filePath = "images/profile-picture/";
	$namaFoto = generateRandomString(5) . basename( $_FILES['profile_picture']['name']);
	$filePath = $filePath . $namaFoto;
    if(!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $filePath)) {
        returnJSON(2, null, "Ganti foto gagal");
        return;
    }
	$result = mysqli_query($mysqli, "UPDATE m_user SET PROFILE_PICTURE = '$namaFoto' WHERE ID_USER = '$userId'");
	if ($result) {
		returnJSON(1, null, "Sukses");
	}else{
		returnJSON(2, null, "Ganti foto gagal");
	}
}

function login($mysqli, $rootPath){
	$username = $_POST["username"];
	$password = $_POST["password"];
	$imei = '1111';//$_POST["imei"];

	$data = array();
	$sqli = "call proc_login_mobile('$username','$password','$imei')";
	$result = $mysqli->query($sqli);
	$numRows = $result->num_rows;
	if ($numRows>0){
		while($rows = $result->fetch_array()) {
			$row["id"]=$rows["ID_USER"];
			$row["id_akses"]=$rows["ID_AKSES"];
			$row["id_unit"]=$rows["ID_UNIT"];
			$row["username"]=$rows["USERNAME"];
			$row["email"]=$rows["EMAIL_USER"];
			$row["nama"]=$rows["NAMA_USER"];
			$row["nama_akses"]=$rows["NAMA_AKSES"];
			$row["nama_unit"]=$rows["NAMA_UNIT"];
			$row["profile_picture"]= $rootPath . "images/profile-picture/" . $rows["PROFILE_PICTURE"];
			break;
		}
		returnJSON(1, $row, "Sukses");
	}else{ 
		returnJSON(2, null, "User tidak ditemukan");
	}
}

function sendProgress($mysqli){
	
	$programId = $_POST["program_id"];
	$wilayahId = $_POST["wilayah_id"];
	$userId = $_POST["user_id"];
	$persen = $_POST["persen"];
	$keterangan = $_POST["keterangan"];
	$stok = $_POST["stok"];
	$jumlahFoto = $_POST["jumlah_foto"];
	
	$listFotoPath = uploadFile();
	if ($listFotoPath == "false") {
		returnJSON(2, null, "Gagal menyimpan foto");
		return;
	}
	$data = array();
	$sqli = "call proc_send_progress_new('$userId','$programId','$wilayahId','$stok','$persen','$keterangan','$jumlahFoto', '$listFotoPath')";
	$result = $mysqli->query($sqli);
	$numRows = $result->num_rows;
	if ($numRows = 1){
		while($rows = $result->fetch_array()) {
			if ($rows["RCDB"] == 'RC01') {
				returnJSON(2, null, "Gagal menyimpan progress");
			}else{
				returnJSON(1, null, "Sukses");
			}
		}
	}else {
		returnJSON(3, null, "Terdapat kesalahan di server");
	} 
	

}

function resetPassword($mysqli){
	$username = $_POST["username"];
	$email = $_POST["email"];
	$newRandomPassword = generateRandomString(6);
	$hashedPassword = md5($newRandomPassword);

	$sqli = "call proc_reset_password_mobile('$username', '$email', '$hashedPassword')";
	$result = $mysqli->query($sqli);
	$numRows = $result->num_rows;
	if ($numRows>0){
		while($rows = $result->fetch_array()) {
			if ($rows["RCDB"] == 'RC01') {
				returnJSON(2, null, "User tidak terdaftar");
			}else if ($rows["RCDB"] == 'RC02') {
				returnJSON(2, null, "Gagal reset password");
			}else{
				$data['new_password'] = $newRandomPassword;
				returnJSON(1, $data, "Sukses");
			}
			break;
		}
	}else{
		returnJSON(3, null, "Terdapat kesalahan di server");
	}
}

function verifikasi($mysqli){
	$progressId = $_POST["progress_id"];
	$keterangan = $_POST["keterangan"];
	$jumlahFoto = $_POST["jumlah_foto"];
	$listFotoPath = "";
	for ($i=0; $i<$jumlahFoto ; $i++) {
    	$listFotoPath .= basename($_POST['image_' . $i]) . '#';
    }
	$data = array();
	$sqli = "call proc_verifikasi('$progressId','$keterangan','$jumlahFoto', '$listFotoPath')";
	$result = $mysqli->query($sqli);
	$numRows = $result->num_rows;
	if ($numRows = 1){
		while($rows = $result->fetch_array()) {
			if ($rows["RCDB"] == 'RC00') {
				returnJSON(1, null, "Sukses");
			}else{
				returnJSON(2, null, "Gagal menyimpan verifikasi");
			}
		}
	}else {
		returnJSON(3, null, "Terdapat kesalahan di server");
	}
}

function uploadFile(){
	$listFotoPath = "";
    
    for ($i=0; $i < $_POST["jumlah_foto"] ; $i++) {
    	$filePath = "images/progress/";
    	$namaFoto = generateRandomString(5) . basename( $_FILES['image_' . $i]['name']);
    	$listFotoPath .= $namaFoto . '#';
    	$filePath = $filePath . $namaFoto;
	    if(!move_uploaded_file($_FILES['image_' . $i]['tmp_name'], $filePath)) {
	        return "false";
	    }
    }
    return $listFotoPath;
}

function returnJSON($kode, $data, $keterangan){
	echo json_encode(array(
			"kode" => $kode,
			"data" => $data,
			"keterangan" => $keterangan
			));
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>