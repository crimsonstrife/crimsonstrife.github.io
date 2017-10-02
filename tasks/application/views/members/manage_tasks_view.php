<?php if (!empty($message)) { ?>
	<?php echo $message; ?>
<?php } ?>


	<div class="portlet box white">
		<div class="portlet-title">
			<div class="caption">
				<i class="icon-reorder"></i>
				<span class="hidden-480">Projects You've Joined Or have been invited to.</span>
			</div>
		</div>


		<div class="portlet-body">
			<!-- BEGIN DASHBOARD STATS -->
			<div class="row-fluid">

				<div class="span9 responsive">


				<?php if(!empty($my_tasks)){ ?>

								<table class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th><i class="icon-tags"></i>Title</th>
											<th class="hidden-phone"><i class="icon-group"></i> Group</th>
											<th class="hidden-phone"><i class="icon-time"></i> Deadline</th>
											<th class="hidden-phone"><i class="icon-star"></i>Status</th>
											<th><i class="icon-cogs"></i> Action</th>
										</tr>
									</thead>
									<tbody>

						<?php foreach ($my_tasks as $prj) { ?>
							<?php 
								$diff = $prj->p_deadline - time();
								//Track Progress
								$timespan = $prj->p_deadline - $prj->p_created_date;
								$elapsed = $timespan - ($prj->p_deadline - time());
								if($diff>0){
									$percent_done =  ceil(($elapsed/$timespan)*100)."%";
								}else{
									$percent_done =  "100%";
								}
								
								//Tracking done
							?>

										<tr>
											<td class="highlight">
												<div class="success"></div> &nbsp;&nbsp;
													<?php echo $prj->p_title; ?>
											</td>

											<td><?php echo $prj->pg_name; ?></td>

											<td><?php echo date('Y-m-d' ,$prj->p_deadline); ?></td>
											<td class="progress progress-success">
												<div class="bar tooltips" title="<?php echo $percent_done; ?> Completed" style="width: <?php echo $percent_done; ?>;"><p><?php echo $prj->p_status; ?></p></div>
											</td>


											<td>
												<a href="<?php echo base_url()."project/view/".$prj->p_url; ?>" class="btn mini green">View</a>

									<?php if($prj->p_active!=2){ ?>
												
											<?php if($prj->pu_joined==1){ ?>
												<a href="#deletedialog<?php echo  $prj->pid; ?>"   data-toggle="modal" class="btn mini red" >Leave</a>
											<?php } else{ ?>
												<a href="<?php echo base_url('project/join')."/".$prj->p_join_token; ?>"  class="btn mini green-stripe" >Join</a>
												<a href="#reject<?php echo  $prj->pid; ?>"   data-toggle="modal" class="btn mini red-stripe" >Reject</a>
											<?php } ?>
											</td>

											<?php if($prj->pu_joined==1){ ?>
											<!-- LETS LOAD THE DELETE DIALOG -->
												<div id="deletedialog<?php echo $prj->pid; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
													<div class="modal-header">
														
															<h3 id="myModalLabel">Leave Project</h3>
													</div>

													<div class="modal-body">
														<p>Are you sure you want to leave the project?</p>
													</div>
													
													<div class="modal-footer">
														<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
														<a href="<?php echo base_url(); ?>project/leave/<?php echo $prj->pid; ?>" class="btn red" >Leave Project</a>
													</div>
												</div>
											<?php }else{ ?>

											<!-- LETS LOAD THE DELETE DIALOG -->
												<div id="reject<?php echo $prj->pid; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
													<div class="modal-header">
														
															<h3 id="myModalLabel">Reject Project Invitation</h3>
													</div>

													<div class="modal-body">
														<p>Are you sure you want to Reject the Project Invitation?</p>
													</div>
													
													<div class="modal-footer">
														<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
														<a href="<?php echo base_url(); ?>project/reject/<?php echo $prj->pid; ?>" class="btn red" >Reject Invitation</a>
													</div>
												</div>
											<?php } ?>
									<?php } else{ ?>

										<span class="label label-success" >Project Finished at <?php echo date('d-M-Y H:i:s A',$prj->p_deadline); ?></span>
									<?php } ?>

										</tr>
										

			

						<?php } ?>

									</tbody>
								</table>	

								<div class='pagination pagination' >
									<ul>
										<?php echo $this->pagination->create_links(); ?>
									</ul>

								</div>	


				<?php } else {
					echo "You have no more tasks at this time...";
				} ?>

				</div>

				<div class="span3 responsive" >
					<?php $this->load->view('includes/right_side_bar'); ?>
				</div>

			</div>
		</div>
	</div>

