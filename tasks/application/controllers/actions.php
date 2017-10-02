<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Actions extends CI_Controller {



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








	public function account(){

        $this->data['message'] = $this->session->flashdata('message');
		$this->data['main_view']="account_view";
		$this->load->helper(array('form', 'url'));

				$this->load->library('form_validation');

				$this->form_validation->set_rules('old_pass', 'Old Password', 'trim|required');

				$this->form_validation->set_rules('new_pass', 'New Password', 'trim|required|xss_clean|min_length[5]|max_length[12]');

				if ($this->form_validation->run() == TRUE){

					$data = array(

						'old_pass' => $this->input->post('old_pass'),

						'new_pass' => $this->input->post('new_pass'),

						'user_name' => $this->session->userdata('username')

					 );

					$this->load->model('auth_model');

					$msg = $this->auth_model->change_pass($data);


					if ($msg['auth']=='1') {

						$this->session->set_flashdata('message', '<div class="alert alert-success" >'.$msg['message'].'</div>');

						redirect('actions/account');

					}

					else {

						$this->session->set_flashdata('message', '<div class="alert alert-success" >'.$msg['message'].'</div>');

						redirect('actions/account');

					}

					

				}		



		$this->load->model('page_info_model');
		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);
		$this->load->view('includes/template',$this->data);		

	}






	public function edit_profile(){

        $this->data['message'] = $this->session->flashdata('message');
		$this->data['main_view']="members/profile_edit_view";


		//Load Profile Info
		$logged = $this->session->userdata('user_id');
		$this->load->model('user_model');
		$this->data['profile_data'] = $this->user_model->find_user_by_id($logged);



		//ONLY IF THE FORM WAS SUBMITTED

			$this->load->library('form_validation');

			$this->form_validation->set_rules('user_fname', 'First Name', 'required|min_length[3]');
			$this->form_validation->set_rules('user_lname', 'Last Name', 'required|min_length[3]');
    

			if ($this->form_validation->run() == TRUE){

				
			//=========================Cleanup on form submit =======================

			$image = $_FILES['userfile']['name']; 

			if(!empty($image)) {

				// Uploading image =========================================================


				$config['upload_path'] = './uploads/profile_data/';

				$config['allowed_types'] = 'gif|jpeg|png|bmp|jpg';
				$config['max_size']	= '5120';
				$config['max_width']  = '2000';
				$config['max_height']  = '2000';
				$config['encrypt_name'] = TRUE;
					

				$this->load->library('upload', $config);

				if($this->upload->do_upload()){

					$file_data = $this->upload->data();

					$file_name = $file_data['raw_name'];
					$file_ext = $file_data['file_ext'];


								//LETS DOWNSIZE

								$config['image_library'] = 'gd2';
								$config['source_image'] = './uploads/profile_data/'.$file_name.$file_ext;
								$config['maintain_ratio'] = TRUE;
								$config['width'] = 200;
								$config['height'] = 200;
								$this->load->library('image_lib', $config);

								$this->image_lib->resize();
								
					//Data with new Profile Image

					$data = array(
							'user_fname' => $this->input->post('user_fname'),
							'user_lname' => $this->input->post('user_lname'),
							'user_phone' => $this->input->post('user_phone'),
							'user_address_1' => $this->input->post('user_address_1'),
							'user_address_2' => $this->input->post('user_address_2'),
							'user_avatar_img' => $file_name,
							'user_avatar_ext' => $file_ext,
							'user_designation' => $this->input->post('user_designation')
							);



						if ($this->input->post('old_image_name')) {

							$del_name = $this->input->post('old_image_name');
							$del_ext = $this->input->post('old_image_ext');

							$del_file_name = $del_name.$del_ext;
									
							$del_file = "uploads/profile_data/".$del_file_name;

								if($action = unlink($del_file)){

							 	} 
						} 
				}// image upload was successfull
				else {
						$error = array('error' => $this->upload->display_errors());
						$this->session->set_flashdata('message', '<div class="alert alert-error" >'.$error['error'].'</div>' );
						redirect('user/edit/profile');
				}

			}//image was not empty 
			else {

				//Data without Profile Image
				$data = array(
							'user_fname' => $this->input->post('user_fname'),
							'user_lname' => $this->input->post('user_lname'),
							'user_phone' => $this->input->post('user_phone'),
							'user_address_1' => $this->input->post('user_address_1'),
							'user_address_2' => $this->input->post('user_address_2'),
							'user_designation' => $this->input->post('user_designation')
							);	

			}

			$this->load->model('user_model');
			$this->user_model->update_profile($logged,$data);
		}
	


		$this->load->model('page_info_model');
		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);

		$this->load->view('includes/template',$this->data);		

	}



	public function manage_team(){

        $this->data['message'] = $this->session->flashdata('message');
		$this->data['main_view']="team/manage_team_view";


		//Load My Team
		$this->load->model('user_model');
		$this->data['team_members'] = $this->user_model->get_team_members();



		//ONLY IF THE USER SUBMITTED SOMETHING
		//=============================================================================


			//lets load the form validation RULES
			$this->form_validation->set_rules('team_invite', 'Username or email', 'required|min_length[2]');
		
			//IF FORM VALIDATION WAS SUCCESSFULL
			if ($this->form_validation->run() == TRUE)
			{  

				
				$email_myself = $this->session->userdata('user_email');
				$user_myself = $this->session->userdata('username');

				$invite = $this->input->post('team_invite');
				$from_member = $this->input->post('from_member');

				//STOP THE FUN, THIS IS SERIOUS!!! YOU CANNOT INVITE YOURSELF :D
				if ( ($email_myself==$invite) || ($user_myself==$invite) ) {
					$this->session->set_flashdata('message', '<div class="alert alert-error" >Opps! Sorry, but you cannot invite yourself... Care to try again?</div> ');
					redirect('team/manage');
				}

				//LETS CHECK IF THIS INVITATION ALREADT EXISTS
				$exist = $this->db->from('tf_team_invite')->where('to_email',$invite)->where('from_member',$from_member)->get()->num_rows();
				
				if ($exist!=0) {
					$this->session->set_flashdata('message', '<div class="alert alert-error" > Sorry, it seems like you\'ve already invited this user. We\'ll notify you whenever the user responds to your request...</div> ');
					redirect('team/manage');
				}


				//Lets see if such an user already exists, if he does, get his email
				$this->load->model('user_model');
				$result = $this->user_model->find_user_by_email_or_username($invite);

				if ($result!=0) {
					foreach ($result as $res) {
						$email = $res->user_email;

						//Lets Create the invitation Request
						//tf_tid, to_email, from_member, created, accepted, status
						$data = array(
										'to_email' => $email,
										'from_member' =>$from_member,
										'created' => time(),
										'status' => 0
										);

						$create = $this->db->insert('tf_team_invite',$data);
						//Done!

						//TIME TO CREATE THE NOTIFICATION AND SEND AN EMAIL

							//Lets create a notification for them
							//pn_id, pn_to, pn_from, pn_created, pn_content, pn_link
							$pn_content = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')
											." Invited you to join his TASKFEED TEAM. ";

							$pn_link = base_url('team/invites');
							$pn_title = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')."
										Invited you to join his TASKFEED TEAM";


							$notification_data = array(
														'pn_to' => $res->id,
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
							
							$subject = "TaskFeed : Invitation to Join a Taskfeed Team";
							$message = 	"<html><body>"
										."<div style='width:99%; height:60px; line-height:50px; background:#852B99;' >
										<h1 style='color:#fff; padding:0px 0px 0px 25px; ' >T a s k F e e d</h1></div>
										<div style='width:95%; border:thin solid #ccc; padding:10px; ' >"
										."<p>Hi ".$res->user_fname." ".$res->user_lname
										." ! Good news! <br /><br />You are invited to join the TASKFEED TEAM managed by <strong>"
										.$this->session->userdata('user_fname')
										." ".$this->session->userdata('user_lname')."</strong><br /><br />"
										."Please use the following link to review the request and join the team at your earliest time!<br /><br />"
										."<a style='display:block; width:200px; text-decoration:none; height:40px; background:#1D943B; font-size:18px; font-weight:bolder;
										color:#fff; line-height:40px; text-align:center; ' href='".base_url('team/manage')."' >Accept &amp; View Invitation </a></p><br />"
										."<p style='color:#888'>TaskFeed Team, ".date('Y')."</p>
										</div></body></html>";
						
						

							//echo $message; exit; 

							$this->email->from('do-not-reply@taskfeed.com', $from_project_creator);
							$this->email->to($res->user_email);

							$this->email->subject($subject);
							$this->email->message($message,TRUE);
							
							if(!$this->email->send()){
								//echo $this->email->print_debugger();
								//exit;
							}


							$this->session->set_flashdata('message', '<div class="alert alert-success" > We have sent an invitation to join your TASKFEED TEAM to the specified user. He will be available to be invited to your projects as soon as he accepts the request. We\'ll notify you too!</div>');
							redirect('team/manage');




					}
				}else{
					$this->form_validation->set_rules('team_invite', 'Email Address', 'trim|required|xss_clean|valid_email');
						if ($this->form_validation->run() == TRUE){ 
							//OKAY this buddy is not in Taskfeed yet... LETS SEND AN EMAIL TO EM!

							$email = $this->input->post('team_invite');
							$from_member = $this->input->post('from_member');

										$data = array(
										'to_email' => $email,
										'from_member' =>$from_member,
										'created' => time(),
										'status' => 0
										);

						$create = $this->db->insert('tf_team_invite',$data);
						//Done!

						//TIME TO CREATE THE NOTIFICATION AND SEND AN EMAIL

										

							$from_project_creator = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')." | Taskfeed";
							
							$subject = "TaskFeed : Invitation to Join a Taskfeed Team";
							$message = 	"<html><body>"
										."<div style='width:99%; height:60px; line-height:50px; background:#852B99;' >
										<h1 style='color:#fff; padding:0px 0px 0px 25px; ' >T a s k F e e d</h1></div>
										<div style='width:95%; border:thin solid #ccc; padding:10px; ' >"
										."<p>Hi! Good news! <br /><br />You have been invited to join the TASKFEED TEAM managed by <strong>"
										.$this->session->userdata('user_fname')
										." ".$this->session->userdata('user_lname')."</strong><br /><br />"
										."Please use the following link to REGISTER at TASKFEED and accept the request to join the team at your earliest time!<br /><br />"
										."<a style='display:block; width:200px; text-decoration:none; height:40px; background:#1D943B; font-size:18px; font-weight:bolder;
										color:#fff; line-height:40px; text-align:center; ' href='".base_url('authentication/register')."' >Register!</a></p><br />"
										."<p style='color:#888'>TaskFeed Team, ".date('Y')."</p>
										</div></body></html>";
						
						

							//echo $message; exit; 

							$this->email->from('do-not-reply@taskfeed.com', $from_project_creator);
							$this->email->to($email);

							$this->email->subject($subject);
							$this->email->message($message,TRUE);
							
							if(!$this->email->send()){
								//echo $this->email->print_debugger();
								//exit;
							}

							$this->session->set_flashdata('message', '<div class="alert alert-success" >User not in Taskfeed yet.. However, we have sent an invitation email to Register here and join your TASKFEED TEAM to the specified email address. He will be available to be invited to your projects as soon as he accepts the request. We\'ll notify you too!</div>');
							redirect('team/manage');

						} else{
							$this->session->set_flashdata('message', '<div class="alert alert-error" > Sorry the email address you entered, is not a valid one. If it was an username, specified user does not exist. Please check that you spelled it correctly...</div>');
							redirect('team/manage');
						}
				}

			}






		$this->load->model('page_info_model');
		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);

		$this->load->view('includes/template',$this->data);				
	}




	public function team_invites(){

    
        $this->data['message'] = $this->session->flashdata('message');

		$this->data['main_view']="team/team_invites_view";

		$this->load->model('user_model');
		$this->data['team_join'] = $this->user_model->get_invites();


		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);
		$this->load->view('includes/template',$this->data);		

	}




	public function team_accept_request(){

		$tf_tid = $this->uri->segment(3);

		$username = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname');
		//Lets pull data about this team and email the owner!
		$this->load->model('user_model');
		$team_info = $this->user_model->get_team_info_owner($tf_tid);

		foreach ($team_info as $info) {
			$email = $info->user_email;
			$team_owner = $info->user_fname." ".$info->user_lname;
		}


		//Lets accept the request quickly
		$data = array('status' => '1', 'accepted' => time());
		$accept = $this->db->where('tf_tid',$tf_tid)->update('tf_team_invite',$data);


		//Lets email him

			$from_project_creator = $team_owner." | Taskfeed";
							
			$subject = "TaskFeed : Team Invitation Accepted by ".$username;
			$message = 	"<html><body>
						<div style='width:99%; height:60px; line-height:50px; background:#852B99;' >
						<h1 style='color:#fff; padding:0px 0px 0px 25px; ' >T a s k F e e d</h1></div>
						<div style='width:95%; border:thin solid #ccc; padding:10px; ' >"
						."<p>Hi ".$team_owner." ! Good news! <br /><br /> Invitation to join your TaskFeed Team has been accepted by <strong>"
						.$username."</strong></p><br /><p>You can now invite ".$username." to your projects.<br />"
						."<p style='color:#888'>TaskFeed Team, ".date('Y')."</p>
						</div></body></html>";
						

						$this->email->from('do-not-reply@taskfeed.com', $from_project_creator);
						$this->email->to($email);

						$this->email->subject($subject);
						$this->email->message($message,TRUE);
							
						if(!$this->email->send()){
							//echo $this->email->print_debugger();
							//exit;
						}	

			$this->session->set_flashdata('message', '<div class="alert alert-success" > Team Joining successfull! Thank you for joining '.$team_owner.'\'s TaskFeed Team.</div>');
			redirect(base_url('team/joined'));	

	}






	public function team_deny_request(){

		$tf_tid = $this->uri->segment(3);

		$username = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname');
		//Lets pull data about this team and email the owner!
		$this->load->model('user_model');
		$team_info = $this->user_model->get_team_info_owner($tf_tid);

		foreach ($team_info as $info) {
			$email = $info->user_email;
			$team_owner = $info->user_fname." ".$info->user_lname;
		}


		//Lets delete the request quickly
		$deny = $this->db->where('tf_tid',$tf_tid)->delete('tf_team_invite');


		//Lets email him

			$from_project_creator = $team_owner." | Taskfeed";
							
			$subject = "TaskFeed : Team Invitation Denied by ".$username;
			$message = 	"<html><body>
						<div style='width:99%; height:60px; line-height:50px; background:#852B99;' >
						<h1 style='color:#fff; padding:0px 0px 0px 25px; ' >T a s k F e e d</h1></div>
						<div style='width:95%; border:thin solid #ccc; padding:10px; ' >"
						."<p>Hi ".$team_owner.", this is to inform you that your Invitation to <strong>"
						.$username."</strong> for joining your Taskfeed Team has been denied at this time. </p><br /><p>If you think it was a mistake by the other party,
						please contact him via email first before inviting him again by TaskFeed. Thank you!<br />"
						."<p style='color:#888'>TaskFeed Team, ".date('Y')."</p>
						</div></body></html>";
						

						$this->email->from('do-not-reply@taskfeed.com', $from_project_creator);
						$this->email->to($email);

						$this->email->subject($subject);
						$this->email->message($message,TRUE);
							
						if(!$this->email->send()){
							//echo $this->email->print_debugger();
							//exit;
						}	

			$this->session->set_flashdata('message', '<div class="alert alert-warning" > Team Invitation denied by you. '.$team_owner.' has been notified by email.</div>');
			redirect(base_url('team/invites'));	

	}





	public function leave_team(){

		$tf_tid = $this->uri->segment(3);

		$username = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname');
		//Lets pull data about this team and email the owner!
		$this->load->model('user_model');
		$team_info = $this->user_model->get_team_info_owner($tf_tid);

		foreach ($team_info as $info) {
			$email = $info->user_email;
			$team_owner = $info->user_fname." ".$info->user_lname;
		}


		//Lets delete the request quickly
		$leave = $this->db->where('tf_tid',$tf_tid)->delete('tf_team_invite');


		//Lets email him

			$from_project_creator = $team_owner." | Taskfeed";
							
			$subject = "TaskFeed :".$username." left your team.";
			$message = 	"<html><body>
						<div style='width:99%; height:60px; line-height:50px; background:#852B99;' >
						<h1 style='color:#fff; padding:0px 0px 0px 25px; ' >T a s k F e e d</h1></div>
						<div style='width:95%; border:thin solid #ccc; padding:10px; ' >"
						."<p>Hi ".$team_owner.", this is to inform you that your team member <strong>"
						.$username."</strong> has left your team recently. </p><br /><p>If you think it was a mistake by the other party,
						please contact him via email first before inviting him again by TaskFeed. However, the user can still keep working for the ongoing project(s).
						Thank you!<br />"
						."<p style='color:#888'>TaskFeed Team, ".date('Y')."</p>
						</div></body></html>";
						

						$this->email->from('do-not-reply@taskfeed.com', $from_project_creator);
						$this->email->to($email);

						$this->email->subject($subject);
						$this->email->message($message,TRUE);
							
						if(!$this->email->send()){
							//echo $this->email->print_debugger();
							//exit;
						}	

			$this->session->set_flashdata('message', '<div class="alert alert-warning" > Team left by you. '.$team_owner.' has been notified by email.</div>');
			redirect(base_url('team/invites'));	

	}




	public function remove_member(){

		$tf_tid = $this->uri->segment(3);

		$team_owner = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname');
		//Lets pull data about this team and email the owner!
		$this->load->model('user_model');
		$team_info = $this->user_model->get_team_info($tf_tid);

		foreach ($team_info as $info) {
			$email = $info->user_email;
			$username = $info->user_fname." ".$info->user_lname;
		}


		//Lets delete the request quickly
		$remove = $this->db->where('tf_tid',$tf_tid)->delete('tf_team_invite');


		//Lets email him

			$from_project_creator = $team_owner." | Taskfeed";
							
			$subject = "TaskFeed : Team Modified By ".$team_owner;
			$message = 	"<html><body>
						<div style='width:99%; height:60px; line-height:50px; background:#852B99;' >
						<h1 style='color:#fff; padding:0px 0px 0px 25px; ' >T a s k F e e d</h1></div>
						<div style='width:95%; border:thin solid #ccc; padding:10px; ' >"
						."<p>Hi ".$username.", this is to inform you that <strong>"
						.$team_owner."</strong> has modified his team recently and unfortunately you are not anymore a member of his/her team. </p><br /><p>If you think it was a mistake by the other party,
						please contact him via email. However, you can still keep working for the ongoing project(s).
						Thank you!<br />"
						."<p style='color:#888'>TaskFeed Team, ".date('Y')."</p>
						</div></body></html>";
						

						$this->email->from('do-not-reply@taskfeed.com', $from_project_creator);
						$this->email->to($email);

						$this->email->subject($subject);
						$this->email->message($message,TRUE);
							
						if(!$this->email->send()){
							//echo $this->email->print_debugger();
							//exit;
						}	

			$this->session->set_flashdata('message', '<div class="alert alert-warning" > Team member removed by you. '.$username.' has been notified by email.</div>');
			redirect(base_url('team/manage'));	

	}



	public function team_joined(){

    
        $this->data['message'] = $this->session->flashdata('message');

		$this->data['main_view']="team/team_joined_view";

		$this->load->model('user_model');
		$this->data['team_join'] = $this->user_model->get_joined_teams();


		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);
		$this->load->view('includes/template',$this->data);		

	}






// end of the controller actions
}

/*		
Copying/Distributing any part of the code is strictly prohibited.
created by A.B.M Shahnewaz Rifat, 2013 
All Rights Reserved
*/