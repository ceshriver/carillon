<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {

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
	
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->library('curl');
		
		
		if (isset($_GET['ticket'])) {
			$ticket = $_GET['ticket'];
			$service = $_GET['service'];
			//curl the ticket and the service ->
			$this->load->library('curl');
			
			
			$serviceCAS = base_url()."index.php/auth?service=".$service;
			
			$validateURL = "https://secure.its.yale.edu/cas/serviceValidate?ticket=".$ticket."&service=".$serviceCAS;
			//echo $validateURL;
			//$validateURL = "http://www.apple.com";
			$netid = $this->curl->simple_get($validateURL);
			//Parse XML File 
			//Parse Net ID
			//$netID = "";
			//Save NET ID IN Session
			$this->session->set_userdata('netid', $netid);
			//echo "<br>Redirecting to: ". rawurldecode($service);
			redirect($service);
			return;
		}
		echo "Login Failed";
		$this->session->set_userdata('netid', FALSE);
	}
}
/* End of file homepage.php */
/* Location: ./application/controllers/homepage.php */