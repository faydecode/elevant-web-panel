<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_admin_ukuran extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model(array('M_ukuran','M_kat_produk'));
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
					$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' AND nama_ukuran LIKE '%".str_replace("'","",$_GET['cari'])."%'";
				}
				else
				{
					$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'";
				}
				
				$this->load->library('pagination');
				$config['first_url'] = site_url('admin-ukuran?'.http_build_query($_GET));
				$config['base_url'] = site_url('admin-ukuran/');
				$config['total_rows'] = $this->M_ukuran->count_ukuran_limit($cari)->JUMLAH;
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

				$list_kat_ukuran = $this->M_kat_produk->list_kat_produk();
				$list_ukuran = $this->M_ukuran->list_ukuran_limit($cari,$config['per_page'],$this->uri->segment(2,0));
				$data = array('page_content'=>'admin_ukuran','halaman'=>$halaman,'list_ukuran'=>$list_ukuran,'list_kat_ukuran'=>$list_kat_ukuran);
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
		
		
		$kode_ukuran = $_POST['kode'];//$this->M_ukuran->get_kode_ukuran->kode_ukuran;
		
		if (!empty($_POST['stat_edit']))
		{
			$this->M_ukuran->edit
			(
				$_POST['stat_edit']
				,$_POST['kat_ukuran']
				,$kode_ukuran
				,$_POST['nama']
				,$_POST['keterangan']
				,$this->session->userdata('ses_id_karyawan')
			);
		
			header('Location: '.base_url().'admin-ukuran?'.http_build_query($_GET));
		}
		else
		{
			$this->M_ukuran->simpan
			(
				$_POST['kat_ukuran']
				,$kode_ukuran
				,$_POST['nama']
				,$_POST['keterangan']
				,$this->session->userdata('ses_kode_kantor')
				,$this->session->userdata('ses_id_karyawan')
			);
			header('Location: '.base_url().'admin-ukuran?'.http_build_query($_GET));
		}
		
	}
	
	
	function cek_ukuran()
	{
		$hasil_cek = $this->M_ukuran->get_ukuran('kode_ukuran',$_POST['kode']);
		echo $hasil_cek;
	}
	
	public function hapus()
	{
		$id = $this->uri->segment(2,0);
		
			$this->M_ukuran->hapus($id);
		
		header('Location: '.base_url().'admin-ukuran?'.http_build_query($_GET));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/c_admin_kat_karyawan.php */