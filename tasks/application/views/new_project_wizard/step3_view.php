<?php if(!empty($message)) { ?>
	<?php echo $message; ?>
<?php } ?>

<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>



				<div class="row-fluid">
					<div class="span12">
						<div class="portlet box purple" id="rootwizard">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-reorder"></i> Create New Project - <span class="step-title">Step 3 of 3</span>
								</div>

							</div>
							<div class="portlet-body form">
								<?php echo form_open('project/new/completed', 'class="form-horizontal"'); ?>
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
												<h3 class="block">Assign Project</h3>

												<div class="control-group">
													<label class="control-label">Assign To<span class="required">*</span></label>
													<div class="controls">
														<select 
															data-placeholder="Choose Contractors" 
															class="chosen span6" 
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


											</div>

									
										</div>
										<div class="form-actions clearfix">


<!-- ==========================================================================
Previous Step Data
=========================================================================== -->
<div style="display:none;" >
<input type="hidden" name="p_title" value='<?php echo $p_title; ?>' />
<input type="hidden" name="p_desc" value='<?php echo $p_desc; ?>' />
<input type="hidden" name="p_deadline" value='<?php echo $p_deadline; ?>' />
<input type="hidden" name="p_group" value="<?php echo $p_group; ?>" />

<input type="hidden" name="p_discussion" value='<?php echo $p_discussion; ?>' />
<input type="hidden" name="p_milestone" value='<?php echo $p_milestone; ?>' />
<input type="hidden" name="p_todo" value='<?php echo $p_todo; ?>' />
</div>
<!-- ==========================================================================
Previous Step Data
=========================================================================== -->





											<input type="submit" class="btn purple button-next" value="Create Project!">

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