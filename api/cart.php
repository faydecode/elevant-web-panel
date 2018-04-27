<?php
	require 'koneksi.php';
	
	$data =  json_decode(file_get_contents('php://input'));
	
	$post = $data->FN;
	
	switch ($post){
		case "tambah_keranjang":
			tambah_keranjang($mysqli,$data);	
			break;
		case "lihat_keranjang":
			lihat_keranjang($mysqli,$data);	
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
	
	
	function returnJSON($kode, $data, $keterangan)
	{
		echo json_encode(array(
				"kode" => $kode,
				"data" => $data,
				"keterangan" => $keterangan
				));
	}
	
	
?>