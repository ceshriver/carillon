<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schedule extends CI_Controller {

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
		$netid = $this->auth_model->ID();
		$me = $this->auth_model->auth();
		
		$this->load->helper('url');
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		$data['guildData'] = $this->db->get('guildies');
		
		$this->db->order_by("day","asc");
		$this->db->order_by("hour","asc");
		$this->db->order_by("minute","asc");
		
		$data['user'] = $me;
		$data['ringData'] = $this->db->get('rings');
		
		$data['tableData'] = $this->tableData($data['guildData']->result(),$data['ringData']->result());
		
		$data['content'] = $this->load->view('schedule_view', $data, TRUE);
		$this->load->view('page_view',$data);	
	}
	
	private function tableData($guildies, $rings) {
		$finalData = array();
		for ($i=0;$i<count($guildies);$i++) {
			
			for ($p=0;$p<count($rings);$p++) {
				if (isset($guildies[$i]->ringID) && $rings[$p]->id == $guildies[$i]->ringID) {
					$ring = $rings[$p];
					$a = false;
					if (isset($finalData[$ring->day][$ring->hour==12])) $a = $finalData[$ring->day][$ring->hour==12];
					if (!$a) $a = array();
					$a[count($a)] = $guildies[$i];
					$finalData[$ring->day][$ring->hour==12] = $a;
					break;
				}
			}
			
		}
		return $finalData;
	}
	
	
	
	public function ring() {
		$this->load->model('auth_model');
		$netid = $this->auth_model->ID();
		$me = $this->auth_model->auth();
		
		$this->load->helper('url');
		
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		
		$this->db->where('id', $this->uri->segment(3));
		
		$query = $this->db->get('rings');
		
		
		if ($query->num_rows() == 0) {
			redirect("schedule");
			return;
		}
		$rings = $query->result();
		$data['ring'] = $rings[0];
		
		$data['content'] = $this->load->view('schedule_entry_view', $data, TRUE);
		$this->load->view('page_view',$data);
	}
	
	public function edit() {
		$this->load->model('auth_model');
		$netid = $this->auth_model->ID();
		$me = $this->auth_model->auth();
		
		$this->editRing(FALSE,0);
	}
	
	private function editRing($error,$i) {
		$this->load->model('auth_model');
		$this->load->helper('url');
		$this->load->helper('form');
		
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		
		
		
		$entryID = $this->uri->segment(3);
		if (!$entryID) $entryID = $i;
		
		if ($entryID) {
			$this->db->where('id', $entryID);
			$query = $this->db->get('rings');
		}
		
		if ($entryID) {
			$entries = $query->result();
			$data['ring'] = $entries[0];
			if ($query->num_rows() == 0) {
				redirect("guildies");
				return;
			}
		} else {
			$data['ring'] = FALSE;
		}
		
		$data['error'] = $error;
		$data['guildies'] = $this->db->get('guildies')->result();
		$data['content'] = $this->load->view('schedule_edit_view', $data, TRUE);
		$this->load->view('page_view',$data);
	}
	
	public function add() {
		$this->load->model('auth_model');
		$netid = $this->auth_model->ID();
		$me = $this->auth_model->auth();
		
		$this->load->helper('url');
		$this->load->helper('form');
		
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		
		$data['ring'] = FALSE;
		$data['guildie'] = $this->db->get('rings')->result();
		$data['content'] = $this->load->view('schedule_edit_view', $data, TRUE);
		
		$this->load->view('page_view',$data);
	}
	
	public function remove() {
		$this->load->model('auth_model');
		$netid = $this->auth_model->ID();
		$me = $this->auth_model->auth();
		
		$this->load->helper('url');
		$this->load->helper('form');
		
		$this->db->where('id', $this->uri->segment(3));
		$this->db->delete('rings');
		
		redirect('schedule');
	}
	
	public function ringUpdate() {
		$this->load->model('auth_model');
		$netid = $this->auth_model->ID();
		$me = $this->auth_model->auth();
		
		$this->load->helper('url');
		$this->load->helper('form');
		
		if ($_POST['day'] == '' || $_POST['time'] == '') {
			$this->editRing('All rings must have a day and time!',$_POST['id']);
			return;
		}
		unset($_POST['update']);
		
		$dictionary = $this->timeValuesFromString($_POST['time']);
		
		if (isset($_POST['duration'])) $dictionary['duration'] = $_POST['duration'];
		$dictionary['day'] = $_POST['day'];
		
		if ($_POST['id'] == 0) {
			//Add the entry
			$this->db->insert('rings', $dictionary);
		} else {
			$this->db->where('id', $_POST['id']); 
			$this->db->update('rings', $dictionary);
		}
		redirect('schedule');
	}
	
	function saveSchedule() {
		$this->load->library('session');
		$this->load->model('auth_model');
		$netid = $this->auth_model->ID();
		$me = $this->auth_model->auth();
		
		$postArray = $_POST;
		$postArrayKeys = array_keys($postArray);
		//print_r($postArrayKeys);
		
		//First take care of guildie removals from the ring schedule
		for ($i=0;$i<count($postArrayKeys);$i++) {
			$key = $postArrayKeys[$i];
			if (strpos($key,'remove') !== false) {
				//Need to remove this guildie's ring
				//Find the guildie ID
				//[remove_14]
				//echo $postArray[$key];
				//print_r($postArray);
				$guildieID = $postArray[$key];
				$this->db->where('id',$guildieID);
				$dict['ringID'] = 0;
				//echo $guildieID;
				$this->db->update('guildies',$dict);
			}
		}
		//Next take care of guildie additions to the ring schedule
		
		
		//print_r($postArrayKeys);
		for ($i=0;$i<count($postArrayKeys);$i++) {
			$key = $postArrayKeys[$i];
			//echo $key;
			if (strpos($key,'add') !== false) {
				//Need to add this guildie to a ring
				//[add_13_0]
				// 13 ring id
				// 0 guildie id
				$elements = explode("_",$key);
				//print_r($elements);
				$ringID = $elements[1];
				
				$guildieID = $postArray[$key];
				
				/*
				echo $guildieID;
				echo "<br>";
				echo $ringID;
				*/
				$dict['ringID'] = $ringID;
				$this->db->where("id",$guildieID);
				$this->db->update('guildies',$dict);
			}
		}
		 // => [remove_14] => [add_14_0] => 2
		redirect("schedule");
	}
	

	private function timeValuesFromString($string) {
		$components = explode(':',$string);
		$hour = $components[0];
		$components = explode(' ', $components[1]);
		$minute = $components[0];
		$ampm = $components[1];
		if ($ampm == "PM" && $hour < 12) $hour+=12;
		return array('hour'=>$hour,'minute'=>$minute);
	}

	//public function testTime() {
	//	echo print_r($this->timeValuesFromString("5:43 PM"));
	//}

	
	private function ID() {
		$this->load->model('auth_model');
		$netid = $this->auth_model->ID();
		$me = $this->auth_model->auth();
		$netID = $this->session->userdata('netid');
		if (!$netID) {
			//Redirect to CAS login page
			//$thisPage = rawurlencode($_SERVER['PATH_INFO']);
			$service = base_url()."index.php/auth?service=".$thisPage;
			//echo $service;
			$service = rawurlencode($service);
			header('Location:'. "https:/secure.its.yale.edu/cas/login?service=".$service);
		} else {
			return $netID;
		}
	}
	
	private function auth() {
		$this->load->library('session');
		$this->load->helper('url');
		$netID = $this->session->userdata('netid');
		//Is this person authenticated? check if they're in the guildie database
		$p = xml_parser_create();
		xml_parse_into_struct($p, $netID, $vals, $index);
		xml_parser_free($p);
		$netIDString = $vals[2]['value'];
		//echo "'".$netIDString."'";
		$this->db->where('netid',$netIDString);
		$results = $this->db->get('guildies')->result();
		//print_r($results);
		if (count($results) > 0) {
		} else {
			header('Location:'.base_url()."index.php/noaccess");
		}
	}
	
	/* Ring Manager Hooks */
	
	
	public function RM_Rings() {		
		$results = $this->db->get('rings')->result();
		$results = json_encode($results);	
		$data = array("content" => $results);
		$this->load->view('script_view', $data);
	}
	
	
	/* End Ring Manager Hooks */
	
	
}

/* End of file homepage.php */
/* Location: ./application/controllers/homepage.php */