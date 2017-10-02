<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project extends CI_Controller {

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


	public function index(){
		redirect('project/manage');
	}




	public function new_project_step1(){
    
        $this->data['message'] = $this->session->flashdata('message');

		$this->data['main_view']="new_project_wizard/step1_view";


		$this->load->model('page_info_model');
		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);


		//Load Project Groups
		$user_id = $this->session->userdata('user_id');
		$this->load->model('project_model');
		$this->data['groups'] = $this->project_model->get_groups($user_id);
		$this->data['team_members'] = $this->project_model->get_contractors_num();

		$this->load->view('includes/template',$this->data);
	}



	public function new_project_step2(){
    
		$this->form_validation->set_rules('p_title', 'Project Title', 'required|min_length[2]');
		$this->form_validation->set_rules('p_deadline', 'Project Deadline', 'required');
		$this->form_validation->set_rules('p_desc', 'Project Description', 'required|min_length[5]');
    

		if ($this->form_validation->run() == TRUE)
		{		

			// Storing previous step Data =================================== //
			$this->data['p_title'] = $this->input->post('p_title');
			$this->data['p_deadline'] = $this->input->post('p_deadline');
			$this->data['p_desc'] = $this->input->post('p_desc',FALSE);


			$p_group_input = $this->input->post('p_group');
			$p_new_group_input = $this->input->post('new_group');

			if(empty($p_group_input) && empty($p_new_group_input) ){
				$this->session->set_flashdata('message', '<div class="alert alert-error" >You must select a Project Group OR Create a new one to proceed...</div>');
				redirect('project/new/step1');
			}
			elseif(!empty($p_group_input) && empty($p_new_group_input) ) {
					$this->data['p_group'] = $this->input->post('p_group');
			}
			else{
					$new_group = $this->input->post('new_group');
					$this->load->model('project_model');
					$new_group_id = $this->project_model->insert_group($new_group);
					$this->data['p_group'] = $new_group_id;
			}
			


			// Storing previous step Data =================================== //	


    
	        $this->data['message'] = $this->session->flashdata('message');

			$this->data['main_view']="new_project_wizard/step2_view";


			$this->load->model('page_info_model');

			$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);


			$this->load->view('includes/template',$this->data);
		}//Validation was true
		else{
			$this->session->set_flashdata('message', validation_errors());
			redirect('project/new/step1');
		}

	}



	public function new_project_step3(){

		$this->data['p_discussion'] = $this->input->post('discussion');
		$this->data['p_milestone'] = $this->input->post('milestone');
		$this->data['p_todo'] = $this->input->post('todo');

		//================ FROM STEP 2 ================================
		$this->data['p_title'] = $this->input->post('p_title');
		$this->data['p_deadline'] = $this->input->post('p_deadline');
		$this->data['p_desc'] = $this->input->post('p_desc',FALSE);
		$this->data['p_group'] = $this->input->post('p_group');
		//================ FROM STEP 2 ================================

		$this->form_validation->set_rules('p_title', 'Project Title', 'required|min_length[2]');
		$this->form_validation->set_rules('p_deadline', 'Project Deadline', 'required');
		$this->form_validation->set_rules('p_desc', 'Project Description', 'required|min_length[5]');


		if ($this->form_validation->run() == TRUE)
		{    
		//Let's load the contractors
		$this->load->model('project_model');
		$this->data['contractors'] = $this->project_model->get_all_contractors();

	        $this->data['message'] = $this->session->flashdata('message');

			$this->data['main_view']="new_project_wizard/step3_view";


			$this->load->model('page_info_model');

			$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);

			$this->load->view('includes/template',$this->data);
		}
		else{
			$this->session->set_flashdata('message', validation_errors());
			redirect('project/new/step1');			
		}

    	
	}


	public function new_project_completed(){

		$this->form_validation->set_rules('p_title', 'Project Title', 'required|min_length[2]');
		$this->form_validation->set_rules('p_deadline', 'Project Deadline', 'required');
		$this->form_validation->set_rules('p_desc', 'Project Description', 'required|min_length[5]');
		$this->form_validation->set_rules('contractors', 'Contractors', 'required');

		if ($this->form_validation->run() == TRUE)
		{  

			//deadline strtotime
			$dead_date = $this->input->post('p_deadline');
			$deadline = strtotime($dead_date);

			//lets get the creator
			$project_creator = $this->session->userdata('user_id');

			//here's the contractors
			$contractors = $this->input->post('contractors');

			$token = sha1($this->input->post('p_title').$this->input->post('p_desc'));


			//generate the URL (friendly)
			$this->load->model('utilities_model');

			$title = $this->input->post('p_title');
			$project_url = $this->utilities_model->create_project_url($title);



			$data = array(
				'p_title' => $this->input->post('p_title'),
				'p_created_by' => $project_creator,
				'p_created_date' => time(),
				'p_deadline' => $deadline,
				'p_active' => 1,
				'p_status' => 'New',
				'p_group' => $this->input->post('p_group'),
				'p_desc' => $this->input->post('p_desc',FALSE),
				'p_discussion' => $this->input->post('p_discussion'),
				'p_milestone' => $this->input->post('p_milestone'),
				'p_todo' => $this->input->post('p_todo'),
				'p_join_token' => $token,
				'p_url' => $project_url 
				);


			//print_r($data); exit;

			$this->load->model('project_model');
			$this->project_model->create_project($data,$contractors);
			//exit;

			//LETS CONGRATULATE!

				$this->data['message'] = $this->session->flashdata('message');

				$this->data['project_message'] = "Thanks <strong>".$this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')
							."</strong>! The project <strong>".$data['p_title']."</strong> has been created successfully.<br /> <br /> 
							Notification email(s) has been sent to the contractor(s) you have chosen. <br /> 

							<br /> <br /> You will be notified back once the contractors Accept and Join your project!<br /> <br /> ";


				$this->data['project_url'] = base_url()."project/view/".$project_url; 

				$this->data['main_view']="new_project_wizard/completed_view";

				$this->load->model('page_info_model');

				$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);

				$this->load->view('includes/template',$this->data);
		}
		else {
			$this->session->set_flashdata('message', validation_errors());
			redirect('project/new/step1');
		}
    
	}



	public function view_project(){

		$project_url = $this->uri->segment(3);

		//lets find the project
		$this->load->model('project_model');
		$project_data = $this->project_model->find_project_by_url($project_url);

		$this->data['project_data'] = $project_data['project'];
		$this->data['project_user'] = $project_data['users'];
		$this->data['project_creator'] = $project_data['creator'];



		//Sharing is caring
		$this->load->model('project_model');
		$this->data['contractors'] = $this->project_model->get_all_contractors();


		//LETS LOAD THE PROJECT VIEW

        $this->data['message'] = $this->session->flashdata('message');

		$this->data['main_view']="project/project_dashboard_view";

		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);

		$this->load->view('includes/template',$this->data);

	}



	public function edit_project(){

		$project_url = $this->uri->segment(3);

		if(empty($project_url)){
			$this->session->set_flashdata('message', '<div class="alert alert-error" >You must select a valid project to edit it.</div>');
			redirect('project/manage/projects');
		}

		//lets find the project
		$this->load->model('project_model');
		$project_data = $this->project_model->find_project_by_url($project_url);

		$this->data['project_data'] = $project_data['project'];
		$this->data['project_user'] = $project_data['users'];
		$this->data['project_creator'] = $project_data['creator'];

		foreach ($project_data['creator'] as $owner_data) {
			$owner_id = $owner_data->id;
		}






		// Lets check if the currently logged in user is eligible for editing this project
		$logged  = $this->session->userdata('user_id');

		if($owner_id!= $logged){
			$this->session->set_flashdata('message', '<div class="alert alert-error" >Sorry, you are not eligible to edit that projet.</div>');
			redirect('dashboard');
		}

		//Load Project Groups
		$user_id = $this->session->userdata('user_id');
		$this->load->model('project_model');
		$this->data['groups'] = $this->project_model->get_groups($user_id);

		//LETS LOAD THE PROJECT VIEW

        $this->data['message'] = $this->session->flashdata('message');

		$this->data['main_view']="project/project_edit_view";

		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);

		$this->load->view('includes/template',$this->data);


		// ONLY IF THE FORM WAS SUBMITTED
		if($this->input->post('prj_edit')){
			//lets load the form validation RULES
			$this->form_validation->set_rules('p_title', 'Project Title', 'required|min_length[2]');
			$this->form_validation->set_rules('p_deadline', 'Project Deadline', 'required');
			$this->form_validation->set_rules('p_desc', 'Project Description', 'required|min_length[20]');

		
			//IF FORM VALIDATION WAS SUCCESSFULL
			if ($this->form_validation->run() == TRUE)
			{  

				$dead_date = $this->input->post('p_deadline');
				$deadline = strtotime($dead_date);


				$p_group_input = $this->input->post('p_group');
				$p_new_group_input = $this->input->post('new_group');

				if(empty($p_group_input) && empty($p_new_group_input) ){
					$this->session->set_flashdata('message', '<div class="alert alert-error" >You must select a Project Group OR Create a new one to proceed...</div>');
					redirect('project/new/step1');
				}
				elseif(!empty($p_group_input) && empty($p_new_group_input) ) {
						$new_group_id = $this->input->post('p_group');
				}
				else{
						$new_group = $this->input->post('new_group');
						$this->load->model('project_model');
						$new_group_id = $this->project_model->insert_group($new_group);
				}


				// Lets store the information to the database
				$data = array(
					'p_title' => $this->input->post('p_title'),
					'p_deadline' => $deadline,
					'p_group' => $new_group_id,
					'p_desc' => $this->input->post('p_desc',FALSE)
					);


				//Lets store the edited data.
				$this->project_model->edit_project($data);



			}
		}

		//EDIT PROJECT ENDS HERE
	}





	public function join_project(){

		// Information we need to join the user
		$join_pin = $this->uri->segment(3);

		//lets send this to model
		$this->load->model('project_model');
		$this->project_model->join_project($join_pin);

	}


	public function join_error(){

        $this->data['message'] = $this->session->flashdata('message');

		$this->data['main_view']="project/join_error_view";


		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);


		$this->load->view('includes/template',$this->data);		

	}



	public function discussion(){

		$this->data['message'] = $this->session->flashdata('message');
		$project_url = $this->uri->segment(3);

		//lets find the project
		$this->load->model('project_model');
		$project_data = $this->project_model->find_project_by_url($project_url);

		$this->data['project_data'] = $project_data['project'];
		$this->data['project_user'] = $project_data['users'];
		$this->data['project_creator'] = $project_data['creator'];



		// Pagination =================================
		$num_rows = $this->project_model->get_discussion_num($project_url);


		$this->load->library('pagination');

		$config['base_url'] = base_url('project/view/'.$project_url.'/discussion');
		$config['total_rows'] = $num_rows;
		$config['uri_segment'] = 5;
		$config['num_links'] = 4;
		$config['use_page_numbers'] = FALSE;


		$config['per_page'] = 5;

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
		$condition =  array('limit' =>$config['per_page'] , 'offset' =>$this->uri->segment(5) );


		//Lets load the discussion thread now
		$this->data['discussion_data'] = $this->project_model->load_discussions($project_url,$condition);


		//If a discussion was added
		if($this->input->post('new_discussion')){
			//lets load the form validation RULES
			$this->form_validation->set_rules('pd_comment', 'Discussion Text', 'required|min_length[5]');

		
			//IF FORM VALIDATION WAS SUCCESSFULL
			if ($this->form_validation->run() == TRUE)
			{  

					//Lets prepare the User Information
					//pd_id, pd_comment, pd_creator, pd_time, pd_pid
					$pd_creator = $this->session->userdata('user_id');
					$pd_pid = $this->project_model->find_project_id_by_url($project_url);
					$pd_comment = $this->input->post('pd_comment');

					$file = $_FILES['userfile']['name']; 
					//echo $file; exit;


						if(!empty($file)) {

							// Uploading image =========================================================

							//Allowing all file types now


							$config['allowed_types'] = 'avi|mov|mpg|mpeg|ai|tiff|gz|swf|tar|tgz|psd|csv|rtf|doc|docx|pdf|xls|xlsx|odt|ppt|pptx|txt|pub|zip|rar|7z|tar|gif|jpeg|png|bmp|jpg|mp3|wav|wmv|flv';
							$config['upload_path'] = './uploads/discussion_data/';
							$config['max_size']	= '5120';
							$config['encrypt_name'] = TRUE;
								

							$this->load->library('upload', $config);

							if($this->upload->do_upload()){

								$file_data = $this->upload->data();

								$file_name = $file_data['raw_name'];
								$file_ext = $file_data['file_ext'];

								//tf_discussion_files : 
								//id, tfile_pid, tfile_real_name, tfile_server_name, tfile_ext, tfile_created_by, tfile_created, tfile_downloaded
								$data_attachment = array(
														'tfile_pid' => $pd_pid,
														'tfile_real_name' => $file ,
														'tfile_server_name' => $file_name,
														'tfile_ext' => $file_ext,
														'tfile_created_by' => $pd_creator,
														'tfile_created' => time()
														);	

								//print_r($data_attachment); exit;

							}else{								
								$data_attachment = array();
							}
						}
						else{
								$data_attachment = array();
						}




					$data_discussion = array(
											'pd_comment' => $pd_comment,
											'pd_creator' => $pd_creator ,
											'pd_time' => time(),
											'pd_pid' => $pd_pid
											);

					$data_powner = array(
										'powner_id' => $this->input->post('powner_id'),
										'powner_name' => $this->input->post('powner_name'),
										'powner_email' => $this->input->post('powner_email') 
										);

					$this->project_model->add_discussion($data_discussion,$data_attachment,$project_url,$pd_creator,$data_powner);				

			}	


		}	




		//Sharing is caring
		$this->load->model('project_model');
		$this->data['contractors'] = $this->project_model->get_all_contractors();


		//LETS LOAD THE PROJECT VIEW

        $this->data['message'] = $this->session->flashdata('message');

		$this->data['main_view']="project/project_discussion_view";

		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);

		$this->load->view('includes/template',$this->data);

	}


	public function todo(){

		$this->data['message'] = $this->session->flashdata('message');
		$project_url = $this->uri->segment(3);

		//lets find the project
		$this->load->model('project_model');
		$project_data = $this->project_model->find_project_by_url($project_url);

		$this->data['project_data'] = $project_data['project'];
		$this->data['project_user'] = $project_data['users'];
		$this->data['project_creator'] = $project_data['creator'];

		//Lets load the discussion thread now
		$this->data['todo_data'] = $this->project_model->load_todo($project_url);


		//If a discussion was added
		if($this->input->post('new_todo')){
			//echo "okay"; exit;
			//lets load the form validation RULES
			$this->form_validation->set_rules('pt_comment', 'ToDo Text', 'required|min_length[5]');
			$this->form_validation->set_rules('pt_priority', 'ToDo Priority', 'required');

		
			//IF FORM VALIDATION WAS SUCCESSFULL
			if ($this->form_validation->run() == TRUE)
			{  
				//echo "okay"; exit;

					//Lets prepare the User Information
					//pd_id, pd_comment, pd_creator, pd_time, pd_pid
					$pt_creator = $this->session->userdata('user_id');
					$pt_pid = $this->project_model->find_project_id_by_url($project_url);
					$pt_comment = $this->input->post('pt_comment');
					$pt_priority = $this->input->post('pt_priority');

					

					$data_todo = array(
											'pt_comment' => $pt_comment,
											'pt_priority' => $pt_priority,
											'pt_creator' => $pt_creator,
											'pt_time' => time(),
											'pt_pid' => $pt_pid
											);

					$data_powner = array(
										'powner_id' => $this->input->post('powner_id'),
										'powner_name' => $this->input->post('powner_name'),
										'powner_email' => $this->input->post('powner_email') 
										);

					$this->project_model->add_todo($data_todo,$project_url,$pt_creator,$data_powner);				

			}	


		}	


		//Sharing is caring
		$this->load->model('project_model');
		$this->data['contractors'] = $this->project_model->get_all_contractors();



		//LETS LOAD THE PROJECT VIEW

        $this->data['message'] = $this->session->flashdata('message');

		$this->data['main_view']="project/project_todo_view";

		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);

		$this->load->view('includes/template',$this->data);
	}



	public function milestones(){

		$this->data['message'] = $this->session->flashdata('message');
		$project_url = $this->uri->segment(3);

		//lets find the project
		$this->load->model('project_model');
		$project_data = $this->project_model->find_project_by_url($project_url);

		$this->data['project_data'] = $project_data['project'];
		$this->data['project_user'] = $project_data['users'];
		$this->data['project_creator'] = $project_data['creator'];

		//Lets load the discussion thread now
		$this->data['milestone_data'] = $this->project_model->load_milestone($project_url);


		//If a discussion was added
		if($this->input->post('new_milestone')){
			//echo "okay"; exit;
			//lets load the form validation RULES
			$this->form_validation->set_rules('pm_title', 'Milestone Title', 'required');
			$this->form_validation->set_rules('pm_due', 'Milestone Deadline', 'required');
			$this->form_validation->set_rules('pm_desc', 'Milestone Description', 'required|min_length[5]');

		
			//IF FORM VALIDATION WAS SUCCESSFULL
			if ($this->form_validation->run() == TRUE)
			{  
				//echo "okay"; exit;

					//Lets prepare the User Information
					//pm_id, pm_desc, pm_due, pm_time, pm_pid, pm_creator
					$pm_creator = $this->session->userdata('user_id');
					$pm_pid = $this->project_model->find_project_id_by_url($project_url);
					$pm_title = $this->input->post('pm_title');
					$pm_desc = $this->input->post('pm_desc');
					$pm_due = strtotime($this->input->post('pm_due'));
					$pm_time = time();

					

					$data_milestone = array(
											'pm_title' => $pm_title,
											'pm_desc' => $pm_desc,
											'pm_creator' => $pm_creator,
											'pm_due' => $pm_due,
											'pm_time' => time(),
											'pm_pid' => $pm_pid
											);

					$data_powner = array(
										'powner_id' => $this->input->post('powner_id'),
										'powner_name' => $this->input->post('powner_name'),
										'powner_email' => $this->input->post('powner_email') 
										);

					$this->project_model->add_milestone($data_milestone,$project_url,$pm_creator,$data_powner);				

			}	


		}	


		//Sharing is caring
		$this->load->model('project_model');
		$this->data['contractors'] = $this->project_model->get_all_contractors();


		//LETS LOAD THE PROJECT VIEW

        $this->data['message'] = $this->session->flashdata('message');

		$this->data['main_view']="project/project_milestones_view";

		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);

		$this->load->view('includes/template',$this->data);
	}


