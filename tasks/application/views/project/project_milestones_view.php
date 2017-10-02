<?php if (!empty($message)) { ?>
			<?php echo $message; ?>
	<?php } ?>

	<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>

<?php 

foreach ($project_data as $project) { 

	$title = $project->p_title;
	$group = $project->pg_name;
	$status = $project->p_status;
	$desc = $project->p_desc;
	$discussion = $project->p_discussion;
	$milestone = $project->p_milestone;
	$todo = $project->p_todo;
	$purl = $project->p_url;

	$token = $project->p_join_token;

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
	$project_creator_fname = $prj_creator->user_fname;
	$project_creator_lname = $prj_creator->user_lname;
	$project_creator_online = $prj_creator->user_online;

	$project_creator_email = $prj_creator->user_email;
	$project_creator_full_name = $prj_creator->user_fname." ".$prj_creator->user_lname;
	$project_creator_id = $prj_creator->id;	
	$owner_id = $prj_creator->id;
}

$prj = $this->uri->segment(3);
$logged = $this->session->userdata('user_id');

$logged_id = $this->session->userdata('user_id');

?>

	<div class="portlet box white">
		<div class="portlet-title">
			<div class="caption">
				<i class="icon-reorder"></i>
				<span class="hidden-480">MileStones : <?php echo $project->p_title; ?> | <a href="<?php echo base_url(); ?>user/profile/<?php echo $project_creator_name; ?>" target="_blank" ><?php echo $project_creator_fname." ".$project_creator_lname; ?></a></span>
			</div>

			<div class="actions">
				<a class="btn bigicn-only green" href="<?php echo base_url()."project/view/".$prj; ?>"><i class="icon-home"></i></a>
				<a class="btn blue-stripe" href="<?php echo base_url()."project/view/".$prj."/discussion" ?>"><i class="icon-comments"></i> Discussion</a>
				<a class="btn yellow-stripe" href="<?php echo base_url()."project/view/".$prj."/todo" ?>"><i class="icon-tasks"></i> To-Do</a>
				<a class="btn purple" href="<?php echo base_url()."project/view/".$prj."/milestones" ?>"><i class="icon-check"></i> MileStones</a>
			</div>
		</div>


		<div class="portlet-body">
			<!-- BEGIN DASHBOARD STATS -->
			<div class="row-fluid">

<?php if($milestone=="install"){ ?>


				<div class="span8 responsive">
					<?php
					if (!empty($milestone_data)) {
						foreach ($milestone_data as $mile) { 
							?>
						

						<div class="tile double bg-blue" style="padding-bottom:50px; height:auto !important;" >
								<h4><?php echo $mile->pm_title; ?></h4>
								<p><?php echo stripslashes($mile->pm_desc); ?></p>
								<span class="label label-warning big" >Due On : <?php echo date('d M Y H:i:s A',$mile->pm_due); ?></span>
								
								<small class="pull-bottom" >Added : <?php echo date('d M Y H:i:s A',$mile->pm_time) ?></strong>

									<?php if ($project_creator_id==$logged) { ?>
										<a href="#deletedialog<?php echo  $mile->pm_id; ?>"   data-toggle="modal" class="btn mini red" ><i class="icon-trash"></i></a>
									
												<!-- LETS LOAD THE DELETE DIALOG -->
											<div id="deletedialog<?php echo $mile->pm_id; ?>" class="modal hide fade bg-grey" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
												<div class="modal-header">
													
														<h3 id="myModalLabel">Delete Milestone</h3>
												</div>

												<div class="modal-body">
													<p>Are you sure you want to delete this Milestone?</p>
												</div>
												
												<div class="modal-footer">
													<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
													<a href="<?php echo base_url(); ?>project/delete-milestone/<?php echo $mile->pm_id."/".$purl; ?>" class="btn red" >Delete Milestone</a>
												</div>
											</div>

									<?php } ?>
								</small>

						</div>


					<?php 
						} 
					}else {
						echo "There is currently no Milestones set for this project.<hr />";
					}

					?>

					<div style="clear:both;" ></div>
				<?php if ($project_creator_id==$logged) {
					
					echo form_open_multipart(current_url()); ?>
					<hr />
					<h1>Add A new Milestone</h1>

					<div class="control-group">
						<label for="pm_title" >Title</label>
					  <div class="controls">

						<input name="pm_title" class="m-wrap large" value="" />
	
					   </div>
					</div>


					<div class="control-group">
						<label class="control-label">Milestone Deadline<span class="required">*</span></label>
							<div class="controls">
								<input type="text" class="m-wrap m-ctrl-medium date-picker" name="pm_due" />
								<span class="help-inline"> Deadline for this Milestone.</span>
							</div>
							<script type="text/javascript">
								$('.date-picker').datepicker({
    								format: 'yyyy-mm-dd'
								});
							</script>
					</div>	

					<div class="control-group">
						<label for="pm_desc" >Description</label>
					  <div class="controls">

						<textarea name="pm_desc" class="m-wrap large" > </textarea>
	
					   </div>
					</div>
		

					<div class="control-group">
					  <div class="controls">
					  <!-- send data -->
					  <input type="hidden" name="powner_email" value="<?php echo $project_creator_email; ?>" />
					  <input type="hidden" name="powner_name" value="<?php echo $project_creator_full_name; ?>" />
					  <input type="hidden" name="powner_id" value="<?php echo $project_creator_id; ?>" />



					  	<input type="submit" name="new_milestone" class="btn green" value="Add Milestone" />
					  </div>
					</div>
					<?php echo form_close(); ?>

			<?php } // showed the new todo add option ?>
				</div>


<?php }else{ ?>

	<div class="span8 responsive">
		<p>Sorry, Milestone App is not INSTALLED for this project.</p>

		<?php if($this->session->userdata('user_id')==$project_creator_id){ ?>

			<br /><a href="<?php echo base_url()."project/install/milestone/".$token."/".$purl; ?>" class="btn green">Install Milestone App Now</a>

		<?php } ?>

	</div>

<?php } ?>

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









