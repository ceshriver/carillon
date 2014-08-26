<?php
/* Guildies PLIST Format /*


/*

<dict>

	<key>4</key> <--- Guildie ID
	<dict>
			<key>Frolic</key> <-- Folder Name
			<dict>
				<key>AllowedGuildies</key>
				<array>
					<integer>2</integer>
					<integer>5</integer>
					<integer>7</integer>
				</array>
				<key>DisplayName</key>		
				<string>Frolic - Frank Della Penna</string>
			</dict>
	</dict>
	
</dict>
*/


	require_once("PropertyListParser.php");

class Recordings extends CI_Controller {

    
    
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
		//$this->auth_model->secure($me,0);
		
		$this->load->helper('url');
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		
		$guildies = $this->loadGuildies("year");
	
		// Load the Logged-In Guildie	
		$data['guildie'] = $me;
	
	
		$fileInfo = $this->loadFiles("Recordings/");
		$folders = $fileInfo['folders'];
		
		$displayFolders = array();
		for ($i=0;$i<count($folders);$i++) {
			$contents = $this->loadFiles($folders[$i]['path']."/");
			$displayFolders[$i] = array('path'=>$folders[$i]['path'], 'contents'=>$contents, 'name'=>basename($folders[$i]['path']));
		}
        
        
        //Attempt to load the PLIST
        //$this->load->library('PropertyListParser.php');
		$filePath = "Recordings/recordings.plist";
		$parser = new PropertyListParser();
		$recordingExceptions = $parser->parsePropertyList($filePath);
		
		if (!$recordingExceptions) {
			// We do not have a global recordings PLIST. Make one!
			$demoPlist = '<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd"><plist version="1.0"><dict></dict></plist>';
			file_put_contents($filePath, $demoPlist);
			$recordingExceptions = array();
		}
		
		
		
		//echo "<pre>";
		//print_r($recordingExceptions);
		//echo "</pre>";
		
		$displayFolders = $this->mapGuildiesAndFolders($guildies, $displayFolders);
		
		
		$data['me'] = $me;
		$data['guildies'] = $guildies;
		$data['folders'] = $displayFolders;
		$data['sharedRecordings'] = $recordingExceptions;
		
