<?php
	
	require 'koneksi.php';
	
	//header("Content-type: application/json; charset=utf-8");
	//header("Content-Type": "application/x-www-form-urlencoded");
	
	
	$post = $_POST["FN"];//!empty($_POST["FN"] ? $_POST["FN"] : "";
	
	
	//$post = "load_score";
	
	//if($post == "" | !$post)
	//	returnJSON(100, null, "Fungsi tidak ditemukan");
	
	
	
	switch ($post){
		case "login":
			login($mysqli);	
			break;
		case "register":
			register($mysqli);	
			break;
		default:
			returnJSON(100, null, "Fungsi tidak ditemukan");
			break;
	}
	
	function login($mysqli)
	{
		$user = 'admin';//$_POST['username'];
		$pass = '8c7909c1c3a51bcfbcef322fa8b25b26';//md5($_POST['password']);
		
		$strsql = "SELECT * FROM tb_akun
				   WHERE user = '".$user."' AND pass = '".$pass."'
				  ";
		
		$result = $mysqli->query($strsql);
		$numRows = $result->num_rows;
		if ($numRows>0){
			while($rows = $result->fetch_array())
			{
				$row["id_akun"] = $rows["id_akun"];
				$row["user"] = $rows["user"];
				break;
			}
			returnJSON(1, $row, "Sukses");
		}else{ 
			returnJSON(2, null, "User tidak ditemukan");
		}
	}
	
	function returnJSON($kode, $data, $keterangan)
	{
		echo json_encode(array(
				"kode" => $kode,
				"data" => $data,
				"keterangan" => $keterangan
				));
	}

?>