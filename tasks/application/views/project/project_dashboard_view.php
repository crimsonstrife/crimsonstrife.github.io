<?php if (!empty($message)) { ?>
			<?php echo $message; ?>
	<?php } ?>


<?php 

foreach ($project_data as $project) { 

	$title = $project->p_title;
	$group = $project->pg_name;
	$status = $project->p_status;
	$desc = $project->p_desc;
	$discussion = $project->p_discussion;
	$milestone = $project->p_milestone;
	$todo = $project->p_todo;

	$active = $project->p_active;

	$project_id = $project->pid;


		//Track Progress
		$timespan = $project->p_deadline - $project->p_created_date;
		$elapsed = $timespan - ($project->p_deadline - time());
		$job_done =  ceil(($elapsed/$timespan)*100);
		$percent_done = $job_done."%";
		//Tracking done

		//TIME CALCULATIONS
		$created = date('Y-m-d H:i:s A',$project->p_created_date);
		$deadline = date('Y-m-d',$project->p_deadline);
		$diff = $project->p_deadline - time();

		$days=floor($diff/(60*60*24));//seconds/minute*minutes/hour*hours/day)
		$hours=round(($diff-$days*60*60*24)/(60*60));

		$time_left = $days." Days ".$hours." Hours";

}


foreach ($project_creator as $prj_creator) { 

	$project_creator_name = $prj_creator->user_name;
	$project_creator_email = $prj_creator->user_email;
	$project_creator_fname = $prj_creator->user_fname;
	$project_creator_lname = $prj_creator->user_lname;
	$project_creator_online = $prj_creator->user_online;

	$owner_id = $prj_creator->id;

}

$prj = $this->uri->segment(3);

$logged_id = $this->session->userdata('user_id');

?>

	<div class="portlet box white">
		<div class="portlet-title">
			<div class="caption">
				<i class="icon-reorder"></i>
				<span class="hidden-480"><?php echo $project->p_title; ?> | <a href="<?php echo base_url(); ?>user/profile/<?php echo $project_creator_name; ?>" target="_blank" ><?php echo $project_creator_fname." ".$project_creator_lname; ?></a></span>
			</div>

			<?php if($active!=2){ ?>
				<div class="actions">
					<a class="btn blue" href="<?php echo base_url()."project/view/".$prj."/discussion" ?>"><i class="icon-comments"></i> Discussion</a>
					<a class="btn yellow" href="<?php echo base_url()."project/view/".$prj."/todo" ?>"><i class="icon-tasks"></i> To-Do</a>
					<a class="btn purple" href="<?php echo base_url()."project/view/".$prj."/milestones" ?>"><i class="icon-check"></i> MileStones</a>
				</div>
			<?php } ?>
		</div>


		<div class="portlet-body">
			<!-- BEGIN DASHBOARD STATS -->
			<div class="row-fluid">

				<div class="span8 responsive">
					<?php echo $desc; ?>
				</div>


				<div class="span4 responsive">
					<h1>Project Details</h1>
					<ul class="profile-classic">
						<li>Project Created By :<a href="<?php echo base_url(); ?>user/profile/<?php echo $project_creator_name; ?>" target="_blank" ><?php echo $project_creator_fname." ".$project_creator_lname; ?></a></li>
						<li>Project Group : <?php echo $group; ?></li>
						<li>Project Status : <span class="label label-success" ><?php echo $status; ?></span></li>
						<li><h3>Assigned To:</h3>
						<?php
							foreach ($project_user as $users) { ?>

							<?php if($users->user_online==1){ ?>
								<span class="label label-success label-mini">Online</span>
							<?php }else{ ?>
								<span class="label label label-mini">Offline</span>
							<?php } ?>

								<a href="<?php echo base_url(); ?>user/profile/<?php echo $users->user_name; ?>" target="_blank" ><?php echo $users->user_fname." ".$users->user_lname; ?></a>

							<?php if($users->pu_joined==1){ ?>
								&nbsp;<span class="text-success">(Joined)</span><br />
							<?php }else{ ?>
								&nbsp;<span class="text-warning">(Invited)</span> <br />
							<?php } ?>


						<?php } ?>
						</li>

						<li><h3>Time Management</h3>
						Created : <?php echo $created; ?><br />

							<?php if($active!=2){ ?>
								
									Deadline : <?php echo $deadline; ?> <br />
								
								<?php if($diff>0){ ?>
									<h3 class="text-success"><?php echo $time_left; ?> left till deadline.</h3>
									Overall Progress
									<div class="progress progress-warning">
										<div class="bar" style="width: <?php echo $percent_done; ?>;"><?php echo $percent_done; ?></div>
									</div>	

								<?php }else{ ?>
									<h4 class="text-error">Deadline Reached <?php echo substr($time_left, 1)." ago"; ?></h4>
								<?php } ?>


							<?php }else{ ?>
								Project Finished At : <?php echo $deadline; ?> <br />
							<?php } ?>

						</li>


				<?php if ( ($logged_id==$owner_id) && ($active!=2) ){ ?>

						<li>
							<h3>Add Contractors:</h3>
								<?php echo form_open('project/contractor/new'); ?>

												<div class="control-group">
													<label class="control-label">Assign To<span class="required">*</span></label>
													<div class="controls">
														<select 
															data-placeholder="Choose Contractors" 
															class="chosen span10" 
															multiple="multiple" 
															tabindex="6" 
															name="contractors[]" >
															<option value="" ></option>
															<?php 
																foreach ($contractors as $contractor) {
																	echo "<option value='".$contractor->id."' >"
																		.$contractor->user_fname." ".$contractor->user_lname
																		."</option>";
																}

															?>

														</select>
														<script type="text/javascript">
															$(".chosen").chosen();
														</script>
													</div>
												</div>

												<input type="hidden" name="project_owner_id" value="<?php echo $owner_id; ?>">
												<input type="hidden" name="project_id" value="<?php echo $project_id; ?>">

												<input type="hidden" name="return_link" value="<?php echo $this->uri->segment(3); ?>">
												<input type="submit" value="Share Now" class="btn green" />
								<?php echo form_close(); ?>
						</li>

						<?php }else{} ?>




					</ul>
				</div>
			</div>
		</div>
	</div>

