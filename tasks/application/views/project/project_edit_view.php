<?php 

foreach ($project_data as $project) { 

	$title = $project->p_title;
	$c_group = $project->pg_name;
	$status = $project->p_status;
	$desc = $project->p_desc;
	$discussion = $project->p_discussion;
	$milestone = $project->p_milestone;
	$todo = $project->p_todo;



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

}

?>



<?php if(!empty($message)) { ?>
	<?php echo $message; ?>
<?php } ?>

<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>



				<div class="row-fluid">

					<div class="span12">
						<div class="portlet box purple" id="rootwizard">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-reorder"></i>Edit Project - <span class="step-title"><?php echo $title; ?></span>
								</div>

							</div>
							<div class="portlet-body form">

								<?php echo form_open(current_url(), 'class="form-horizontal"'); ?>
									

										<div class="tab-content">
											



										<!-- TAB 1 STARTS 
										=============================================================================-->
											<div class="tab-pane active" id="tab1">
												<div class="span12">
												<h3 class="block">Enter Project Description</h3>

												<div class="control-group">
													<label class="control-label">Project Title<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="span6 m-wrap" name="p_title" value="<?php echo $title; ?>" />
														<span class="help-inline">Name of the project</span>
													</div>
												</div>

												<div class="control-group">
													<label class="control-label">Project Deadline<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap m-ctrl-medium date-picker" name="p_deadline" value="<?php echo $deadline; ?>" />
														<span class="help-inline"> Deadline for this project.</span>
													</div>
													<script type="text/javascript">
														$('.date-picker').datepicker({
    														format: 'yyyy-mm-dd'
														});
													</script>
												</div>

												<div class="control-group">
													<label class="control-label">Project Group<span class="required">*</span></label>
													<div class="controls">												
														<select name="p_group" id="p_group">
															<?php foreach ($groups as $group) { ?>
																<option value="<?php echo $group->pg_id; ?>" <?php if($c_group == $group->pg_name){ echo "selected"; } ?> ><?php echo $group->pg_name; ?></option>
															<?php } ?>
														</select>
														<input name="new_group" id="new_group" type="text" placeholder="Project Group" class="m-wrap">

														<a class="btn large green" id="add_group" href="#" >+</a>	
													</div>
												</div>

												<div class="control-group">
													<label class="control-label">Project Description<span class="required">*</span></label>
													<div class="controls">												
														<textarea class="m-wrap huge" name="p_desc" ><?php echo $desc; ?></textarea> 
														    <script>
										                		CKEDITOR.replace( 'p_desc' );
										            		</script>
										            </div>
								            	</div>

								            	</div>


											</div>

									
										</div>
										<div class="form-actions clearfix">

											<input type="hidden" name="project_url" value="<?php echo $this->uri->segment(3); ?>" >

											<input type="submit" class="btn purple button-next" name="prj_edit" value="Finish Editing >>">

										</div>
									</div>
								</form>

							</div>
						</div>
					</div>
				</div>


				
























<script type="text/javascript" >
	$(document).ready(
    	function(){
	        $("#add_group").click(function () {
	            $("#new_group").toggle("slow");
	            $("#p_group").toggle("slow");
	        });
    });
</script>