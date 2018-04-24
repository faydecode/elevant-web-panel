<?php

include "conn.php";
header("Content-type: application/json; charset=utf-8");

//$rootPath = "http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER['SERVER_PORT'] . "/bbpbat/";

$rootPath = "http://" . $_SERVER["SERVER_NAME"] . "/api/";

$get = !empty($_GET["FN"]) ? $_GET["FN"] : "";

if($get == "" | !$get)
	returnJSON(100, null, "Fungsi tidak ditemukan");

switch ($get){
	case "load_cbo_program":
		cboProgram($mysqli);
		break;
	case "load_cbo_wilayah":
		cboWilayah($mysqli);
		break;
	case "load_history_progress":
		loadHistoryProgress($mysqli, $rootPath);
		break;
	case "load_history_verifikasi":
		loadHistoryVerifikasi($mysqli, $rootPath);
		break;
	case "cari_program":
		cariProgram($mysqli, $rootPath);
		break;
	case "view_program":
		viewProgram($mysqli);
		break;
	case "load_unverified_progress":
		loadUnverifiedProgress($mysqli, $rootPath);
		break;
	case "convert_to_md5_password"://Testing md5 password
		convertToMd5Password();
		break;
	case "get_progress_count":
		getProgressCount($mysqli);
		break;
	case "load_rekap":
		load_rekap($mysqli);
		break;
	default:
		returnJSON(100, null, "Fungsi tidak ditemukan");
		break;
}

function getProgressCount($mysqli){
	$resultVerified = mysqli_query($mysqli, "SELECT * FROM progres_program WHERE is_agree = 1");
	$resultUnVerified = mysqli_query($mysqli, "SELECT * FROM progres_program WHERE is_agree IS NULL");
	returnJSON(1, array(
			'verified_count' => $resultVerified->num_rows,
			'un_verified_count' => $resultUnVerified->num_rows
		), "Berhasil");
}

function loadUnverifiedProgress($mysqli, $rootPath){
	$username = $_GET["username"];
	$sqli = "CALL `proc_load_unverified_progress`()";
	$result = $mysqli->query($sqli);
	$numRows = $result->num_rows;
	$data = array();
	if ($numRows>0){
		while($rows = $result->fetch_array()) {
			if ($rows['RC']='RC01') {
				$row["id"] = $rows["ID_PROGRES"];
				$row["nama_user"] = $rows["NAMA_USER"];
				$row["ket"] = $rows["KET"];
				$row["persen"] = $rows["PERSEN_PROGRESS"];
				$row["stok"] = $rows["STOK_PROGRESS"];
				$row["tgl"] = $rows["TGL_PROGRESS"];
				$row["nama_wilayah"] = $rows["NAMA_WILAYAH"];
				$row["nama_program"] = $rows["NAMA_PROGRAM"];
				$row["list_foto"] = array();
				$sqli = "CALL `proc_load_path_foto`('0','".$rows["ID_PROGRES"]."')";
					$mysqli = mysqli_connect("localhost","root","","db_bbpbat");
				$resultFoto = $mysqli->query($sqli);
				while ($rowsFoto = $resultFoto->fetch_array()) {
					if ($rowsFoto['RC']=='RC001') {
						$rowFoto["path"] = $rootPath . "images/progress/" . $rowsFoto["path_foto"];
						array_push($row['list_foto'], $rowFoto);
					}
					
				}
				array_push($data, $row);
			}else{
				returnJSON(2, null, $rows["PESANDB"]);
				break;
			}
		}
		returnJSON(1, $data, "Sukses");
	}else{
		returnJSON(2, null, "Tidak ada data");
	}
}

function convertToMd5Password(){
	$password = $_GET["password"];
	$data['password'] = md5($password);
	returnJSON(1, $data, "Konversi password ke md5");
}

function cboProgram($mysqli){
	$userId = $_GET["user_id"];

	$data = array();
	$sqli = "CALL `proc_program_user_mobile`('$userId')";
	$result = $mysqli->query($sqli);
	$numRows = $result->num_rows;
	if ($numRows>0){
		while($rows = $result->fetch_array()) {
			$row["id"] = $rows["ID_PROGRAM"];
			$row["nama"] = $rows["NAMA_PROGRAM"];
			array_push($data, $row);
		}
		returnJSON(1, $data, "Sukses");
	}else{
		returnJSON(2, null, "User belum terdaftar pada program apapun");
	}
}

