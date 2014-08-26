<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Library extends CI_Controller {

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
		$this->load->helper('form');
		$this->load->library('session');
		
		$defaultGet = "A";
		
		redirect("library/offset/".$defaultGet);
		
		/*
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		
		$data['libraryData'] = $this->libraryGet($defaultGet,0,0,FALSE, "title");
		
		$data['selectMode'] = FALSE;
		$data['content'] = $this->load->view('library_view', $data, TRUE);
		
		$this->load->view('page_view',$data);*/	
	}
	
	public function offset($page = "") {
		
		$this->load->model('auth_model');
		if (!$netid = $this->auth_model->ID()) return;
		$me = $this->auth_model->auth();
		$this->auth_model->secure($me,1);
		
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('session');
		
		$data['libraryPage'] = $page;
		
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		
		$letter = $this->uri->segment(3);
		$defaultGet = "A";
		if ($letter) $defaultGet = $letter;
		
		$data['libraryData'] = $this->libraryGet($defaultGet,0,0,FALSE, "title");
		
		$data['libraryCount'] = $this->libraryItemCount();
		
		$data['user'] = $me;
		
		$data['selectMode'] = FALSE;
		$data['content'] = $this->load->view('library_view', $data, TRUE);
		
		
		
		$this->load->view('page_view',$data);	
		
	}
	
	public function search() {
		$this->load->model('auth_model');
		if (!$netid = $this->auth_model->ID()) return;
		$me = $this->auth_model->auth();
		$this->auth_model->secure($me,1);
		
		$searchString = "";
		$searchString = $_POST['query'];
		if ($searchString == "") $searchString = FALSE;
		$limit = isset($_POST['limit'])?$_POST['limit']:false;
		$offset = FALSE;//$_POST['offset'];
		$isJSON = TRUE;
		$sortCol = FALSE;
		$resultString = $this->librarySearch($searchString, $limit, $offset, $isJSON, $sortCol);
		$data = array("content" => $resultString);
		$this->load->view('script_view', $data);
	}
	
	private function libraryGet($searchString, $limit, $offset, $isJSON, $sortCol) {
	$result = FALSE;
	
	if ($offset > 0) {$this->db->limit($limit,$offset);}
	else if ($limit > 0) { $this->db->limit($limit);}
	
	
	if ($sortCol) $this->db->order_by($sortCol, "asc");
	
	if ($searchString) {
	
		$searchTerm = "composer";
		
		if ($searchString == "#") {
			
			$searchTerm = "";
			
			for ($i=0;$i<10;$i++)  {
				$searchTerm = $searchTerm."($searchTerm LIKE $i)";
				if ($i<9) $searchTerm = $searchTerm." OR ";
			}
			
			echo $searchTerm;
			
			$this->db->where($searchTerm);
		} else if ($searchString == "ALL") {
			//DO NOTHING!
			$this->db->order_by("title", "asc");
		} else {
			$this->db->where("$searchTerm LIKE '$searchString%'");
		}
			
	}
	
	$results = $this->db->get('library');
	
	if ($isJSON) {
		$results = json_encode($results->result());
	}
		return $results;
	}
	
	private function libraryItemCount() {
		$count = $this->db->count_all_results('library');
		return $count;
	}
	
	private  function librarySearch($searchString, $limit, $offset, $isJSON, $sortCol) {
		$this->load->helper('url');
		
		$result = FALSE;
				
		$results = false;
		
		if ($searchString != "") {
			
			if ($offset > 0) {$this->db->limit($limit,$offset);}
			else if ($limit > 0) { $this->db->limit($limit);}
			
			if ($sortCol) $this->db->order_by($sortCol, "dec");
			
		    /*
			$this->db->where("title LIKE '%$searchString%'
								OR composer LIKE '%$searchString%'
								OR year LIKE '%$searchString%'
								OR genre LIKE '%$searchString%'
								OR collection LIKE '%$searchString%'
								OR arranger LIKE '%$searchString%'
								OR time LIKE '%$searchString%'
			");
			
			*/
			$sql = $this->smartSearch($searchString);
			$results = $this->db->where($sql, null, FALSE);
			$results = $this->db->get('library');
			
		}
		
		if ($isJSON) {
			
			$results = json_encode($results->result());
		}
		return $results;
	}
	
	
	public function searchTest() {
	    $query = "";
	   
	    if (isset($_GET['query'])) $query = $_GET['query'];
	    
		$results = $this->librarySearch($query, false, false, false, false);
		echo "Query is: $query <br>";
		$sql = $this->smartSearch($query);
		echo $sql."<br><br>";
		//$this->db->where($sql, null, FALSE);
		//$results = $this->db->get('library');
		echo "<pre>";
		print_r($results->result());
		echo "</pre>";
	}
	
	
	public function entries() {
		$this->load->model('auth_model');
		if (!$netid = $this->auth_model->ID()) return;
		$me = $this->auth_model->auth();
		$this->auth_model->secure($me,1);
		
		$this->load->helper('url');
		
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		
		$ID = $this->uri->segment(3);
		$this->db->where('id', $ID);
		
		$query = $this->db->get('library');
		
		
		if ($query->num_rows() == 0) {
			$data['content'] = $this->load->view('library_not_found_view', $data, TRUE);
			$this->load->view('page_view',$data);
			return;
		}
		$entries = $query->result();
		$data['entry'] = $entries[0];
		
		
		/*Load who plays piece */
		$this->db->where('libraryID', $ID);
		$guildieQuery = $this->db->get('guildiepieces')->result();
		$guildies = array();
		for ($i=0;$i<count($guildieQuery);$i++) {
			$guildieID = $guildieQuery[$i]->guildieID;
			$this->db->where('id',$guildieID);
			$guildie = $this->db->get('guildies')->result();
			if ($guildie[0]) $guildies[count($guildies)] = $guildie[0];
		}
		
		$data['players'] = $guildies;
		
		//Let's load some recordings too->
		$this->db->where('isRecording',1);
		$this->db->where('libraryID',$ID);
		$this->db->where('libraryID',$ID);
		$this->db->order_by('guildieID', "dec");
		$recordings = $this->db->get('guildiepieces')->result();
		$data['recordings'] = $recordings;
		/* - - -  */ 
		
		$data['user'] = $me;
		
		
		$data['sheetMusicLinks'] = array();
	    $this->db->where("libraryID", $ID);
	    $sheets = $this->db->get("sheetmusic");
	    if (count($sheets) > 0) $data['sheetMusicLinks'] = $sheets->result();
		
		
		
		$data['content'] = $this->load->view('library_entry_view', $data, TRUE);
		$this->load->view('page_view',$data);
	}
	
	public function edit() {
		$this->load->model('auth_model');
		if (!$netid = $this->auth_model->ID()) return;
		$me = $this->auth_model->auth();
		$this->auth_model->secure($me,1);
		
		$this->editEntry(FALSE,0);
	}
	
	public function uploadSheet() {
		$this->load->model('auth_model');
		if (!$netid = $this->auth_model->ID()) return;
		$me = $this->auth_model->auth();
		$this->auth_model->secure($me,1);

		$this->load->helper('url');
		$this->load->helper('form');
		
		$config['upload_path'] = './assets/sheetmusic/';
		$config['allowed_types'] = 'pdf|jpg|doc|docx';
		//config['max_size']	= '1000';
		//$config['max_width']  = '1024';
		//$config['max_height']  = '768';

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if ( ! $this->upload->do_upload('file') ) {
			echo "";
		} else {
			$uploadData = $this->upload->data();
			
			$entryID = $_GET['id'];
			
			$fullPath = base_url()."assets/sheetmusic/".$uploadData['file_name'];
			
			$pieceArray = array("link"=>$fullPath, "libraryID"=>$entryID);
			$this->db->insert('sheetmusic', $pieceArray);
			
			echo $uploadData['file_name'];
		}
	}
	
	private function editEntry($error,$i) {
		$this->load->helper('url');
		$this->load->helper('form');
		
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		
		$entryID = $this->uri->segment(3);
		if (!$entryID) $entryID = $i;
		
		if ($entryID) {
			$this->db->where('id', $entryID);
			$query = $this->db->get('library');
		}
		
		if ($entryID) {
			$entries = $query->result();
			$data['entry'] = $entries[0];
			if ($query->num_rows() == 0) {
				$data['content'] = $this->load->view('library_not_found_view', $data, TRUE);
				$this->load->view('page_view',$data);
				return;
			}
		} else {
			$data['entry'] = FALSE;
		}
		
		
		
		//Load the PDFs			
		$data['sheetMusicLinks'] = array();
	    $this->db->where("libraryID", $entryID);
	    $sheets = $this->db->get("sheetmusic");
	    if (count($sheets) > 0) $data['sheetMusicLinks'] = $sheets->result();
	    
		
		
		$data['error'] = $error;
		$data['content'] = $this->load->view('library_entry_edit_view', $data, TRUE);
		$this->load->view('page_view',$data);
	}
	
	public function add() {
		$this->load->model('auth_model');
		if (!$netid = $this->auth_model->ID()) return;
		$me = $this->auth_model->auth();
		$this->auth_model->secure($me,1);
		
		$this->load->helper('url');
		$this->load->helper('form');
		
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		
		$data['user'] = $me;
		
		$data['sheetMusicLinks'] = array();
		
		
		$data['entry'] = FALSE;
		$data['content'] = $this->load->view('library_entry_edit_view', $data, TRUE);
		$this->load->view('page_view',$data);
	}
	
	public function remove() {
		$this->load->model('auth_model');
		if (!$netid = $this->auth_model->ID()) return;
		$me = $this->auth_model->auth();
		$this->auth_model->secure($me,1);
		
		$this->load->helper('url');
		$this->load->helper('form');
		
		$this->db->where('id', $this->uri->segment(3));
		$this->db->delete('library');
		
		redirect('library');
	}
	
	public function entryUpdate() {
		$this->load->helper('url');
		$this->load->helper('form');
		
		if ($_POST['title'] == '') {
			$this->editEntry('All entries must have a title',$_POST['id']);
			return;
		}
		
		unset($_POST['update']);
		
		if ($_POST['id'] == 0) {
			//Add the entry
			$this->db->insert('library', $_POST);
		} else {
			$this->db->where('id', $_POST['id']); 
			$this->db->update('library', $_POST);
		}
		redirect('library');
	}
	
	
	/* Ring Manager Hooks */
	
	
	public function RM_LibrarySearch() {	
		
		$limit = 0;	
		if (isset($_GET['limit'])) $limit = $_GET['limit'];
		$query = $_GET['query'];
		
		/*
		$results = $this->db->get('library')->result();
		$items = array();
		for ($i=0;$i<count($results);$i++) {
			//Only copy in certain items for delivery to the app
			$newArray = array(
							'id'=>$results[$i]->id,
							'title'=>$results[$i]->title,
							'composer'=>$results[$i]->composer,
							'year'=>$results[$i]->year
							);
			$items[$i] = $newArray;
			
			if ($limit && $i == $limit-1) break;
		}
		*/
		
		if ($query != "") {
			$results = $this->librarySearch($query, $limit, 0, 1,0);
		} else {
			$results = $this->db->get('library')->result();
			
			if ($limit) {
				$items = array();
				for ($i=0;$i<count($results);$i++) {
					$items[$i] = $results[$i];
					if ($limit && $i == $limit-1) break;
				}
				$results = json_encode($results);
			}
		}
		$data = array("content" => $results);
		$this->load->view('script_view', $data);
	}
	public function RM_LibraryInfo() {		
		$anItem = $_GET['item'];
		$this->db->where("id",$anItem);
		$results = $this->db->get('library')->result();
		if ($results) $results = $results[0];
		$results = json_encode($results);	
		$data = array("content" => $results);
		$this->load->view('script_view', $data);
	}
	/* End Ring Manager Hooks */
	
	/*
	public function searchTest($query) {
	    
	    //$query = "toccata and fugue";
	    $sql = $this->smartSearch($query);
	    echo $sql;
        $this->db->where($sql, null, FALSE);
        $results = $this->db->get("library");
        
        //echo "<pre>";
        //print_r($results);
        //echo "</pre>";
	}
	*/
	
	
