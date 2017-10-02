<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authentication extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('utilities_model');
		$this->data['cshaex'] = $this->utilities_model->get_cshaex();
		$this->data['csha'] = $this->utilities_model->get_csha();
	}


	public function authenticate(){

					$data = array(
						'action' => 'authenticate'
					 );
					$this->load->model('auth_model');
					$msg = $this->auth_model->authenticate($data);	

					if ($msg['auth']=='1') {
						
					}
					else {
						$this->session->set_flashdata('message', '<div class="alert alert-error" >'.$msg['message'].'</div');
						redirect('authentication/login');
					}

	}



	public function login(){

		//No login if already logged in
		$logged_in_user = $this->session->userdata('user_id');
		if ($logged_in_user) {
			$this->session->set_flashdata('message', '<div class="alert alert-warning" > Already Logged In </div>');
			redirect('dashboard');
		}

		$this->data['message'] = $this->session->flashdata('message');
		$this->data['old_url'] = $this->session->flashdata('old_url');

		$this->data['main_view']="login_view";


			
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');
				$this->form_validation->set_rules('user_name', 'Username', 'trim|required|xss_clean');
				$this->form_validation->set_rules('user_pass', 'Password', 'trim|required|xss_clean');


				if ($this->form_validation->run() == TRUE){

					$old_url = $this->input->post('old_url');
					if (empty($old_url)) {
						$old_url = "dashboard";
					}

					$data = array(
						'user_name' => $this->input->post('user_name'),
						'user_pass' => $this->input->post('user_pass'),
						'old_url' => $old_url,
						'action' => 'form'
					 );

					$this->load->model('auth_model');
					$msg = $this->auth_model->authenticate($data);

					if ($msg['auth']=='1') {
						$this->session->set_flashdata('message', '<div class="alert alert-success" >'.$msg['message'].'</div>');
						redirect('dashboard');
					}
					else {
						$this->session->set_flashdata('message', '<div class="alert alert-error" >'.$msg['message'].'</div>');
						redirect('authentication/login');
					}
					
				}

		$this->load->model('utilities_model');
		$this->data['csha'] = $this->utilities_model->get_csha();

		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);

		$this->load->view('login_view',$this->data);
	}




	public function register(){

		//No login if already logged in
		$logged_in_user = $this->session->userdata('user_id');
		if ($logged_in_user) {
			$this->session->set_flashdata('message', '<div class="alert alert-warning" > Already Logged In. </div>');
			redirect('dashboard');
		}



		$this->data['message'] = $this->session->flashdata('message');
		$this->data['main_view']="register_view";	


				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');
				$this->form_validation->set_rules('user_name', 'Username', 'trim|required|xss_clean|min_length[4]|alpha_dash|is_unique[users.user_name]');
				$this->form_validation->set_rules('user_fname', 'First Name', 'trim|required|xss_clean|min_length[2]');
				$this->form_validation->set_rules('user_lname', 'Last Name', 'trim|required|xss_clean|min_length[2]');
				$this->form_validation->set_rules('user_pass', 'Password', 'trim|required|xss_clean|min_length[6]');
				$this->form_validation->set_rules('user_pass_c', 'Password Confirmation', 'required|matches[user_pass]');
				$this->form_validation->set_rules('user_email', 'Email Address', 'trim|required|xss_clean|valid_email|is_unique[users.user_email]');

				if ($this->form_validation->run() == TRUE){

					//Lets prepare the password
					$password = sha1($this->input->post('user_pass'));

					$image = $_FILES['userfile']['name']; 


						if(!empty($image)) {

							// Uploading image =========================================================


							$config['upload_path'] = './uploads/profile_data/';

							$config['allowed_types'] = 'gif|jpeg|png|bmp|jpg';
							$config['max_size']	= '2048';
							$config['max_width']  = '1000';
							$config['max_height']  = '1000';
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

							}else{								
								$file_name = "";
								$file_ext = "";
							}
						}
						else{
								$file_name = "";
								$file_ext = "";
						}
						$uemail = $this->input->post('user_email');
						$crypt = sha1($uemail.time());

						$data = array(
								'user_name' => $this->input->post('user_name'), 
								'user_email' => $this->input->post('user_email'), 
								'user_pass' => $password, 
								'user_fname' => $this->input->post('user_fname'), 
								'user_lname' => $this->input->post('user_lname'), 
								'user_status' => 0, 
								'user_online' => 0,
								'user_crypt' => $crypt,
								'crypt_activated' => time(),
								'user_role' => 'User', 
								'user_avatar_img' => $file_name, 
								'user_avatar_ext' => $file_ext, 
								'user_created' => time()
						);

						$this->load->model('auth_model');
						$this->auth_model->register($data);


				}


		$this->load->model('utilities_model');
		$this->data['csha'] = $this->utilities_model->get_csha();


		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);

		$this->load->view('register_view',$this->data);



	}


	public function logout(){
		//Lets make the user offline from db
		$id = $this->session->userdata('user_id');
		$online = array('user_online' => '0');
		$this->db->where('id',$id)->update('users',$online);

		$this->session->sess_destroy();
		redirect('authentication/login');
	}



	public function forgot_password(){

        $this->data['message'] = $this->session->flashdata('message');
		$this->data['main_view']="password_recovery_view";


		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('user_email', 'Email Address', 'trim|required|xss_clean|valid_email');


			if ($this->form_validation->run() == TRUE){

				//check whether the email is registered
				$email = $this->input->post('user_email');

				$email_found = $this->db
								->from('users')
								->where('user_email',$email)
								->get()
								->num_rows();


				if ($email_found!=0) {

					$crypt = sha1($email.time());

					$data = array(
								'user_crypt' => $crypt,
								'crypt_activated' =>time() 
								);

					$create_crypt = $this->db
										->where('user_email',$email)
										->update('users',$data);

					//Lets email the link to user now


						$from = "Password Reset | Taskfeed";
						
						$subject = "TaskFeed : Reset Your Password";
						$message = 	"<html><body>"
									."<div style='width:99%; height:60px; line-height:50px; background:#852B99;' >
									<h1 style='color:#fff; padding:0px 0px 0px 25px; ' >T a s k F e e d</h1></div>
									<div style='width:95%; border:thin solid #ccc; padding:10px; ' >"
									."<p>Hello dear user, A password reset request was submitted using this email address
									 (".$email.") at Taskfeed.net. 
									 <br /><br />
									 If it was not you, simply ignore this message.
									 <br />"

									."Please click the following link to reset your password now if it was you. Note that, this link will
									be valid for only the next 24 hours.<br /><br />"
									."<a style='display:block; width:200px; text-decoration:none; height:40px; background:#1D943B; font-size:18px; font-weight:bolder;
									color:#fff; line-height:40px; text-align:center; ' href='".base_url('authentication/reset-password/'.$crypt)."' >Password Reset Link </a></p><br />"
									."<p style='color:#888'>TaskFeed Team, ".date('Y')."</p>
									</div></body></html>";
					
					

						//echo $message; exit; 

						$this->email->from('do-not-reply@taskfeed.com', $from);
						$this->email->to($email);

						$this->email->subject($subject);
						$this->email->message($message,TRUE);
						
						if(!$this->email->send()){
							//echo $this->email->print_debugger();
							//exit;
						}					

					$this->session->set_flashdata('message', '<div class="alert alert-success"><p>An email has been sent to the email address '.$email.' with instructions to reset your password. Please check your inbox and follow the instuctions.</p></div>');
					redirect('authentication/password-recovery');

								
				}else{
					$this->session->set_flashdata('message', '<div class="alert alert-error"><p>Sorry, this email address is not registered with Taskfeed...</p></div>');
					redirect('authentication/password-recovery');
				}				
			

			}





		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);

		$this->load->view('password_recovery_view',$this->data);

	}






	public function reset_password(){

        $this->data['message'] = $this->session->flashdata('message');

		$this->data['main_view']="password_reset_view";


		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('user_pass', 'New Password', 'trim|required|xss_clean|min_length[6]');
		$this->form_validation->set_rules('user_pass_c', 'Password Confirmation', 'required|matches[user_pass]');


		if ($this->form_validation->run() == TRUE){

			$crypt = $this->uri->segment(3);
			//Lets check whether the reset link is valid and get information about user
			$found_crypt = $this->db
								->from('users')
								->where('user_crypt',$crypt)
								->get()
								->num_rows();
			if ($found_crypt!=0) {
				//Lets check whether the crypt is still valid 86400
				$get_crypt = $this->db
								->from('users')
								->where('user_crypt',$crypt)
								->get()
								->result();
				foreach ($get_crypt as $reset) {
					$crypt_made = $reset->crypt_activated;
				}

				$elapsed = time()-$crypt_made;

				if ($elapsed<=86400) {
					$data = array('user_pass' =>sha1($this->input->post('user_pass')) );
					$this->db->where('user_crypt',$crypt)->update('users',$data);
					$this->session->set_flashdata('message', '<div class="alert alert-success"><p>Password reset was successfull. You may now login with the new password. Thank you.</p></div>');
					redirect('authentication/login');
				}
				else{
					$this->session->set_flashdata('message', '<div class="alert alert-error"><p>The password reset link is expired. You must use the password reset link within 24 hours of requesting...</p></div>');
					redirect('authentication/login');
				}


			}else{
				$this->session->set_flashdata('message', '<div class="alert alert-error"><p>Invalid Password Reset Link</p></div>');
				redirect('authentication/login');
			}




		}





		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);

		$this->load->view('password_reset_view',$this->data);

	}








	public function resend_activation(){

        $this->data['message'] = $this->session->flashdata('message');


				//check whether the email is registered
				$username = $this->uri->segment(3);

				$find_username = $this->db
								->from('users')
								->where('user_name',$username)
								->get();

				$username_found = $find_username->num_rows();


				if ($username_found!=0) {

					//Get user's information now
					$ac_user = $find_username->result();
					foreach ($ac_user as $activate) {
						$email = $activate->user_email;
						$ac_status = $activate->user_status;
					}

					if ($ac_status==1) {
						$this->session->set_flashdata('message', '<div class="alert alert-error" >Account activated already! You may login with the username and password... </div>');
						redirect('authentication/login');
					}

					$crypt = sha1($username.time());

					$data = array(
								'user_crypt' => $crypt,
								'crypt_activated' =>time() 
								);

					$create_crypt = $this->db
										->where('user_name',$username)
										->update('users',$data);

					//Lets email the link to user now


						$from = "Activate Account | Taskfeed";
						
						$subject = "TaskFeed : Activate Your Account";
						$message = 	"<html><body>"
									."<div style='width:99%; height:60px; line-height:50px; background:#852B99;' >
									<h1 style='color:#fff; padding:0px 0px 0px 25px; ' >T a s k F e e d</h1></div>
									<div style='width:95%; border:thin solid #ccc; padding:10px; ' >"
									."<p>Hello dear user, A RESEND ACTIVATION request was submitted using this email address
									  at Taskfeed.net. 
									 <br /><br />
									 If it was not you, simply ignore this message.
									 <br />"

									."Please click the following link to ACTIVATE your ACCOUNT now if it was you. Note that, this link will
									be valid for only the next 24 hours.<br /><br />"
									."<a style='display:block; width:200px; text-decoration:none; height:40px; background:#1D943B; font-size:18px; font-weight:bolder;
									color:#fff; line-height:40px; text-align:center; ' href='".base_url('activate/account/'.$crypt)."' >Activate Now</a></p><br />"
									."<p style='color:#888'>TaskFeed Team, ".date('Y')."</p>
									</div></body></html>";
					
					

						//echo $message; exit; 

						$this->email->from('do-not-reply@taskfeed.com', $from);
						$this->email->to($email);

						$this->email->subject($subject);
						$this->email->message($message,TRUE);
						
						if(!$this->email->send()){
							//echo $this->email->print_debugger();
							//exit;
						}					

					$this->session->set_flashdata('message', '<div class="alert alert-success"><p>An email has been sent to the registered email address with Activation link. Please don\'t forget to check your Junk/Spam folder if you don\'t see the email.</p></div>');
					redirect('authentication/login');

								
				}else{
					$this->session->set_flashdata('message', '<div class="alert alert-error"><p>Sorry, this email address is not registered with Taskfeed...</p></div>');
					redirect('authentication/login');
				}				
			







		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);

		$this->load->view('password_recovery_view',$this->data);

	}



	public function activate_account(){

		$crypt = $this->uri->segment(3);

		if ($crypt) {
			//LETS CHECK IF USER WITH THIS CRYPT EXISTS!
			$found_crypt = $this->db
								->from('users')
								->where('user_crypt',$crypt)
								->get()
								->num_rows();

			if ($found_crypt!=0) {
				//Lets check whether the crypt is still valid 86400
				$get_crypt = $this->db
								->from('users')
								->where('user_crypt',$crypt)
								->get()
								->result();

				foreach ($get_crypt as $reset) {
					$crypt_made = $reset->crypt_activated;
					$username = $reset->user_name;
					$user_status = $reset->user_status;
				}

				if ($user_status==1) {
					$this->session->set_flashdata('message', '<div class="alert alert-error" >Account already activated. Please use your username and password to login.</div> ');
					redirect('authentication/login');
				}	
				

					$elapsed = time()-$crypt_made;

					if ($elapsed<=86400) {
						$data = array('user_status' =>'1');
						$this->db->where('user_crypt',$crypt)->update('users',$data);
						$this->session->set_flashdata('message', '<div class="alert alert-success"><p>Account Activation was successfull. You may now login with the username and password. Thank you.</p></div>');
						redirect('authentication/login');
					}
					else{
						$this->session->set_flashdata('message', '<div class="alert alert-error"><p>The Activation link is expired. You must use the Activaton link within 24 hours of requesting...<br />OR, you may 
						<a href="'.base_url('authentication/resend-activation').'/'.$username.'" >Resend Activation Link</a></p></div>');
						redirect('authentication/login');
					}
			}else{
				$this->session->set_flashdata('message', '<div class="alert alert-error" >Invalid activation token. <a href="'.base_url('authentication/resend-activation').'/'.$username.'" >Resend Activation Link</a> ?</div> ');
				redirect('authentication/login');				
			}


		}else{
			$this->session->set_flashdata('message', '<div class="alert alert-error" >Invalid activation token. <a href="'.base_url('authentication/resend-activation').'/'.$username.'" >Resend Activation Link</a> ?</div> ');
			redirect('authentication/login');
		}


	}






// CLASS ENDS AFTER THIS LINE
}

/*		
Copying/Distributing any part of the code is strictly prohibited.
created by A.B.M Shahnewaz Rifat, 2013 
All Rights Reserved
*/