<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project_model extends CI_Model {

	public function __construct(){
		parent::__construct();		
	}

	


	// Preload USER'S already created groups
	public function get_groups($user_id){
		$groups = $this->db->select('*')
					->from('tf_project_group')
					->where('pg_owner',$user_id)
					->where('pg_active','1')
					->get()
					->result();
		return $groups;
	}



	public function find_project_id_by_url($project_url){
		$project = $this->db
						->select('pid','p_url')
						->from('tf_projects')
						->where('p_url',$project_url)
						->get()
						->result();


		if($project) {
			foreach ($project as $prj) {
				$pid = $prj->pid;
			}

			return $pid;	
		} else{
			$this->session->set_flashdata('message', '<div class="alert alert-error"> Sorry the project does not exists OR have been deleted by the project owner. </div> ');
			redirect('dashboard');
		}				
	
	}



	public function find_project_by_url($project_url){


		$project = $this->db
				->select('*')
				->from('tf_projects')
				->where('p_url',$project_url)
				->join('tf_project_group','tf_project_group.pg_id=tf_projects.p_group')
				->get()
				->result();

		if($project) {

			foreach ($project as $prj) {
				$pid = $prj->pid;
				$project_creator = $prj->p_created_by;
			}

			//get creator details
			$this->load->model('user_model');
			$creator = $this->user_model->find_user_by_id($project_creator);

			$users = $this->db
							->select('*')
							->from('tf_project_user')
							->where('pu_pid',$pid)
							->join('users','tf_project_user.pu_uid=users.id')
							->get()
							->result();

			//print_r($users); exit;

			//Check if the logged in guy is eligible to view this project
			$logged = $this->session->userdata('user_id');

			$check = $this->db
							->select('*')
							->from('tf_project_user')
							->where('pu_pid',$pid)
							->where('pu_uid',$logged)
							->get()
							->num_rows();

			if( ($check==0) && ($logged!=$project_creator) ){
				$this->session->set_flashdata('message', '<div class="alert alert-error" >Sorry, You are not authorized to view this project. OR maybe the project does not exist anymore.</div>');
				redirect('dashboard');
			}else{

				$project_data['project'] = $project;
				$project_data['users'] = $users;
				$project_data['creator'] = $creator;
				return $project_data;
			}


		} else{
			$this->session->set_flashdata('message', '<div class="alert alert-error"> Sorry the project does not exist OR have been deleted by the project owner. </div> ');
			redirect('dashboard');
		}


	}


	public function find_project_by_id($pid){
		$project = $this->db
				->select('*')
				->from('tf_projects')
				->where('pid',$pid)
				->get()
				->result();	


		if($project) {

			return $project;

		} else{
			$this->session->set_flashdata('message', '<div class="alert alert-error"> Sorry the project does not exist OR have been deleted by the project owner. </div> ');
			redirect('dashboard');
		}			
	}



	public function find_project_detail_by_id($pid){
		$project = $this->db
				->select('*')
				->from('tf_projects')
				->where('pid',$pid)
				->join('users','users.id=tf_projects.p_created_by')
				->get()
				->result();	


		if($project) {			
			return $project;

		} else{
			$this->session->set_flashdata('message', '<div class="alert alert-error"> Sorry the project does not exist OR have been deleted by the project owner. </div> ');
			redirect('dashboard');
		}		
	}




	public function insert_group($new_group){
		//pg_id, pg_name, pg_active, pg_owner
		$data = array(
			'pg_name' =>$new_group,
			'pg_active' => '1',
			'pg_owner' => $this->session->userdata('user_id'),
			'pg_created' => time() 
			);
		$this->db->insert('tf_project_group',$data);

		$pg_id = $this->db->insert_id();

		return $pg_id;
	}




	public function get_all_contractors(){

		$my_id = $this->session->userdata('user_id');

		$qry = $this->db
					->from('tf_team_invite')
					->where('from_member',$my_id)
					->where('user_status','1')
					->where('user_role','User')
					->order_by('user_fname','ASC')
					->where('status',1)
					->join('users', 'users.user_email=tf_team_invite.to_email')
					->get()
					->result();
		return $qry;
	}


	public function get_contractors_num(){

		$my_id = $this->session->userdata('user_id');

		$qry = $this->db
					->from('tf_team_invite')
					->where('from_member',$my_id)
					->where('user_status','1')
					->where('user_role','User')
					->where('status',1)
					->join('users', 'users.user_email=tf_team_invite.to_email')
					->get()
					->num_rows();
		return $qry;
	}

	public function create_project($data,$contractors){

		//lets check if the project already exists!!
		$ex_project = $this->db->select('*')
						->from('tf_projects')->where('p_join_token',$data['p_join_token'])
						->get()
						->num_rows();
		if ($ex_project>0) {
			$this->session->set_flashdata('message', '<div class="alert alert-error" >Sorry, this project already exists... Try again.</div>');
			redirect('project/new/step1');
			exit;
		}

		//lets register the project first

		$qry1 = $this->db->insert('tf_projects',$data);

		//retrieve the proejct ID
		$pid = $this->db->insert_id();

		//Get the project title
		$project = $this->find_project_by_id($pid);

		foreach ($project as $prj) {
			$project_url = $prj->p_url;
		}


		//assign the contractors and send email notifications to them


		foreach ($contractors as $contractor) {

			$assign = array(
				'pu_uid' => $contractor,
				'pu_pid' =>$pid
				);
			$store_project = $this->db->insert('tf_project_user',$assign);

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
								." Invited you to join his project ".$data['p_title'];

				$pn_link = base_url('project/view')."/".$project_url."/discussion";
				$pn_title = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')."
							Invited you to a project";

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
				
				$subject = "TaskFeed : Invitation for Project - ".$data['p_title'];
				$message = 	"<html><body>"
							."<div style='width:99%; height:60px; line-height:50px; background:#852B99;' >
							<h1 style='color:#fff; padding:0px 0px 0px 25px; ' >T a s k F e e d</h1></div>
							<div style='width:95%; border:thin solid #ccc; padding:10px; ' >"
							."<p>Hi ".$invite->user_fname." ".$invite->user_lname
							." ! Good news! <br /><br />You are invited to join the project <strong>"
							.$data['p_title']."</strong> by <strong>".$this->session->userdata('user_fname')
							." ".$this->session->userdata('user_lname')."</strong><br /><br />"
							."Please use the following link to join the project at your earliest time!<br /><br />"
							."<a style='display:block; width:200px; text-decoration:none; height:40px; background:#1D943B; font-size:18px; font-weight:bolder;
							color:#fff; line-height:40px; text-align:center; ' href='".base_url('project/join')."/"
							.$data['p_join_token']."' >Accept &amp; Join Now! </a></p><br />"
							."<p style='color:#888'>TaskFeed Team, ".date('Y')."</p>
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


				
			}

			//=============================================================================================
			//========================================== SEND EMAIL =======================================
			//=============================================================================================


		}


		if(!$qry1){
			$this->session->set_flashdata('message', '<div class="alert alert-error" >Something went wrong and we could not store your project... Try again?</div>');
			redirect('project/new/step1');
		}

	}



