<?php
	foreach ($profile_data as $pdata) {
		$business_name = $pdata->user_fname." ".$pdata->user_lname;
		$designation = $pdata->user_designation;
		$phone = $pdata->user_phone;

		$address_1 = $pdata->user_address_1;
		$address_2 = $pdata->user_address_2;

		$email_address = $pdata->user_email;

		$profile_id = $pdata->id;

		$img = $pdata->user_avatar_img;

		if (!empty($img)) {
			$avatar = $img.$pdata->user_avatar_ext;
		}
	}




		$logged_id = $this->session->userdata('user_id');

	
	

	if ( (!empty($designation)) && (!empty($phone)) ) {
		$front_side = "<h1>".$business_name."</h1>"
						."<h3>".$designation."</h3>"
				        ."<hr /><span>".$phone."</span>";

	}

	elseif ((empty($designation)) && (!empty($phone))) {
		$front_side = "<h1>".$business_name."</h1>"
				        ."<hr /><span>".$phone."</span>";
	}

	elseif ((!empty($designation)) && (empty($phone))) {
		$front_side = "<h1>".$business_name."</h1>"
						."<h3>".$designation."</h3>"
				        ."<hr />";
	}
	else{
		$front_side = "<h1>".$business_name."</h1>"
				        ."<hr />";		
	}






	if ( (!empty($address_1)) && (!empty($address_2)) ) {
		$back_side = "<p>".$address_1."</p>"."<h5>email : ".$email_address."</h5><hr /><p>".$address_2."</p>";

	}

	elseif ((empty($address_1)) && (!empty($address_2))) {
		$back_side = "<h5>email : ".$email_address."</h5><hr /><p>".$address_2."</p>";
	}

	elseif ((!empty($address_1)) && (empty($address_2))) {
		$back_side = "<p>".$address_1."</p>"."<h5>email : ".$email_address."</h5><hr />";
	}
	else{
		$back_side = "<h5>email : ".$email_address."</h5><hr />";	
	}





 ?>


<div class="portlet box white">

							<div class="portlet-title">
								<div class="caption">
									<i class="icon-reorder"></i>
									<span class="hidden-480">Business Card of <strong><?php echo $business_name; ?></strong></span>
								</div>
							</div>


					<div class="portlet-body">



					<!-- BEGIN DASHBOARD STATS -->
					<div class="row-fluid">

						<div class="span9 responsive">


							<div class="row-fluid">

								<div class="span8 responsive">
						            <div class="flipbox-container box100">
						                <div id="flipbox1" class="flipbox">
						                    <?php echo $front_side; ?>
						                </div>
						            </div>

						        </div>
						        <div class="span4 responsive">
								<?php 
									if(!empty($img)){ ?> 
										<img style="height:150px;" class="pull-right" alt="<?php echo $business_name; ?>" src="<?php echo base_url(); ?>uploads/profile_data/<?php echo $avatar; ?>" />
									<?php }else{	?>
										<img class="pull-right" alt="<?php echo $business_name; ?>" src="<?php echo base_url(); ?>themes/assets/img/avatar.png" />
									<?php } ?>		
								</div>
							</div>						        






				            <hr />

				            <a href="#" class="btn blue frontside">Front Side</a>
				            <a href="#" class="btn green backside">Back Side</a>
				            <?php if ( ($logged_id!==FALSE) && ($logged_id==$profile_id)) { ?>
				            	<a href="<?php echo base_url()."user/edit/profile" ?>" class="btn black"><i class="icon-edit" ></i> Edit Profile Data</a>
				            <?php } ?>

							<?php //LOAD FLIPPY======================= ?>
								<script type="text/javascript">

									    $(".backside").on("click",function(e){
									        $(".flipbox").flippy({
									            color_target: "#222",
									            direction: "left",
									            duration: "750",
									            verso: "<?php echo $back_side; ?>",
									         });
									         e.preventDefault();
									    });
									    
									    $(".frontside").on("click",function(e){
									        $(".flipbox").flippy({
									            color_target: "#3B3132",
									            direction: "right",
									            duration: "750",
									            verso: "<?php echo $front_side; ?>",
									         });
									         e.preventDefault();
									    });
								</script>
								<script type="text/javascript" src="<?php echo base_url()."themes/assets/scripts/plugins/" ?>jquery.flippy.min.js"></script>	
							<?php //LOAD FLIPPY======================= ?>

						</div>


			
						<div class="span3 responsive">
							<?php $this->load->view('includes/right_side_bar') ?>
						</div>



					</div>
					<!-- END DASHBOARD STATS -->



			</div>
		</div>						