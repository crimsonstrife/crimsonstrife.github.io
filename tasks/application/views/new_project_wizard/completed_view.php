<?php if(!empty($message)) { ?>
	<?php echo $message; ?>
<?php } ?>

<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>



				<div class="row-fluid">
					<div class="span12">
						<div class="portlet box purple" id="rootwizard">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-reorder"></i> Create New Project - <span class="step-title">Congratulatios!</span>
								</div>

							</div>
							<div class="portlet-body form">
								
									<div class="form-wizard">
										<div class="navbar steps">
											<div class="navbar-inner">
												<ul class="row-fluid nav nav-pills">
													<li class="span4">
														<a href="#" class="step">
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
													<li class="span4 active">
														<a href="#" class="step active">
														<span class="number">3</span>
														<span class="desc"><i class="icon-ok"></i>Assign &amp; Finish</span>   
														</a>
													</li>
												</ul>
											</div>
										</div>


										<div id="bar" class="progress progress-success progress-striped">
											 <div style="width: 100%;" class="bar"></div>
										</div>

										<div class="tab-content">
											



										<!-- TAB 1 STARTS 
										=============================================================================-->
											<div class="tab-pane active" id="tab1">
												<h3 class="block">Project Created Successfully</h3>

													<?php echo $project_message; ?>

												<small>Thanks, TaskFeed Team.</small>
												

											</div>

									
										</div>

										<div class="form-actions clearfix">

											<a class="btn green large" href="<?php echo $project_url; ?>" >View Project </a>

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