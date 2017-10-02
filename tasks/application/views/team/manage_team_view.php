<?php if(!empty($message)) { ?>
	<?php echo $message; ?>
<?php } ?>

<?php 

$logged_id = $this->session->userdata('user_id');
$link = $this->uri->segment(3);

?>

	<div class="portlet box white">
		<div class="portlet-title">
			<div class="caption">
				<i class="icon-reorder"></i>
				<?php echo $this->session->userdata('username'); ?>'s Team
			</div>

			<div class="actions">

				<a class="btn purple" href="<?php echo base_url()."team/manage/"; ?>"><i class="icon-tasks"></i>&nbsp;&nbsp;My Team</a>
				
				<a class="btn blue-stripe" href="<?php echo base_url()."team/invites/"; ?>"><i class="icon-tasks"></i>&nbsp;&nbsp;Invitations
					<?php if($team_invites!=0){ ?>
						<span class="badge badge-important"><?php echo $team_invites; ?></span>
					<?php }  ?>
				</a>				

				<a class="btn green-stripe" href="<?php echo base_url()."team/joined/"; ?>"><i class="icon-tasks"></i>&nbsp;&nbsp;Teams I'm In</a>

			</div>			


		</div>


		<div class="portlet-body">
			<!-- BEGIN DASHBOARD STATS -->
			<div class="row-fluid">

				<div class="span9 responsive">


					<h5>Add a new Member to Your Team : </h5>

					<?php //ADD A NEW TASK TO PRIORITY QUEUE
						echo form_open(current_url(),'class="form-horizontal"'); ?>

							<div class="control-group">
								<label class="control-label"><i class="icon-plus-sign"></i>&nbsp;&nbsp;Add New Member</label>
									<div class="controls">
										<input type="text" class="span6 m-wrap" name="team_invite" placeholder="Type a username or email and Hit Enter!" />
										<input type="hidden" name="from_member" value="<?php echo $logged_id; ?>"   />
										<input type="submit" value="" style="display:none;" />
									</div>
		
							</div>
							

						<?php echo form_close(); ?>
				

						<h3>Current Team Members</h3>

					<?php if (!empty($team_members)) { ?>
						<p class="text-info">***Note : Only the users who have already accepted your invitation are being displayed***</p>
							<table class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th><i class="icon-user"></i>&nbsp;&nbsp;User</th>
										<th class="hidden-phone"><i class="icon-time"></i>&nbsp;&nbsp;Online Status</th>
										<th><i class="icon-cogs"></i>&nbsp;&nbsp;Action</th>
									</tr>
								</thead>
								<tbody>

						<?php

							foreach ($team_members as $users) { ?>
							<tr>
								<td>
									<a href="<?php echo base_url(); ?>user/profile/<?php echo $users->user_name; ?>" target="_blank" >
										<?php echo $users->user_fname." ".$users->user_lname; ?>
									</a>
								</td>


							<td>
								<?php if($users->user_online==1){ ?>
									<span class="label label-success label-mini">Online</span>
								<?php }else{ ?>
									<span class="label label label-mini">Offline</span>
								<?php } ?>
							</td>

							<td>
								<a href="#deletedialog<?php echo  $users->tf_tid; ?>"   data-toggle="modal" class="btn mini red" >Remove From Team</a>
								<!-- LETS LOAD THE DELETE DIALOG -->
									<div id="deletedialog<?php echo $users->tf_tid; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<div class="modal-header">	
												<h3 id="myModalLabel">Remove User From Team</h3>
										</div>

										<div class="modal-body">
											<p>Are you sure you want to Remove this user from your team? </p>
											<br /><p><strong>Please note that you won't be able to invite this user for your projects anymore.</strong></p>
										</div>
												
										<div class="modal-footer">
											<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
											<a href="<?php echo base_url(); ?>team/remove-member/<?php echo $users->tf_tid; ?>" class="btn red" >Remove This User!</a>
										</div>
									</div>								
							</td>								

						</tr>
						<?php } ?>
					</tbody>
				</table>
					<?php }else{ ?>

						<p>You do not have any Users in your TASKFEED TEAM currently. Start by inviting someone now!</p>
					<?php } ?>

				</div>


				<div class="span3 responsive">
					<?php $this->load->view('includes/right_side_bar') ?>
				</div>
			</div>
		</div>
	</div>

