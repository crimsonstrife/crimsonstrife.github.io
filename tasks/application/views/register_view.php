<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8" />
	<title>TaskFeed | Create A New Account!</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta name="description" content="Taskfeed is developed by me (A.B.M Shahnewaz Rifat) as one of my dream project. Taskfeed is built with a lot of passion and self realizations. Over the years I've used many project management tools
and while they served the purpose right, I found them bloated or overly complicated at times.I realized that, Task/Project Management softwares can do a world of good if there was a Team Environment. A way to keep it simple, clean and elegant.
Taskfeed is meant for TeamWork.Create your team and manage your projects together! Get notified on the go and have fun organizing your ever so cluttered 
project queue. More features will be added in the future based on user requests. Since this is a single handed project, it may take longer than usual to bring
new and exciting features...Taskfeed Currently features (but is not limited to) 
:Invitation to join Taskfeed/Your Project by email.Creating your own team to work with.Apps for To-Do, Milestones and Discussion.Attachment in discussion.Threaded Discussion View.Email Notification for all major events on a project.Runs on simple Apache Server.User Roles as Project Owner and Contractors.Managing your priority Queue. Task Calendar.Public 3D Business Card! Task and Project Management Panels.The public business card will help to create an eco-system where you can find professional contractors and use Taskfeed as a freelancing resource in future!" />
	<meta content="A.B.M Shahnewaz Rifat" name="author" />
	<!-- BEGIN GLOBAL MANDATORY STYLES -->
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>/favicon.ico">
	<!-- BEGIN GLOBAL MANDATORY STYLES -->
	<link href="<?php echo base_url(); ?>themes/assets/css/taskfeed.css" rel="stylesheet" type="text/css" id="style_color"/>
	<link href="<?php echo base_url(); ?>themes/assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
	<!-- END GLOBAL MANDATORY STYLES -->

	<!-- END GLOBAL MANDATORY STYLES -->
	<!-- BEGIN PAGE LEVEL STYLES -->
	<link href="<?php echo base_url(); ?>themes/assets/css/pages/login.css" rel="stylesheet" type="text/css"/>
	<!-- END PAGE LEVEL STYLES -->
	<link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
	<!-- BEGIN LOGO -->
	<div class="logo">
		<a href="<?php echo base_url(); ?>" ><img src="<?php echo base_url(); ?>themes/assets/img/logo-big.png" alt="Taskfeed | Meant For TeamWork" /> </a>
	</div>
	<!-- END LOGO -->
	<!-- BEGIN LOGIN -->
	<div class="content" style="width:600px;">


	<?php if(!empty($message)) { ?>

			<?php echo $message; ?>

	<?php } ?>

	<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>





		<!-- BEGIN LOGIN FORM -->
			<?php echo form_open_multipart(current_url(), 'form-vertical register-form"'); ?>
			<h3 class="form-title">Create a new account</h3>
			<div class="alert alert-error hide">
			</div>


			<div class="control-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label visible-ie8 visible-ie9">Username</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-user"></i>
						<input class="m-wrap placeholder-no-fix tooltips" type="text" placeholder="Username" name="user_name"
						title="This will be your login handle to TaskFeed. Must be at least 4 characters long and unique. MUST NOT contain spaces and special characters." />
					</div>
				</div>
			</div>



			<div class="control-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label visible-ie8 visible-ie9">First Name</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-text-height"></i>
						<input class="m-wrap placeholder-no-fix tooltips" type="text" placeholder="First Name" name="user_fname"
						title="Your First Name."  />
					</div>
				</div>
			</div>


			<div class="control-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label visible-ie8 visible-ie9">Last Name</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-text-height"></i>
						<input class="m-wrap placeholder-no-fix tooltips" type="text" placeholder="Last Name" name="user_lname"
						title="Your Last Name."  />
					</div>
				</div>
			</div>



			<div class="control-group">
				<label class="control-label visible-ie8 visible-ie9">Password</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-lock"></i>
						<input class="m-wrap placeholder-no-fix tooltips" type="password" placeholder="Password" name="user_pass"
						title="Password must be at least 6 characters long."  />
					</div>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label visible-ie8 visible-ie9">Confirm Password</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-lock"></i>
						<input class="m-wrap placeholder-no-fix tooltips" type="password" placeholder="Confirm Password" name="user_pass_c" 
						title="Confirm Password."  />
					</div>
				</div>
			</div>




			<div class="control-group">
				<label class="control-label visible-ie8 visible-ie9">Email</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-envelope"></i>
						<input class="m-wrap placeholder-no-fix tooltips" type="text" placeholder="johndoe@email.com" name="user_email"
						title="You must provide a valid email address as we will be using this email to communicate with you. "   />
					</div>
				</div>
			</div>





			<div class="control-group">
			  <label class="control-label">Your Avatar</label>
			  <div class="controls">
			    <div class="fileupload fileupload-new" data-provides="fileupload">
			      <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
			          <img src="<?php echo base_url()."uploads/profile_data/no_image.gif" ?>" alt="" />
			      </div>
			      <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
			      <div>
			          <span class="btn btn-file"><span class="fileupload-new">Select image</span>
			          <span class="fileupload-exists">Change</span>
			              <input type="file" class="default" name="userfile" /></span>
			              <a href="#" class="btn fileupload-exists" data-dismiss="fileupload" >Remove</a>
			      </div>
			    </div>
			   </div>
			</div><br />


			<div class="form-actions">
				<input type="submit" class="btn green" value="Create Account" />          
			</div>

		<?php echo form_close(); ?>
		OR

		<a href="<?php echo base_url('authentication/login'); ?>" >Login</a>
		<!-- END LOGIN FORM -->        

	</div>

	<?php echo $csha; ?>

	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
	<!-- BEGIN CORE PLUGINS -->
	<script src="<?php echo base_url(); ?>themes/assets/scripts/jquery-1.10.1.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>themes/assets/scripts/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>   
	<script src="<?php echo base_url(); ?>themes/assets/scripts/bootstrap.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>themes/assets/scripts/plugins/bootstrap-fileupload.js"></script>
   
	<!-- END PAGE LEVEL SCRIPTS --> 
			<script type="text/javascript">
				$(function() {
					jQuery('.tooltips').tooltip();
				});
			</script>
	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>