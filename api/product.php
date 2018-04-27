<?php
	require 'koneksi.php';
	
	$data =  json_decode(file_get_contents('php://input'));
	
	$post = $data->FN;
	
	switch ($post){
		case "list_produk":
			list_produk($mysqli,$data);	
			break;
		case "list_image_produk":
			list_image_produk($mysqli,$data);	
			break;
		case "list_ukuran":
			list_ukuran($mysqli,$data);	
			break;
		case "detail_produk":
			detail_produk($mysqli,$data);	
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
	
	function list_image_produk($mysqli,$post)
	{
		$id_produk = $post->id_produk;
		
		
		$strsql = "
			SELECT id_images,img_file,img_url
			FROM tb_images A
			WHERE id = '".$id_produk."' AND group_by = 'produks'
		";
		$data2 = array();
		
		$result = $mysqli->query($strsql);
		$numRows = $result->num_rows;
		if ($numRows>0){
			while($rows = $result->fetch_array()) {
				$row["id_images"] = $rows["id_images"];
				$row["img_file"] = $rows["img_file"];
				$row["img_url"] = $rows["img_url"];
				array_push($data2, $row);
			}
			returnJSON(1, $data2, "Sukses");
		}else{
			returnJSON(2, null, "Tidak ada data produk");
		}
	}
	
	function list_ukuran($mysqli,$post)
	{
		$id_produk = $post->id_produk;
		
		
		$strsql = "
			SELECT A.id_ukuran,A.kode_ukuran
			FROM tb_ukuran A
			LEFT JOIN tb_kat_produk B
			  ON A.id_kat_ukuran = B.id_kat_produk
			LEFT JOIN tb_produk C
			  ON B.id_kat_produk = C.id_kat_produk
			WHERE C.id_produk = '".$id_produk."'
		";
		$data2 = array();
		
		$result = $mysqli->query($strsql);
		$numRows = $result->num_rows;
		if ($numRows>0){
			while($rows = $result->fetch_array()) {
				$row["id_ukuran"] = $rows["id_ukuran"];
				$row["kode_ukuran"] = $rows["kode_ukuran"];
				array_push($data2, $row);
			}
			returnJSON(1, $data2, "Sukses");
		}else{
			returnJSON(2, null, "Tidak ada data produk");
		}
	}
	
	function detail_produk($mysqli,$post)
	{
		$id_produk = $post->id_produk;
		
		
		$strsql = "
			SELECT A.id_produk,A.id_kat_produk,A.kode_produk,A.nama_produk,
				   B.nama_kat_produk,C.harga,A.spek_produk
			FROM tb_produk A
			LEFT JOIN tb_kat_produk B
			  ON A.id_kat_produk = B.id_kat_produk
			LEFT JOIN tb_harga C
			  ON C.id = A.id_produk
			WHERE A.id_produk = '".$id_produk."'

		";
		$data2 = array();
		
		$result = $mysqli->query($strsql);
		$numRows = $result->num_rows;
		if ($numRows>0){
			while($rows = $result->fetch_array()) {
				$row["id_produk"] = $rows["id_produk"];
				$row["id_kat_produk"] = $rows["id_kat_produk"];
				$row["kode_produk"] = $rows["kode_produk"];
				$row["nama_produk"] = $rows["nama_produk"];
				$row["nama_kat_produk"] = $rows["nama_kat_produk"];
				$row["harga"] = $rows["harga"];
				$row["spek_produk"] = $rows["spek_produk"];
				array_push($data2, $row);
			}
			returnJSON(1, $data2, "Sukses");
		}else{
			returnJSON(2, null, "Tidak ada data produk");
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