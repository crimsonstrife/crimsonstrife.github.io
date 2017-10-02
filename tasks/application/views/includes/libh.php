<?php 

	foreach ($page_info as $page) {

?>
<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8" />
	<title>

		<?php 
			if (!isset($custom_page_title)) {
				echo $page->page_title; 
			}
		
			else{
				echo $custom_page_title;
			}
		?>
	</title>

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


	<!-- BEGIN CORE PLUGINS -->
	 <script src="//code.jquery.com/jquery-1.9.1.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script src="<?php echo base_url(); ?>themes/assets/scripts/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
	<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
	
	<script type="text/javascript" src="<?php echo base_url(); ?>themes/assets/scripts/plugins/jquery.toggle.buttons.js"></script>
	<script src="<?php echo base_url(); ?>themes/assets/scripts/bootstrap.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>themes/assets/scripts/plugins/bootstrap-datepicker.js" type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>themes/assets/scripts/plugins/chosen.jquery.min.js"></script>
	<script src="<?php echo base_url();?>vendor/ckeditor/ckeditor.js" type="text/javascript"></script>



	

	
	<link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed">
<?php } ?>