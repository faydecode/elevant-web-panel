<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
//$route['default_controller'] = 'C_home';
$route['default_controller'] = 'C_admin_login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//ROUTE INI UNTUK AREA ADMIN PANEL / BACKEND


//LOGIN
	$route['admin-cek-login'] = "C_admin_login/cek_login";
	$route['admin-cek-login/(:any)'] = 'C_admin_login/cek_login';

	$route['admin-login'] = "C_admin_login/index";
	$route['admin-login/(:any)'] = 'C_admin_login/index';

	$route['admin'] = "C_admin/index";
	$route['admin/(:any)'] = 'C_admin/index';
	
	$route['admin-logout'] = "C_admin_login/logout";
	$route['admin-logout/(:any)'] = 'C_admin_login/logout';

	$route['admin-profile'] = "C_admin/profile";
	$route['admin-profile/(:any)'] = 'C_admin/profile';
	
	$route['admin-profile-simpan'] = "C_admin/simpan_profile";
	$route['admin-profile-simpan/(:any)'] = 'C_admin/simpan_profile';
//LOGIN

//KATEGORI UKURAN
	$route['admin-kat-ukuran'] = "C_admin_kat_ukuran/index";
	$route['admin-kat-ukuran/(:any)'] = 'C_admin_kat_ukuran/index';

	$route['admin-kat-ukuran-simpan'] = "C_admin_kat_ukuran/simpan";
	$route['admin-kat-ukuran-simpan/(:any)'] = 'C_admin_kat_ukuran/simpan';

	$route['admin-kat-ukuran-hapus'] = "C_admin_kat_ukuran/hapus";
	$route['admin-kat-ukuran-hapus/(:any)'] = 'C_admin_kat_ukuran/hapus';
//KATEGORI UKURAN

//UKURAN
	$route['admin-ukuran'] = "C_admin_ukuran/index";
	$route['admin-ukuran/(:any)'] = 'C_admin_ukuran/index';

	$route['admin-ukuran-simpan'] = "C_admin_ukuran/simpan";
	$route['admin-ukuran-simpan/(:any)'] = 'C_admin_ukuran/simpan';

	$route['admin-ukuran-hapus'] = "C_admin_ukuran/hapus";
	$route['admin-ukuran-hapus/(:any)'] = 'C_admin_ukuran/hapus';
//UKURAN

//KATEGORI PRODUK
	$route['admin-kat-produk'] = "C_admin_kat_produk/index";
	$route['admin-kat-produk/(:any)'] = 'C_admin_kat_produk/index';

	$route['admin-kat-produk-simpan'] = "C_admin_kat_produk/simpan";
	$route['admin-kat-produk-simpan/(:any)'] = 'C_admin_kat_produk/simpan';

	$route['admin-kat-produk-hapus'] = "C_admin_kat_produk/hapus";
	$route['admin-kat-produk-hapus/(:any)'] = 'C_admin_kat_produk/hapus';
//KATEGORI PRODUK

//PRODUK
	$route['admin-produk'] = "C_admin_produk/index";
	$route['admin-produk/(:any)'] = 'C_admin_produk/index';

	$route['admin-produk-simpan'] = "C_admin_produk/simpan";
	$route['admin-produk-simpan/(:any)'] = 'C_admin_produk/simpan';

	$route['admin-produk-hapus'] = "C_admin_produk/hapus";
	$route['admin-produk-hapus/(:any)'] = 'C_admin_produk/hapus';
//PRODUK

//PRODUK GAMBAR
	$route['admin-produk-gambar'] = "C_admin_images/index";
	$route['admin-produk-gambar/(:any)'] = 'C_admin_images/index';

	$route['admin-produk-gambar-simpan'] = "C_admin_images/simpan";
	$route['admin-produk-gambar-simpan/(:any)'] = 'C_admin_images/simpan';

	$route['admin-produk-gambar-hapus'] = "C_admin_images/hapus";
	$route['admin-produk-gambar-hapus/(:any)'] = 'C_admin_images/hapus';
	$route['admin-produk-gambar-hapus/(:any)/(:any)'] = 'C_admin_images/hapus';
//PRODUK GAMBAR


//MEMBER
	$route['admin-member'] = "C_admin_member/index";
	$route['admin-member/(:any)'] = 'C_admin_member/index';

	$route['admin-member-simpan'] = "C_admin_member/simpan";
	$route['admin-member-simpan/(:any)'] = 'C_admin_member/simpan';

	$route['admin-member-hapus'] = "C_admin_member/hapus";
	$route['admin-member-hapus/(:any)'] = 'C_admin_member/hapus';
//MEMBER

//HARGA
	$route['admin-harga-satuan'] = "C_admin_harga_satuan/index";
	$route['admin-harga-satuan/(:any)'] = "C_admin_harga_satuan/index";
//HARGA

//DISKON PRODUK
	
	$route['admin-diskon-produk'] = "C_admin_diskon_produk/index";
	$route['admin-diskon-produk/(:any)'] = "C_admin_diskon_produk/index";
		
 	$route['admin-diskon-produk-simpan'] = "C_admin_diskon_produk/simpan";
	$route['admin-diskon-produk-simpan/(:any)'] = 'C_admin_diskon_produk/simpan';

	$route['admin-diskon-produk-hapus'] = "C_admin_diskon_produk/hapus";
	$route['admin-diskon-produk-hapus/(:any)'] = 'C_admin_diskon_produk/hapus';
	
//DISKON PRODUK


//PENJUALAN
	$route['history-penjualan'] = "C_admin_history_penjualan/index";
	$route['history-penjualan/(:any)'] = "C_admin_history_penjualan/index";
	
	//PENJUALAN
	
	

//AKUN
	$route['admin-akun'] = "C_admin_akun/index";
	$route['admin-akun/(:any)'] = 'C_admin_akun/index';

	$route['admin-simpan'] = "C_admin_akun/simpan";
	$route['admin-simpan/(:any)'] = 'C_admin_akun/simpan';

	$route['admin-akun-hapus'] = "C_admin_akun/hapus";
	$route['admin-akun-hapus/(:any)'] = 'C_admin_akun/hapus';
//AKUN




