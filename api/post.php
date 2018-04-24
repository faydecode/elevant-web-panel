<?php
	require 'koneksi.php';
	
	header("Content-type: application/json; charset=utf-8");
	
	$post = !empty($_POST["FN"]) ? $_POST["FN"] : "";
	
	
	//$post = "load_score";
	
	if($post == "" | !$post)
		returnJSON(100, null, "Fungsi tidak ditemukan");
	
	switch ($post){
		case "koneksi":
			koneksi($mysqli);	
			break;
		case "login":
			login($mysqli);	
			break;
		case "register":
			register($mysqli);	
			break;
		case "load_game":
			load_kategori_game($mysqli);	
			break;
		case "simpan_score":
			simpan_score($mysqli);	
			break;
		case "load_score":
			load_score($mysqli);	
			break;
		default:
			returnJSON(100, null, "Fungsi tidak ditemukan");
			break;
	}
	
	
	//register($mysqli);
	
	function koneksi($mysqli)
	{
		$strsql = "SELECT NOW() AS TGL";
		
		$result = $mysqli->query($strsql);
		$numRows = $result->num_rows;
		if ($numRows>0){
			returnJSON(1, "", "Sukses Koneksi");
		} else {
			returnJSON(2, null, "Gagal Koneksi");	
		}
	}
	
	function login($mysqli)
	{
		$user = $_POST['user'];
		$pass = md5($_POST['pass']);
		
		$strsql = "SELECT * FROM tb_member_app
				   WHERE username = '".$user."' AND pass = '".$pass."'
				  ";
		
		
		$result = $mysqli->query($strsql);
		$numRows = $result->num_rows;
		if ($numRows>0){
			while($rows = $result->fetch_array())
			{
				$row["id_member"] = $rows["id_member"];
				$row["nama_member"] = $rows["nama_member"];
				$row["username"] = $rows["username"];
				break;
			}
			returnJSON(1, $row, "Sukses");
		}else{ 
			returnJSON(2, null, "User tidak ditemukan");
		}
	}
	
	
	function register($mysqli)
	{
		$nama = $_POST['nama'];
		$user = $_POST['user'];
		$pass = md5($_POST['pass']);
		
		$strsql = "
			INSERT INTO tb_member_app
						(id_member,
						 nama_member,
						 username,
						 pass,
						 tgl_ins
						 )
			VALUES (
				(
				 SELECT CONCAT('MB',FRMTGL,ORD) AS id_member
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
						 COALESCE(MAX(CAST(RIGHT(id_member,5) AS UNSIGNED)) + 1,1) AS ORD
						 FROM tb_member_app
						 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
					 ) AS A
				 ) AS AA
				),
					'".$nama."',
					'".$user."',
					'".$pass."',
					NOW()
			 );
		";
		
		$result = $mysqli->query($strsql);
		
		if($result)
		{
			returnJSON(1, null, "Sukses");
		} else
		{
			returnJSON(2, null, "Gagal Simpan Database");
		}
	}
	
	function load_kategori_game($mysqli)
	{
		$strsql = "
			SELECT id_game,kode_game,nama_game,ket_game
			FROM tb_game
		";
		
		$result = $mysqli->query($strsql);
		$numRows = $result->num_rows;
		if ($numRows>0){
			while($rows = $result->fetch_array())
			{
				$row["id_game"] = $rows["id_game"];
				$row["kode_game"] = $rows["kode_game"];
				$row["nama_game"] = $rows["nama_game"];
				$row["ket_game"] = $rows["ket_game"];
				break;
			}
			returnJSON(1, $row, "Sukses");
		}else{ 
			returnJSON(2, null, "Data tidak ditemukan");
		}
		
	}
	
	function load_score($mysqli)
	{
		$id_member = $_POST['id_member'];
		$id_game = $_POST['id_game'];
		
		$strsql = "
			SELECT id_score,score FROM tb_score
			WHERE id_game = '".$id_game."' AND id_member = '".$id_member."'
		";
		
		$result = $mysqli->query($strsql);
		$numRows = $result->num_rows;
		if ($numRows>0){
			while($rows = $result->fetch_array())
			{
				$row["id_score"] = $rows["id_score"];
				$row["score"] = $rows["score"];
				
				break;
			}
			returnJSON(1, $row, "Sukses");
		}else{ 
			returnJSON(2, null, "Data tidak ditemukan");
		}
		
	}
	
	
	function simpan_score($mysqli)
	{
		$id_member = $_POST['id_member'];
		$id_game = $_POST['id_game'];
		$score = $_POST['score'];
		
		$strsql = "
			INSERT INTO tb_score
						(id_score,
						 id_game,
						 id_member,
						 score,
						 tgl_ins)
			VALUES (
				(
				  SELECT CONCAT('MB',FRMTGL,ORD) AS id_score
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
						 COALESCE(MAX(CAST(RIGHT(id_score,5) AS UNSIGNED)) + 1,1) AS ORD
						 FROM tb_score
						 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
					 ) AS A
				 ) AS AA
				),
					'".$id_game."',
					'".$id_member."',
					'".$score."',
					now()
			);
		";
		
		$result = $mysqli->query($strsql);
		if($result)
		{
			returnJSON(1, null, "Sukses");
		} else
		{
			returnJSON(2, null, "Gagal Simpan Database");
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