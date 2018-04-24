<?php
	
	require 'koneksi.php';
	
	//header("Content-type: application/json; charset=utf-8");
	//header("Content-Type": "application/x-www-form-urlencoded");
	
	$data =  json_decode(file_get_contents('php://input'));
	
	$post = $data->FN;//$_POST["FN"];//!empty($_POST["FN"] ? $_POST["FN"] : "";
	//echo $post;
	
	//$post = "load_score";
	
	//if($post == "" | !$post)
	//	returnJSON(100, null, "Fungsi tidak ditemukan");
	
	
	
	switch ($post){
		case "login":
			login($mysqli,$data);	
			break;
		case "register":
			register($mysqli);	
			break;
		default:
			returnJSON(100, null, "Fungsi tidak ditemukan");
			break;
	}
	
	function login($mysqli,$data)
	{
		$user = $data->username;//$_POST['username'];
		$pass = md5($data->password);//md5($_POST['password']);
		
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