<?php
	class M_ukuran extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function get_ukuran_id($id_ukuran)
		{
			//$query = $this->db->get_where('tb_ukuran', array('id_ukuran' => $id_ukuran), $limit, $offset);
			$query = $this->db->get_where('tb_ukuran', array('id_ukuran' => $id_ukuran,'kode_kantor' => $this->session->userdata('ses_kode_kantor') ));
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function get_kode_ukuran()
		{
			$query = $this->db->query(
			"
				SELECT CONCAT(FRMTGL,COALESCE(ORD,'0001')) AS kode_ukuran
				FROM
				(
					SELECT CONCAT(Y,M) AS FRMTGL
					 ,CASE
						WHEN ORD >= 10 THEN CONCAT('00',CAST(ORD AS CHAR))
						WHEN ORD >= 100 THEN CONCAT('0',CAST(ORD AS CHAR))
						WHEN ORD >= 1000 THEN CAST(ORD AS CHAR)
						ELSE CONCAT('000',CAST(ORD AS CHAR))
						END AS ORD
					FROM
					(
						SELECT 
						CAST(LEFT(NOW(),4) AS CHAR) AS Y,
						CAST(MID(NOW(),6,2) AS CHAR) AS M,
						MID(NOW(),9,2) AS D,
						(MAX(CAST(RIGHT(kode_ukuran,4) AS UNSIGNED)) + 1) AS ORD FROM tb_ukuran
					) AS A
				) AS AA
			"
			);
			
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
	/* 	function list_ukuran_no_akun($cari,$limit,$offset)
		{
			$query = $this->db->query("
										SELECT A.id_ukuran,A.nik_ukuran, A.nama_ukuran,A.pnd,A.tlp,A.email,A.avatar,A.avatar_url,A.alamat,A.ket_ukuran,B.id_jabatan,B.nama_jabatan,C.id_akun,C.user,C.pass,C.pertanyaan1,C.pertanyaan2,C.jawaban1,C.jawaban2 FROM tb_ukuran AS A
										LEFT JOIN tb_jabatan AS B ON A.id_jabatan = B.id_jabatan
										LEFT JOIN tb_akun AS C ON A.id_ukuran = C.id_ukuran
										WHERE C.user IS NULL ".$cari." ORDER BY nama_ukuran ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		} */
		
		function list_ukuran_limit($cari,$limit,$offset)
		{
			$query = $this->db->query("
										SELECT * FROM tb_ukuran AS A
										LEFT JOIN tb_kat_produk AS B
											ON A.id_kat_ukuran = B.id_kat_produk
										".$cari." ORDER BY nama_ukuran ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function list_ukuran_produk($id_kat_produk)
		{
			$query = $this->db->query("
										SELECT * FROM tb_ukuran AS A
										LEFT JOIN tb_kat_produk AS B
											ON A.id_kat_ukuran = B.id_kat_produk
										WHERE A.id_kat_ukuran = '".$id_kat_produk."'");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_ukuran_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_ukuran) AS JUMLAH FROM tb_ukuran A ".$cari);
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function simpan
		(
			$id_kat_ukuran
			,$kode_ukuran
			,$nama_ukuran
			,$keterangan
			,$kode_kantor
			,$user_updt
		)
		{
			$query = "
				INSERT INTO tb_ukuran
				(
							 id_ukuran,
							 id_kat_ukuran,
							 kode_ukuran,
							 nama_ukuran,
							 ket_ukuran,
							 tgl_ins,
							 user_updt,
							 kode_kantor)
				VALUES (
					(
					 SELECT CONCAT('MB',FRMTGL,ORD) AS id_ukuran
					 From
					 (
						 SELECT CONCAT(Y,M) AS FRMTGL
						  ,CASE
							 WHEN (ORD >= 10 AND ORD < 99) THEN CONCAT('000',CAST(ORD AS CHAR))
							 WHEN (ORD >= 100 AND ORD < 999) THEN CONCAT('00',CAST(ORD AS CHAR))
							 WHEN (ORD >= 1000 AND ORD < 9999) THEN CONCAT('0',CAST(ORD AS CHAR))
							 WHEN ORD >= 10000 THEN CAST(ORD AS CHAR)
							 ELSE CONCAT('0000',CAST(ORD AS CHAR))
							 END As ORD
						 From
						 (
							 SELECT
							 CAST(LEFT(NOW(),4) AS CHAR) AS Y,
							 CAST(MID(NOW(),6,2) AS CHAR) AS M,
							 MID(NOW(),9,2) AS D,
							 COALESCE(MAX(CAST(RIGHT(id_ukuran,5) AS UNSIGNED)) + 1,1) AS ORD
							 From tb_ukuran
							 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
							 AND kode_kantor = '".$kode_kantor."'
						 ) AS A
					 ) AS AA
				 ),
						'".$id_kat_ukuran."',
						'".$kode_ukuran."',
						'".$nama_ukuran."',
						'".$keterangan."',
						now(),
						'".$user_updt."',
						'".$kode_kantor."'
				 );
				";
			
			
			
			$this->db->query($query);
		}
		
		
		function edit
		(
			$id_ukuran
			,$id_kat_ukuran
			,$kode_ukuran
			,$nama_ukuran
			,$keterangan
			,$user_updt
		)
		{
			$query = "
				UPDATE tb_ukuran
				SET id_kat_ukuran = '".$id_kat_ukuran."',
				  kode_ukuran = '".$kode_ukuran."',
				  nama_ukuran = '".$nama_ukuran."',
				  ket_ukuran = '".$keterangan."',
				  tgl_updt = now(),
				  user_updt = '".$user_updt."'
				WHERE id_ukuran = '".$id_ukuran."'
					AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."';
			";	
			
			$this->db->query($query);
		}
		
		function hapus($id)
		{
			$this->db->query("DELETE FROM tb_ukuran WHERE id_ukuran = '".$id."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'");
		}
		
		function get_ukuran($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_ukuran', array($berdasarkan => $cari, 'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
            if($query->num_rows() > 0)
            {
                return $query->num_rows();
            }
            else
            {
                return false;
            }
        }
	}
?>