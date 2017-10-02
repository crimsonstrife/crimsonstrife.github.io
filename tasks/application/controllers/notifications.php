<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notifications extends CI_Controller {


	public function __construct(){

		parent::__construct();
		
		$this->load->model('utilities_model');
		$this->data['cshaex'] = $this->utilities_model->get_cshaex();
	}



	public function index()
	{
		
	}


	public function read(){
		$id = $this->uri->segment(3);

		$notification = $this->db
				->from('tf_notification')
				->where('pn_id',$id)
				->get()
				->result();

		$mark = array('pn_unread' => 0 );

		$read = $this->db
					->where('pn_id',$id)
					->update('tf_notification',$mark);

		foreach ($notification as $notif) {
			$link = $notif->pn_link;
		}

		redirect($link);


	}




	public function get_poll_notification(){

		$my_id = $this->session->userdata('user_id');


		$notify['num'] = $this->db
							->from('tf_notification')
							->where('pn_to',$my_id)
							->where('pn_unread',1)
							->order_by('pn_created','DESC')
							->get()->num_rows();

		//return $notify['num']; 

		echo $notify['num'];


	}







}
/*		
Copying/Distributing any part of the code is strictly prohibited.
created by A.B.M Shahnewaz Rifat, 2013 
All Rights Reserved
*/


/* End of file notifications.php */
/* Location: ./application/controllers/notifications.php */