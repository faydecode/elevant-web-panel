<?php
	require 'koneksi.php';
	
	//header("Content-type: application/json; charset=utf-8");
	
	//$post = !empty($_POST["FN"]) ? $_POST["FN"] : "";
	
	$data =  json_decode(file_get_contents('php://input'));
	
	$post = $data->FN;//$_POST["FN"];//!empty($_POST["FN"] ? $_POST["FN"] : "";
	
	
	if($post == "" | !$post)
		returnJSON(100, null, "Fungsi tidak ditemukan");

	switch ($post){
		case "dashboard_menu":
			dashboard_menu($mysqli);	
			break;
		default:
			returnJSON(100, null, "Fungsi tidak ditemukan");
			break;
	}
	
	function dashboard_menu($mysqli)
	{
		$strsql = "
			SELECT id_kat_produk,nama_kat_produk
			FROM tb_kat_produk
			WHERE set_aktif = '1'
		";
		$data = array();
		
		$result = $mysqli->query($strsql);
		$numRows = $result->num_rows;
		if ($numRows>0){
			while($rows = $result->fetch_array()) {
				$row["id_kat_produk"] = $rows["id_kat_produk"];
				$row["nama_kat_produk"] = $rows["nama_kat_produk"];
				array_push($data, $row);
			}
			returnJSON(1, $data, "Sukses");
		}else{
			returnJSON(2, null, "Tidak ada kategori apapun");
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