<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification_model extends CI_Model {

	

	public function unread_notifications(){
		$my_id = $this->session->userdata('user_id');

		//tf_notification
		//pn_id, pn_to, pn_from, pn_created, pn_content, pn_link, pn_title, pn_unread
							

		$notify['num'] = $this->db
							->from('tf_notification')
							->where('pn_to',$my_id)
							->where('pn_unread',1)
							->order_by('pn_created','DESC')
							->get()->num_rows();


		$notify['notifications'] = $this->db
							->from('tf_notification')
							->where('pn_to',$my_id)
							->where('pn_unread',1)
							->order_by('pn_created','DESC')
							->limit('5')
							->get()->result();

		return $notify; 


	}




	public function team_invites(){
		$email = $this->session->userdata('user_email');

		$team_invites = $this->db
							->from('tf_team_invite')
							->where('to_email',$email)
							->where('status',0)
							->order_by('created','DESC')
							->get()
							->num_rows();
		return $team_invites; 

	}





	public function home_notifications(){
		$my_id = $this->session->userdata('user_id');

		//tf_notification
		//pn_id, pn_to, pn_from, pn_created, pn_content, pn_link, pn_title, pn_unread

		$notification = $this->db
							->from('tf_notification')
							->where('pn_to',$my_id)
							->join('users','users.id=tf_notification.pn_from')
							->limit('10')
							->order_by('pn_created','DESC')
							->get()
							->result();


		return $notification; 


	}



	public function get_notifications_num(){
		$my_id = $this->session->userdata('user_id');


		$num = $this->db
							->from('tf_notification')
							->where('pn_to',$my_id)
							->order_by('pn_created','DESC')
							->get()
							->num_rows();


		return $num; 		
	}


	public function notifications($condition){
		$my_id = $this->session->userdata('user_id');

		//tf_notification
		//pn_id, pn_to, pn_from, pn_created, pn_content, pn_link, pn_title, pn_unread

		$notification = $this->db
							->from('tf_notification')
							->where('pn_to',$my_id)
							->order_by('pn_created','DESC')
							->join('users','users.id=tf_notification.pn_from')
							->limit($condition['limit'],$condition['offset'])
							->get()
							->result();


		return $notification; 


	}




	public function all_unread_notifications($condition){
		$my_id = $this->session->userdata('user_id');

		//tf_notification
		//pn_id, pn_to, pn_from, pn_created, pn_content, pn_link, pn_title, pn_unread

		$notification = $this->db
							->from('tf_notification')
							->where('pn_to',$my_id)
							->where('pn_unread',1)
							->order_by('pn_created','DESC')
							->join('users','users.id=tf_notification.pn_from')
							->limit($condition['limit'],$condition['offset'])
							->get()
							->result();


		return $notification; 


	}


	public function calendar_data(){
		$my_id = $this->session->userdata('user_id');

		//tf_notification
		//pn_id, pn_to, pn_from, pn_created, pn_content, pn_link, pn_title, pn_unread

		$events = $this->db
							->from('tf_project_user')
							->where('pu_uid',$my_id)
							->join('tf_projects','tf_projects.pid=tf_project_user.pu_pid')
							->get()
							->result();


		//print_r($events); exit;
		return $events; 		
	}





}

/*		
Copying/Distributing any part of the code is strictly prohibited.
created by A.B.M Shahnewaz Rifat, 2013 
All Rights Reserved
*/

/* End of file notification_model.php */
/* Location: ./application/models/notification_model.php */