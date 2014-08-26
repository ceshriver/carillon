<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {

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
		//authenticate
		$this->load->model('auth_model');
		$netid = $this->auth_model->ID();
		$me = $this->auth_model->auth();
		
		//Load headers
		$this->load->helper('url');
		$this->load->helper('form');
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		$data['guildData'] = $this->db->get('guildies');
		
		$data['ringData'] = $this->db->get('rings');
		
		//$this->db->order_by("day","asc");
		//$this->db->order_by("hour","asc");
		//$this->db->order_by("minute","asc");
		
		
		//$data['ringData'] = $this->db->get('rings');
		//$data['tableData'] = $this->tableData($data['guildData']->result(),$data['ringData']->result());
		
		$settingsRaw = $this->db->get('settings')->result();
		for ($i=0;$i<count($settingsRaw);$i++) {
			$settings[$settingsRaw[$i]->key] = $settingsRaw[$i]->value;
		}
		
		//echo "<pre>";
		//print_r($settings);
		//echo "</pre>";
		//return;
		
		$settings["heel-start-month"];
		
		$data['heelstart'] = array("month"=>$settings["heel-start-month"],"day"=>$settings["heel-start-day"],"year"=>$settings["heel-start-year"]);
	    $data['audition'] = array("month"=>$settings["audition-month"],"day"=>$settings["audition-day"],"year"=>$settings["audition-year"]);
	    $data['heelstarthour'] = $settings["heelstarthour"];
	    $data['season'] = $settings["season"];
		
		
		//Load the settings page
		$data['user'] = $me;
		$data['content'] = $this->load->view('settings_view', $data, TRUE);
		$this->load->view('page_view',$data);	
	}

	public function settingsUpdate() {
		$this->load->model('auth_model');
		if (!$netid = $this->auth_model->ID()) return;
		$me = $this->auth_model->auth();
		$this->auth_model->secure($me,1);
		
		$this->load->helper('url');
		$this->load->helper('form');

		
		foreach($_POST as $key=>$value) {
			//echo "Updating Key: $key with new value: $value <br>";
			$newValue = array("value"=>$value);
			$this->db->where("key", $key);
			$this->db->update('settings', $newValue);
		}
		redirect('settings');	
	}


	public function resetHeelManager() {
		$this->load->model('auth_model');
		$netid = $this->auth_model->ID();
		$me = $this->auth_model->auth();
		$this->db->empty_table('heelers');
		$this->db->empty_table('bookedslots');
		redirect('settings');
	}

}

/* End of file homepage.php */
/* Location: ./application/controllers/homepage.php */