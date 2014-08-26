<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends CI_Controller {

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
		$this->auth_model->secure($me,2);
		
		$data['guildie'] = $me;
		
		$this->load->helper('url');
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		$this->db->order_by("id","desc");
		$data['newsData'] = $this->db->get('news')->result();
		$this->db->order_by("year", "desc");
		$data['guildies'] = $this->db->get('guildies')->result();
		$data['content'] = $this->load->view('news_view', $data, TRUE);
		$this->load->view('page_view',$data);	
	}
	
	
	/* This shouldn't get called - guildies can only edit, not view
	public function item() {
		$this->load->helper('url');
		
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		
		$this->db->where('id', $this->uri->segment(3));
		
		$query = $this->db->get('news');
		
		
		if ($query->num_rows() == 0) {
			redirect("news");
			return;
		}
		$rings = $query->result();
		$data['story'] = $rings[0];
		
		$data['content'] = $this->load->view('news_entry_view', $data, TRUE);
		$this->load->view('page_view',$data);
	}
	*/
	
	public function edit() {
		$this->load->model('auth_model');
		if (!$netid = $this->auth_model->ID()) return;
		$me = $this->auth_model->auth();
		$this->auth_model->secure($me,2);
		
		$this->editStory(FALSE,0);
	}
	
	private function editStory($error,$i) {
		$this->load->helper('url');
		$this->load->helper('form');
		
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		
		
		$entryID = $this->uri->segment(3);
		if (!$entryID) $entryID = $i;
		
		if ($entryID) {
			$this->db->where('id', $entryID);
			$query = $this->db->get('news');
			$entries = $query->result();
			$data['story'] = $entries[0];
			if ($query->num_rows() == 0) {
				redirect("news");
				return;
			}
		} else {
			$data['story'] = FALSE;
			
			if(false && $_POST['title'] || $_POST['content']) {
				//$data['story'] = array("title" => "","content" => "");
				
				if ($_POST['title']) $data['story']->title = $_POST['title'];
				if ($_POST['content']) $data['story']->content = $_POST['content'];
			}
			
		}
		
		$data['error'] = $error;
		
		$this->db->order_by("year", "desc");
		$data['guildies'] = $this->db->get('guildies')->result();
		$data['content'] = $this->load->view('news_edit_view', $data, TRUE);
		$this->load->view('page_view',$data);
	}
	
	public function add() {
		$this->load->model('auth_model');
		if (!$netid = $this->auth_model->ID()) return;
		$me = $this->auth_model->auth();
		$this->auth_model->secure($me,2);
		
		$this->load->helper('url');
		$this->load->helper('form');
		
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		
		$data['story'] = FALSE;
		$data['guildie'] = FALSE;
		
		$this->db->order_by("year", "desc");
		$data['guildies'] = $this->db->get('guildies')->result();
		
		$data['content'] = $this->load->view('news_edit_view', $data, TRUE);
		
		$this->load->view('page_view',$data);
	}
	
	public function remove() {
		$this->load->model('auth_model');
		if (!$netid = $this->auth_model->ID()) return;
		$me = $this->auth_model->auth();
		$this->auth_model->secure($me,2);
		
		$this->load->helper('url');
		$this->load->helper('form');
		
		$this->db->where('id', $this->uri->segment(3));
		$this->db->delete('news');
		
		redirect('news');
	}
	
	public function newsUpdate() {
		$this->load->model('auth_model');
		if (!$netid = $this->auth_model->ID()) return;
		$me = $this->auth_model->auth();
		$this->auth_model->secure($me,2);
		
		$this->load->helper('url');
		$this->load->helper('form');
		
		if ($_POST['title'] == '' || $_POST['content'] == '') {
			$id = false;
			if (isset($_POST['id'])) $id = $_POST['id'];
			$this->editStory('All news stories must have a title and content!',$id);
			return;
		}
		unset($_POST['update']);
		
		if ($_POST['id'] == 0) {
			//Add the entry
			$this->db->insert('news', $_POST);
		} else {
			$this->db->where('id', $_POST['id']); 
			$this->db->update('news', $_POST);
		}
		redirect('news');
	}
	
	
	
	private  function newsSearch($searchString, $limit, $offset, $isJSON, $sortCol) {
		$result = FALSE;
		
		if ($offset > 0) {$this->db->limit($limit,$offset);}
		else if ($limit > 0) { $this->db->limit($limit);}
		
		
		
		//if ($sortCol) $this->db->order_by($sortCol, "asc");
		
		if ($searchString) {
			$this->db->where("title LIKE '%$searchString%'
								OR content LIKE '%$searchString%'
								OR created LIKE '%$searchString%'
			");
		}
		
		$this->db->order_by("id", "desc");
		
		$results = $this->db->get('news');
		
		
		if ($isJSON) {
			//Jump in here and load guildie names for IDs
			$results = $results->result();
			for ($i=0;$i<count($results);$i++) {
				$this->db->where('id',$results[$i]->author);
				$this->db->order_by("year", "desc");
				$query = $this->db->get('guildies')->result();
				if (count($query)>0) {
					$results[$i]->author = $query[0]->firstname." ".$query[0]->lastname;
				}
			}
			
			$results = json_encode($results);
		}
		return $results;
	}
	
	public function search() {
		//$netid = $this->ID();
		//$this->auth();
		
		$searchString = "";
		$searchString = $_POST['query'];
		if ($searchString == "") $searchString = FALSE;
		$limit = isset($_POST['limit'])?$_POST['limit']:false;
		$offset = FALSE;//$_POST['offset'];
		$isJSON = TRUE;
		$sortCol = FALSE;
		$resultString = $this->newsSearch($searchString, $limit, $offset, $isJSON, $sortCol);
		$data = array("content" => $resultString);
		$this->load->view('script_view', $data);
	}
	
	public function uploadimage() {
		$this->load->model('auth_model');
		if (!$netid = $this->auth_model->ID()) return;
		$me = $this->auth_model->auth();
		$this->auth_model->secure($me,2);
		
		$this->load->helper('url');
		$this->load->helper('form');
		
		$config['upload_path'] = './assets/images/NewsPhotos/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '1600';
		$config['max_width']  = '1600';
		$config['max_height']  = '1600';

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if ( ! $this->upload->do_upload('file') ) {
			echo "";
		} else {
			$uploadData = $this->upload->data();
			echo $uploadData['file_name'];
		}
	}
		
}

/* End of file homepage.php */
/* Location: ./application/controllers/homepage.php */