//=================================MANAGE======================================//

	public function manage_projects(){

		// FIND PROJECTS CREATED BY ME
		$this->load->model('project_model');


		// Pagination =================================
		$num_rows = $this->project_model->find_my_projects_num();


		$this->load->library('pagination');

		$config['base_url'] = base_url('project/manage/projects');
		$config['total_rows'] = $num_rows;
		$config['uri_segment'] = 4;
		$config['num_links'] = 4;
		$config['use_page_numbers'] = FALSE;


		$config['per_page'] = 10;

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
		$condition =  array('limit' =>$config['per_page'] , 'offset' =>$this->uri->segment(4) );


		$this->data['my_projects'] = $this->project_model->find_my_projects($condition);


		//LETS LOAD THE PROJECT VIEW

        $this->data['message'] = $this->session->flashdata('message');

		$this->data['main_view']="members/manage_projects_view";

		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);

		$this->load->view('includes/template',$this->data);
	}



	public function manage_tasks(){

		// FIND PROJECTS I'VE JOINED/INVITED
		$this->load->model('project_model');



		// Pagination =================================
		$num_rows = $this->project_model->find_my_tasks_num();


		$this->load->library('pagination');

		$config['base_url'] = base_url('project/manage/tasks');
		$config['total_rows'] = $num_rows;
		$config['uri_segment'] = 4;
		$config['num_links'] = 4;
		$config['use_page_numbers'] = FALSE;


		$config['per_page'] = 10;

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
		$condition =  array('limit' =>$config['per_page'] , 'offset' =>$this->uri->segment(4) );




		$this->data['my_tasks'] = $this->project_model->find_my_tasks($condition);


		//LETS LOAD THE PROJECT VIEW

        $this->data['message'] = $this->session->flashdata('message');

		$this->data['main_view']="members/manage_tasks_view";

		$this->load->model('page_info_model');

		$this->data['page_info'] = $this->page_info_model->get_info($this->data['main_view']);

		$this->load->view('includes/template',$this->data);
	}




