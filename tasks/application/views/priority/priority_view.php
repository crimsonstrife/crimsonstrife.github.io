<?php if(!empty($message)) { ?>
	<?php echo $message; ?>
<?php } ?>
<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>

<?php 

$logged_id = $this->session->userdata('user_id');

foreach ($priority as $prio) { 
	$owner = $prio->user_fname." ".$prio->user_lname;
	$owner_id = $prio->prio_owner;
	$created = $prio->prio_created;
	$modified = $prio->prio_last_modified;
	$title = $prio->prio_name;
	$prio_id = $prio->prio_id;

}


$link = $this->uri->segment(3);


?>

	<div class="portlet box white">
		<div class="portlet-title">
			<div class="caption">
				<i class="icon-reorder"></i>
				<span class="hidden-480"><?php echo $title; ?> | <?php echo $owner; ?></span>
			</div>


		</div>


		<div class="portlet-body">
			<!-- BEGIN DASHBOARD STATS -->
			<div class="row-fluid">

				<div class="span8 responsive">


			<?php if ($logged_id==$owner_id){ ?>

				<h5>Add a new task to the list : </h5>

				<?php //ADD A NEW TASK TO PRIORITY QUEUE
					echo form_open('priority/new/task','class="form-horizontal"'); ?>

						<div class="control-group">
							<label class="control-label">New Task<span class="required">*</span></label>
								<div class="controls">
									<input type="text" class="span6 m-wrap" name="pdata_title" placeholder="Type A New Task Here &amp; Press Enter!" />
									<input type="hidden" name="prio_id" value="<?php echo $prio_id; ?>"   />
									<input type="hidden" name="return_link" value="<?php echo $this->uri->segment(3); ?>">
									<input type="submit" value="" name="new_task" style="display:none;" />
								</div>
						</div>

						<div id="saved" style="display:none;" ><p class="alert alert-success"> Order Saved Successfully...</div>

					<?php echo form_close(); ?>
			<?php } else{} ?>

					<h3>Current Queue</h3>

					

					<?php if (!empty($prio_data)) { ?>

				<?php if ($logged_id==$owner_id){ ?>
				<div id="nestable_list_3" class="dd">
					<ol id="sortable" class="dd-list"  >
				<?php }else{ ?>
					<div id="nestable_list_3" class="dd">
					<ol>
				<?php } ?>
					<?php foreach ($prio_data as $data) { ?>
						<li class="dd-item" id="<?php echo $data->pdata_id; ?>" >
							<div class="dd-handle dd3-handle"></div>
							<div class="dd3-content"><?php echo $data->pdata_title; ?>
							<?php if ($logged_id==$owner_id){ ?>
									<a class="pull-right tintinbox" style="padding:1px 2px 1px 2px; margin:0px;" href="#deletedialog<?php echo  $data->pdata_id; ?>"data-toggle="modal" class="btn mini red" ><i class="icon-trash"></i></a>
							<?php } ?>
							</div>

						</li>
										<?php if ($logged_id==$owner_id){ ?>
											<!-- LETS LOAD THE DELETE DIALOG -->
											<div id="deletedialog<?php echo $data->pdata_id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
												<div class="modal-header">
													
												<h3 id="myModalLabel">Delete Task</h3>
												</div>

												<div class="modal-body">
													<p>Are you sure you want to delete the following task?<br />
													<span class="text-error" ><?php echo $data->pdata_title; ?></span>
													</p>
												</div>
												
												<div class="modal-footer">
													<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
													<a href="<?php echo base_url()."priority/delete/".$data->pdata_id."/".$link; ?>" class="btn red" >Delete Task</a>
												</div>
											</div>
										<?php } ?>


					<?php }  ?>

					</ol>
					</div>
					<?php if ($logged_id==$owner_id){ ?>
					 <script>
						$(document).ready(function(){
						    $('#sortable').sortable({
						        update: function() {
						        	var stringDiv = $(this).sortable("serialize");
						            $("#sortable").children().each(function(i) {
						                var li = $(this);
						                stringDiv += " "+li.attr("id") + '=' + i + '&';
						            });

						            $.ajax({
						                type: "POST",
						                url: "<?php echo base_url().'priority/order/save/'.$prio_id ?>",
						                data: stringDiv,
					                       success: function(msg){
					                       		$("#saved").show();
					                       		$('#saved').delay(4000).slideUp(500);
					                       }
						            }); 
						        }
						    }); 
						    $( "#sortable" ).disableSelection();    
						});
					</script>
					<?php }else{} ?>


						
					<?php }else{ echo "No task queued yet..."; } ?>


				</div>


				<div class="span4 responsive">
					<h1>Priority Queue Details</h1>
					<ul class="profile-classic">
						<li>Created By :<?php echo $owner; ?></li>
						<li>Created at: <?php echo date('d M H:i:s A',$created); ?></li>
						<li>Last Modified : <?php echo date('d M H:i:s A',$modified); ?></li>
						<li><h3>Shared With:</h3>
						<?php
						if (!empty($prio_shared)) {
					
							foreach ($prio_shared as $users) { ?>

							<?php if($users->user_online==1){ ?>
								<span class="label label-success label-mini">Online</span>
							<?php }else{ ?>
								<span class="label label label-mini">Offline</span>
							<?php } ?>

								<?php echo $users->user_fname." ".$users->user_lname."<br />"; ?>


							<?php } 
						} else {
							echo "Currently not shared with anyone"; 
						}?>

						</li>

					<?php if ($logged_id==$owner_id){ ?>

						<li>
							<h3>Share With:</h3>
								<?php echo form_open('priority/share/new'); ?>

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

												<input type="hidden" name="prio_owner_id" value="<?php echo $owner_id; ?>">
												<input type="hidden" name="prio_id" value="<?php echo $prio_id; ?>">

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

