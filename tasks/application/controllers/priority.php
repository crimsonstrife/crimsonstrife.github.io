<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Priority extends CI_Controller {

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
		
		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');  		
	}




	public function index()
	{
		redirect('priority/shared');
	}


	public function shared(){

        $this->data['message'] = $this->session->flashdata('message');

		$this->data['main_view']="priority/priority_shared_view";


		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);


		//Load Project Groups
		$user_id = $this->session->userdata('user_id');
		$this->load->model('priority_model');
		$this->data['priority_shared'] = $this->priority_model->get_shared_priority($user_id);

		$this->load->view('includes/template',$this->data);		
	}




	public function manage(){

        $this->data['message'] = $this->session->flashdata('message');

		$this->data['main_view']="priority/priority_manage_view";


		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);


		//Load Project Groups
		$user_id = $this->session->userdata('user_id');

		$this->load->model('priority_model');
		$this->data['priority_manage'] = $this->priority_model->get_own_priority($user_id);

		$this->load->view('includes/template',$this->data);		
	}




	public function view_priority(){

        $this->data['message'] = $this->session->flashdata('message');

		$this->data['main_view']="priority/priority_view";


		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);


		//Load Project Groups
		$prio_link = $this->uri->segment(3);

		$this->load->model('priority_model');

		$priority = $this->priority_model->view_priority($prio_link);

		$this->data['priority'] = $priority['priority'];
		$this->data['prio_data'] = $priority['data'];
		$this->data['prio_shared'] = $priority['shared'];



		//Sharing is caring
		$this->load->model('project_model');
		$this->data['contractors'] = $this->project_model->get_all_contractors();


		$this->load->view('includes/template',$this->data);				
	}


	public function create(){

		$this->form_validation->set_rules('prio_name', 'Priority Queue Name', 'required');

		if ($this->form_validation->run() == TRUE)
		{  


			//generate the URL (friendly)
			$this->load->model('utilities_model');

			$title = $this->input->post('prio_name');
			$prio_link = $this->utilities_model->create_project_url($title);

			$owner = $this->session->userdata('user_id');

			//echo $prio_url; exit;
			//prio_id, prio_owner, prio_name, prio_created, prio_last_modified, prio_link

			$data = array(
							'prio_name' => $title,
							'prio_link' => $prio_link,
							'prio_owner' => $owner,
							'prio_created' => time(), 
							'prio_last_modified' => time()
						);

			$this->load->model('priority_model');
			$ret = $this->priority_model->create_priority($data);

			if ($ret==1) {
				$this->session->set_flashdata('message', '<div class="alert alert-success" >Priority Queue Created.</div>');
				redirect('priority/view/'.$prio_link);
			}
			else{
				$this->session->set_flashdata('message', '<div class="alert alert-error" >Could not create the priority queue. Please try again later...</div>');
				redirect('priority/shared');				
			}



		} else {

			$this->session->set_flashdata('message', '<div class="alert alert-error" >'.validation_errors().'</div>');
			redirect('priority/shared');

			//echo validation_errors(); exit;	

		}


	}



	public function new_task(){

		$prio_link = $this->input->post('return_link');

		$this->form_validation->set_rules('pdata_title', 'Task Title', 'required');

		if ($this->form_validation->run() == TRUE)
		{  

			//pdata_id, pdata_title, pdata_order, pdata_progress, pdata_last_modified, prio_id, pdata_requested_by

			$data = array(
							'pdata_title' => $this->input->post('pdata_title'),
							'pdata_order' => 99999999,
							'pdata_progress' => 'New',
							'pdata_last_modified' => time(), 
							'prio_id' => $this->input->post('prio_id')
						);

			$this->load->model('priority_model');
			$ret = $this->priority_model->add_priority_task($data);

			if ($ret==1) {
				$this->session->set_flashdata('message', '<div class="alert alert-success">Task added to priority queue.</div>');
				redirect('priority/view/'.$prio_link);
			}
			else{
				$this->session->set_flashdata('message', '<div class="alert alert-error">Could not add the task to priority queue. Please try again later...</div>');
				redirect('priority/view/'.$prio_link);				
			}



		} else {

			$this->session->set_flashdata('message', '<div class="alert alert-error">'.validation_errors().'</div>');
			redirect('priority/view/'.$prio_link);

			//echo validation_errors(); exit;	

		}


	}






	public function save_order(){


		foreach ( $_POST as $key => $value )
        {
            $newRank = $value;
            $currentRank = $key;

            //echo "New rank is ".$newRank." For item number". $currentRank."<br />";

            $data = array('pdata_order' => $newRank );

            $qry1 = $this->db->where('pdata_id',$currentRank)
            		->update('tf_priority_data',$data);


           	$update_data = array('prio_last_modified' =>time() );
            $prio_id = $this->uri->segment(4);
            $qry2 = $this->db->where('prio_id',$prio_id)
            		->update('tf_priority_queue',$update_data);
		}
	}



	public function share_new(){

		$prio_link = $this->input->post('return_link');

		$this->form_validation->set_rules('contractors', 'Contractors', 'required');

		if ($this->form_validation->run() == TRUE){  
			$contractors = $this->input->post('contractors');

		//================NEW CODE


		//assign the contractors and send email notifications to them


		foreach ($contractors as $contractor) {

			$prio_id = $this->input->post('prio_id');

			$assign = array(
				'prio_id' => $prio_id,
				'prio_share_id' =>$contractor,
				'prio_owner_id' =>$this->input->post('prio_owner_id')
				);

			//lets check if this has been already shared
			$share_num = $this->db->from('tf_priority_queue_share')
							->where('prio_id',$prio_id)
							->where('prio_share_id',$contractor)
							->get()
							->num_rows();
			if ($share_num==0) {

				$share_now = $this->db->insert('tf_priority_queue_share',$assign);
			
			
			//=============================================================================================
			//========================================== SEND EMAIL =======================================
			//=============================================================================================

			//lets find out who they are and lets email them!
			$this->load->model('user_model');
			$invited = $this->user_model->find_user_by_id($contractor);

			//print_r($invited); exit;


			foreach ($invited as $invite) {


				//Lets create a notification for them
				//pn_id, pn_to, pn_from, pn_created, pn_content, pn_link
				$pn_content = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')
								." has shared a Priority Queue with you.";

				$pn_link = base_url('priority/view')."/".$prio_link;
				$pn_title = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')."
							has shared a Priority Queue with you.";

				$notification_data = array(
											'pn_to' => $invite->id,
											'pn_from' => $this->session->userdata('user_id'),
											'pn_created' => time(),
											'pn_title' =>  $pn_title,
											'pn_content' => $pn_content,
											'pn_link' => $pn_link,
											'pn_unread' => 1

										);


				$notify = $this->db
							->insert('tf_notification',$notification_data);
							

				$from_project_creator = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')." | Taskfeed";
				
				$subject = "TaskFeed : Shared Priority Queue";
				$message = 	"<html><body>"
							."<div style='width:99%; height:60px; line-height:50px; background:#852B99;' >
							<h1 style='color:#fff; padding:0px 0px 0px 25px; ' >T a s k F e e d</h1></div>
							<div style='width:95%; border:thin solid #ccc; padding:10px; ' >"
							."<p>Hi ".$invite->user_fname." ".$invite->user_lname
							." ! This is email is to notify you that <br /><br /><strong>"
							.$this->session->userdata('user_fname')
							." ".$this->session->userdata('user_lname')."</strong> Has shared a Priority Queue with you.<br /><br />"
							."Please use the following link to view the Priority Queue at your earliest time!<br /><br />"
							."<a style='display:block; width:200px; text-decoration:none; height:40px; background:#1D943B; font-size:18px; font-weight:bolder;
							color:#fff; line-height:40px; text-align:center; ' href='".base_url('priority/view')."/"
							.$prio_link."' >Check The Queue Now! </a></p><br />"
							."<p style='color:#888'>TaskFeed Team, 2013</p>
							</div></body></html>";
			
			

				//echo $message; exit; 

				$this->email->from('do-not-reply@taskfeed.com', $from_project_creator);
				$this->email->to($invite->user_email);

				$this->email->subject($subject);
				$this->email->message($message,TRUE);
				
				if(!$this->email->send()){
					//echo $this->email->print_debugger();
					//exit;
				}




				}//FOREACH ENDS HERE
			}/*NOT SHARING IF ITS ALREADY SHARED */ else{} 
		}//SECOND FOREACH ENDS HERE
	
			$this->session->set_flashdata('message', '<div class="alert alert-success">Priority Queue Successfully shared with the selected users and they have been notified by an email! </div>');
			redirect('priority/view/'.$prio_link);	
		//=====================================
		//IF CONDITION after this line 
		}
		else{
			
			$this->session->set_flashdata('message', '<div class="alert alert-error">'.validation_errors().'</div>');
			redirect('priority/view/'.$prio_link);
		}

	}




	public function delete(){

		$id = $this->uri->segment(3);
		$link = $this->uri->segment(4);

		$this->load->model('priority_model');
		$res = $this->priority_model->delete_task($id);

		if ($res) {
			$this->session->set_flashdata('message', '<div class="alert alert-success" >Task deleted successfully.</div>');
			redirect('priority/view/'.$link);
		}
		else {
			$this->session->set_flashdata('message', '<div class="alert alert-error" >Sorry, the task could not be deleted...</div>');
			redirect('priority/view/'.$link);
		}	

	}




/* End of file priority.php */
}

/*		
Copying/Distributing any part of the code is strictly prohibited.
created by A.B.M Shahnewaz Rifat, 2013 
All Rights Reserved
*/


/* End of file priority.php */
/* Location: ./application/controllers/priority.php */