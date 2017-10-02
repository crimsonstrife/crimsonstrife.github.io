<?php 


    $role = $this->session->userdata('role');
    if(!isset($role)){
        $role = "blank";
    }



    $logged_in = $this->session->userdata('authenticated');

	$seg = $this->uri->segment(2);
	$home = "";
	$settings = "";



?>


	<!-- BEGIN HEADER -->
	<div class="header navbar navbar-white navbar-fixed-top">
		<!-- BEGIN TOP NAVIGATION BAR -->
		<div class="navbar-inner">
			<div class="container-fluid">
				<!-- BEGIN LOGO -->
				<a class="brand" href="<?php echo base_url('dashboard'); ?>">
				<img src="<?php echo base_url(); ?>themes/assets/img/logo.png" alt="Taskfeed | Meant For TeamWork"/>
				<span style="font-size:22px; " >T a s k f e e d <small>[PE]</small>|</span><span style="font-size:14px; " > Meant For TeamWork</span>
				</a>

				<!-- END LOGO -->
				<!-- BEGIN RESPONSIVE MENU TOGGLER -->
				<a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
				<img src="<?php echo base_url('dashboard'); ?>themes/assets/img/menu-toggler.png" alt="" />
				</a>          
				<!-- END RESPONSIVE MENU TOGGLER -->            
				<!-- BEGIN TOP NAVIGATION MENU --> 

		<?php if($logged_in!==FALSE) { ?>             
				<ul class="nav pull-right">
					<!-- BEGIN NOTIFICATION DROPDOWN -->   
					<li class="dropdown" id="header_notification_bar">
						<a href="#" class="dropdown-toggle notify_badge" data-toggle="dropdown" >
						<i class="icon-bullhorn"></i>
							<?php if($unread!=0){ ?>
								<span class="badge"><?php echo $unread; ?></span>
							<?php } else { $unread="no"; } ?>
						</a>
						<ul class="dropdown-menu extended notification">
							<li>
								<p>You have <?php echo $unread; ?> new notifications</p>
								<?php if($unread>=5){ ?>
									<p><a href="<?php echo base_url('dashboard/mark_all_read'); ?>" >Mark All As Read</a> &nbsp; <a href="<?php echo base_url('dashboard/unread'); ?>">View All Unread</a> </p>
								<?php } ?>
							</li>
							<?php foreach ($notifications as $notif) { ?>

								<li>
									<a href="<?php echo base_url('notifications/read')."/".$notif->pn_id; ?>" >
									<i class="icon-plus"></i>
										<?php echo $notif->pn_title; ?>
									<span class="time">(<?php echo date('M Y d H:i:s A',$notif->pn_created); ?>)</span>
									</a>
								</li>
								
							<?php } ?>
							<?php if($unread>5){ ?>
								<li><a href="<?php echo base_url('dashboard/unread'); ?>">Load More...</a></li>
							<?php } ?>
						</ul>
					</li>


					<!-- QUICK ACTION DROPDOWN -->
						<li class="dropdown" id="header_notification_bar">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-th"></i>
							</a>

							<div class="dropdown-menu extended2">
							<ul><li><p style="color:#fff;">Quick Launcher</p></li></ul>
									<a title="My Dashboard"
										class="icon-btn-blue tooltips span5" 
										href="<?php echo base_url('dashboard') ?>" >
										<i class="icon-dashboard"></i>
											<?php if($unread!=0){ ?>
												<span class="badge badge-important"><?php echo $unread; ?></span>
											<?php }  ?>
									</a>

									<a title="Create A New Project"
										class="icon-btn-green tooltips span5" 
										href="<?php echo base_url('project/new/step1') ?>" >
										<i class="icon-magic"></i>
									</a>


									<a title="Manage My Projects"
										class="icon-btn-purple tooltips span5" 
										href="<?php echo base_url('project/manage/projects') ?>" >
										<i class="icon-sitemap"></i>
									</a>


									<a title="Manage My Tasks"
										class="icon-btn-yellow tooltips span5" 
										href="<?php echo base_url('project/manage/tasks') ?>" >
										<i class="icon-tasks"></i>
									</a>


									<a title="Manage Priority Queue"
										class="icon-btn-violet tooltips span5" 
										href="<?php echo base_url('priority/') ?>" >
										<i class="icon-retweet"></i>
									</a>


									<a title="Manage My Team"
										class="icon-btn-pink tooltips span5" 
										href="<?php echo base_url('team/manage') ?>" >
										<i class="icon-group"></i>

											<?php if($team_invites!=0){ ?>
												<span class="badge badge-important"><?php echo $team_invites; ?></span>
											<?php }  ?>										
									</a>
							</div>
						</li>					



					<!-- END TODO DROPDOWN -->
					<!-- BEGIN USER LOGIN DROPDOWN -->
					<li class="dropdown user">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">

						<?php 
						$avatar = $this->session->userdata('avatar');
						if(!empty($avatar)){ ?> 
							<img style="height:45px; " alt="<?php echo $this->session->userdata('username'); ?>" src="<?php echo base_url(); ?>uploads/profile_data/<?php echo $avatar; ?>" />
						<?php }else{	?>
							<img alt="<?php echo $this->session->userdata('username'); ?>" src="<?php echo base_url(); ?>themes/assets/img/avatar.png" />
						<?php } ?>



						<span class="username"><?php echo $this->session->userdata('username'); ?>>
						<i class="icon-angle-down"></i>
						</a>
						<ul class="dropdown-menu">
							<li><a href="<?php echo base_url('actions/account'); ?>"><i class="icon-lock"></i> Account Settings </a></li>
							<li><a href="<?php echo base_url('user/profile')."/".$this->session->userdata('username'); ?>"><i class="icon-user"></i> My Profile </a></li>
							<li><a href="<?php echo base_url('authentication/logout'); ?>"><i class="icon-key"></i> Log Out</a></li>
						</ul>
					</li>
					<!-- END USER LOGIN DROPDOWN -->
				</ul>
				<!-- END TOP NAVIGATION MENU --> 
		<?php } else{ ?>


				
				<a class="btn  blue pull-right" href="<?php echo base_url('authentication/register'); ?>">Register</a>
				<a class="btn  green pull-right" href="<?php echo base_url('authentication/login'); ?>">Login</a>
		<?php } ?>

			</div>
		</div>
		<!-- END TOP NAVIGATION BAR -->
	</div>


	<!-- END HEADER -->
	<!-- BEGIN CONTAINER -->
	<div class="page-container">