<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_admin_member extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model(array('M_member','M_kat_member','M_akun'));
	}
	
	public function index()
	{
		if(($this->session->userdata('ses_user_admin') == null) or ($this->session->userdata('ses_pass_admin') == null))
		{
			header('Location: '.base_url().'admin-login');
		}
		else
		{
			$cek_ses_login = $this->M_akun->get_cek_login($this->session->userdata('ses_user_admin'),md5(base64_decode($this->session->userdata('ses_pass_admin'))));
			
			if(!empty($cek_ses_login))
			{
				if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
				{
					$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' AND nama_lengkap LIKE '%".str_replace("'","",$_GET['cari'])."%'";
				}
				else
				{
					$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'";
				}
				
				$this->load->library('pagination');
				$config['first_url'] = site_url('admin-member?'.http_build_query($_GET));
				$config['base_url'] = site_url('admin-member/');
				$config['total_rows'] = $this->M_member->count_member_limit($cari)->JUMLAH;
				$config['uri_segment'] = 2;	
				$config['per_page'] = 30;
				$config['num_links'] = 2;
				$config['suffix'] = '?' . http_build_query($_GET, '', "&");
				$config['first_page'] = 'Awal';
				$config['last_page'] = 'Akhir';
				$config['next_page'] = '&laquo;';
				$config['prev_page'] = '&raquo;';
				
				
				$config['full_tag_open'] = '<div><ul class="pagination">';
				$config['full_tag_close'] = '</ul></div>';
				$config['first_link'] = '&laquo; First';
				$config['first_tag_open'] = '<li class="prev page">';
				$config['first_tag_close'] = '</li>';
				$config['last_link'] = 'Last &raquo;';
				$config['last_tag_open'] = '<li class="next page">';
				$config['last_tag_close'] = '</li>';
				$config['next_link'] = 'Next &rarr;';
				$config['next_tag_open'] = '<li class="next page">';
				$config['next_tag_close'] = '</li>';
				$config['prev_link'] = '&larr; Previous';
				$config['prev_tag_open'] = '<li class="prev page">';
				$config['prev_tag_close'] = '</li>';
				$config['cur_tag_open'] = '<li class="active"><a href="">';
				$config['cur_tag_close'] = '</a></li>';
				$config['num_tag_open'] = '<li class="page">';
				$config['num_tag_close'] = '</li>';
				
				
				//inisialisasi config
				$this->pagination->initialize($config);
				$halaman = $this->pagination->create_links();
				$kode_member = $this->M_akun->get_no_costumer()->no_costumer;
				$list_kat_member = $this->M_kat_member->list_kat_member();
				$list_member = $this->M_member->list_member_limit($cari,$config['per_page'],$this->uri->segment(2,0));
				$data = array('page_content'=>'admin_member','halaman'=>$halaman,'list_member'=>$list_member,'list_kat_member'=>$list_kat_member,'kode_member'=>$kode_member);
				$this->load->view('admin/container',$data);
			}
			else
			{
				header('Location: '.base_url().'admin-login');
			}
		}
	}
	
	public function simpan()
	{
		
		//$kode_member = $_POST['kode_member'];
		$kode_member = $this->M_akun->get_no_costumer()->no_costumer;
		
		if (!empty($_POST['stat_edit']))
		{
			if (empty($_FILES['foto']['name']))
			{
				$this->M_member->edit_no_image
				(
					$_POST['stat_edit']
					,$_POST['kat_member']
					,$_POST['nama']
					,$_POST['tgl_lahir']
					,$_POST['jkel']
					,$_POST['alamat']
					,$_POST['tlp']
					,$_POST['username']
					,MD5($_POST['pass'])
					,$_POST['email']
					,$_POST['keterangan']
					,$this->session->userdata('ses_id_karyawan')
				);
			}
			else
			{
				$data_member = $this->M_member->get_member_id($_POST['stat_edit']);
				$this->do_upload($_FILES['foto']['name'],$data_member->avatar);
				$foto = $_FILES['foto']['name'];
				$this->M_member->edit_with_image
				(
					$_POST['stat_edit']
					,$_POST['kat_member']
					,$_POST['nama']
					,$_POST['tgl_lahir']
					,$_POST['jkel']
					,$_POST['alamat']
					,$_POST['tlp']
					,$_POST['username']
					,md5($_POST['pass'])
					,$foto
					,base_url().'assets/global/member/'.$foto
					,$_POST['alamat']
					,$_POST['keterangan']
					,$this->session->userdata('ses_id_karyawan')
				);
			}
			
			header('Location: '.base_url().'admin-member?'.http_build_query($_GET));
		}
		else
		{
			if (!empty($_FILES['foto']['name']))
			{
				$this->do_upload($_FILES['foto']['name'],'');
				$foto = $_FILES['foto']['name'];
			}
			else
			{
				$foto = '';
			}
			$this->M_member->simpan
			(
				$_POST['kat_member']
				,$this->session->userdata('ses_id_karyawan')
				,$kode_member
				,$_POST['nama']
				,''
				,$_POST['tgl_lahir']
				,$_POST['jkel']
				,$_POST['alamat']
				,$_POST['tlp']
				,$_POST['username']
				,md5($_POST['pass'])
				,$foto
				,base_url().'assets/global/member/'.$foto
				,$_POST['email']
				,$_POST['keterangan']
				,$this->session->userdata('ses_kode_kantor')
				,$this->session->userdata('ses_id_karyawan')
			);
			header('Location: '.base_url().'admin-member?'.http_build_query($_GET));
		}
		
	}
	
	function do_upload($id,$cek_bfr)
	{
		$this->load->library('upload');

		if($cek_bfr != '')
		{
			@unlink('./assets/global/member/'.$cek_bfr);
		}
		
		if (!empty($_FILES['foto']['name']))
		{
			$config['upload_path'] = 'assets/global/member/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']	= '2024';
			//$config['max_widtd']  = '300';
			//$config['max_height']  = '300';
			$config['file_name']	= $id;
			$config['overwrite']	= true;
			

			$this->upload->initialize($config);

			//Upload file 1
			if ($this->upload->do_upload('foto'))
			{
				$hasil = $this->upload->data();
			}
		}
	}
	
	function cek_member()
	{
		$hasil_cek = $this->M_member->get_member('nama_costumer',$_POST['nama']);
		echo $hasil_cek;
	}
	
	public function hapus()
	{
		$id = $this->uri->segment(2,0);
		$hasil_cek = $this->M_member->get_member_id($id);
		$avatar = $hasil_cek->avatar;
		if(!empty($hasil_cek))
		{
			$this->do_upload('',$avatar);
			$this->M_member->hapus($id);
		}
		header('Location: '.base_url().'admin-member?'.http_build_query($_GET));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/c_admin_kat_karyawan.php */