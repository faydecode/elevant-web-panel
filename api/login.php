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
			register($mysqli,$data);	
			break;
		case "cek_email":
			cek_email($mysqli,$data);	
			break;
		default:
			returnJSON(100, null, "Fungsi tidak ditemukan");
			break;
	}
	
	function login($mysqli,$data)
	{
		$user = $data->username;//$_POST['username'];
		$pass = md5($data->password);//md5($_POST['password']);
		
		$strsql = "SELECT * FROM tb_costumer
				   WHERE email_costumer = '".$user."' AND pass = '".$pass."'
				  ";
		
		$result = $mysqli->query($strsql);
		$numRows = $result->num_rows;
		if ($numRows>0){
			while($rows = $result->fetch_array())
			{
				$row["nama_lengkap"] = $rows["nama_lengkap"];
				$row["email_costumer"] = $rows["email_costumer"];
				$row["hp"] = $rows["hp"];
				$row["alamat_rumah"] = $rows["alamat_rumah"];
				
				break;
			}
			returnJSON(1, $row, "Sukses");
		}else{ 
			returnJSON(2, null, "User tidak ditemukan");
		}
	}
	
	function register($mysqli,$data)
	{
		$email = $data->email;//$_POST['username'];
		$user = $data->username;//$_POST['username'];
		$pass = md5($data->password);//md5($_POST['password']);
		
		$date  = date("Y-m-d");
		
		$strsql = "INSERT INTO tb_costumer
							(id_costumer,
							 id_karyawan,
							 id_kat_costumer,
							 tgl_costumer,
							 no_costumer,
							 nama_lengkap,
							 no_ktp,
							 status_costumer,
							 alamat_rumah,
							 hp,
							 username,
							 pass,
							 avatar,
							 avatar_url,
							 email_costumer,
							 ket_costumer,
							 tgl_ins)
				VALUES ((
					 SELECT CONCAT('CS',FRMTGL,ORD) AS id_costumer
					 FROM
					 (
						 SELECT CONCAT(Y,M) AS FRMTGL
						  ,CASE
							 WHEN (ORD >= 10 AND ORD < 99) THEN CONCAT('000',CAST(ORD AS CHAR))
							 WHEN (ORD >= 100 AND ORD < 999) THEN CONCAT('00',CAST(ORD AS CHAR))
							 WHEN (ORD >= 1000 AND ORD < 9999) THEN CONCAT('0',CAST(ORD AS CHAR))
							 WHEN ORD >= 10000 THEN CAST(ORD AS CHAR)
							 ELSE CONCAT('0000',CAST(ORD AS CHAR))
							 END AS ORD
						 FROM
						 (
							 SELECT
							 CAST(LEFT(NOW(),4) AS CHAR) AS Y,
							 CAST(MID(NOW(),6,2) AS CHAR) AS M,
							 MID(NOW(),9,2) AS D,
							 COALESCE(MAX(CAST(RIGHT(id_costumer,5) AS UNSIGNED)) + 1,1) AS ORD
							 FROM tb_costumer
							 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
						 ) AS A
					 ) AS AA
					),
						'',
						'',
						'".$date."',
						'',
						'".$user."',
						'',
						'1',
						'',
						'',
						'',
						'".$pass."',
						'',
						'',
						'".$email."',
						'',
						NOW());
								  ";
		
		$result = $mysqli->query($strsql);
		if ($result){
			returnJSON(1, '', "Sukses");
		}else{ 
			returnJSON(2, null, "Registrasi gagal");
		}
	}
	
	function cek_email($mysqli,$data)
	{
		$email = $data->email;//$_POST['username'];
		
		$strsql = "SELECT * FROM tb_costumer
					WHERE email_costumer = '".$email."'
				  ";
		
		$result = $mysqli->query($strsql);
		$numRows = $result->num_rows;
		if ($numRows>0){
			returnJSON(1, null, "Email sudah terdaftar");
		}else{ 
			returnJSON(2, null, "Email belum terdaftar");
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