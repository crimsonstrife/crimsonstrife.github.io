<div class="portlet box white">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-reorder"></i>
									<span class="hidden-480">

									<?php if(!isset($custom_nav_title)){ ?>
										All Feeds for <?php echo $this->session->userdata('username'); ?>
									<?php }else{ 
										echo $custom_nav_title
											." ".$this->session->userdata('username')
											." (".$unread.") | "
											."<a href='"
												.base_url('dashboard/mark_all_read')
											."' >Mark All As Read</a>";  

										} ?>




										

									</span>
								</div>
							</div>


							<div class="portlet-body">



					<!-- BEGIN DASHBOARD STATS -->
					<div class="row-fluid">

						<div class="span9 responsive">
							<div class="dashboard-stat white">

								<div class="dash-contents">


								<div class='pagination pagination' >
									<ul>
										<?php echo $this->pagination->create_links(); ?>
									</ul>

								</div>	

									<ul class="taskfeed">
								
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

									</ul>
								</div>
               
							</div>

								<div class='pagination pagination' >
									<ul>
										<?php echo $this->pagination->create_links(); ?>
									</ul>

								</div>							
						</div>


			
						<div class="span3 responsive">
							<?php $this->load->view('includes/right_side_bar') ?>
						</div>



					</div>
					<!-- END DASHBOARD STATS -->



			</div>
		</div>						