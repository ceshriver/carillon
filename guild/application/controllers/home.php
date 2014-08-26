<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	 
	public function index()
	{
		$this->load->model('auth_model');
		if (!$netid = $this->auth_model->ID()) return;
		$me = $this->auth_model->auth();
		$this->auth_model->secure($me,1);
		
		$this->load->helper('url');
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		$data['content'] = $this->load->view('home_view', '', TRUE);
		$this->load->view('page_view',$data);	
	}
	
	public function noaccess() {
		$this->load->helper('url');
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		$data['content'] = $this->load->view('noaccess_view', '', TRUE);
		$this->load->view('nomenu_page_view',$data);
	}
	
	public function logout() {
		$this->load->library('session');
		$this->session->set_userdata('netid', FALSE);
		header('Location:https://secure.its.yale.edu/cas/logout');
		//echo "Logged Out";
	}
	
	
	public function imitate($netid=false) {
		$this->load->helper('url');
		$this->load->library('session');
		$this->session->set_userdata('imitateNetID',$netid);
		redirect(base_url());
	}
	
}

/* End of file homepage.php */
/* Location: ./application/controllers/homepage.php */