public function delete_project(){

	$pid = $this->uri->segment(3);

	$this->load->model('project_model');

	// Lets send to the authority :) 
	$pr_delete = $this->project_model->delete_project($pid);


	if ($pr_delete) {
		$this->session->set_flashdata('message', '<div class="alert alert-success" > Project deleted successfully. Notification sent to users...</div>');
		redirect('project/manage/projects');
	}
	else {
		$this->session->set_flashdata('message', '<div class="alert alert-error" >Sorry, the project could not be deleted...</div>');
		redirect('project/manage/projects');
	}

}//DELETE PROJECT ENDS




public function finish_project(){

	$pid = $this->uri->segment(3);

	$this->load->model('project_model');

	// Lets send to the authority :) 
	$pr_delete = $this->project_model->finish_project($pid);


	if ($pr_delete) {
		$this->session->set_flashdata('message', '<div class="alert alert-success" > Congratulations! Project Marked as finished. Notification sent to users...</div>');
		redirect('project/manage/projects');
	}
	else {
		$this->session->set_flashdata('message', '<div class="alert alert-error" >Sorry, the project could not be Finished at this moment...</div>');
		redirect('project/manage/projects');
	}

}//DELETE PROJECT ENDS



//POST INSTALLATION SCRIPTS

	public function install_discussion(){
		$purl = $this->uri->segment(5);
		$token = $this->uri->segment(4);
		$data = array('p_discussion' =>'install');
		
		$install = $this->db->where('p_join_token',$token)->update('tf_projects',$data);

		if($install){
			$this->session->set_flashdata('message', '<div class="alert alert-success" >Discussion app successfully installed for this project.</div>');
			redirect('project/view'."/".$purl."/discussion");
		}
		else {
			$this->session->set_flashdata('message', '<div class="alert alert-error" >Sorry, Discussion app could not be installed for this project.</div>');
			redirect('project/view'."/".$purl."/discussion");			
		}


	}

	public function install_milestone(){
		$purl = $this->uri->segment(5);
		$token = $this->uri->segment(4);
		$data = array('p_milestone' =>'install');
		
		$install = $this->db->where('p_join_token',$token)->update('tf_projects',$data);

		if($install){
			$this->session->set_flashdata('message', '<div class="alert alert-success" >Milestone app successfully installed for this project.</div>');
			redirect('project/view'."/".$purl."/milestones");
		}
		else {
			$this->session->set_flashdata('message', '<div class="alert alert-error" >Sorry, Milestone app could not be installed for this project.</div>');
			redirect('project/view'."/".$purl."/milestones");			
		}

	}

	public function install_todo(){
		$purl = $this->uri->segment(5);
		$token = $this->uri->segment(4);
		$data = array('p_todo' =>'install');
		
		$install = $this->db->where('p_join_token',$token)->update('tf_projects',$data);

		if($install){
			$this->session->set_flashdata('message', '<div class="alert alert-success" >ToDo app successfully installed for this project.</div>');
			redirect('project/view'."/".$purl."/todo");
		}	
		else {
			$this->session->set_flashdata('message', '<div class="alert alert-error" >Sorry, ToDo app could not be installed for this project.</div>');
			redirect('project/view'."/".$purl."/todo");			
		}

	}//ENDS INSTALL TODO


	public function delete_todo(){

		$pt_id = $this->uri->segment(3);
		$project_url = $this->uri->segment(4);

		$qry1 = $this->db->where('pt_id',$pt_id)->delete('tf_project_todo');
		if ($qry1) {
			$this->session->set_flashdata('message', '<div class="alert alert-success" >ToDo Deleted from project...</div>');
			redirect(base_url('project/view')."/".$project_url."/todo");
		}
		else {
			$this->session->set_flashdata('message', '<div class="alert alert-error" >ToDo could not be deleted...</div>');
			redirect(base_url('project/view')."/".$project_url."/todo");
		}		

	}//ENDS THE DELETE TODO FUNCTION	



	public function delete_milestone(){

		$pm_id = $this->uri->segment(3);
		$project_url = $this->uri->segment(4);

		$qry1 = $this->db->where('pm_id',$pm_id)->delete('tf_milestones');
		if ($qry1) {
			$this->session->set_flashdata('message', '<div class="alert alert-success" >Milestone Deleted from project...</div>');
			redirect(base_url('project/view')."/".$project_url."/milestones");
		}
		else {
			$this->session->set_flashdata('message', '<div class="alert alert-error" >Milestone could not be deleted...</div>');
			redirect(base_url('project/view')."/".$project_url."/milestones");
		}		

	}//ENDS THE DELETE TODO FUNCTION	








	public function leave_project(){

		$pid = $this->uri->segment(3);
		$pu_uid = $this->session->userdata('user_id');

		$username = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname');
		//Lets pull data about this team and email the owner!
		$this->load->model('project_model');
		$project_info = $this->project_model->find_project_detail_by_id($pid);

		foreach ($project_info as $info) {
			$email = $info->user_email;
			$project_owner = $info->user_fname." ".$info->user_lname;
			$project_name = $info->p_title;
		}


		//Lets delete the request quickly
		$leave = $this->db->where('pu_uid',$pu_uid)->where('pu_pid',$pid)->delete('tf_project_user');


		//Lets email him

			$from_project_creator = $username." | Taskfeed";
							
			$subject = "TaskFeed :".$username." left your Project.";
			$message = 	"<html><body>
						<div style='width:99%; height:60px; line-height:50px; background:#852B99;' >
						<h1 style='color:#fff; padding:0px 0px 0px 25px; ' >T a s k F e e d</h1></div>
						<div style='width:95%; border:thin solid #ccc; padding:10px; ' >"
						."<p>Hi ".$project_owner.", this is to inform you that one of your Project Member <strong>".$username."</strong> of the project <strong>"
						.$project_name."</strong> has left the project recently. </p><br /><p>If you think it was a mistake by the other party,
						please contact him via email first before inviting him again by TaskFeed.
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

			$this->session->set_flashdata('message', '<div class="alert alert-warning" > Project left by you. '.$project_owner.' has been notified by email.</div>');
			redirect(base_url('project/manage/tasks'));	

	}






	public function reject_project(){

		$pid = $this->uri->segment(3);
		$pu_uid = $this->session->userdata('user_id');

		$username = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname');
		//Lets pull data about this team and email the owner!
		$this->load->model('project_model');
		$project_info = $this->project_model->find_project_detail_by_id($pid);

		foreach ($project_info as $info) {
			$email = $info->user_email;
			$project_owner = $info->user_fname." ".$info->user_lname;
			$project_name = $info->p_title;
		}


		//Lets delete the request quickly
		$leave = $this->db->where('pu_uid',$pu_uid)->where('pu_pid',$pid)->delete('tf_project_user');


		//Lets email him

			$from_project_creator = $username." | Taskfeed";
							
			$subject = "TaskFeed :".$username." Rejected your Project Invitation.";
			$message = 	"<html><body>
						<div style='width:99%; height:60px; line-height:50px; background:#852B99;' >
						<h1 style='color:#fff; padding:0px 0px 0px 25px; ' >T a s k F e e d</h1></div>
						<div style='width:95%; border:thin solid #ccc; padding:10px; ' >"
						."<p>Hi ".$project_owner.", this is to inform you that <strong>".$username."</strong> has unfortunately Rejected the invitation to join the project : <strong>"
						.$project_name."</strong>. </p><br /><p>If you think it was a mistake by the other party,
						please contact him via email first before inviting him again by TaskFeed.
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

			$this->session->set_flashdata('message', '<div class="alert alert-warning" > Project Invitation Rejected by you. '.$project_owner.' has been notified by email.</div>');
			redirect(base_url('project/manage/tasks'));	

	}






	public function add_new_contractors(){

		$project_link = $this->input->post('return_link');

		$this->form_validation->set_rules('contractors', 'Contractors', 'required');

		if ($this->form_validation->run() == TRUE){  
			$contractors = $this->input->post('contractors');

		//================NEW CODE

			$project_id = $this->input->post('project_id');
			//Lets get the project Info
			$this->load->model('project_model');
			$pinfo = $this->project_model->find_project_detail_by_id($project_id);
			foreach ($pinfo as $prinfo) {
				$project_title = $prinfo->p_title;
				$project_token = $prinfo->p_join_token;
			}

		//assign the contractors and send email notifications to them


			foreach ($contractors as $contractor) {

			

			$assign = array(
				'pu_uid' => $contractor,
				'pu_pid' => $project_id,
				'pu_joined' => 0
				);

			//lets check if this has been already shared
			$share_num = $this->db->from('tf_project_user')
							->where('pu_uid',$contractor)
							->where('pu_pid',$project_id)
							->get()
							->num_rows();
				if ($share_num==0) {

					$share_now = $this->db->insert('tf_project_user',$assign);
			
			
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
								." has invited you to the Project ".$project_title.".";

					$pn_link = base_url('project/view')."/".$project_link;
					$pn_title = $this->session->userdata('user_fname')." ".$this->session->userdata('user_lname')."
							has invited you to the Project ".$project_title.".";

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
				
					$subject = "TaskFeed : Invitation for Project - ".$project_title;
					$message = 	"<html><body>"
							."<div style='width:99%; height:60px; line-height:50px; background:#852B99;' >
							<h1 style='color:#fff; padding:0px 0px 0px 25px; ' >T a s k F e e d</h1></div>
							<div style='width:95%; border:thin solid #ccc; padding:10px; ' >"
							."<p>Hi ".$invite->user_fname." ".$invite->user_lname
							." ! Good news! <br /><br />You are invited to join the project <strong>"
							.$project_title."</strong> by <strong>".$this->session->userdata('user_fname')
							." ".$this->session->userdata('user_lname')."</strong><br /><br />"
							."Please use the following link to join the project at your earliest time!<br /><br />"
							."<a style='display:block; width:200px; text-decoration:none; height:40px; background:#1D943B; font-size:18px; font-weight:bolder;
							color:#fff; line-height:40px; text-align:center; ' href='".base_url('project/join')."/"
							.$project_token."' >Accept &amp; Join Now! </a></p><br />"
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




				}//FOREACH ENDS HERE
			}/*NOT SHARING IF ITS ALREADY SHARED */ else{
				$this->session->set_flashdata('message', '<div class="alert alert-error">Project Invitation already sent to the specified user.</div>');
				redirect('project/view/'.$project_link);	
			} 
		}//SECOND FOREACH ENDS HERE
	
			$this->session->set_flashdata('message', '<div class="alert alert-success">Project Invitation Successfully sent to the selected users and they have been notified by an email! </div>');
			redirect('project/view/'.$project_link);	
		//=====================================
		//IF CONDITION after this line 
		}
		else{
			
			$this->session->set_flashdata('message', '<div class="alert alert-error">'.validation_errors().'</div>');
			redirect('project/view/'.$project_link);
		}

	}




//class ends after this line
}


/*		
Copying/Distributing any part of the code is strictly prohibited.
created by A.B.M Shahnewaz Rifat, 2013 
All Rights Reserved
*/