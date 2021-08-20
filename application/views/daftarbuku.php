<?php
defined('BASEPATH') or exit('No direct Script access allowed');
{
	function index(){
		$data['buku'] = $this->M_perpus->get_data('buku')->result();
}
}
?>