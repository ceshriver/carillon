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
		echo "Script";	
	}
	
	
	public function searchLibrary() {
		$query = $_POST['query'];

		$queryObject = $this->db->select('title, composer, year, genre, filedunder, collection, octaves, arranger,copies,time');
		$this->db->where("title LIKE '%$query%' OR content LIKE '%$query%'");
		
		echo $queryObject->result();
	}
}

/* End of file homepage.php */
/* Location: ./application/controllers/homepage.php */