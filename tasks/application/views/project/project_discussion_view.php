
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

$logged_id = $this->session->userdata('user_id');

?>

	<div class="portlet box white">
		<div class="portlet-title">
			<div class="caption">
				<i class="icon-reorder"></i>
				<span class="hidden-480">Discussion : <?php echo $project->p_title; ?> | <a href="<?php echo base_url(); ?>user/profile/<?php echo $project_creator_name; ?>" target="_blank" ><?php echo $project_creator_fname." ".$project_creator_lname; ?></a></span>
			</div>

			<div class="actions">
				<a class="btn bigicn-only green" href="<?php echo base_url()."project/view/".$prj; ?>"><i class="icon-home"></i></a>
				<a class="btn blue" href="<?php echo base_url()."project/view/".$prj."/discussion" ?>"><i class="icon-comment"></i> Discussion</a>
				<a class="btn yellow-stripe" href="<?php echo base_url()."project/view/".$prj."/todo" ?>"><i class="icon-tasks"></i> To-Do</a>
				<a class="btn purple-stripe" href="<?php echo base_url()."project/view/".$prj."/milestones" ?>"><i class="icon-check"></i> MileStones</a>
			</div>
		</div>


		<div class="portlet-body">
			<!-- BEGIN DASHBOARD STATS -->
			<div class="row-fluid">



<?php if($discussion=="install"){ ?>

				<div class="span8 responsive">

								<div class='pagination pagination' >
									<ul>
										<?php echo $this->pagination->create_links(); ?>
									</ul>

								</div>	



					<?php
					if (!empty($discussion_data)) {
						foreach ($discussion_data as $discussion) { 
							if ($discussion->pd_creator==$discussion->p_created_by) {
								$div_class = " blue-hue";
							} else {
								$div_class = " green-hue";
							}
							?>
						

						<div class="media" >

							<a class="pull-left" href="#" >			
								<?php if(!empty($discussion->user_avatar_img)){ ?> 
									<img class="media-object" style="height:45px; " alt="<?php echo $discussion->user_fname." ".$discussion->user_lname; ?>" src="<?php echo base_url(); ?>uploads/profile_data/<?php echo $discussion->user_avatar_img.$discussion->user_avatar_ext; ?>" />
								<?php }else{	?>
									<img class="media-object" alt="<?php echo $discussion->user_fname." ".$discussion->user_lname; ?>" src="<?php echo base_url(); ?>themes/assets/img/avatar.png" />
								<?php } ?>
							</a>

							<div class="media-body <?php echo $div_class; ?>" >
								<?php echo stripslashes($discussion->pd_comment); ?>
								
								<small>Posted by : <a href="<?php echo base_url(); ?>user/profile/<?php echo $discussion->user_name; ?>" target="_blank" ><?php echo $discussion->user_fname." ".$discussion->user_lname; ?></a>
								&nbsp;&nbsp;At, <strong><?php echo date('d M Y H:i:s A',$discussion->pd_time) ?></strong>
								</small>

							<?php if(!empty($discussion->tfile_server_name)){ ?>

								<hr />
								
									<strong>Attachment: 
										<a target="_blank" href="<?php echo base_url()."uploads/discussion_data/".$discussion->tfile_server_name.$discussion->tfile_ext; ?>" >
											<?php echo $discussion->tfile_real_name; ?>
										</a>
									</strong>
								

							<?php } ?>

							</div>

						</div>
						<hr />

					<?php 
						} 
					}else {
						echo "There is currently no discussion for this project. Add a comment below and start the discussion!<hr />";
					}

					?>


					<?php echo form_open_multipart(current_url()); ?>
					<div class="control-group">
					  <div class="controls">

						<textarea name="pd_comment" > </textarea>
							<script>
								CKEDITOR.replace( 'pd_comment' );
							</script>
					   </div>
					</div>

					<div class="control-group">
					  <div class="controls">
					    <div class="fileupload fileupload-new" data-provides="fileupload">

							<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
						      
						      <div>
						          <span class="btn btn-file"><span class="fileupload-new">Select File</span>
						          <span class="fileupload-exists">Change</span>
						              <input type="file" class="default" name="userfile" /></span>
						              <a href="#" class="btn fileupload-exists" data-dismiss="fileupload" >Remove</a>
						      </div>
					    </div>
					   </div>
					</div>					

					<div class="control-group">
					  <div class="controls">
					  <!-- send data -->
					  <input type="hidden" name="powner_email" value="<?php echo $project_creator_email; ?>" />
					  <input type="hidden" name="powner_name" value="<?php echo $project_creator_full_name; ?>" />
					  <input type="hidden" name="powner_id" value="<?php echo $project_creator_id; ?>" />



					  	<input type="submit" name="new_discussion" class="btn green" value="Add Comment" />
					  </div>
					</div>
					<?php echo form_close(); ?>



								<div class='pagination pagination' >
									<ul>
										<?php echo $this->pagination->create_links(); ?>
									</ul>

								</div>	

				</div>


<?php }else{ ?>

	<div class="span8 responsive">
		<p>Sorry, Discussion App is not INSTALLED for this project.</p>

		<?php if($this->session->userdata('user_id')==$project_creator_id){ ?>

			<br /><a href="<?php echo base_url()."project/install/discussion/".$token."/".$purl; ?>" class="btn green">Install Discussion App Now</a>

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

