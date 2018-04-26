<?php
	require 'koneksi.php';
	
	$data =  json_decode(file_get_contents('php://input'));
	
	$post = $data->FN;
	
	switch ($post){
		case "list_produk":
			list_produk($mysqli,$data);	
			break;
		default:
			returnJSON(100, null, "Fungsi tidak ditemukan");
			break;
	}
	
	
	function list_produk($mysqli,$post)
	{
		$id_kat_produk = $post->id_kat_produk;
		
		
		$strsql = "
			SELECT A.id_produk,A.nama_produk,B.harga
			FROM tb_produk A
			LEFT JOIN tb_harga B
			  ON B.id = A.id_produk AND B.group_by = 'produks'
			WHERE id_kat_produk = '".$id_kat_produk."'
		";
		$data2 = array();
		
		$result = $mysqli->query($strsql);
		$numRows = $result->num_rows;
		if ($numRows>0){
			while($rows = $result->fetch_array()) {
				$row["id_produk"] = $rows["id_produk"];
				$row["nama_produk"] = $rows["nama_produk"];
				$row["harga"] = $rows["harga"];
				array_push($data2, $row);
			}
			returnJSON(1, $data2, "Sukses");
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