/* Searchable Values
			• title
			• composer
			year
			• genre
			• collection
			• arranger
			time
			• key
			• Filed Under
			• Time
*/		
	
	// Search Query Stuff Here
	private function smartSearch($query) {
        $searchtermsarray = explode(' ', $query);
        if (count($searchtermsarray) > 1) {
            $trimmedsearchtermsarray = array();
            foreach ($searchtermsarray as $term) {
                     $trimmedsearchtermsarray[] = "%" . trim(mysql_real_escape_string($term)) . "%";
                 }
        
            $whereclause2 = "";
            $numberofkeys = count($trimmedsearchtermsarray);

            for ($i=0; $i < $numberofkeys; $i++) {
                    $aWord = $trimmedsearchtermsarray[$i];
                    $subClause = "(
                                        library.title LIKE '%$aWord%' 
                                    OR 	library.composer LIKE '%$aWord%' 
                                    OR	library.arranger LIKE '%$aWord%' 
                                    OR	library.genre LIKE '%$aWord%' 
                                    OR	library.collection LIKE '%$aWord%' 
                                    OR	library.year LIKE '%$aWord%' 
                                    OR	library.key LIKE '%$aWord%'
                                    OR	library.time LIKE '%$aWord%'
                                    OR	library.filedunder LIKE '%$aWord%' 
                                    )";
                    
                    $whereclause2.= $subClause;
                    if ($i < count($trimmedsearchtermsarray)-1) $whereclause2.=" AND ";
            }  
        } else {
          $whereclause2 = trim("   library.title LIKE '%$query%'
								OR library.composer LIKE '%$query%'
								OR library.year LIKE '%$query%'
								OR library.genre LIKE '%$query%'
								OR library.collection LIKE '%$query%'
								OR library.arranger LIKE '%$query%'
								OR library.time LIKE '%$query%'
								OR library.key LIKE '%$query%'
								OR	library.time LIKE '%$query%' 
								OR	library.filedunder LIKE '%$query%' 
								");
        }    
    
    return $whereclause2;	
    
    }
	
	
	
	// End the Class	
}
