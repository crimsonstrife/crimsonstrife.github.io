<?php if(!empty($message)) { ?>
	<?php echo $message; ?>
<?php } ?>

<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>



				<div class="row-fluid">
					<div class="span12">
						<div class="portlet box purple" id="rootwizard">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-reorder"></i>Create New Project - <span class="step-title">Step 2 of 3</span>
								</div>

							</div>
							<div class="portlet-body form">
								<?php echo form_open('project/new/step3', 'class="form-horizontal"'); ?>
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
													<li class="span4 active">
														<a href="#" class="step active">
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
											 <div style="width: 70%;" class="bar"></div>
										</div>

										<div class="tab-content">
											



										<!-- TAB 1 STARTS 
										=============================================================================-->
											<div class="tab-pane active" id="tab1">
												<h3 class="block">Install Apps</h3>

													<div class="control-group">
														<label class="control-label">Discussion App</label>
														<div class="controls">
															<div class="toggle-button">
																<input type="checkbox" name="discussion" value="install" class="toggle" checked="checked" />
															</div>
														</div>
													</div>


													<div class="control-group">
														<label class="control-label">MileStones App</label>
														<div class="controls">
															<div class="toggle-button">
																<input type="checkbox" class="toggle" name="milestone" value="install"  checked="checked" />
															</div>
														</div>
													</div>	


													<div class="control-group">
														<label class="control-label">ToDo App</label>
														<div class="controls">
															<div class="toggle-button">
																<input type="checkbox" class="toggle" name="todo" value="install" checked="checked" />
															</div>
														</div>
													</div>																							
											</div>

									
										</div>
										<div class="form-actions clearfix">



<!-- ==========================================================================
Previous Step Data
=========================================================================== -->

<input type="hidden" name="p_title" value='<?php echo $p_title; ?>' />
<input type="hidden" name="p_desc" value='<?php echo $p_desc; ?>' />
<input type="hidden" name="p_deadline" value='<?php echo $p_deadline; ?>' />
<input type="hidden" name="p_group" value='<?php echo $p_group; ?>' />

<!-- ==========================================================================
Previous Step Data
=========================================================================== -->

											<input type="submit" class="btn purple button-next" value="Next >>" />

										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>


				
<script type="text/javascript" src="<?php echo base_url(); ?>themes/assets/plugins/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"></script>  

<script type="text/javascript">

	$('.toggle-button').toggleButtons({
	  onChange: function ($el, status, e) {
	    // $el = $('#toggle-button'); 
	    // status = [true, false], the value of the checkbox
	    // e = the event
	    console.log($el, status, e); 
	  },
	  width: 150,
	  height: 40,
	  font: {
	    'font-size': '20px'
	  },
	  animated: true,
	  transitionspeed: 0.5, // Accepted values float or "percent" [ 1, 0.5, "150%" ]
	  label: {
	    enabled: "Install",
	    disabled: "Uninstall"
	  },
	  style: {
	    // Accepted values ["primary", "danger", "info", "success", "warning"] or nothing
	    enabled: "success",
	    disabled: "danger"
	  }
	});
</script>

<script type="text/javascript">
	$('.toggle').toggleButtons('toggleActivation'); // to toggle the disabled status
</script>












