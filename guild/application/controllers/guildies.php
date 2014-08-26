<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Guildies extends CI_Controller {

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
		$this->auth_model->secure($me,0);
		
		$this->load->helper('url');
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		
		$this->db->order_by("year", "desc");
		$data['guildData'] = $this->db->get('guildies');
		
		$data['guildie'] = $me;
		
		$data['ringData'] = $this->db->get('rings');
		$data['content'] = $this->load->view('guildies_view', $data, TRUE);
		$this->load->view('page_view',$data);	
	}
	
	
	
	public function guildie() {

		$this->load->model('auth_model');
		if (!$netid = $this->auth_model->ID()) return;
		$me = $this->auth_model->auth();
		$this->auth_model->secure($me,1);
		
		
		$this->load->helper('url');
		
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		
		$this->db->where('id', $this->uri->segment(3));
		
		$query = $this->db->get('guildies');
		
		
		if ($query->num_rows() == 0) {
			redirect("guildies");
			return;
		}
		$guildies = $query->result();
		$data['guildie'] = $guildies[0];
		
		$data['content'] = $this->load->view('guildie_entry_view', $data, TRUE);
		$this->load->view('page_view',$data);
	}
	
	public function edit() {
		$this->load->model('auth_model');
		$netid = $this->auth_model->ID();
		$me = $this->auth_model->auth();
		
		
		$this->editGuildie(FALSE,0);
	}
	
	private function editGuildie($error,$i) {
	
		$this->load->model('auth_model');
		if (!$netid = $this->auth_model->ID()) return;
		$me = $this->auth_model->auth();
		$this->auth_model->secure($me,1);
	
		$this->load->helper('url');
		$this->load->helper('form');
		
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		
		
		
		$entryID = $this->uri->segment(3);
		if (!$entryID) $entryID = $i;
		
		if ($entryID) {
			$this->db->where('id', $entryID);
			$query = $this->db->get('guildies');
		}
		
		if ($entryID) {
			$entries = $query->result();
			$data['guildie'] = $entries[0];
			if ($query->num_rows() == 0) {
				redirect("guildies");
				return;
			}
		} else {
			$data['guildie'] = FALSE;
		}
		
		$data['error'] = $error;
		$data['rings'] = $this->db->get('rings')->result();
		
		/* Load What they Play */
		if ($entryID) {
			$this->db->where('guildieID',$entryID);
			$pieceIDs = $this->db->get('guildiepieces')->result();
			
			$pieceArray = array();
			for($i=0;$i<count($pieceIDs);$i++) {
				$this->db->where('id', $pieceIDs[$i]->libraryID);
				
				$matchedPieces = $this->db->get('library')->result();
				$pieceArray[$i] = $matchedPieces[0];
			}
			$data['pieces'] = $pieceArray;
		}
		
		$data['content'] = $this->load->view('guildie_edit_view', $data, TRUE);
		$this->load->view('page_view',$data);
	}
	
	public function add() {
		$this->load->model('auth_model');
		if (!$netid = $this->auth_model->ID()) return;
		$me = $this->auth_model->auth();
		$this->auth_model->secure($me,4);
		
		$this->load->helper('url');
		$this->load->helper('form');
		
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		
		$data['guildie'] = FALSE;
		$data['rings'] = $this->db->get('rings')->result();
		$data['content'] = $this->load->view('guildie_edit_view', $data, TRUE);
		
		$this->load->view('page_view',$data);
	}
	
	public function remove() {
		$this->load->model('auth_model');
		if (!$netid = $this->auth_model->ID()) return;
		$me = $this->auth_model->auth();
		$this->auth_model->secure($me,4);
		
		
		$this->load->helper('url');
		$this->load->helper('form');
		
		$this->db->where('id', $this->uri->segment(3));
		$this->db->delete('guildies');
		
		redirect('guildies');
	}
	
	public function guildieUpdate() {
		$this->load->model('auth_model');
		if (!$netid = $this->auth_model->ID()) return;
		$me = $this->auth_model->auth();
		$this->auth_model->secure($me,1);
		
		
		$this->load->helper('url');
		$this->load->helper('form');
		
		if ($_POST['firstname'] == '') {
			$this->editGuildie('All members must have a name!',$_POST['id']);
			return;
		}
		
		unset($_POST['update']);
		
		if (!isset($_POST['active'])) {
		    $_POST['active'] = 0;
		} else {
		    $_POST['active'] = 1;
		}
		
		$this->db->where('guildieID', $_POST['id']);
		$this->db->delete('guildiepieces');
		
		if (isset($_POST['pieces'])) {
			$pieces = $_POST['pieces'];
			for ($i=0;$i<count($_POST['pieces']);$i++) {
				
				$guildiePieces = array('guildieID' => $_POST['id'], 'libraryID'=>$_POST['pieces'][$i]);
				//print_r($guildiePieces);
				$this->db->insert('guildiepieces', $guildiePieces);
			}
			unset($_POST['pieces']);
		}
		
		if ($_POST['id'] == 0) {
			//Add the entry
			$this->db->insert('guildies', $_POST);
		} else {
			$this->db->where('id', $_POST['id']); 
			$this->db->update('guildies', $_POST);
		}
		
			
		redirect('guildies');
	}
	
	
	public function search() {
		//$netid = $this->ID();
		//$this->auth();
		
		$searchString = "";
		$searchString = $_POST['query'];
		if ($searchString == "") $searchString = FALSE;


		$full = 0;
		if (isset($_POST['full'])) $full = $_POST['full'];
		
		$active = 0;
		if (isset($_POST['active'])) $active = $_POST['active'];

		$sortCol = "year";
		if ($sortCol) {
			$this->db->order_by($sortCol, "desc");
		}
				
		if ($searchString) {
			$this->db->where("firstname LIKE '%$searchString%'
								OR lastname LIKE '%$searchString%'
								OR year LIKE '%$searchString%'	
			");
			
		}
		
		if ($active) $this->db->where('active','1');
		$results = $this->db->get('guildies')->result();
		
		if ($full) {
			$rings = $this->db->get('rings')->result();
			
			$this->db->where("key", "season");
			$preference = $this->db->get("settings")->result();
			$season = $preference[0]->value;
			
			$theYear = date("Y");
			
			$seasonTitle = $seasonTitle = $season." ".$theYear;
			
			$results = array('season'=> $seasonTitle, 'guildies'=>$results,'rings'=>$rings);
		}
		
		
		
		$results = json_encode($results);
		
		$data = array("content" => $results);
		$this->load->view('script_view', $data);
	}
	
	public function uploadimage() {
		$this->load->model('auth_model');
		if (!$netid = $this->auth_model->ID()) return;
		$me = $this->auth_model->auth();
		$this->auth_model->secure($me,1);
		
		$this->load->helper('url');
		$this->load->helper('form');
		
		$config['upload_path'] = './assets/images/GuildPhotos/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '1000';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if ( ! $this->upload->do_upload('file') ) {
			echo "";
		} else {
			$uploadData = $this->upload->data();
			echo $uploadData['file_name'];
		}
	}
	
	
	
	
	
	/* Ring Manager Hooks */
	
	
	public function RM_Guildies() {		
		if (1) $this->db->where('active','1');
		$results = $this->db->get('guildies')->result();
		$results = json_encode($results);
		
		$data = array("content" => $results);
		$this->load->view('script_view', $data);
	}
	
	
	/* End Ring Manager Hooks */
	
	
	
	
	
	
}

/* End of file homepage.php */
/* Location: ./application/controllers/homepage.php */