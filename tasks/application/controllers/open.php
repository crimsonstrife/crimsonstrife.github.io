<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Open extends CI_Controller {

	public function __construct(){
		parent::__construct();


		//NOTIFICATIONS ========================
		$logged_in = $this->session->userdata('authenticated');
		if ($logged_in!==FALSE) {

			$this->load->model('notification_model');
			$notif = $this->notification_model->unread_notifications();
			$this->data['notifications'] = $notif['notifications'];
			$this->data['unread'] = $notif['num'];

			$this->data['team_invites'] = $this->notification_model->team_invites(); 
			
		}
		//NOTIFICATIONS ========================		

		$this->load->model('utilities_model');
		$this->data['cshaex'] = $this->utilities_model->get_cshaex();
	}


	public function index(){

			redirect('about-taskfeed');
	}



	public function profile(){

        $this->data['message'] = $this->session->flashdata('message');
		$this->data['main_view']="members/profile_view";


		//Load Profile Info
		$uname = $this->uri->segment(3);
		$this->load->model('user_model');
		$this->data['profile_data'] = $this->user_model->find_user_by_username($uname);


		$this->load->model('page_info_model');
		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);

		$this->load->view('includes/template',$this->data);		

	}





	public function about_taskfeed(){
    
        $this->data['message'] = $this->session->flashdata('message');

		$this->data['main_view']="general/about_view";


		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);

		$this->load->view('includes/template',$this->data);
	}




	public function terms(){
    
        $this->data['message'] = $this->session->flashdata('message');

		$this->data['main_view']="general/terms_view";


		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);
		
		$this->load->view('includes/template',$this->data);
	}



	public function privacy(){
    
        $this->data['message'] = $this->session->flashdata('message');

		$this->data['main_view']="general/privacy_view";


		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);

		$this->load->view('includes/template',$this->data);
	}









//class ends after this line
}


/*		
Copying/Distributing any part of the code is strictly prohibited.
created by A.B.M Shahnewaz Rifat, 2013 
All Rights Reserved
*/