function cboWilayah($mysqli){
	$userId = $_GET["user_id"];
	$programId = $_GET["program_id"];
	$sqli = "CALL `proc_wilayah_user_mobile`('$userId', '$programId')";
	$result = $mysqli->query($sqli);
	$numRows = $result->num_rows;
	
	//$row=[];
	
	$data = array();
	if ($numRows>0){
		//while($rows = $result->fetch_array()) {
			while($rows = $result->fetch_array()) {
				$row["id"] = $rows["ID_WILAYAH"];
				$row["nama"] = $rows["NAMA_WILAYAH"];
				array_push($data, $row);
			}
			returnJSON(1, $data, "Sukses");
		//}
	}else{
		returnJSON(2, null, "User belum terdaftar pada program apapun");
	}
}

function loadHistoryProgress($mysqli, $rootPath){
	$username = $_GET["username"];
	$sqli = "CALL `proc_load_history`('$username')";
	$result = $mysqli->query($sqli);
	$numRows = $result->num_rows;
	$data = array();
	if ($numRows>0){
		mysqli_close($mysqli);
		while($rows = $result->fetch_array()) {
			if ($rows['RC']='RC01') {
				$row["id"] = $rows["ID_PROGRES"];
				$row["ket"] = $rows["KET"];
				$row["verified_ket"] = $rows["VERIFIED_KET"];
				$row["persen"] = $rows["PERSEN_PROGRESS"];
				$row["stok"] = $rows["STOK_PROGRESS"];
				$row["tgl"] = $rows["TGL_PROGRESS"];
				$row["nama_wilayah"] = $rows["NAMA_WILAYAH"];
				$row["nama_program"] = $rows["NAMA_PROGRAM"];
				$row["list_foto"] = array();
				$sqli = "CALL `proc_load_path_foto`('0','".$rows["ID_PROGRES"]."')";
					$mysqli = mysqli_connect("localhost","root","","db_bbpbat");
				$resultFoto = $mysqli->query($sqli);
				while ($rowsFoto = $resultFoto->fetch_array()) {
					if ($rowsFoto['RC']=='RC001') {
						$rowFoto["path"] = $rootPath . "images/progress/" . $rowsFoto["path_foto"];
						array_push($row['list_foto'], $rowFoto);
					}
				}
				$row["verified_list_foto"] = array();
				mysqli_close($mysqli);
				$sqli = "CALL `proc_load_path_foto`('1','".$rows["ID_PROGRES"]."')";
					$mysqli = mysqli_connect("localhost","root","","db_bbpbat");
				$resultFoto = $mysqli->query($sqli);
				while ($rowsFoto = $resultFoto->fetch_array()) {
					if ($rowsFoto['RC']=='RC001') {
						$rowFoto["path"] = $rootPath . "images/progress/" . $rowsFoto["path_foto"];
						array_push($row['verified_list_foto'], $rowFoto);
					}
					
				}
				array_push($data, $row);
			}else{
				returnJSON(2, null, $rows["PESANDB"]);
				break;
			}
		}
		returnJSON(1, $data, "Sukses");
	}else{
		returnJSON(2, null, "Tidak ada data");
	}
}

function loadHistoryVerifikasi($mysqli, $rootPath){
	$sqli = "CALL `proc_view_history_verifikasi`()";
	$result = $mysqli->query($sqli);
	$numRows = $result->num_rows;
	$data = array();
	if ($numRows>0){
		mysqli_close($mysqli);
		while($rows = $result->fetch_array()) {
			if ($rows['RC']='RC01') {
				$row["id"] = $rows["ID_PROGRES"];
				$row["ket"] = $rows["KET_PROGRESS"];
				$row["persen"] = $rows["PERSEN_PROGRESS"];
				$row["stok"] = $rows["STOK_PROGRESS"];
				$row["tgl"] = $rows["TGL_PROGRESS"];
				$row["nama_wilayah"] = $rows["NAMA_WILAYAH"];
				$row["nama_program"] = $rows["NAMA_PROGRAM"];
				$row["list_foto"] = array();
				$sqli = "CALL `proc_load_path_foto`('0','".$rows["ID_PROGRES"]."')";
					$mysqli = mysqli_connect("localhost","root","","db_bbpbat");
				$resultFoto = $mysqli->query($sqli);
				while ($rowsFoto = $resultFoto->fetch_array()) {
					if ($rowsFoto['RC']=='RC001') {
						$rowFoto["path"] = $rootPath . "images/progress/" . $rowsFoto["path_foto"];
						array_push($row['list_foto'], $rowFoto);
					}
					
				}
				array_push($data, $row);
			}else{
				returnJSON(2, null, $rows["PESANDB"]);
				break;
			}
		}
		returnJSON(1, $data, "Sukses");
	}else{
		returnJSON(2, null, "Tidak ada data");
	}
}

