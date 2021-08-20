<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
	function construct()
	{
		parent:: __construct();
		#cek Login
		if($this->session->userdate('status') != "login"){
			redirect(base_url(). 'welcome?pesan=belumlogin');
		}
	}

	function index(){
		$data['transaksi'] = $this->db->query("SELECT * FROM transaksi ORDER BY id_pinjam DESC LIMIT 10")->result();
		$data['anggota'] = $this->db->query("SELECT * FROM anggota ORDER BY id_anggota DESC LIMIT 10")->result();
		$data['alatmusik'] = $this->db->query("SELECT * FROM alatmusik ORDER BY kode_alat DESC LIMIT 10")->result();

		$this->load->view('admin/header');
		$this->load->view('admin/index',$data);
		$this->load->view('admin/footer');
	}

	function logout(){
		$this->session->sess_destroy();
		redirect(base_url().'welcome?pesan=logout');
	}

 	function ganti_password(){
 		$this->load->view('admin/header');
 		$this->load->view('admin/ganti_password');
 		$this->load->view('admin/footer');
	}

 	function ganti_password_act(){
 		$pass_baru = $this->input->post('pass_baru');
 		$ulang_pass = $this->input->post('ulang_pass');

 		$this->form_validation->set_rules('pass_baru','Password Baru','required|matches[ulang_pass}');
 		$this->form_validation->set_rules('ulang_pass','Ulangi Password Baru','required');
 		if($this->form_validation->run() != false){
 			$data = array('password' =>md5($pass_baru));
 			$w = array('id_admin' => $this->session->userdata('id'));
 			$this->M_perpus->update_data($w,$data,'admin');
 			redirect(base_url().'admin/ganti_password?pesan=berhasil');
 		}else{
 			$this->load->view('admin/header');
 			$this->load->view('admin/ganti_password');
 			$this->load->view('admin/footer');
 			}
	}

	function alatmusik(){
		$data['alatmusik'] = $this->M_perpus->get_data('alatmusik')->result();
		$this->load->view('admin/header');
 		$this->load->view('admin/alatmusik',$data);
 		$this->load->view('admin/footer');
	}

	function tambah_alatmusik(){
		$data['kategori'] = $this->M_perpus->get_data('kategori')->result();
		$this->load->view('admin/header');
 		$this->load->view('admin/tambahalatmusik',$data);
 		$this->load->view('admin/footer');
 	}

 	function tambah_alat_act(){
 		$kode_alat = $this->input->post('kode_alat');
 		$nama_alat = $this->input->post('nama_alat');
 		$jenis_alat = $this->input->post('jenis_alat');
 		$harga_sewa = $this->input->post('harga_sewa');
 		$jumlah_alat = $this->input->post('jumlah_alat');
 		$status = $this->input->post('status');
 		$this->form_validation->set_rules('kode_alat','Kategori','required');
 		$this->form_validation->set_rules('nama_alat','Nama Alat','required');
 		$this->form_validation->set_rules('status','Status alat','required');
 		if($this->form_validation->run() != false){
 			// configurasi upload gambar
 			$config['upload_path'] = './assets/upload/';
 			$config['allowed_types'] = 'jpg|png|jpeg';
 			$config['max_size'] = '2048';
 			$config['file_name'] = 'gambar'. time();

 			$this->load->library('upload',$config);

 			if($this->upload->do_upload('foto')){
 				$image = $this->upload->data();

 				$data = array(
 					'kode_alat' => $kode_alat,
 					'nama_alat' => $nama_alat,
 					'jenis_alat' => $jenis_alat,
 					'harga_sewa' => $harga_sewa,
 					'jumlah_alat' => $jumlah_alat,
 					'gambar' => $image['file_name'],
 					'status_alat' => $status
 					);
 					$this->M_perpus->insert_data($data,'alatmusik');
 					redirect(base_url().'admin/alatmusik');
 				}else{
 					$this->load->view('admin/header');
 					$this->load->view('admin/tambahalatmusik');
 					$this->load->view('admin/footer');
 				}
 			}
 		}

 	function hapus_alat($id){
 		$where = array('kode_alat' => $id);
 		$this->M_perpus->delete_data($where,'alatmusik');
 		redirect(base_url().'admin/alatmusik');
 	}

 	function edit_alat($id){
        $where = array('kode_alat' =>$id);
        $data['alatmusik'] = $this->db->query("select * from alatmusik B, kategori K where B.id_kategori=K.id_kategori and B.kode_alat='$id'")->result();
        $data['kategori'] =$this->M_perpus->get_data('kategori')->result();

        $this->load->view('admin/header');
        $this->load->view('admin/editalat',$data);
        $this->load->view('admin/footer');
    }
	
	function anggota(){
        $data['anggota'] = $this->M_perpus->get_data('anggota')->result();
        $this->load->view('admin/header');
        $this->load->view('admin/anggota',$data);
        $this->load->view('admin/footer');
        }

    function tambah_anggota(){
        $this->load->view('admin/header');
        $this->load->view('admin/tambahanggota');
        $this->load->view('admin/footer');
        }

    function tambah_anggota_act(){
        $nama_anggota = $this->input->post('nama_anggota');
        $gender = $this->input->post('gender');
        $no_telp = $this->input->post('no_telp');
        $alamat = $this->input->post('alamat');
		$email = $this->input->post('email');
		$username = $this->input->post('username');
        $password = $this->input->post('password');
        $this->form_validation->set_rules('nama_anggota','Nama Anggota','required');
        $this->form_validation->set_rules('no_telp','No.Telpon','required');
        $this->form_validation->set_rules('alamat','Alamat','required');
		$this->form_validation->set_rules('email','Email','required');
		$this->form_validation->set_rules('username','Username','required');
        $this->form_validation->set_rules('password','Password','required');
    if($this->form_validation->run() != false){
        $data = array(
        'nama_anggota' => $nama_anggota,
        'gender' => $gender,
        'no_telp' => $no_telp,
        'alamat' => $alamat,
		'email' => $email,
		'username' => $username,
        'password' => $password,
        );
        $this->M_perpus->insert_data($data,'anggota');
        redirect(base_url().'admin/anggota');
    }else{
        $this->load->view('admin/header');
        $this->load->view('admin/tambahanggota');
        $this->load->view('admin/footer');
        }
		}
	
		function edit_anggota($id){
			$where = array('id_anggota' =>$id);
			$data['anggota'] = $this->db->query("select * from anggota where id_anggota='$id'")->result();
			$this->load->view('admin/header');
			$this->load->view('admin/editanggota',$data);
			$this->load->view('admin/footer');
			}
	
		function update_anggota(){
			$id = $this->input->post('id');
			$nama_anggota = $this->input->post('nama_anggota');
			$gender = $this->input->post('gender');
			$penerbit = $this->input->post('penerbit');
			$no_telp = $this->input->post('no_telp');
			$alamat = $this->input->post('alamat');
			$email = $this->input->post('email');
			$username = $this->input->post('username');
			$password = $this->input->post('password');
				$this->form_validation->set_rules('nama_anggota','Nama Anggota','required');
				$this->form_validation->set_rules('no_telp','No.Telpon','required');
				$this->form_validation->set_rules('alamat','Alamat','required');
				$this->form_validation->set_rules('email','Email','required');
				$this->form_validation->set_rules('username','Username','required');
				$this->form_validation->set_rules('password','Password','required');
			if($this->form_validation->run() != false){
				$where = array('id_anggota' => $id);
				$data = array(
				'nama_anggota' => $nama_anggota,
				'gender' => $gender,
				'no_telp' => $no_telp,
				'alamat' => $alamat,
				'email' => $email,
				'username' => $username,
				'password' => $password,
			);
			$this->M_perpus->update_data('anggota',$data,$where);
			redirect(base_url().'admin/anggota');
			} else{
				$where = array('id_anggota' =>$id);
				$data['anggota'] = $this->db->query("select * from anggota where id_anggota='$id'")->result();
			
			$this->load->view('admin/header');
			$this->load->view('admin/editanggota',$data);
			$this->load->view('admin/footer');
			}
			}

		function peminjaman(){
				$data['peminjaman'] = $this->db->query("SELECT * FROM transaksi T, alatmusik B, anggota A WHERE T.kode_alat = B.kode_alat and T.id_anggota = A.id_anggota")->result();
				$this->load->view('admin/header');
				$this->load->view('admin/peminjaman',$data);
				$this->load->view('admin/footer');
			}
	
		function tambah_peminjaman(){
				$w = array('status_alat'=>'1');
				$data['alatmusik'] = $this->M_perpus->edit_data($w,'alatmusik')->result();
				$data['anggota'] = $this->M_perpus->get_data('anggota')->result();
				$data['peminjaman'] = $this->M_perpus->get_data('transaksi')->result();
					$this->load->view('admin/header');
					$this->load->view('admin/tambah_peminjaman',$data);
					$this->load->view('admin/footer');
			}
	
		function tambah_peminjaman_act(){
				$tgl_pencatatan = date('Y-m-d H:i:s');
				$anggota = $this->input->post('anggota');
				$kode_alat = $this->input->post('alatmusik');
				$tgl_pinjam = $this->input->post('tgl_pinjam');
				$tgl_kembali = $this->input->post('tgl_kembali');
				$denda = $this->input->post('denda');
			$this->form_validation->set_rules('anggota','Anggota','required');
			$this->form_validation->set_rules('kode_alat','alatmusik','required');
			$this->form_validation->set_rules('tgl_pinjam','Tanggal Pinjam','required');
			$this->form_validation->set_rules('tgl_kembali','Tanggal Kembali','required');
			$this->form_validation->set_rules('denda','Denda','required');
			if($this->form_validation->run() != false){
				$data = array(
				'tgl_pencatatan' => $tgl_pencatatan,
				'id_anggota' => $anggota,
				'kode_alat' => $alatmusik,
				'tgl_pinjam' => $tgl_pinjam,
				'tgl_kembali' => $tgl_kembali,
				'denda' => $denda,
				'tgl_pengembalian' => '0000-00-00',
				'total_denda' => '0',
				'status_pengembalian' =>'0',
				'status_peminjaman' =>'0'
			);
				$this->M_perpus->insert_data($data,'transaksi');
				$d = array('status_alatmusik' =>'0','tgl_input' =>substr($tgl_pencatatan,0,10));
				$w = array('kode_alat' => $alatmusik);
				$this->M_perpus->update_data('alatmusik',$d,$w);
				redirect(base_url().'admin/peminjaman');
			}else{
				$w = array('status_alatmusik' => '1');
				$data['alatmusik'] = $this->M_perpus->edit_data($w,'alatmusik')->result();
				$data['anggota'] = $this->M_perpus->get_data('anggota')->result();
					$this->load->view('admin/header');
					$this->load->view('admin/tambah_peminjaman',$data);
					$this->load->view('admin/footer');
			}
			}

		function transaksi_hapus($id){
				$w = array('id_pinjam'=>$id);
				$data = $this->M_perpus->edit_data($w,'transaksi')->row();
				$ww = array('kode_alat'=>$data->kode_alat);
				$data2 = array('status_alatmusik'=>'1');
				$this->M_perpus->update_data('alatmusik',$data2,$ww);
				$this->M_perpus->delete_data($w,'transaksi');
				redirect(base_url().'admin/peminjaman');
		}

		function transaksi_selesai($id){
				$data['alatmusik'] = $this->M_perpus->get_data('alatmusik')->result();
				$data['anggota'] = $this->M_perpus->get_data('anggota')->result();
				$data['peminjaman'] = $this->db->query("SELECT * FROM peminjaman P, detail_pinjam D, anggota A, alatmusik B WHERE T. kode_alat = B.kode_alat AND T.id_anggota = A.id_anggota AND T.id_pinjam = '$id'")-result();
				$this->load->view('admin/header');
				$this->load->view('admin/transaksi_selesai',$data);
				$this->load->view('admin/footer');
		}

		function transaksi_selesai_act(){
				$id = $this->input->post('id');
				$tgl_dikembalikan = $this->input->post('tgl_dikembalikan');
				$tgl_kembali = $this->inpus->post('tgl_kembali');
				$alatmusik = $this->input->post('alatmusik');
				$denda = $this->input->post('denda');
				$this->form_validation->set_rules('tgl_dikembalikan','Tanggal dikembalikan','required');
					if($this->form_validation->run()!=false){
						//hitung selisih hari
						$batas_kembali = strtotime($tgl_kembali);
						$dikembalikan = strtotime($tgl_dikembalikan);
						$selisih = abs(($batas_kembali - $dikembalikan)/(60*60*24));
						$total_denda = $denda*$selisih;

						//update status Pinjaman
						$data = array('status_peminjaman' =>'1','total_denda' =>$total_denda,'tgl_pengembalian' =>$tgl_dikembalikan,'status_pengembalian' =>'1');
						$w = array('id_pinjam' =>$id);
						$this->M_perpus->update_data('peminjaman',$data,$w);
					}
		}
	}
?>