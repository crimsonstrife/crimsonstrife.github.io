
	<?php if(!empty($message)) { ?>

			<?php echo $message; ?>

	<?php } ?>

	<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>


	   




			<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-reorder"></i>
									<span class="hidden-480">Account Settings</span>
								</div>
							</div>


							<div class="portlet-body form">

											<h4>Enter old and new password to reset</h4>
											<?php echo form_open(current_url(), 'class="form-horizontal"'); ?>
												<div class="input-icon left">
													<i class="icon-lock"></i>
													<input name="old_pass" type="password" placeholder="Old Password" class="m-wrap">
												</div><br />
												<div class="input-icon left">                                          
													<i class="icon-lock"></i>
													<input name="new_pass" type="password" placeholder="NewPassword" class="m-wrap">
												</div><br />
												<input class="btn purple" type="submit" Value="Change" />
											<?php echo form_close(); ?>
											

							</div>
</div>