function cariProgram($mysqli, $rootPath){
	$username = $_GET["username"];
	$programId = $_GET["program_id"];
	$wilayahId = $_GET["wilayah_id"];

	$data = array();
	$sqli = "call proc_cari_program('$username','$programId','$wilayahId')";
	$result = $mysqli->query($sqli);
	$numRows = $result->num_rows;
	if ($numRows>0){
		mysqli_close($mysqli);
		while($rows = $result->fetch_array()) {
			if ($rows['RC']=='RC01') {
				$row["id"] = $rows["ID_PROGRES"];
				$row["ket"] = $rows["KET"];
				$row["verified_ket"] = $rows["VERIFIED_KET"];
				$row["persen"] = $rows["PERSEN_PROGRESS"];
				$row["stok"] = $rows["STOK_PROGRESS"];
				$row["tgl"] = $rows["TGL_PROGRESS"];
				$row["nama_wilayah"] = $rows["NAMA_WILAYAH"];
				$row["nama_program"] = $rows["NAMA_PROGRAM"];
				$row["list_foto"] = array();
				$sqli = "CALL `proc_load_path_foto`('0','".$rows["ID_PROGRES"]."')";
					$mysqli = mysqli_connect("localhost","root","","db_bbpbat");
				$resultFoto = $mysqli->query($sqli);
				while ($rowsFoto = $resultFoto->fetch_array()) {
					if ($rowsFoto['RC']=='RC001') {
						$rowFoto["path"] = $rootPath . "images/progress/" . $rowsFoto["path_foto"];
						array_push($row['list_foto'], $rowFoto);
					}
					
				}
				$row["verified_list_foto"] = array();
				mysqli_close($mysqli);
				$sqli = "CALL `proc_load_path_foto`('1','".$rows["ID_PROGRES"]."')";
					$mysqli = mysqli_connect("localhost","root","","db_bbpbat");
				$resultFoto = $mysqli->query($sqli);
				while ($rowsFoto = $resultFoto->fetch_array()) {
					if ($rowsFoto['RC']=='RC001') {
						$rowFoto["path"] = $rootPath . "images/progress/" . $rowsFoto["path_foto"];
						array_push($row['verified_list_foto'], $rowFoto);
					}
					
				}
				array_push($data, $row);
			}else{
				returnJSON(2, null, $rows["PESANDB"]);
				break;
			}
		}
		returnJSON(1, $data, "Sukses");
	}else{
		returnJSON(2, null, "Tidak ada data");
	}
	
}

function viewprogram($mysqli){
	$ret = "RC01";
	$data = array();
	$val = array();
	$content = "";
	$idprogress = $_GET["idprogress"];

	$sqli = "call proc_viewprogram('$idprogress')";
	$result = $mysqli->query($sqli);
	$jmlh = $result->num_rows;
	if ($jmlh>0){
		while($rows = $result->fetch_array()) {
			array_push($val, array("id"=>$rows["ID_PROGRES"], 
									"ket"=>$rows["KET_PROGRESS"],
									"persen"=>$rows["PERSEN_PROGRESS"],
									"tgl"=>$rows["TGL_PROGRESS"],
									"stok"=>$rows["STOK_PROGRESS"],
									"nama_wilayah"=>$rows["NAMA_WILAYAH"],
									"nama_program"=>$rows["NAMA_PROGRAM"]));
		}
	}else{
		$ret = 'RC02';
		$content = "TERJADI KESALAHAN PADA API SERVER";
	}
	$data = array("rc" => $ret, "value" => $val, "content"=> $content, "method"=>"viewprogram");
	echo json_encode(array("data"=> $data));
}

function load_rekap($mysqli){
	$userId = $_GET["user_id"];

	$data = array();
	$sqli = "CALL `proc_loadrekap`('$userId')";
	$result = $mysqli->query($sqli);
	$numRows = $result->num_rows;
	if ($numRows>0){
		while($rows = $result->fetch_array()) {
			$row["total"] = $rows["TOTAL"];
			$row["pesan"] = $rows["PESAN"];
			array_push($data, $row);
		}
		returnJSON(1, $data, "Sukses");
	}else{
		returnJSON(2, null, "Belum ada Data");
	}
}

function returnJSON($kode, $data, $keterangan){
	echo json_encode(array(
			"kode" => $kode,
			"data" => $data,
			"keterangan" => $keterangan
			));
}

?>