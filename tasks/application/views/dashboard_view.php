<?php if (!empty($message)) { ?>
	<?php echo $message; ?>
<?php } ?>

	


			<div class="portlet box white">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-reorder"></i>
									<span class="hidden-480">Dashboard for <?php echo $this->session->userdata('username'); ?></span>
								</div>
							</div>


							<div class="portlet-body">



					<!-- BEGIN DASHBOARD STATS -->
					<div class="row-fluid">

						<div class="span5 responsive">
							<div class="dashboard-stat white">
								<div class="details" style="clear:both;">
									<div class="pull-right" style="color:#444;">                           
										Taskfeed for <?php echo $this->session->userdata('username'); ?>
									</div>

								</div>


								<div class="dash-contents tintinbox" style="clear:both;">

									<hr />
								
								<?php 
									if (!empty($feeds)) {

										foreach ($feeds as $feed) { ?>

						<div class="media" >
						<a class="pull-left" href="#" >			
							<?php if(!empty($feed->user_avatar_img)){ ?> 
								<img class="media-object" style="height:45px; " alt="<?php echo $feed->user_fname." ".$feed->user_lname; ?>" src="<?php echo base_url(); ?>uploads/profile_data/<?php echo $feed->user_avatar_img.$feed->user_avatar_ext; ?>" />
							<?php }else{	?>
								<img class="media-object" alt="<?php echo $feed->user_fname." ".$feed->user_lname; ?>" src="<?php echo base_url(); ?>themes/assets/img/avatar.png" />
							<?php } ?>
						</a>


										<div class="media-body" >
											<a href="<?php echo $feed->pn_link; ?>" ><?php echo $feed->pn_title; ?></a><br />
												<p><?php echo $feed->pn_content; ?></p>
											
												<small>(<?php echo date('M Y d H:i:s A',$feed->pn_created); ?>)</small>
											</a>
										</div>
						</div><hr />
									
								<?php 
										}
									} else {
										echo "Your Taskfeed is currently empty...";
									} 
								?>

									
								</div>

								<a class="btn" href="<?php echo base_url()."dashboard/all-feeds" ?>">
								View All <i class="m-icon-swapright m-icon-black"></i>
								</a>                 
							</div>
						</div>


						<div class="span4 responsive">

						<span class="label label-success" >Task Calendar</span>
							<div id="calendar">
								
							</div>





								<script type="text/javascript">
									$(document).ready(function() {

									    // page is now ready, initialize the calendar...

									    $('#calendar').fullCalendar({
											events: [
											<?php foreach ($calendar_events as $events) { ?>

										        {
										            title  : '<?php echo $events->p_title; ?>',
										            start  : '<?php echo date('Y-m-d',$events->p_created_date); ?>',
										            end    : '<?php echo date('Y-m-d',$events->p_deadline); ?>',
										            url    : '<?php echo base_url()."project/view/".$events->p_url."/discussion"; ?>',
										            backgroundColor: '<?php 

										            $array = array('#35AA47', '#852B99', '#4B8DF8', '#F99B17', '#0786C5');
													echo $array[rand(0, count($array) - 1)]; ?>'
										        },

										     <?php } ?>

										    ]
									    })

									});	
								</script>
						
						</div>

						
						<div class="span3 responsive">
							<?php $this->load->view('includes/right_side_bar') ?>

						</div>



					</div>
					<!-- END DASHBOARD STATS -->



			</div>
		</div>
