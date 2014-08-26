<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Heelmanager extends CI_Controller {

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
		$this->load->library('session');
		$netid = $this->auth_model->ID();
		$me = $this->auth_model->auth();
		$this->auth_model->secure($me,0);//Heel manager page open to everyone who is in the DB. The page handles security on its own
		
		
		
		$this->setDates();
		
		
		
		$heelviewdata['user'] = $me;
		
		if (!$this->session->userdata("up")) {
			$this->session->set_userdata("up",0);
		}
		
		
		if ($me->inGuild) {
			//If in the guild, load our heelers!
			
			$this->db->where('teacher',$me->id);
			$this->db->order_by("firstname", "asc");
			$heelers = $this->db->get('heelers');
			
			if ($heelers->num_rows() > 0) {
				$heelers= $heelers->result();
				
				
				$days = array("Sun","Mon","Tues","Wed","Thur","Fri","Sat");
				
				for ($i=0;$i<count($heelers);$i++) {
					$heeler = $heelers[$i];
					$isHalf = $heeler->lessonhalf?"30":"00";
					
					$AM = $heeler->lessonhour >= 12 ? "PM":"AM";
					$hour = $heeler->lessonhour > 12 ? $heeler->lessonhour - 12 : $heeler->lessonhour;
					$timeString = $days[$heeler->lessonday]." ".$hour.":".$isHalf." ".$AM;
					$heeler->lesson = $timeString;
				}
				$heelviewdata['heelers'] = $heelers;
			}	
		}
		
		
		if (!$me->inGuild) {
			//Load some heeler data
			$this->db->where('id',$me->teacher);
			$me->teacher = false;
			$query = $this->db->get('guildies')->result();
			if (count($query) != 0) $me->teacher = $query[0];
		}
		
		$nowSlot = $this->nowSlot();
		$week = $nowSlot['week'];
		
		//Admin View Variables
		if ($me->inGuild && $me->heeladmin) {
			
			if (!$this->session->userdata("superweek")) {
				$week = $this->nowSlot();
				$week = $week['week'];
				$this->session->set_userdata("superweek",$week);
			} else {
				$week = $this->session->userdata("superweek");
			}
			
			$this->load->helper("form");
		
			$guildies = $this->db->get("guildies")->result();
			$heelviewdata['guildiesAdmin'] = $guildies;
			$this->db->order_by("firstname","asc");
			$heelers = $this->db->get("heelers")->result();
			$heelviewdata['heelersAdmin'] = $heelers;
			
			$heelviewdata['superuser'] = $this->session->userdata("superuser");
			$heelviewdata['supertype'] = $this->session->userdata("supertype");
			$heelviewdata['superweek'] = $this->session->userdata("superweek");
		}
		
		$heelviewdata['now'] = $nowSlot;
		
		$isUp = $this->session->userdata("up");
		$bookData = $this->booked($me,$isUp,$week);
		
		/*
		echo "<pre>";
		print_r($bookData);
		echo "</pre>";
		return;
		*/
		
		
		
		$heelviewdata['booked'] = $bookData['other'];
		$heelviewdata['myBookings'] = $bookData['myBookings'];
		
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs : Heel Manager';
		
		
		//Set the audition and heel defines
		$array = $this->setDates();
		$heelviewdata['HEEL_START_HOUR'] = $array["HEEL_START_HOUR"];
		$heelviewdata['AUDITION_DATE'] = $array["AUDITION_DATE"];
		
		
		$data['content'] = $this->load->view('heel_view', $heelviewdata, TRUE);
		
		
		//Woah, if we're a heeler, we don't get to see the special menus… 
		if ($me->inGuild) {
			$this->load->view('page_view',$data);	
		} else {
			$this->load->view('nomenu_page_view',$data);
		}
	}
	
	function setDates() {
		//Load Settings from DB
		$settingsRaw = $this->db->get('settings')->result();
		for ($i=0;$i<count($settingsRaw);$i++) {
			$settings[$settingsRaw[$i]->key] = $settingsRaw[$i]->value;
		}
		$heelstartmonth = $settings["heel-start-month"];
		$heelstartday = $settings["heel-start-day"];
		$heelstartyear = $settings["heel-start-year"];
		
		$auditionmonth = $settings["audition-month"];
		$auditionday = $settings["audition-day"];
		$auditionyear = $settings["audition-year"];
		
		$heelstarthour = $settings["heelstarthour"];
		
		/*
		if (!isset($HEEL_START)) {
			// Convert the Old Defines into Database Settings
			define('HEEL_START', );
			define('CURRENT_TIME', );
			define('HEEL_START_HOUR',);
			//define('AUDITION_DATE', date("F j, Y, g:i a",mktime(11, 45, 0, 11, 11, 2012)));
			define('AUDITION_DATE', );
		}
		*/
		
		return array("HEEL_START"=>date('z-H-i',mktime(0, 0, 0, $heelstartmonth, $heelstartday, $heelstartyear)),
					"CURRENT_TIME"=>date('z-H-i'),
					"HEEL_START_HOUR"=>$heelstarthour,
					"AUDITION_DATE"=>mktime(11, 0, 0, $auditionmonth, $auditionday, $auditionyear)
					);
		
		/* End old Defines */
	}
	
	
	
	public function book($week = 0, $day = 0, $hour = 0, $half = 0, $isUp = 0, $superUser = false) {
		$this->load->model('auth_model');
		$this->load->library('session');
		$netid = $this->auth_model->ID();
		$me = $this->auth_model->auth();
		$bookData = $this->booked($me,$isUp,$week);
		$myBooked = $bookData['myBookings'];

		//Check that the slot is open
		$user = $bookData['other'][$day][$hour][$half];
		
		
		if (isset($user['user']) && !$superUser) {
			//HEEL ERROR HERE
			$this->session->set_userdata('heelError',"Someone has already booked that slot.");
			redirect("/heelmanager");
		}
		
		if ($me->inGuild) {
			//This is a guildie - infinite bookings.......
			$slotData['isHeeler'] = 0;
			
		} else {
			//Heeler. Check allowed slots
			
			$atTime = $me->attimecount;
			
			$maxBooks = $me->totalcount;
			$instrumentBook = $isUp? ($me->upcount):($me->downcount);
			$bookCount = 0;
			
			
			//Total Count Error
			if (count($myBooked) >= $maxBooks) {
				$this->session->set_userdata('heelError',"You cannot book any more slots this week.");
				// Can't book any more slots this week
				redirect("/heelmanager");
			}
			
			
			$now = $this->nowSlot();
			
			$outstandingBooked = 0;
			for ($i=0;$i<count($myBooked);$i++) {
				//echo "<pre>";
				//print_r($myBooked[$i]);
				//echo "</pre>";				
				$past = $this->hasSlotPast($myBooked[$i],$now);
				if (!$past) $outstandingBooked++;
				if ($myBooked[$i]['isup'] == $isUp && !$past) $bookCount++;
			}
			
			if ($outstandingBooked >= $atTime) {
				$this->session->set_userdata('heelError',"You cannot book more than ".$atTime." slots at a time.");//Assume always greater than 2
				// Can't book any more slots this week
				redirect("/heelmanager");
			}
			
			if ($bookCount >= $instrumentBook) {
				$this->session->set_userdata('heelError',"You cannot book any more slots on the ".(($isUp)?"Upstairs":"Downstairs")." Carillon yet. Practice first!");
				redirect("/heelmanager");
			}
			
			//Booking seems successful
			
			$slotData['isHeeler'] = 1;
		}
		
		$slotData['bookee'] = $me->id;
		
		if ($superUser) {
			$slotData['bookee'] = $superUser->id;
			$slotData['isHeeler'] = !$superUser->inGuild;
		}
		
		$slotData['week'] = $week;
		$slotData['hour'] = $hour;
		$slotData['half'] = $half;
		$slotData['type'] = 0; //Can't book lessons….
		$slotData['day'] = $day;
		$slotData['isup'] = $isUp; 
		
		$this->db->insert('bookedslots', $slotData);
		
		//Send the user back to the matrix
		redirect("/heelmanager");
	}
	
	public function unbook($week = 0, $day = 0, $hour = 0, $half = 0, $isUp = 0) {
		$this->load->model('auth_model');
		$this->load->library('session');
		$netid = $this->auth_model->ID();
		$me = $this->auth_model->auth();
		$bookData = $this->booked($me,$isUp,$week);
		$myBooked = $bookData['myBookings'];
		
		//Check that the slot is open
		$user = $bookData['other'][$day][$hour][$half]['user'];

		if (!$user || $user->netid != $me->netid && !($me->inGuild && $me->heeladmin)) {//Heel admins can book/unbook slots
			$this->session->set_userdata('heelError',"This slot is booked by someone else.");
			redirect("/heelmanager");
		}
		
		$slot['week'] = $week;
		$slot['day'] = $day;
		$slot['hour'] = $hour;
		$slot['half'] = $half;
		$slot['isup'] = $isUp;
		if ($this->hasSlotPast($slot,false) && !($me->inGuild && $me->heeladmin)) {//Heel admins can book/unbook slots
			$this->session->set_userdata('heelError',"This slot has already passed - you cannot unbook it.");
			redirect("/heelmanager");
		}
		
		//Unbook the user's slot
		$this->db->where('day',$day);
		$this->db->where('week',$week);
		$this->db->where('hour',$hour);
		$this->db->where('half',$half);
		$this->db->where('isup',$isUp);
		$this->db->delete('bookedslots');
		
		//Send the user back to the matrix
		redirect("/heelmanager");
	}
	
	
	//The Superbook function allows admins to book and unbook slots in the time matrix, namely lessons and practice slots on both instruments
	public function superbook($week = 0, $day = 0, $hour = 0, $half = 0, $isUp = 0,$idString = "", $slotString = "") {
		$this->load->model('auth_model');
		$this->load->library('session');
		$netid = $this->auth_model->ID();
		$me = $this->auth_model->auth();
		$bookData = $this->booked($me,$isUp,$week);
		
		$content = $bookData['other'][$day][$hour][$half];
		
		if ($slotString == "slot") {
			//Book or unhook a slot for this user
		
			
			if (isset($content['user'])) {
				//Unbook the slot
				$this->unbook($week, $day, $hour, $half, $isUp);
			} else {
				//Book the slot under the user!
				$idString = explode("_", $idString);
				$id = $idString[1];
				$guild = $idString[0];
				
					
				$this->db->where('id',$id);
				if ($guild == "guildie") {
					//Guildie
					$query = $this->db->get("guildies")->result();
				} else {
					//Heeler
					$query = $this->db->get("heelers")->result();
				}
				if (count($query) == 0) {
					$this->session->set_userdata('heelError',"User is not in the database.");
					redirect("/heelmanager");
				}
				
				$superUser = $query[0];
				
				$this->book($week, $day, $hour, $half, $isUp,$superUser);
			}
		
		} else if ($slotString == "master") {
		
		if (isset($content['user'])) {
			//Unbook the slot
			$this->unbook($week, $day, $hour, $half, $isUp);
		} else {
			$slotData['bookee'] = $me->id;
			$slotData['isHeeler'] = 0;
			$slotData['week'] = $week;
			$slotData['hour'] = $hour;
			$slotData['half'] = $half;
			$slotData['type'] = 2; //Book a Master Class!
			$slotData['day'] = $day;
			$slotData['isup'] = $isUp; 
			
			$this->db->insert('bookedslots', $slotData);
		}
		
		
		} else if ($slotString == "lesson") {
			//Book or unbook a lesson
			
			if (isset($content['user']) && !$content['user']->inGuild) {
				//Okay simple, just update this heeler with a fake lesson
				$user = $content['user'];
				$this->db->where('id', $user->id);
				
				$lessonData['lessonday'] = 0;
				$lessonData['lessonhour'] = 0;
				$lessonData['lessonhalf'] = 0;
				$lessonData['lessonup'] = 1;
				
				$this->db->update('heelers', $lessonData);
				
			} else if (!isset($content['user']))  {
				//Okay first we need to get the heeler associated with the session, then modify their lesson				
				$idString = explode("_", $idString);
				$id = $idString[1];
				
				$this->db->where('id', $id);
				
				$query = $this->db->get("heelers")->result();
				
				if (count($query) == 0) {
					$this->session->set_userdata('heelError',"Heeler is not in the database (or you're giving a guildie a lesson).");
					redirect("/heelmanager");
				}
				
				
				$lessonData['lessonday'] = $day;
				$lessonData['lessonhour'] = $hour;
				$lessonData['lessonhalf'] = $half;
				$lessonData['lessonup'] = $isUp;
				
				$this->db->where('id', $id);
				$this->db->update('heelers', $lessonData);
				
				
				
			} else {
				$this->session->set_userdata('heelError',"Database error.");
				redirect("/heelmanager");
			}
		
		}
		
		redirect("/heelmanager");
	}
	
	//Pass through function adding super users and types to session
	public function superswitch() {
		$this->load->library('session');
		$this->load->model('auth_model');
		$netid = $this->auth_model->ID();
		$me = $this->auth_model->auth();
		
		$supertype = $this->input->post("supertype");
		$superuser = $this->input->post("superuser");
		$superweek = $this->input->post("superweek");
		
		//echo $supertype;
		//echo $superuser;
		
		$this->session->set_userdata("supertype",$supertype);
		$this->session->set_userdata("superuser",$superuser);
		$this->session->set_userdata("superweek",$superweek);
		
		redirect("/heelmanager");
	}
	
	public function switchcarillon($up) {
		$this->load->library("session");
		$this->load->model('auth_model');
		$netid = $this->auth_model->ID();
		$me = $this->auth_model->auth();
		$this->session->set_userdata("up",$up);
		redirect("/heelmanager/");
	}
	
	public function listBooked() {
		$this->load->library('session');
		$this->load->model('auth_model');
		$netid = $this->auth_model->ID();
		$me = $this->auth_model->auth();
		
		$data = $this->booked($me, 0, 1);
		
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}
	
	
	public function printNow() {
		
		
		$array = $this->setDates();
		$CURRENT_TIME = $array["CURRENT_TIME"];
		$HEEL_START = $array["HEEL_START"];
	
		$slot = $this->nowSlot();
		$slot['hour'] = 22;
		print_r($CURRENT_TIME);
		echo "<br>";
		print_r($HEEL_START);
		//print_r($this->hasSlotPast($slot, false)==1?1:0);
	}
	
	private function booked($me, $upstairsCarillon = 0, $week = -1) {
		//Need to load all heeler lessons and all booked slots and intersect them
		/* Booked Data
		
		[Day]
			[hour]
			[hour]
			...
			[hour]
		...
		[Day]
		
		*/
		$this->load->library("session");
		
		
		$myBookings = array();
		
		$nowSlot = $this->nowSlot();
		
		$array = $this->setDates();
		$HEEL_START_HOUR = $array["HEEL_START_HOUR"];
		
		
		$slot['week'] = $week;
		for ($i=0;$i<7;$i++) {
			$slot['day'] = $i;
			for ($p=0;$p<16*2;$p++) {
				$hour = floor($p/2)+$HEEL_START_HOUR;
				$slot['hour'] = $hour;
				$slot['half'] = 0;
				$bookedData[$i][$hour][0] = array("past"=>$this->hasSlotPast($slot,$nowSlot)==1?1:0);
				$slot['half'] = 1;
				$bookedData[$i][$hour][1] = array("past"=>$this->hasSlotPast($slot,$nowSlot)==1?1:0);

			}
		}
		
		$heelers = $this->db->get('heelers')->result();
		
		/* Heeler Lessons */
		for ($i=0;$i<count($heelers);$i++) {
		
			$heeler = $heelers[$i];
			
			if ($upstairsCarillon == $heeler->lessonup) {//Only load lessons marked for the same carillon.
				
				$bookData['user'] = $heeler;
				$bookData['type'] = 1;
				
				$timeSlot = array("week"=>-1,"day"=>$heeler->lessonday,"hour"=>$heeler->lessonhour,"half"=>$heeler->lessonhalf);
				$bookData['past'] = $this->hasSlotPast($timeSlot,$nowSlot)==1?1:0;
				
				if ($week > $nowSlot['week']) $bookData['past'] = 0;
				
				$bookedData[$heeler->lessonday][$heeler->lessonhour][$heeler->lessonhalf] = $bookData;
			}
			/*
			if ($me && $heeler->netid == $me->netid &&) {
				//These are my booked lesson slots, save these to another array
				$aBooking['day'] = $heeler->lessonday;
				$aBooking['hour'] = $heeler->lessonhour;
				$aBooking['half'] = $heeler->lessonhalf;
				$aBooking['type'] = 1; // Mark this as my lesson
				$aBooking['isup'] = 1;
				//$myBookings[count($myBookings)] = $aBooking;
			}*/
		}
		
		/* Practice Slots */
		if ($week < 0) {
			$now = $this->nowSlot();
			$week = $now['week'];
		} 
		$this->db->where("week",$week);
		$bookedslots = $this->db->get('bookedslots')->result();
		
		for ($i=0;$i<count($bookedslots);$i++) {
			$slot = $bookedslots[$i];
			$bookeeID = $slot->bookee;
			$bookeeHeeler = $slot->isHeeler;
			
			$this->db->where('id',$bookeeID);
			if ($bookeeHeeler) {
				$user = $this->db->get('heelers')->result();
			} else {
				$user = $this->db->get('guildies')->result();
			}
			
			if (count($user) > 0 && $slot->isup == $upstairsCarillon) {//Only add booked slots for the give carillon to the matrix
				$bookData['user'] = $user[0];
				$bookData['type'] = $slot->type;
				
				$timeSlot = array("week"=>$slot->week,"day"=>$slot->day,"hour"=>$slot->hour,"half"=>$slot->half);
				$bookData['past'] = $this->hasSlotPast($timeSlot,$nowSlot)==1?1:0;
				
				$bookedData[$slot->day][$slot->hour][$slot->half] = $bookData;
			}
			
			//However, we need to list the booked slots in the list (Only if they are practice slots! Don't add master classes)
			if ($me && $user[0] && $user[0]->netid == $me->netid && $slot->type == 0) {
				//These are my booked practice slots, save these to another array
				$aBooking['day'] = $slot->day;
				$aBooking['hour'] = $slot->hour;
				$aBooking['half'] = $slot->half;
				$aBooking['type'] = $slot->type; 
				$aBooking['isup'] = $slot->isup;
				$aBooking['past'] = $this->hasSlotPast($aBooking,$nowSlot)==1?1:0;
				//$timeSlot = $array($slot->week,$slot->day,$slot->hour,$slot->half);
				//$bookData['past'] = $this->hasSlotPast($timeslot);
				
				$myBookings[count($myBookings)] = $aBooking;
			}
			
		}
		
		$data['other'] = $bookedData;
		$data['myBookings'] = $myBookings;
		return $data;
	}
	
	
	
	//Date Functions
	
	//Slots are arrays with the following format
	/*
		week <- heel week (index) from start date
		day
		hour
		half
	
	*/
	
	
	public function nowSlot() {
		
		$this->setDates();
		$array = $this->setDates();
		$CURRENT_TIME = $array["CURRENT_TIME"];
		$HEEL_START = $array["HEEL_START"];
		
		$now = explode("-",$CURRENT_TIME);
		$heel = explode("-",$HEEL_START);
		$nowSlot["week"] = floor(($now[0]-$heel[0])/7);
		$nowSlot["day"] = ($now[0]-$heel[0])%7;
		$nowSlot["hour"] = $now[1];
		$nowSlot["half"] = $now[2]>=30?1:0;
		return $nowSlot;
	}

	public function hasSlotPast($slot, $nowSlot) {
		if (!$nowSlot) $nowSlot = $this->nowSlot();
		if (!isset($slot['week']) || $slot['week'] < 0) $slot['week'] = $nowSlot['week']; // Assume current week if not defined
		return $this->slotBeforeSlot($slot,$nowSlot);
	}


	public function slotBeforeSlot($before, $after) {
		
		if ($after['week'] < 0) return true;
		
		if ($after['week'] > $before['week']) {
			return true;
		} else if ($after['week'] == $before['week']) {
			if ($after['day'] > $before['day']) {
				return true;
			} else if ($after['day'] == $before['day']) {
				if ($after['hour'] > $before['hour']) {
					return true;
				} else if ($after['hour'] == $before['hour']) {
					if ($after['half'] >= $before['half']) return true;
				}
			}
		}
		return false;
	}
	
	
	
	//Admin Functions
	
	/* Adding and Removing of Heelers */
	
	public function heelers() {
		$this->load->model('auth_model');
		$netid = $this->auth_model->ID();
		$me = $this->auth_model->auth();
		$this->auth_model->secure($me,3);//Heel admin only
		
		$this->load->helper('form');
		$this->load->helper('url');
		
		$data['header'] = $this->load->view('header_view', '', TRUE);
		$data['title'] = 'Guild of Carillonneurs';
		
		$this->db->order_by("firstname", "asc");
		$heelers = $this->db->get('heelers')->result();
		
		
		
		/*
		for ($i=0;$i<count($heelers);$i++) {
			$heeler = $heelers[$i];
			$teacher = false;
			
			for ($p=0;$p<count($guildies);$p++) {
				if ($guildies[$p]->id == $heelers[$i]->teacher) {
					$teacher = $guildies[$p];
					break;
				}
			}
			$heeler->teacher = $teacher;
		}*/
		$data['slotup'] = 0;
		$data['slotdown'] = 0;
		$data['slottotal'] = 0;
		$data['slotattime'] = 0;
		
		if (count($heelers) > 0) {
			$heeler = $heelers[0];
			$data['slotup'] = $heeler->upcount;
			$data['slotdown'] = $heeler->downcount;
			$data['slottotal'] = $heeler->totalcount;
			$data['slotattime'] = $heeler->attimecount;
		}
		
		
		
		$data['heelers'] = $heelers;
		$guildies = $this->db->get('guildies')->result();
		$data['guildies'] = $guildies;
		
		$data['user'] = $me;
		
		$data['content'] = $this->load->view('heelers_view', $data, TRUE);
		$this->load->view('page_view',$data);
	}
	
	public function edit() {
			$this->load->model('auth_model');
			$netid = $this->auth_model->ID();
			$me = $this->auth_model->auth();
			
			$this->editHeeler(FALSE,0);
	}
		
		private function editHeeler($error,$i) {
			$this->load->helper('url');
			$this->load->helper('form');
			
			$data['header'] = $this->load->view('header_view', '', TRUE);
			$data['title'] = 'Guild of Carillonneurs';
			
			
			
			$entryID = $this->uri->segment(3);
			if (!$entryID) $entryID = $i;
			
			if ($entryID) {
				$this->db->where('id', $entryID);
				$query = $this->db->get('heelers');
			}
			
			if ($entryID) {
				$entries = $query->result();
				$data['heeler'] = $entries[0];
				if ($query->num_rows() == 0) {
					redirect("heelmanager/heelers");
					return;
				}
			} else {
				$data['heeler'] = FALSE;
			}
			
			$data['error'] = $error;
			
			$guildies = $this->db->get('guildies')->result();
			$data['guildies'] = $guildies;
			
			$data['content'] = $this->load->view('heeler_edit_view', $data, TRUE);
			$this->load->view('page_view',$data);
		}
		
		public function add() {
			$this->load->model('auth_model');
			$netid = $this->auth_model->ID();
			$me = $this->auth_model->auth();
			
			$this->load->helper('url');
			$this->load->helper('form');
			
			$guildies = $this->db->get('guildies')->result();
			$data['guildies'] = $guildies;
			
			$data['header'] = $this->load->view('header_view', '', TRUE);
			$data['title'] = 'Guild of Carillonneurs';
			
			$data['heeler'] = FALSE;
			$data['content'] = $this->load->view('heeler_edit_view', $data, TRUE);
			
			$this->load->view('page_view',$data);
		}
		
		public function superadd() {
			$this->load->model('auth_model');
			$netid = $this->auth_model->ID();
			$me = $this->auth_model->auth();
			
			$this->load->helper('url');
			$this->load->helper('form');
			
			$data['header'] = $this->load->view('header_view', '', TRUE);
			$data['title'] = 'Guild of Carillonneurs';
			$data['content'] = $this->load->view('heeler_superadd_view', $data, TRUE);
			$this->load->view('page_view',$data);
		}
		
		public function superaddSubmit() {
			$this->load->model('auth_model');
			$netid = $this->auth_model->ID();
			$me = $this->auth_model->auth();
			
			$netIDs = $this->input->post('netids');
			
			$newHeelers = explode("\n", $netIDs);
			
			for ($i=0;$i<count($newHeelers);$i++) {
				$netid = $newHeelers[$i];
				
				$ldapData = $this->auth_model->ldapget($netid);
				
				if ($ldapData) {
					$name = explode(" ",$ldapData['name']);
					
					if (count($name) == 1) {
						//No spaces
						$heelerData['firstname'] = implode(" ",$name);
						$heelerData['lastname'] = ''; 
					} else {
						$heelerData['firstname'] = $name[0];
						$name[0] = "";
						$heelerData['lastname'] = implode("",$name);
					}
					
					$heelerData['email'] = $ldapData['email'];
					$heelerData['year'] = $ldapData['class'];
					$heelerData['college'] = $ldapData['college'];
				}
				$heelerData['netid'] = $netid;
				$heelerData['phone'] = '';
				$heelerData['teacher'] = '';
				$this->db->insert('heelers', $heelerData);
			}
			
			redirect("./heelmanager/heelers");
		}
		
		public function remove() {
			$this->load->model('auth_model');
			$netid = $this->auth_model->ID();
			$me = $this->auth_model->auth();
			
			
			$this->load->helper('url');
			$this->load->helper('form');
			
			$this->db->where('id', $this->uri->segment(3));
			$this->db->delete('heelers');
			
			//Also need to clear their practice slots!!!
			
			$this->db->where('bookee', $this->uri->segment(3));
			$this->db->where('isHeeler',1);
			$this->db->delete('bookedslots');
			
			redirect('heelmanager/heelers');
		}
		
		
		public function upgradeGuildie() {
			$this->load->model('auth_model');
			$netid = $this->auth_model->ID();
			$me = $this->auth_model->auth();
			
			
			$this->db->where('id', $this->uri->segment(3));
			$guildie = $this->db->get("heelers")->result();
			$guildie = $guildie[0];
			
			$this->db->where('id', $this->uri->segment(3));
			$this->db->delete('heelers');
			
			
			$newGuildieData = array(
			   	'firstname' => $guildie->firstname,
				'lastname' => $guildie->lastname,
				'year' => $guildie->year,
				'email' => $guildie->email,
				'college' => $guildie->college,
				'netid' => $guildie->netid,
				//'teacher' => $guildie->teacher,
				'phone' => $guildie->phone,
				'inGuild' => 1,
			   	//nickname
				//bio
				//from
				'active' => 1,
				//major
				//office
				'inGuild' => 1,
				//birthday 
			);
			
			$this->db->insert('guildies', $newGuildieData);
			
			//echo "<pre>";
			//print_r($newGuildieData);
			//echo "</pre>";
			
			$this->load->helper('url');
			$this->load->helper('form');
			
			redirect('heelmanager/heelers');
		}
		
		
		public function heelerUpdate() {
			$this->load->model('auth_model');
			$netid = $this->auth_model->ID();
			$me = $this->auth_model->auth();
			
			
			$this->load->helper('url');
			$this->load->helper('form');
			
			if ($_POST['firstname'] == '') {
				$this->editHeeler('All heelers must have a name!',$_POST['id']);
				return;
			}
			
			unset($_POST['update']);
						
			if ($_POST['id'] == 0) {
				//Add the entry
				$this->db->insert('heelers', $_POST);
			} else {
				$this->db->where('id', $_POST['id']); 
				$this->db->update('heelers', $_POST);
			}
			
			
			//print_r($_POST);
					
			redirect('heelmanager/heelers');
		}
		
		public function heelersListSubmit() {
			$this->load->model('auth_model');
			$netid = $this->auth_model->ID();
			$me = $this->auth_model->auth();
			
			$this->load->helper('url');
			$this->load->helper('form');
			
			$postData = $this->input->post();
			
			unset($postData['update']);
			
			$slotup = $postData['slotup'];
			$slotdown = $postData['slotdown'];
			$slottotal = $postData['slottotal'];
			$slotattime = $postData['slotattime'];
			unset($postData['slotup']);
			unset($postData['slotdown']);
			unset($postData['slottotal']);
			unset($postData['slotattime']);
			
			foreach ($postData as $postString => $value) {
				$postString = explode("_",$postString);
				$heelerID = $postString[1];
				$this->db->where('id', $heelerID); //Heeler ID is second element
				if ($postString[0] == "phone") {
					$heelerData['phone'] = $value;
				} else if ($postString[0] == "teacher") {
					$heelerData['teacher'] = $value;
				}
				
				$heelerData['downcount'] = $slotdown;
				$heelerData['upcount'] = $slotup;
				$heelerData['totalcount'] = $slottotal;
				$heelerData['attimecount'] = $slotattime;
				$this->db->update('heelers', $heelerData);
			}
			redirect("./heelmanager/heelers");
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
			
			$results = $this->db->get('heelers')->result();
			
			$results = json_encode($results);
			
			$data = array("content" => $results);
			$this->load->view('script_view', $data);
		}
	
	
	
}

/* End of file homepage.php */
/* Location: ./application/controllers/homepage.php */