//=========================================================================================================
//====================================== PROJECT EDIT =====================================================


	public function edit_project($data){

		$project_url = $this->input->post('project_url');

		//echo $project_url; exit;

		//lets update the project first

		$qry1 = $this->db->where('p_url',$project_url)->update('tf_projects',$data);

		if($qry1){

			//echo "edited"; exit;


		

		//assign the contractors and send email notifications to them




		// GET Contractor Info
		$contractors = $this->db
						->select('*')
						->from('tf_projects')
						->where('p_url',$project_url)
						->join('tf_project_user','tf_projects.pid=tf_project_user.pu_pid')
						->join('users','tf_project_user.pu_uid=users.id')
						->get()
						->result();





		//print_r($owner_data); exit;
		//print_r($contractors); exit;

		foreach ($contractors as $invite) {


				//Lets create a notification for them
				//pn_id, pn_to, pn_from, pn_created, pn_content, pn_link
				$pn_content = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')
								." edited the project- ".$data['p_title'];

				$pn_link = base_url('project/view')."/".$project_url;
				$pn_title = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')."
							Edited a project you are working on...";

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
			//=============================================================================================
			//========================================== SEND EMAIL =======================================
			//=============================================================================================




				$from_project_creator = $this->session->userdata('user_fname')
										." ".$this->session->userdata('user_lname')." | Taskfeed";
				
				$subject = "TaskFeed : Project Information Updated - ".$data['p_title'];
				$message = 	"<html><body>"
							."<div style='width:99%; height:60px; line-height:50px; background:#852B99;' >
							<h1 style='color:#fff; padding:0px 0px 0px 25px; ' >T a s k F e e d</h1></div>
							<div style='width:95%; border:thin solid #ccc; padding:10px; ' >"
							."<p>Hi ".$invite->user_fname." ".$invite->user_lname
							." <br />One of your project <strong>"
							.$data['p_title']."</strong> by <strong>".$this->session->userdata('user_fname')
							." ".$this->session->userdata('user_lname')."</strong> has been edited recently.<br /><br />"
							."To view the changes made, please click the following link and check.<br /><br />"
							."<a style='display:block; width:200px; text-decoration:none; height:40px; background:#1D943B; font-size:18px; font-weight:bolder;
							color:#fff; line-height:40px; text-align:center; ' href='".base_url('project/view')."/"
							.$project_url."' >View Project </a></p><br />"
							."<p style='color:#888'>TaskFeed Team, ".date('Y')."</p>
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

				else{
					//echo "Successfull!";

				}


			$this->session->set_flashdata('message', '<div class="alert alert-success" >Your changes to the project <strong>'.$data['p_title'].'</strong> were saved. Contractors were notified via email.</div>');
			redirect('project/manage/projects');
			//=============================================================================================
			//========================================== SEND EMAIL =======================================
			//=============================================================================================


		}


		}else{
			$this->session->set_flashdata('message', '<div class="alert alert-error" >Something went wrong and we could not finish editing your project... Try again?</div>');
			redirect('project/manage/projects');
		}


	}	










	public function find_my_projects_num(){

		$my_id = $this->session->userdata('user_id');

		$num_projects = $this->db
				->select('*')
				->from('tf_projects')
				->where('p_created_by',$my_id)
				->join('tf_project_group','tf_project_group.pg_id=tf_projects.p_group')
				->get()
				->num_rows();

		return $num_projects;
	}



	public function find_my_projects($condition){

		$my_id = $this->session->userdata('user_id');

		$my_projects = $this->db
				->select('*')
				->from('tf_projects')
				->where('p_created_by',$my_id)
				->join('tf_project_group','tf_project_group.pg_id=tf_projects.p_group')
				->limit($condition['limit'],$condition['offset'])
				->get()
				->result();

		return $my_projects;
	}

	public function find_my_tasks_num(){

		$my_id = $this->session->userdata('user_id');

		$num_task = $this->db
				->select('*')
				->from('tf_project_user')
				->where('pu_uid',$my_id)
				->join('tf_projects','tf_project_user.pu_pid=tf_projects.pid')
				->join('tf_project_group','tf_project_group.pg_id=tf_projects.p_group')
				->get()
				->num_rows();

		return $num_task;
	}

	public function find_my_tasks($condition){

		$my_id = $this->session->userdata('user_id');

		$my_tasks = $this->db
				->select('*')
				->from('tf_project_user')
				->where('pu_uid',$my_id)
				->join('tf_projects','tf_project_user.pu_pid=tf_projects.pid')
				->join('tf_project_group','tf_project_group.pg_id=tf_projects.p_group')
				->limit($condition['limit'],$condition['offset'])
				->get()
				->result();

		return $my_tasks;
	}


	public function delete_project($pid){

		//echo $pid; exit;

		//lets get the project name
		$this->load->model('project_model');
		$project = $this->project_model->find_project_by_id($pid);

		foreach ($project as $prj) {
			$project_title = $prj->p_title;
		}

		//assign the contractors and send email notifications to them

		//Lets delete all associated Discussion
		$del_dis = $this->db->where('pd_pid',$pid)->delete('tf_discussion');

		// Lets delete all the milestones associated
		$del_mil = $this->db->where('pm_pid',$pid)->delete('tf_milestones');

		//Lets delete all the TODO
		$del_todo = $this->db->where('pt_pid',$pid)->delete('tf_project_todo');


		// Lets delete all the files uploaded for this project!!!
		$find_files = $this->db
						->from('tf_discussion_files')
						->where('tfile_pid',$pid)
						->get()
						->result();
		

		foreach ($find_files as $files) {
			$file_name = "uploads/discussion_data/".$files->tfile_server_name.$files->tfile_ext;
			unlink($file_name);
		}

		// Now lets delete the discussion file entries
		$del_file_dis = $this->db->where('tfile_pid',$pid)->delete('tf_discussion_files');

	

			$deleted = "success";
			// Lets notify the users
			//Find them first
			$invited = $this->db
						->from('tf_project_user')
						->join('users','users.id=tf_project_user.pu_uid')
						->where('tf_project_user.pu_pid',$pid)
						->get()->result();

		if ($invited) {

			

			foreach ($invited as $invite) {

			//=============================================================================================
			//========================================== SEND EMAIL =======================================
			//=============================================================================================

				$from_project_creator = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')." | Taskfeed";
				
				$subject = "TaskFeed : Project Deleted - ".$project_title;
				$message = 	"<html><body>"
							."<div style='width:99%; height:60px; line-height:50px; background:#852B99;' >
							<h1 style='color:#fff; padding:0px 0px 0px 25px; ' >T a s k F e e d</h1></div>
							<div style='width:95%; border:thin solid #ccc; padding:10px; ' >"
							."<p>Hi ".$invite->user_fname." ".$invite->user_lname
							." . <br /><br />This is to inform you that the project <strong>"
							.$project_title."</strong> by <strong>".$this->session->userdata('user_fname')
							." ".$this->session->userdata('user_lname')."</strong> was deleted by the project creator.<br /><br />"
							."As a result, you will no longer receive updates about this project from us.<br />Thank you.<br />"
							."<p style='color:#888'>TaskFeed Team, ".date('Y')."</p>
							</div></body></html>";
			
		

				$this->email->from('do-not-reply@taskfeed.com', $from_project_creator);
				$this->email->to($invite->user_email);

				$this->email->subject($subject);
				$this->email->message($message,TRUE);
				
				if(!$this->email->send()){
					//echo $this->email->print_debugger();
					//exit;
				}

				//Delete Project Users
				
			}

			//=============================================================================================
			//========================================== SEND EMAIL =======================================
			//=============================================================================================



		}

			//Delete All user associations with project
			$del_assoc = $this->db->where('pu_pid',$pid)->delete('tf_project_user');

			//Lets Delete the project First
			$qry1 = $this->db->where('pid',$pid)->delete('tf_projects');


		return $deleted;

	}//ENDS THE DELETE FUNCTION





	public function finish_project($pid){


		//lets get the project name
		$this->load->model('project_model');
		$project = $this->project_model->find_project_by_id($pid);

		foreach ($project as $prj) {
			$project_title = $prj->p_title;
		}

		//assign the contractors and send email notifications to them


		//We wont delete the project this time. Lets update and make status to Completed
		$status = array('p_status' =>'Completed' ,'p_milestone'=>'uninstall','p_todo'=>'uninstall','p_discussion'=>'uninstall', 'p_deadline'=>time(),'p_active'=>2 );
		$qry1 = $this->db->where('pid',$pid)->update('tf_projects',$status);


		//Lets delete all associated Discussion
		$del_dis = $this->db->where('pd_pid',$pid)->delete('tf_discussion');

		// Lets delete all the milestones associated
		$del_mil = $this->db->where('pm_pid',$pid)->delete('tf_milestones');

		//Lets delete all the TODO
		$del_todo = $this->db->where('pt_pid',$pid)->delete('tf_project_todo');




		// Lets delete all the files uploaded for this project!!!
		$find_files = $this->db
						->from('tf_discussion_files')
						->where('tfile_pid',$pid)
						->get()
						->result();
		

		foreach ($find_files as $files) {
			$file_name = "uploads/discussion_data/".$files->tfile_server_name.$files->tfile_ext;
			unlink($file_name);
		}

		// Now lets delete the discussion file entries
		$del_file_dis = $this->db->where('tfile_pid',$pid)->delete('tf_discussion_files');

		


		if ($qry1) {

			$deleted = "success";
			// Lets notify the users
			//Find them first
			$invited = $this->db
						->from('tf_project_user')
						->where('pu_pid',$pid)
						->join('users','users.id=tf_project_user.pu_uid')
						->get()->result();



		//======================EMAIL ==============================
			//===============================================================

			foreach ($invited as $invite) {


			//=============================================================================================
			//========================================== SEND EMAIL =======================================
			//=============================================================================================

				$from_project_creator = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')." | Taskfeed";
				
				$subject = "TaskFeed : Project Finished - ".$project_title;
				$message = 	"<html><body>"
							."<div style='width:99%; height:60px; line-height:50px; background:#852B99;' >
							<h1 style='color:#fff; padding:0px 0px 0px 25px; ' >T a s k F e e d</h1></div>
							<div style='width:95%; border:thin solid #ccc; padding:10px; ' >"
							."<p>Hi ".$invite->user_fname." ".$invite->user_lname
							." . <br /><br />This is to inform you that the project <strong>"
							.$project_title."</strong> by <strong>".$this->session->userdata('user_fname')
							." ".$this->session->userdata('user_lname')."</strong> is now marked as FINISHED by the project creator.<br /><br />"
							."As a result, you will no longer receive updates about this project from us.<br />Thank you.<br />"
							."<p style='color:#888'>TaskFeed Team, ".date('Y')."</p>
							</div></body></html>";
			
			

				$this->email->from('do-not-reply@taskfeed.com', $from_project_creator);
				$this->email->to($invite->user_email);

				$this->email->subject($subject);
				$this->email->message($message,TRUE);
				
				if(!$this->email->send()){
					//echo $this->email->print_debugger();
					//exit;
				}


			
				
			}

			//=============================================================================================
			//========================================== SEND EMAIL =======================================
			//=============================================================================================

			
		}
		else {
			echo "Something went wrong"; exit;
		}

		return $deleted;

	}//ENDS THE Finish Project FUNCTION













	public function join_project($join_token){

		//echo $join_token; exit;

		//Lets find out the project
		$logged = $this->session->userdata('user_id');
		//echo $logged; exit;

		$qry = $this->db
				->from('tf_projects')
				->where('p_join_token',$join_token)
				->join('tf_project_user','tf_projects.pid=tf_project_user.pu_pid')
				->where('pu_uid',$logged)
				->where('pu_joined', 0)
				->get()
				->num_rows();

		//echo $qry; exit;

		if($qry!=0){

			//get the project_id
			$prj = $this->db
				->select('pid','p_join_token')
				->from('tf_projects')
				->where('p_join_token',$join_token)
				->get()
				->result();

			foreach ($prj as $p) {
				$pid = $p->pid;
			}

			$joined = array('pu_joined' =>1);
			//lets update the db
			$upd = $this->db
				->where('pu_pid',$pid)
				->where('pu_uid',$logged)
				->update('tf_project_user',$joined);

			//Lets change the project status

			$p_status = array('p_status' => 'In Progress' );
			$status = $this->db
				->where('pid',$pid)
				->update('tf_projects',$p_status);
		
			if($upd){
				$this->session->set_flashdata('message', '<div class="alert alert-success" >Thank you for joining the project.</div>');
				redirect('project/manage/tasks');
			}
			else{
				$this->session->set_flashdata('message', '<div class="alert alert-error" >Sorry, we are facing some technical error right now. Please try again later.</div>');
				redirect('project/join/error');
			}

		}

		else{
			$this->session->set_flashdata('message', 
				'Sorry, We could not join you to the requested project...<br />
				Possible reasons: 
				<ul>
					<li>The invitation is no longer valid.</li>
					<li>You were not invited to this project. </li>
					<li>The project was deleted by the owner.</li>
					<li>The project does not exist.</li>
					<li>Technical Error</li>
				</ul><br />

				Sorry for the inconvenience. Please contact the project owner for any query.

				');
			redirect('project/join/error');
		}

	}// Join Project ends


	public function get_discussion_num($project_url){


		//lets find out the project ID first

		$pid = $this->find_project_id_by_url($project_url);

		//echo $pid; exit;

		//Lets get all the threads for this project
		$num = $this->db
						->from('tf_discussion')
						->where('pd_pid',$pid)
						->get()
						->num_rows();

		return $num;
	}





	public function load_discussions($project_url,$condition){

		//lets find out the project ID first

		$pid = $this->find_project_id_by_url($project_url);

		//echo $pid; exit;

		//Lets get all the threads for this project
		$discussions = $this->db
						->select('*')
						->group_by("tf_discussion.pd_id")
						->where('pd_pid',$pid)
						->join('tf_projects','tf_projects.pid=tf_discussion.pd_pid')
						->join('users','users.id=tf_discussion.pd_creator')
						->join('tf_discussion_files','tf_discussion.pd_id=tf_discussion_files.tfile_pdid','left')
						->order_by('pd_time','ASC')
						->get('tf_discussion',$condition['limit'],$condition['offset'])
						->result();

		//print_r($discussions); exit;
		return $discussions;

	} // Discussion Loading Ends


	public function add_discussion($data_discussion,$data_attachment,$project_url,$pd_creator,$data_powner){

		//echo $data_powner['powner_id']; exit;


		$this->db->db_set_charset('utf8', 'utf8_unicode_ci');
		//echo "I am Okay 1"; exit;
		$qry1 = $this->db->insert('tf_discussion',$data_discussion);
		//echo "I am Okay 2"; exit;

		$tfile_pdid = $this->db->insert_id();

		$comment_num = $this->project_model->get_discussion_num($project_url);

		if ($comment_num>=4) {
			$current_page = $comment_num - 4;
		}
		else {
			$current_page = 0;
		}
		

		//echo $tfile_pdid; exit;


		if(!empty($data_attachment)){

			$data_attachment['tfile_pdid'] = $tfile_pdid;
			//print_r($data_attachment); exit;

			$qry2 = $this->db->insert('tf_discussion_files',$data_attachment);
		}



		//get project data
		$project_data = $this->find_project_by_id($data_discussion['pd_pid']);
		foreach ($project_data as $prj) {
			$project_title = $prj->p_title;
		}



		if($qry1){

			//AH we got to email again :(


			//Am I the creator of project?
			$logged = $this->session->userdata('user_id');

			if ($logged!=$data_powner['powner_id']) {

				//Lets create a notification for him
				//pn_id, pn_to, pn_from, pn_created, pn_content, pn_link
				$pn_content = substr(strip_tags($data_discussion['pd_comment']), 0,150 )."...";
				$pn_link = base_url('project/view')."/".$project_url."/discussion/".$current_page;
				$pn_title = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')."
							added a comment on ".$project_title;

				$notification_data = array(
											'pn_to' => $data_powner['powner_id'],
											'pn_from' => $this->session->userdata('user_id'),
											'pn_created' => time(),
											'pn_title' =>  $pn_title,
											'pn_content' => $pn_content,
											'pn_link' => $pn_link,
											'pn_unread' => 1

										);

				//print_r($notification_data); exit;

				$notify = $this->db->insert('tf_notification',$notification_data);
				

				$from = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')." | Taskfeed";
				
				$subject = "TaskFeed : New Comment Added - ".$project_title;
				$message = 	"<html><body>"
							."<div style='width:99%; height:60px; line-height:50px; background:#852B99;' >
							<h1 style='color:#fff; padding:0px 0px 0px 25px; ' >T a s k F e e d</h1></div>
							<div style='width:95%; border:thin solid #ccc; padding:10px; ' >"
							."<p>Hi ".$data_powner['powner_name']
							." ! <br /><br />A new comment has been added by <strong>"
							.$this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')." </strong>
							on the discussion thread for the project <strong>"
							.$project_title."</strong> Created by you.<br /><br />"
							."Here's the original comment from him : <br /><br />"
							."<blockquote>".$data_discussion['pd_comment']."</blockquote>"
							."<br />"
							."Please use the following link to discuss more.<br /><br />"
							."<a style='display:block; width:200px; text-decoration:none; height:40px; background:#1D943B; font-size:18px; font-weight:bolder;
							color:#fff; line-height:40px; text-align:center; ' href='".base_url('project/view')."/"
							.$project_url."/discussion/".$current_page."' >View Discussion</a></p><br />"
							."<p style='color:#888'>TaskFeed Team, ".date('Y')."</p>
							</div></body></html>";
			
			

				//echo $message; exit; 

				$this->email->from('do-not-reply@taskfeed.com', $from);
				$this->email->to($data_powner['powner_email']);

				$this->email->subject($subject);
				$this->email->message($message,TRUE);
				
				if(!$this->email->send()){
					//echo $this->email->print_debugger();
					//exit;
				}



			}


			//get everyone in the project except me
			$team = $this->db
						->from('tf_project_user')
						->where('pu_pid',$data_discussion['pd_pid'])
						->where('pu_uid !=',$pd_creator)
						->join('users','users.id=tf_project_user.pu_uid')
						->get()
						->result();

			//print_r($team); exit;

			if(!empty($team)){
				
				foreach ($team as $contractor) {


				//Lets create a notification for them
				//pn_id, pn_to, pn_from, pn_created, pn_content, pn_link
				$pn_content = substr(strip_tags($data_discussion['pd_comment']), 0,150 )."...";
				$pn_link = base_url('project/view')."/".$project_url."/discussion/".$current_page;
				$pn_title = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')."
							added a comment on ".$project_title;

				$notification_data = array(
											'pn_to' => $contractor->id,
											'pn_from' => $this->session->userdata('user_id'),
											'pn_created' => time(),
											'pn_title' =>  $pn_title,
											'pn_content' => $pn_content,
											'pn_link' => $pn_link,
											'pn_unread' => 1

										);

				//print_r($notification_data); exit;

				$notify = $this->db
							->insert('tf_notification',$notification_data);


					//=============================================================================================
					//========================================== SEND EMAIL =======================================
					//=============================================================================================


					$from = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')." | Taskfeed";
					
					$subject = "TaskFeed : New Comment Added - ".$project_title;
					$message = 	"<html><body>"
								."<div style='width:99%; height:60px; line-height:50px; background:#852B99;' >
								<h1 style='color:#fff; padding:0px 0px 0px 25px; ' >T a s k F e e d</h1></div>
								<div style='width:95%; border:thin solid #ccc; padding:10px; ' >"
								."<p>Hi ".$contractor->user_fname." ".$contractor->user_lname
								." ! <br /><br />A new comment has been added by <strong>"
								.$this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')." </strong>
								on the discussion thread for the project <strong>"
								.$project_title."</strong> .We are notifying you as a team member of this project.<br /><br />"
								."Here's the original comment from him : <br /><br />"
								."<blockquote>".$data_discussion['pd_comment']."</blockquote>"
								."<br />"
								."Please use the following link to discuss more.<br /><br />"
								."<a style='display:block; width:200px; text-decoration:none; height:40px; background:#1D943B; font-size:18px; font-weight:bolder;
								color:#fff; line-height:40px; text-align:center; ' href='".base_url('project/view')."/"
								.$project_url."/discussion/".$current_page."' >View Discussion</a></p><br />"
								."<p style='color:#888'>TaskFeed Team, ".date('Y')."</p>
								</div></body></html>";
				
				

					//echo $message; exit; 

					$this->email->from('do-not-reply@taskfeed.com', $from);
					$this->email->to($contractor->user_email);

					$this->email->subject($subject);
					$this->email->message($message,TRUE);
					
					if(!$this->email->send()){
						//echo $this->email->print_debugger();
						//exit;
					}
				}
			}






			$this->session->set_flashdata('message', '<div class="alert alert-success" >Discussion added.</div>');
			redirect(base_url('project/view')."/".$project_url."/discussion/".$current_page);
		}






		else{
			$this->session->set_flashdata('message', '<div class="alert alert-error" >Sorry, something went wrong and we could not add the discussion... Try again?</div>');
			redirect(base_url('project/view')."/".$project_url."/discussion");
		}


	}//DISCUSSION ADDING COMPLETE


	public function load_todo($project_url){

		//lets find out the project ID first

		$pid = $this->find_project_id_by_url($project_url);

		//echo $pid; exit;

		//Lets get all the threads for this project
		$todo = $this->db
						->select('*')
						->from('tf_project_todo')
						->group_by("tf_project_todo.pt_id")
						->where('pt_pid',$pid)
						->join('users','users.id=tf_project_todo.pt_creator')
						->order_by('pt_time','ASC')
						->get()
						->result();

		//print_r($discussions); exit;
		return $todo;

	} // Discussion Loading Ends





	public function add_todo($data_todo,$project_url,$pt_creator,$data_powner){

		//echo $data_powner['powner_id']; exit;

		$qry1 = $this->db->insert('tf_project_todo',$data_todo);


		//get project data
		$project_data = $this->find_project_by_id($data_todo['pt_pid']);

		foreach ($project_data as $prj) {
			$project_title = $prj->p_title;
		}



		if($qry1){

			//AH we got to email again :(


			//Am I the creator of project?
			$logged = $this->session->userdata('user_id');


			//get everyone in the project except me
			$team = $this->db
						->from('tf_project_user')
						->where('pu_pid',$data_todo['pt_pid'])
						->where('pu_uid !=',$pt_creator)
						->join('users','users.id=tf_project_user.pu_uid')
						->get()
						->result();

			//print_r($team); exit;

			if(!empty($team)){
				
				foreach ($team as $contractor) {


				//Lets create a notification for them
				//pn_id, pn_to, pn_from, pn_created, pn_content, pn_link
				$pn_content = substr(strip_tags($data_todo['pt_comment']), 0,150 )."...";
				$pn_link = base_url('project/view')."/".$project_url."/todo";
				$pn_title = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')."
							added a ToDo on ".$project_title;

				$notification_data = array(
											'pn_to' => $contractor->id,
											'pn_from' => $this->session->userdata('user_id'),
											'pn_created' => time(),
											'pn_title' =>  $pn_title,
											'pn_content' => $pn_content,
											'pn_link' => $pn_link,
											'pn_unread' => 1

										);

				//print_r($notification_data); exit;

				$notify = $this->db
							->insert('tf_notification',$notification_data);


					//=============================================================================================
					//========================================== SEND EMAIL =======================================
					//=============================================================================================


					$from = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')." | Taskfeed";
					
					$subject = "TaskFeed : New ToDo Added - ".$project_title;
					$message = 	"<html><body>"
								."<div style='width:99%; height:60px; line-height:50px; background:#852B99;' >
								<h1 style='color:#fff; padding:0px 0px 0px 25px; ' >T a s k F e e d</h1></div>
								<div style='width:95%; border:thin solid #ccc; padding:10px; ' >"
								."<p>Hi ".$contractor->user_fname." ".$contractor->user_lname
								." ! <br /><br />A new ToDo has been added by <strong>"
								.$this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')." </strong>
								on the ToDo section for the project <strong>"
								.$project_title."</strong> .We are notifying you as a team member of this project.<br /><br />"
								."Here's the ToDo Description from him : <br /><br />"
								."<blockquote>".$data_todo['pt_comment']."</blockquote>"
								."<br />"
								."Please use the following link to View more.<br /><br />"
								."<a style='display:block; width:200px; text-decoration:none; height:40px; background:#1D943B; font-size:18px; font-weight:bolder;
								color:#fff; line-height:40px; text-align:center; ' href='".base_url('project/view')."/"
								.$project_url."/todo"."' >View ToDo</a></p><br />"
								."<p style='color:#888'>TaskFeed Team, ".date('Y')."</p>
								</div></body></html>";
				
				

					//echo $message; exit; 

					$this->email->from('do-not-reply@taskfeed.com', $from);
					$this->email->to($contractor->user_email);

					$this->email->subject($subject);
					$this->email->message($message,TRUE);
					
					if(!$this->email->send()){
						//echo $this->email->print_debugger();
						//exit;
					}
				}
			}






			$this->session->set_flashdata('message', '<div class="alert alert-success" >ToDo added.</div>');
			redirect(base_url('project/view')."/".$project_url."/todo");
		}






		else{
			$this->session->set_flashdata('message', '<div class="alert alert-error" >Sorry, something went wrong and we could not add the ToDo... Try again?</div>');
			redirect(base_url('project/view')."/".$project_url."/todo");
		}


	}//DISCUSSION ADDING COMPLETE










	public function load_milestone($project_url){

		//lets find out the project ID first

		$pid = $this->find_project_id_by_url($project_url);

		//echo $pid; exit;

		//Lets get all the threads for this project
		$milestone = $this->db
						->select('*')
						->from('tf_milestones')
						->group_by("tf_milestones.pm_id")
						->where('pm_pid',$pid)
						->join('users','users.id=tf_milestones.pm_creator')
						->order_by('pm_time','ASC')
						->get()
						->result();

		//print_r($discussions); exit;
		return $milestone;

	} // Milestone Loading Ends







	public function add_milestone($data_milestone,$project_url,$pt_creator,$data_powner){

		//echo $data_powner['powner_id']; exit;

		$qry1 = $this->db->insert('tf_milestones',$data_milestone);


		//get project data
		$project_data = $this->find_project_by_id($data_milestone['pm_pid']);

		foreach ($project_data as $prj) {
			$project_title = $prj->p_title;
		}



		if($qry1){

			//AH we got to email again :(

			//Am I the creator of project?
			$logged = $this->session->userdata('user_id');


			//get everyone in the project except me
			$team = $this->db
						->from('tf_project_user')
						->where('pu_pid',$data_milestone['pm_pid'])
						->where('pu_uid !=',$pt_creator)
						->join('users','users.id=tf_project_user.pu_uid')
						->get()
						->result();

			//print_r($team); exit;

			if(!empty($team)){
				
				foreach ($team as $contractor) {


				//Lets create a notification for them
				//pn_id, pn_to, pn_from, pn_created, pn_content, pn_link
				$pn_content = substr(strip_tags($data_milestone['pm_desc']), 0,150 )."...";
				$pn_link = base_url('project/view')."/".$project_url."/milestones";
				$pn_title = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')."
							added a Milestone on ".$project_title;

				$notification_data = array(
											'pn_to' => $contractor->id,
											'pn_from' => $this->session->userdata('user_id'),
											'pn_created' => time(),
											'pn_title' =>  $pn_title,
											'pn_content' => $pn_content,
											'pn_link' => $pn_link,
											'pn_unread' => 1

										);

				//print_r($notification_data); exit;

				$notify = $this->db
							->insert('tf_notification',$notification_data);


					//=============================================================================================
					//========================================== SEND EMAIL =======================================
					//=============================================================================================


					$from = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')." | Taskfeed";
					
					$subject = "TaskFeed : New Milestone Added - ".$project_title;
					$message = 	"<html><body>"
								."<div style='width:99%; height:60px; line-height:50px; background:#852B99;' >
								<h1 style='color:#fff; padding:0px 0px 0px 25px; ' >T a s k F e e d</h1></div>
								<div style='width:95%; border:thin solid #ccc; padding:10px; ' >"
								."<p>Hi ".$contractor->user_fname." ".$contractor->user_lname
								." ! <br /><br />A new Milestone has been added by <strong>"
								.$this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')." </strong>
								on the Milestone section for the project <strong>"
								.$project_title."</strong> .We are notifying you as a team member of this project.<br /><br />"
								."Here's the Milestone Description from him : <br /><br />"
								."<blockquote>".$data_milestone['pm_desc']."</blockquote>"
								."<br />"
								."Please use the following link to View more.<br /><br />"
								."<a style='display:block; width:200px; text-decoration:none; height:40px; background:#1D943B; font-size:18px; font-weight:bolder;
								color:#fff; line-height:40px; text-align:center; ' href='".base_url('project/view')."/"
								.$project_url."/milestones"."' >View ToDo</a></p><br />"
								."<p style='color:#888'>TaskFeed Team, ".date('Y')."</p>
								</div></body></html>";
				
				

					//echo $message; exit; 

					$this->email->from('do-not-reply@taskfeed.com', $from);
					$this->email->to($contractor->user_email);

					$this->email->subject($subject);
					$this->email->message($message,TRUE);
					
					if(!$this->email->send()){
						//echo $this->email->print_debugger();
						//exit;
					}
				}
			}






			$this->session->set_flashdata('message', '<div class="alert alert-success" >Milestone Added.</div>');
			redirect(base_url('project/view')."/".$project_url."/milestones");
		}






		else{
			$this->session->set_flashdata('message', '<div class="alert alert-error" >Sorry, something went wrong and we could not add the Milestone... Try again?</div>');
			redirect(base_url('project/view')."/".$project_url."/milestones");
		}


	}//Milestone ADDING COMPLETE




//================================ NOTHING AFTER THIS LINE
}
/* End of file project_model.php */
/* Location: ./application/models/project_model.php */

/*		
Copying/Distributing any part of the code is strictly prohibited.
created by A.B.M Shahnewaz Rifat, 2013 
All Rights Reserved
*/