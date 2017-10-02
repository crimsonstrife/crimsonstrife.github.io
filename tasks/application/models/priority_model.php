<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Priority_model extends CI_Model {

	public function __construct(){
		parent::__construct();		
	}

	

	public function get_shared_priority($user_id){

		$shared_prio = $this->db->select('*')
					->from('tf_priority_queue_share')
					->where('prio_share_id',$user_id)
					->join('tf_priority_queue','tf_priority_queue.prio_owner=tf_priority_queue_share.prio_owner_id')
					->join('users','tf_priority_queue.prio_owner=users.id')
					->get()
					->result();
		return $shared_prio;
	}



	public function get_own_priority($user_id){

		$own_prio = $this->db->select('*')
					->from('tf_priority_queue')
					->where('prio_owner',$user_id)
					->get()
					->result();
		return $own_prio;
	}


	public function view_priority($prio_link){

		$prio['priority'] = $this->db->select('*')
					->from('tf_priority_queue')
					->where('prio_link',$prio_link)
					->join('users', 'users.id=tf_priority_queue.prio_owner')
					->get()
					->result();

		$prio['data'] = $this->db->select('*')
					->from('tf_priority_queue')
					->where('prio_link',$prio_link)
					->join('tf_priority_data', 'tf_priority_data.prio_id=tf_priority_queue.prio_id')
					->join('users', 'users.id=tf_priority_queue.prio_owner')
					->order_by('pdata_order','ASC')
					->get()
					->result();


		$prio['shared'] = $this->db->select('*')
					->from('tf_priority_queue')
					->where('prio_link',$prio_link)
					->join('tf_priority_queue_share', 'tf_priority_queue_share.prio_id=tf_priority_queue.prio_id')
					->join('users', 'users.id=tf_priority_queue_share.prio_share_id')
					->get()
					->result();

		return $prio;
	}




	public function create_priority($data){

		$qry = $this->db->insert('tf_priority_queue',$data);

		if($qry){
			$ret = 1;

		} else{
			$ret = 2;
		}

		return $ret;

	}




	public function add_priority_task($data){

		$qry = $this->db->insert('tf_priority_data',$data);

		if($qry){
			$ret = 1;

		} else{
			$ret = 2;
		}

		return $ret;

	}



	public function delete_task($id){

		$qry1 = $this->db->where('pdata_id',$id)->delete('tf_priority_data');

		if ($qry1) {
			$res = 1;
			return $res;
		}
		else{
			
		}
		
	}




//clas ends after this line...
}



/*		
Copying/Distributing any part of the code is strictly prohibited.
created by A.B.M Shahnewaz Rifat, 2013 
All Rights Reserved
*/

/* End of file priority_model.php */
/* Location: ./application/models/priority_model.php */