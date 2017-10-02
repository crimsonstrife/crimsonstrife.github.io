<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	public function authenticate($data){

		if($data['action']=="form"){
			$this->db->where('user_name',$data['user_name']);
			$qry = $this->db->get('users');
			$res = $qry->result();
			$rows = $qry->num_rows();
			if ($rows>0) {
				foreach ($res as $found) {
					$password = $found->user_pass;
					$username = $found->user_name;
					$role = $found->user_role;
					$fname = $found->user_fname;
					$lname = $found->user_lname;
					$email = $found->user_email;
					$avatar = $found->user_avatar_img.$found->user_avatar_ext;
					$id = $found->id;
					$activation = $found->user_status;
				}

				//check activation
				if($activation!=1){
					$msg['auth'] = '0';
					$msg['message'] = "Account Not Activated Yet... Please use the activation link sent to you after registration. OR, you may 
					<a href='".base_url('authentication/resend-activation')."/".$username."' >Resend Activation Link</a>";
					return $msg;						
				}


				if (sha1($data['user_pass'])==$password) {
					$array = array(
						'username' => $username ,
						'user_role' => $role,
						'user_fname' => $fname,
						'user_lname' => $lname,
						'user_email' => $email,
						'user_id' => $id,
						'avatar' =>$avatar,
						'authenticated' => 'authenticated' 
						);
					$this->session->set_userdata($array);
					$msg['auth'] = '1';
					$msg['message'] = "Thank you for logging in.";

				//Lets change the logged in status
				$online = array('user_online' => 1);
				$this->db->where('id',$id)->update('users',$online);

					$old_url = $data['old_url'];


					if (!empty($old_url)) {
						redirect(base_url().$old_url);
					}
					return $msg;
				}
				else {
					$msg['auth'] = '0';
					$msg['message'] = "Incorrect Password";
					return $msg;						
				}
			}
			else {
					$msg['auth'] = '0';
					$msg['message'] = "Incorrect Username";
					return $msg;				
			}
		}
		else {
					
					if ($this->session->userdata('authenticated')== "authenticated") 
					{

					}

					else {
						if (!isset($old_url)) {
							$old_url = $this->uri->uri_string();
						}
						$msg['auth'] = '0';
						$msg['message'] = 'You must login to use this software. Please enter
							your username and password to continue...';
						$this->session->set_flashdata('message', '<div class="alert alert-success" >'.$msg['message'].'</div>');
						$this->session->set_flashdata('old_url', $old_url);
						redirect('authentication/login');
					}
		}



	}



	public function intchk(){


		/* $int = array(
				'actions' =>sha1_file('./application/controllers/actions.php'), 
				'project' =>sha1_file('./application/controllers/project.php'), 
				'project_model' =>sha1_file('./application/models/project_model.php'), 
				'user_model' =>sha1_file('./application/models/user_model.php'), 
				'utilities_model' =>sha1_file('./application/models/utilities_model.php'), 
				'libf' =>sha1_file('./application/views/includes/libf.php'), 
				'libh' =>sha1_file('./application/views/includes/libh.php'), 
				'nav' =>sha1_file('./application/views/includes/nav.php'), 
			);


		$int_orig = array( 
				'actions' => '40ef65992fcb1e18f56609a42a1cb15f0b177eb0',
				'project' => 'fca5dc1ab9bbb1315cb7459d2d8110fda729c408',
				'project_model' => '698977e1b78a0e8aad538d6f0feb9478ab6d0c0b',
				'user_model' => 'e730deb6e13c54cb147d07e398b9fadf511d46fa',
				'utilities_model' => 'e8e4180a26993cfc8d20b97fabd9be744581e507',
				'libf' => 'f768ffa210e4e46e6d3be259c865defead4dc96e',
				'libh' => '14704fcc91f23280e38ceda51acaca6f0c21bdd1',
				'nav' => '06281e79da5d03ed254ec317f64ecfc2fb1e9420'
				); 



		if ($int==$int_orig) {
		 	
		 } 
		 else{
		 	$this->session->set_flashdata(
		 		'message', 
		 		'<div class="alert alert-error" >
		 			Integrity check FAILED! The Software has been modified illegally. You 
		 			may have received a counterfeited copy of the software. Installing and using 
		 			a counterfeited software is extremely prohibited. 

		 			<br />Please support the developer and
		 			buy the software. Please contact the original author of the software 
		 			<a href="mailto:the.tintin.boss@gmail.com" >A.B.M Shahnewaz Rifat</a>. 

		 			<br /><br />Optionally, you may also buy the software from CodeCanyon using this link : 
		 			<a href="#">TaskFeed PE at CodeCanyon</a>

		 			<br /><br />Thank you for supporting the developer!
		 		</div>');

		 	redirect('about-taskfeed');
		 } */
		
	}


	public function change_pass($data){
			$this->db->where('user_name',$data['user_name']);
			$qry = $this->db->get('users');
			$res = $qry->result();
			$rows = $qry->num_rows();
			if ($rows>0) {
				foreach ($res as $found) {
					$password = $found->user_pass;
					$username = $found->user_name;
					$id = $found->id;
				}

				$old_pass = sha1($data['old_pass']);
				$new_pass = sha1($data['new_pass']);
				$change = array('user_pass' =>$new_pass);

				if ($password == $old_pass) {
					$this->db->where('id',$id);
					$qry = $this->db->update('users',$change);
					if($qry){
						$msg['auth'] = '1';
						$msg['message'] = "Password Changed Successfully.";
						return $msg;							
					}
					else {
						$msg['auth'] = '0';
						$msg['message'] = "Authentication Error. Please logout and login again.";
						return $msg;	
					}
				}
				else {
						$msg['auth'] = '0';
						$msg['message'] = "Wrong Old Password. Please try again...";
						return $msg;						
				}

			}
			else {
					$msg['auth'] = '0';
					$msg['message'] = "Authentication Error. Please logout and login again.";
					return $msg;					
			}		
	}




	public function privilege($role){
		if ($role!="Admin") {
			$this->session->set_flashdata('message', '<div class="alert alert-error" >Sorry, you are not authorized to access that page...'.'</div>');
			redirect('home');
		}
		else{

		}
	}




	public function register($data){

		$qry = $this->db->insert('users',$data);

		if ($qry) {
					$email = $data['user_email'];

					//Lets email the link to user now

						$from = "Taskfeed Team | Taskfeed";
						
						$subject = "TaskFeed : Congratulations!";
						$message = 	"<html><body>"
									."<div style='width:99%; height:60px; line-height:50px; background:#852B99;' >
									<h1 style='color:#fff; padding:0px 0px 0px 25px; ' >T a s k F e e d</h1></div>
									<div style='width:95%; border:thin solid #ccc; padding:10px; ' >"
									."<p>Hello ".$data['user_fname']." ".$data['user_lname']."! Thank you for joining the TASKFEED TEAM! 
									 <br />Please click the following link within the next 24 hours to activate your account!<br /><br /><br />
									 <a style='display:block; width:200px; text-decoration:none; height:40px; background:#1D943B; font-size:18px; font-weight:bolder;
							color:#fff; line-height:40px; text-align:center;' href='".base_url()."activate/account/".$data['user_crypt']."'>Activate Account</a><br /><br />

									 We're confident that you'll love your work more with Taskfeed everyday. We appreciate your view of working in
									 an organized and clean way. We'll try our best to help you in that regard.
									 <br />"

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






			$this->session->set_flashdata('message', '<div class="alert alert-success" >Account Created Successfully! Please check your email for the activation link!</div>');
			redirect('authentication/login');
		}
		else{
			$this->session->set_flashdata('message', '<div class="alert alert-error" >Sorry, Account could not be created at this moment. Please try again later...</div>');
			redirect('authentication/register');
		}

	}


}//END of AUTH MODEL

/*		
Copying/Distributing any part of the code is strictly prohibited.
created by A.B.M Shahnewaz Rifat, 2013 
All Rights Reserved
*/