<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {

	public function __construct(){
		parent::__construct();		
	}


	public function find_user_by_id($id){
		//id, user_name, user_email, user_role, user_pass, user_fname, user_lname, user_pin, user_status
		$qry = $this->db
				->select('*')
				->from('users')
				->where('user_status','1')
				->where('user_role','User')				
				->where('id',$id)
				->get()
				->result();
		return $qry;
	}


	public function find_user_by_username($uname){
		//id, user_name, user_email, user_role, user_pass, user_fname, user_lname, user_pin, user_status
		$qry = $this->db
				->select('*')
				->from('users')
				->where('user_status','1')
				->where('user_role','User')				
				->where('user_name',$uname)
				->get()
				->result();

		if ($qry) {
			return $qry;
		}else{
			$this->session->set_flashdata('message', '<div class="alert alert-error">Sorry, No such user found. Please try with a different username or check you have typed the correct spelling...</div> ');
			redirect('dashboard');
		}	
		
	}







	public function find_user_by_email_or_username($invite){
		$qry = $this->db
				->select('*')
				->from('users')
				->where('user_status','1')
				->where('user_role','User')				
				->where('user_name',$invite)
				->or_where('user_email',$invite)
				->get();

		$num = $qry->num_rows();

		if ($num!=0) {
			$result = $qry->result();
			return $result;
		} else{
			$result = 0;
			return $result;
		}
	}




	public function get_team_members(){

		$my_id = $this->session->userdata('user_id');

		$qry = $this->db
					->from('tf_team_invite')
					->where('from_member',$my_id)					
					->where('status',1)
					->join('users', 'users.user_email=tf_team_invite.to_email')
					->where('users.user_status','1')
					->where('users.user_role','User')
					->get()
					->result();
		return $qry;
	}





	public function get_invites(){

		$email = $this->session->userdata('user_email');

		$qry = $this->db
					->from('tf_team_invite')
					->where('to_email',$email)					
					->where('status',0)
					->join('users', 'users.id=tf_team_invite.from_member')
					->where('users.user_status','1')
					->get()
					->result();
		return $qry;
	}



	public function get_joined_teams(){
		$email = $this->session->userdata('user_email');

		$qry = $this->db
					->from('tf_team_invite')
					->where('to_email',$email)					
					->where('status',1)
					->join('users', 'users.id=tf_team_invite.from_member')
					->where('users.user_status','1')
					->get()
					->result();
		return $qry;		
	}


	public function get_team_info($tf_tid){

		$qry = $this->db
					->from('tf_team_invite')
					->where('tf_tid',$tf_tid)					
					->join('users', 'users.user_email=tf_team_invite.to_email')
					->where('users.user_status','1')
					->get()
					->result();

		return $qry;
	}


	public function get_team_info_owner($tf_tid){

		$qry = $this->db
					->from('tf_team_invite')
					->where('tf_tid',$tf_tid)					
					->join('users', 'users.id=tf_team_invite.from_member')
					->where('users.user_status','1')
					->get()
					->result();

		return $qry;
	}




	public function update_profile($logged,$data){

		$qry = $this->db
			->where('id',$logged)
			->update('users',$data);

		if ($qry) {
			$this->session->set_flashdata('message', '<div class="alert alert-success" >Business Card and profile data updated successfully!</div>');
			redirect('user/profile'."/".$this->session->userdata('username'));
		}else{
			$this->session->set_flashdata('message', '<div class="alert alert-error" >Something went wrong :( We could not update your business card at this moment...</div>');
			redirect('user/profile'."/".$this->session->userdata('username'));			
		}

	}




}

/*		
Copying/Distributing any part of the code is strictly prohibited.
created by A.B.M Shahnewaz Rifat, 2013 
All Rights Reserved
*/


/* End of file user_model.php */
/* Location: ./application/models/user_model.php */