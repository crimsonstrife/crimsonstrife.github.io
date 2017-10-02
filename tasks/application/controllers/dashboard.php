<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();

		//Authentication========================
		$old_uri = uri_string();
		$data = array('action' =>'authenticate');
		$this->load->model('auth_model');
		$this->auth_model->authenticate($data,$old_uri);
		$this->auth_model->intchk();
		//Authentication========================

		//NOTIFICATIONS ========================
		$this->load->model('notification_model');
		$notif = $this->notification_model->unread_notifications();
		$this->data['notifications'] = $notif['notifications'];
		$this->data['unread'] = $notif['num'];

		$this->data['team_invites'] = $this->notification_model->team_invites(); 
		//NOTIFICATIONS ========================	

		$this->load->model('utilities_model');
		$this->data['cshaex'] = $this->utilities_model->get_cshaex();				
	}


	public function index(){
    
        $this->data['message'] = $this->session->flashdata('message');

		$this->data['main_view']="dashboard_view";


		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);

		// Load the work Feed now
		$this->data['feeds'] = $this->notification_model->home_notifications();

		$this->data['calendar_events'] = $this->notification_model->calendar_data();
		

		$this->load->view('includes/template',$this->data);
	}





	public function all_feeds(){
    
        $this->data['message'] = $this->session->flashdata('message');

		$this->data['main_view']="members/all_feeds_view";


		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);


		// Pagination =================================
		$num_rows = $this->notification_model->get_notifications_num();


		$this->load->library('pagination');

		$config['base_url'] = base_url('dashboard/all-feeds');
		$config['total_rows'] = $num_rows;
		$config['per_page'] = 10;
		$config['uri_segment'] = 3;
		$config['num_links'] = 4;
		$config['use_page_numbers'] = FALSE;

		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$config['cur_tag_open'] = '<li><a href="#" ><b>';
		$config['cur_tag_close'] = '</b></a></li>';

		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';	

		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';

		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';


		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';	

		$this->pagination->initialize($config);

		
		//Pagination ==================================


		// Load the work Feed now
		$condition =  array('limit' =>$config['per_page'] , 'offset' =>$this->uri->segment(3) );
		$this->data['feeds'] = $this->notification_model->notifications($condition);

		$this->data['calendar_events'] = $this->notification_model->calendar_data();
		

		$this->load->view('includes/template',$this->data);
	}







	public function unread(){
    
        $this->data['message'] = $this->session->flashdata('message');

		$this->data['main_view']="members/all_feeds_view";

		$this->data['custom_nav_title'] = "All Unread Notifications For ";

		$this->data['custom_page_title'] = "All Unread Notifications";


		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);


		// Pagination =================================
		$num = $this->notification_model->unread_notifications();
		$num_rows = $num['num'];


		$this->load->library('pagination');

		$config['base_url'] = base_url('dashboard/unread');
		$config['total_rows'] = $num_rows;
		$config['per_page'] = 10;
		$config['uri_segment'] = 3;
		$config['num_links'] = 4;
		$config['use_page_numbers'] = FALSE;

		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$config['cur_tag_open'] = '<li><a href="#" ><b>';
		$config['cur_tag_close'] = '</b></a></li>';

		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';	

		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';

		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';


		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';	

		$this->pagination->initialize($config);

		
		//Pagination ==================================


		// Load the work Feed now
		$condition =  array('limit' =>$config['per_page'] , 'offset' =>$this->uri->segment(3) );
		$this->data['feeds'] = $this->notification_model->all_unread_notifications($condition);

		$this->data['calendar_events'] = $this->notification_model->calendar_data();
		

		$this->load->view('includes/template',$this->data);
	}





	public function mark_all_read(){

		$my_id = $this->session->userdata('user_id');

		//tf_notification
		//pn_id, pn_to, pn_from, pn_created, pn_content, pn_link, pn_title, pn_unread
							
		$data = array('pn_unread' => 0 );
		$notify['num'] = $this->db
							->where('pn_to',$my_id)
							->where('pn_unread',1)
							->update('tf_notification',$data);

		redirect('dashboard');

	}


//class ends after this line
}


/*		
Copying/Distributing any part of the code is strictly prohibited.
created by A.B.M Shahnewaz Rifat, 2013 
All Rights Reserved
*/