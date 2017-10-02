<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8" />
	<title>TaskFeed | Reset Password </title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta name="description" content="Taskfeed is developed by me (A.B.M Shahnewaz Rifat) as one of my dream project. Taskfeed is built with a lot of passion and self realizations. Over the years I've used many project management tools
and while they served the purpose right, I found them bloated or overly complicated at times.I realized that, Task/Project Management softwares can do a world of good if there was a Team Environment. A way to keep it simple, clean and elegant.
Taskfeed is meant for TeamWork.Create your team and manage your projects together! Get notified on the go and have fun organizing your ever so cluttered 
project queue. More features will be added in the future based on user requests. Since this is a single handed project, it may take longer than usual to bring
new and exciting features...Taskfeed Currently features (but is not limited to) 
:Invitation to join Taskfeed/Your Project by email.Creating your own team to work with.Apps for To-Do, Milestones and Discussion.Attachment in discussion.Threaded Discussion View.Email Notification for all major events on a project.Runs on simple Apache Server.User Roles as Project Owner and Contractors.Managing your priority Queue. Task Calendar.Public 3D Business Card! Task and Project Management Panels.The public business card will help to create an eco-system where you can find professional contractors and use Taskfeed as a freelancing resource in future!" />
	<meta content="A.B.M Shahnewaz Rifat" name="author" />
	
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
	<div class="content">


	<?php if(!empty($message)) { ?>

			<?php echo $message; ?>

	<?php } ?>

	<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>




		<!-- BEGIN LOGIN FORM -->
			<?php echo form_open(current_url(), 'form-vertical login-form"'); ?>
			<h3 class="form-title">Reset Your Password</h3>

			<div class="control-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label visible-ie8 visible-ie9">New Password</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-lock"></i>
						<input class="m-wrap placeholder-no-fix" type="password" placeholder="New Password" name="user_pass"/>
					</div>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label visible-ie8 visible-ie9">Confirm New Password</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-lock"></i>
						<input class="m-wrap placeholder-no-fix" type="password" placeholder="Confirm Password" name="user_pass_c"/>
					</div>
				</div>
			</div>

			<div class="form-actions">
				<label class="submit" for="submit" >
					<input type="submit" name="submit" class="btn green" value="Reset My Password" /> 
				</label>
				         
			</div>

		<?php echo form_close(); ?>


		OR <br />

		<a href="<?php echo base_url('authentication/login'); ?>" >Login</a>
		<!-- END LOGIN FORM -->        

	</div>
	<!-- END LOGIN -->
	<!-- BEGIN COPYRIGHT -->
	<div class="copyright">
		<?php echo date('Y'); ?> &copy; <?php echo date('Y'); ?> | Designed &amp; Developed by <br /><a href="http://www.shahnewazrifat.com" >A.B.M Shahnewaz Rifat</a>
	</div>
	<!-- END COPYRIGHT -->
	<?php echo $csha; ?>
</body>
<!-- END BODY -->
</html>