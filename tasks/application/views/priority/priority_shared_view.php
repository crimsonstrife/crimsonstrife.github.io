<?php if (!empty($message)) { ?>
	<?php echo $message; ?>
<?php } ?>
<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>


	<div class="portlet box white">
		<div class="portlet-title">
			<div class="caption">
				<i class="icon-reorder"></i>
				<span class="hidden-480">Priority Queues Shared With You</span>
			</div>


			<div class="actions">

				<a class="btn purple-stripe" href="<?php echo base_url()."priority/manage/"; ?>"><i class="icon-tasks"></i>&nbsp;&nbsp;My Queue</a>
				
				<a class="btn blue" href="<?php echo base_url()."priority/shared/"; ?>"><i class="icon-tasks"></i>&nbsp;&nbsp;Shared Queue</a>				

				<a href="#create_queue" data-toggle="modal" class="btn bigicn-only green" ><i class="icon-plus"></i>&nbsp;&nbsp;Create Queue</a>


					<!-- LETS LOAD THE CREATE DIALOG -->
					<div id="create_queue" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-header">						
							<h3 id="myModalLabel">Create Priority Queue</h3>
						</div>

						<div class="modal-body">
							<?php echo form_open('priority/create', 'class="form-horizontal"'); ?>
								<div class="control-group">
								<label class="control-label">Priority Queue Title<span class="required">*</span></label>
										<div class="controls">
											<input type="text" class="m-wrap" name="prio_name" />
											<span class="help-inline">Name of the priority queue.</span>
									</div>
								</div>
						</div>
													
						<div class="modal-footer">
						<input class="btn green" value="Create" name="priority" type="submit" />
						<?php echo form_close(); ?>
						</div>
					</div>


			</div>


		</div>


		<div class="portlet-body">
			<!-- BEGIN DASHBOARD STATS -->
			<div class="row-fluid">

				<div class="span9 responsive">


				<?php if(!empty($priority_shared)){ ?>

								<table class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th><i class="icon-tags"></i>Title</th>
											<th><i class="icon-time"></i> Created By</th>
											<th><i class="icon-time"></i> Created at</th>
											<th><i class="icon-cogs"></i> Last Modified</th>
										</tr>
									</thead>
									<tbody>

						<?php foreach ($priority_shared as $prio) { ?>

						<?php //prio_id, prio_owner, prio_name, prio_created, prio_last_modified ?>

										<tr>
											<td class="highlight">
												<div class="success"></div> &nbsp;&nbsp;
													<a href="<?php echo base_url()."priority/view/".$prio->prio_link; ?>" >
														<?php echo $prio->prio_name; ?>
													</a>
											</td>

											<td><?php echo $prio->user_fname." ".$prio->user_lname; ?></td>

											<td><?php echo date('Y-m-d H:i:s A' ,$prio->prio_created); ?></td>

											<td><?php echo date('Y-m-d H:i:s A' ,$prio->prio_last_modified); ?></td>

										</tr>
										

			

						<?php } ?>

									</tbody>
								</table>							

				<?php } else {
					echo "You do not have any shared Priority Queue at this moment...";
				} ?>

				</div>

				<div class="span3 responsive" >
					<?php $this->load->view('includes/right_side_bar'); ?>
				</div>

			</div>
		</div>
	</div>