		$data['content'] = $this->load->view('recordings_view', $data, TRUE);
		$this->load->view('page_view',$data);	
	}
	
	private function loadGuildies($sort) {
		$this->db->order_by($sort, "asc");
		$guildies = $this->db->get('guildies')->result();
		return $guildies;
	}
	
	private function mapGuildiesAndFolders($guildies, $folders) {
		for ($i=0;$i<count($guildies);$i++) {
			$name = $guildies[$i]->firstname." ".$guildies[$i]->lastname;
			for($p=0;$p<count($folders);$p++) {
				if ("Recordings/".$name == $folders[$p]['path']) {
					$folders[$p]['guildie'] = $guildies[$i];
				}
			}
		}
		return $folders;
	}
	
    // Load Files from a Given Directory
    
	private function loadFiles($path) {
		$this->load->helper('file');
	
		$files = array();
		$folders = array();
        
		$results = scandir($path);
		
		foreach ($results as $result) {
		    if ($result === '.' or $result === '..' or $result === '.DS_Store') continue;
		    if (is_dir($path . '/' . $result)) {
		        $folders[count($folders)] = $this->folderInfoAtPath($path.$result);
		    } else {
		    	$files[count($files)] = $this->fileInfoAtPath($path . $result);
		    }
		}
		
		return array('folders'=>$folders, 'files'=>$files);
	}
	private function fileInfoAtPath($path) {
		$fileSize = filesize($path);
		$modified = filemtime($path);
		$created = filectime($path);
		$name = basename($path);
		$info = array('size'=>$fileSize, 'modified'=>$modified, 'created'=>$created, 'path'=>$path, 'name'=>$name);
		return $info;
	}
	private function folderInfoAtPath($path) {
		$fileSize = filesize($path);
		$modified = filemtime($path);
		$created = filectime($path);
		$name = basename($path);
		
		//Check for library entry
		$pattern = '/\(([0-9]*)\)/'; 
		$replace = array('');
		
		$library = array();
		preg_match($pattern,$name,$library);
		if (count($library) > 0) {
			//Have Library Tag
			$name = preg_replace($pattern, '', $name);
			$library = str_replace("(","",$library[0]);
			$library = str_replace(")","",$library);
		} else {
			$library = 0;
		}
		
		$info = array('size'=>$fileSize, 'modified'=>$modified, 'created'=>$created, 'path'=>$path, 'name'=>$name);
		if ($library > 0) $info['inLibrary'] = $library;
		
		return $info;
	}
	
	
	public function edit() {
		$netid = $this->ID();
		$this->auth();
		
		$this->load->helper('url');
		$this->load->helper('form');
		
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		
		$guildieID = 0;
		$guildieID = $_GET['guildie'];
		
		if ($guildieID) {
			$this->db->where('id', $guildieID);
			$guildies = $this->db->get('guildies')->result();
			$guildie = $guildies[0];
		} else {
			$guildie = 0;
		}
		
		$data['guildie'] = $guildie;
		
		$data['path'] = $_GET['path'];
		
		//If in library, look up the piece
		$data['piece'] = 0;
		if ($_GET['library'] > 0) {
			$this->db->where('id', $_GET['library']);
			$data['piece'] = $this->db->get('library')->result();
			$data['piece'] = $data['piece'][0];
		}
		
		
		//$data['folders']
		$data['fileinfo'] = $this->fileInfoAtPath($_GET['path']);
		
		$data['content'] = $this->load->view('recordings_edit_view', $data, TRUE);
		$this->load->view('page_view',$data);
	}
	
	public function manageFolder() {
		$netid = $this->ID();
		$this->load->model('auth_model');
		$me = $this->auth_model->auth();
		
		$this->load->helper('url');
		$this->load->helper('form');
		
		//echo "POST";
		//echo "<pre>";
		//print_r($_POST);
		//echo "</pre>";
		
		
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		
		$library = 0;
		if (isset($_GET['library'])) $library = rawurldecode($_GET['library']);
		
		$path = rawurldecode($_GET['path']);
		$guildie = rawurldecode($_GET['guildie']);
		
		
		//Check if we need to share this recording
		$shareGuildies = array();
		
		
		$index = 0;
		while (isset($_POST['shares__'.$index])) {
			$guildieIndex = $_POST['shares__'.$index];
			$shareGuildies[count($shareGuildies)] = $guildieIndex;
			$index++;
		}
		
		//echo "<pre>";
		//print_r($shareGuildies);
		//echo "</pre>";
		
		$index = 1;
		while (isset($_POST['add__'.$index])) {
			$guildieIndex = $_POST['add__'.$index];
			$shareGuildies[count($shareGuildies)] = $guildieIndex;
			$index++;
		}
		
		if (count($shareGuildies) > 0) { 
			
			//echo "Sharing this file:";
				
			$filePath = "Recordings/recordings.plist";
			$parser = new PropertyListParser();
			$recordingExceptions = false;
			$recordingExceptions = $parser->parsePropertyList($filePath);
			$sharedRecordings = false;
			
			
		
			$guildieExceptions = false;
			if ($recordingExceptions && $recordingExceptions[0] && array_key_exists($guildie, $recordingExceptions[0])) {
				//Let's look to see if this song is shared with anyone else
				$guildieExceptions = $recordingExceptions[0][$guildie];
			}
			
			$lastPathObject = explode("/",$path);
			$lastPathObject = $lastPathObject[count($lastPathObject)-1];
		
			if ($guildieExceptions && array_key_exists($lastPathObject,$guildieExceptions)) {
				//echo "hello";
				//This piece has been shared with people!
				//$sharedRecordings = $guildieExceptions[$lastPathObject];
				$recordingExceptions[0][$guildie][$filePath]["AllowedGuildies"] = $shareGuildies;
			} else {
				// This piece doesn't have an entry yet...
				if ($recordingExceptions[0] && array_key_exists($guildie,$recordingExceptions[0])) {
					//echo "Case 1: Guildie doesn't have file";
					$files = array("AllowedGuildies"=>$shareGuildies);
					$guildieFile = array($path=>$files);
					$recordingExceptions["0"][$guildie] = $guildieFile;
					
					//echo "<pre>";
					//print_r($recordingExceptions);
					//echo "</pre>";
					
				} else {
					//We don't have anything in the file...
					//echo "Case 2: Recordings File doesn't exist...or guildie isn't in there";
					$files = array("AllowedGuildies"=>$shareGuildies);
					$guildieFile = array($path=>$files);
					
					//echo "<pre>";
					//echo "Before";
					//print_r($recordingExceptions);
					//echo "</pre>";
					
					if (!$recordingExceptions[0]) {
						$guildies = array($guildie=>$guildieFile);
						$recordingExceptions = array($guildies);
					} else {
						$recordingExceptions[0][$guildie] = $guildieFile;
					}
					
					
					
					
					//echo "<pre>";
					//print_r($recordingExceptions);
					//echo "</pre>";

				}
				//$recordingExceptions["0"][$guildie] = $file;
				
			}
			//Turn this back into the property list!
			
			$parser->serializePropertyListToFile($recordingExceptions[0], $filePath);
			
			//echo "<pre>";
			//print_r($recordingExceptions);
			//echo "</pre>";
		}
		
		
		
		
		//If library, look up the piece
		$data['piece'] = 0;
		if ($library > 0) {
			$this->db->where('id',$library);
			$data['piece'] = $this->db->get('library')->result();
			$data['piece'] = $data['piece'][0];
		}
		$data['path'] = $path;
		
		$data['guildie'] = 0;
		if ($guildie > 0) {
			$this->db->where('id',$guildie);
			$data['guildie'] = $this->db->get('guildies')->result();
			$data['guildie'] = $data['guildie'][0];
		}
		
		//Load the files in the path
		
		//Load the Shared Recordings
		
		$filePath = "Recordings/recordings.plist";
		$parser = new PropertyListParser();
		$recordingExceptions = $parser->parsePropertyList($filePath);
		$sharedRecordings = false;
		if ($recordingExceptions) {
			//echo "Exceptions";
		

//echo "<pre>";
//print_r($recordingExceptions);
//echo "</pre>";

		
			//Let's look to see if this song is shared with anyone else
			$guildieExceptions = $recordingExceptions[0];
			
			
			if ($guildieExceptions && !array_key_exists($guildie,$guildieExceptions)) {
				$guildieExceptions = false;
			}
			
			
			$lastPathObject = explode("/",$path);
			$lastPathObject = $lastPathObject[count($lastPathObject)-1];
			
			
			//echo $path;
			//echo "<pre>";
			//print_r($recordingExceptions[0][$guildie][$path]);
			//print_r($guildieExceptions);
			//echo "</pre>";
			
			if ($guildieExceptions && array_key_exists($guildie,$guildieExceptions) && array_key_exists($path,$guildieExceptions[$guildie])) {
				//echo "hello";
				//This piece has been shared with people!
				$sharedRecordings = $guildieExceptions[$guildie][$path];
			}				
		}
		
		//echo "<pre>";
		//print_r($sharedRecordings);
		//echo "</pre>";
		
		
		$data['sharedRecordings'] = $sharedRecordings;
		
		
		$data['records'] = $this->loadFiles($path."/");
		$guildies = $this->loadGuildies("year");
		$data['guildies'] = $guildies;
		$data['me'] = $me;
		$data['content'] = $this->load->view('recordings_manage_view', $data, TRUE);
		$this->load->view('page_view',$data);
	}
	
	private function updateRecordingDatabase($folderPath, $library,$guildie) {
		$folderInfo = $this->loadFiles($folderPath);
		$folder = false;
		for ($i=0;$i<count($folderInfo['folders']);$i++) {
			$aFile = $folderInfo['folders'][$i];	
			
			//Save the folder for the file we're editing 
			if(preg_match('/\(.'.$library.'\)/', $aFile['path'])) {
				$folder=$aFile;
			}
			
			if($guildie && preg_match('/\([0-9]*\)/', $aFile['path'], $matched)) {
				//Parse the library ID out of the path
				
				$matched = $matched[0];
				
				$matched = str_replace("(", "", $matched);
				$matched = str_replace(")", "", $matched);
				
				$libraryID = $matched;
				//echo $libraryID; 
				
				$recordingEntry = array("guildieID"=>$guildie,"libraryID"=>$libraryID, "isRecording"=>1);
				$this->db->insert('guildiepieces',$recordingEntry);
			}
			
		}
		return $folder;
	}
	
	
	public function recategorize() {
		$netid = $this->ID();
		$this->auth();
		
		$this->load->helper('url');
		$this->load->helper('form');
		
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		
		$library = rawurldecode($_GET['library']);
		$oldPath = rawurldecode($_GET['oldpath']);
		
		$guildie = 0;
		if (isset($_GET['guildie'])) {
			$guildie = rawurldecode($_GET['guildie']);
		}
		
		$name = 0;
		if (isset($_GET['name'])) {
			$name = $_GET['name'];
		}
		
		
		$newPath = explode('/',$oldPath);
		
		
		//All we need to do is rename the item…
		
		
		//Is it a folder?
		$isDir = is_dir($oldPath);
		
		if ($guildie) {
		//Clear all guildie recordings that are marked as RECORDING
			$this->db->where('guildieID',$guildie);
			$this->db->where('isRecording', true);
			$this->db->delete('guildiepieces');
		}
		
		$folderPath = $newPath[0]."/".$newPath[1]."/";
		
		$folder = $this->updateRecordingDatabase($folderPath, $library,$guildie);
		
		if (!$isDir) {
			//okay we're trying to recategorize a file here.
			//First look if the guildie's folder has an entry for this library item and put it inside
			
			
			
			if ($folder) {
				//Guildie already has a folder for these recordings
				//echo "Already Has Folder";
				$newPath = $folder['path'].'/';
				
				
				if (!file_exists($newPath)) { 
					mkdir($newPath, 0777, true);
				}
				
			} else {
			
				$this->db->where('id',$library);
				$entries = $this->db->get('library')->result();
				
				$piecename = $entries[0]->title;
			
				$newPath[count($newPath)-1] = $piecename." (".$library.")/";
				$newPath = implode('/',$newPath);
				if(!file_exists($newPath)) {
					mkdir($newPath, 0777, true);
				}
			}
			$newPath = $newPath.$name;
			
			
			
		
		} else {
			// We're categorizing the folder. Rename the whole thing and redirect back to same page.
			
			if ($library > 0) {
				$this->db->where('id',$library);
				$entries = $this->db->get('library')->result();
				$newname = $entries[0]->title." (".$library.")";
				$newPath[count($newPath)-1] = $newname;
			}
			
			$newPath = implode('/',$newPath);
		}

		//Rename the folder		
		rename($oldPath, $newPath);
		
		//Redirect here with the right get string	
		
		if ($isDir) {
			redirect('recordings/manageFolder?path='.rawurlencode($newPath).'&library='.$library.'&guildie='.$guildie);
		} else {
			//Redirect back to editing file
			redirect('recordings/edit?path='.rawurlencode($newPath).'&library='.$library.'&guildie='.$guildie);
		}
		
	}
	
	
	
	
	/*
	public function add() {
		$this->load->helper('url');
		$this->load->helper('form');
		
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		
		$data['recording'] = FALSE;
		$data['rings'] = $this->db->get('rings')->result();
		$data['content'] = $this->load->view('guildie_edit_view', $data, TRUE);
		
		$this->load->view('page_view',$data);
	}
	*/
	
	public function remove() {
		$netid = $this->ID();
		$this->auth();
		
		$this->load->helper('url');
		$this->load->helper('form');
		
		//remove path
		$_GET['path'];
		
		redirect($_GET['return']);
	}
	
	
	public function recordingUpdate() {
		$netid = $this->ID();
		$this->auth();
		
		$this->load->helper('url');
		$this->load->helper('form');
		
		$path = $_POST['path'];
		$name = $_POST['piecename'];
		
		
		$piece = $_POST['library'];
		
		
		$newPath = explode("/",$path);
		$newPath[count($newPath)-1] = $name; 
		$newPath = implode("/",$newPath);
		
		
		
		if ($piece) {
			$newPath = explode(".",$newPath);
			if (count($newPath) > 1) {
				//has extension
				$newPath[count($newPath)-2] = $newPath[count($newPath)-2]." (".$piece.")";
				$newPath = implode(".",$newPath);
			} else {
				$newPath = implode(".",$newPath)." (".$piece.")";
			}
		}
		
		//rename($path, $newPath);
		
		$return = "recordings";
		if (isset($_POST['return'])) $return = $_POST['return'];
		redirect($return);
	}
	

	public function search() {
		$netid = $this->ID();
		$this->auth();
		
		$searchString = "";
		$searchString = $_POST['query'];
		if ($searchString == "") $searchString = FALSE;

		$sortCol = "lastname";
		if ($sortCol) $this->db->order_by($sortCol, "asc");
				
		if ($searchString) {
			$this->db->where("firstname LIKE '%$searchString%'
								OR lastname LIKE '%$searchString%'
								OR year LIKE '%$searchString%'
								
			");	
		}
		$results = $this->db->get('guildies');
		
		$results = json_encode($results->result());
		
		$data = array("content" => $results);
		$this->load->view('script_view', $data);
	}
	
	public function uploadimage() {
			$netid = $this->ID();
			$this->auth();
			
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
		
		
		private function ID() {
			$this->load->library('session');
			$this->load->helper('url');
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
}

/* End of file homepage.php */
/* Location: ./application/controllers/homepage.php */

    
    
