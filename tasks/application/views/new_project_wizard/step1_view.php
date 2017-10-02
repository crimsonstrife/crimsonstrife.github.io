<?php if(!empty($message)) { ?>
	<?php echo $message; ?>
<?php } ?>

<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>



				<div class="row-fluid">
					<div class="span12">
						<div class="portlet box purple" id="rootwizard">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-reorder"></i>Create New Project - <span class="step-title">Step 1 of 3</span>
								</div>

							</div>
							<div class="portlet-body form">

								<?php echo form_open('project/new/step2', 'class="form-horizontal"'); ?>
									<div class="form-wizard">
										<div class="navbar steps">
											<div class="navbar-inner">
												<ul class="row-fluid nav nav-pills">
													<li class="span4 active">
														<a href="#" class="step active">
														<span class="number">1</span>
														<span class="desc"><i class="icon-ok"></i>Project Detail</span>   
														</a>
													</li>

													<li class="span4">
														<a href="#" class="step">
														<span class="number">2</span>
														<span class="desc"><i class="icon-ok"></i>Applications</span>   
														</a>
													</li>

													<li class="span4">
														<a href="#" class="step">
														<span class="number">3</span>
														<span class="desc"><i class="icon-ok"></i>Assign &amp; Finish</span>   
														</a>
													</li>
												</ul>
											</div>
										</div>


										<div id="bar" class="progress progress-success progress-striped">
											 <div style="width: 30%;" class="bar"></div>
										</div>

										<div class="tab-content">
											



										<!-- TAB 1 STARTS 
										=============================================================================-->
											<div class="tab-pane active" id="tab1">
												<h3 class="block">Enter Project Description</h3>

												<div class="control-group">
													<label class="control-label">Project Title<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="span6 m-wrap" name="p_title" />
														<span class="help-inline">Name of the project</span>
													</div>
												</div>

												<div class="control-group">
													<label class="control-label">Project Deadline<span class="required">*</span></label>
													<div class="controls">
														<input type="text" class="m-wrap m-ctrl-medium date-picker" name="p_deadline" />
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
															<option value="<?php echo $group->pg_id; ?>"><?php echo $group->pg_name; ?></option>
															<?php } ?>
														</select>
														<input name="new_group" id="new_group" type="text" placeholder="Project Group" class="m-wrap">

														<a class="btn large green" id="add_group" href="#" >+</a>	
														<span style="margin-left:20px;" class="badge badge-important tooltips" title="Click on the [+] sign and create a new one. Groups are a nice way to organize your Projects By Category. e.g. :Urgent, Soccer Team, Finance, Web etc... " >?</span> 
													</div>
												</div>

												<div class="control-group">
													<label class="control-label">Project Description<span class="required">*</span></label>
													<div class="controls">												
														<textarea class="m-wrap huge" name="p_desc" ></textarea> 
														    <script>
										                		CKEDITOR.replace( 'p_desc' );
										            		</script>
										            </div>
								            	</div>

											</div>

									
										</div>
										<div class="form-actions clearfix">

											<input type="submit" class="btn purple button-next" value="Next >>">

										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>


				




<?php if($team_members==0){ ?>


<!-- LOAD THE MODAL ON FIRST LOAD -->

<script type="text/javascript">

    $(window).load(function(){

        $('#confirm_modal').modal('show');

    });

</script>



      <div id="confirm_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="confirm_modal" aria-hidden="true">

        <div class="modal-header">

            <h3 id="hiremodal">Hello there!</h3>

        </div>



        <div class="modal-body">
			<h3>Welcome to Taskfeed!</h3>
			<p> Taskfeed is all about TEAM WORK. As it seems, <strong>You do not have anyone in your team yet.</strong>. </p>

			<p>Don't worry, it's really very easy! Just click the button below and start by inviting someone in your team. You'll be able to start new projects and invite them
			as soon as they accept your request!</p>

			<p class="text-success">Also, we'll notify you as soon as someone accepts your request</p>

        </div>

        

        <div class="modal-footer">

          <a href="<?php echo base_url('team/manage'); ?>" class="btn green" >Create Your Team Now!</a>

        </div>

     </div>

<?php } ?>

















<script type="text/javascript" >
	$(document).ready(
    	function(){
	        $("#add_group").click(function () {
	            $("#new_group").toggle("slow");
	            $("#p_group").toggle("slow");
	        });
    });
</script>