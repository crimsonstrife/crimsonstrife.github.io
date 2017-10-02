	<div class="portlet box white">
		<div class="portlet-title">
			<div class="caption">
				<i class="icon-reorder"></i>
				<span class="hidden-480">Error Joining Project...</span>
			</div>
		</div>


		<div class="portlet-body">
			<!-- BEGIN DASHBOARD STATS -->
			<div class="row-fluid">
						<div class="span9 responsive alert-error" style="padding:10px;">
							<?php if(!empty($message)) { ?>

									<?php echo $message; ?>

							<?php } ?>

						</div>

						<div class="span3 responsive">
							<?php $this->load->view('includes/right_side_bar'); ?>
						</div>				
					
				
			</div>
		</div>
	</div>
